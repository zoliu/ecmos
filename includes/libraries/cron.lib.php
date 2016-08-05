<?php

/**
 *    计划任务守护进程
 *
 *    @author    Garbin
 *    @usage    none
 */
class Crond extends Object
{
    /* 配置 */
    var $_config = array();

    /* 任务列表 */
    var $_tasks  = null;

    /* 当前时间 */
    var $_now    = 0;
    var $_lock_fp = null;

    function __construct($setting)
    {
        $this->Crond($setting);
    }
    function Crond($setting)
    {
        $this->_now = time();   //以服务器当前时间为主
        $this->_config($setting);
    }

    /**
     *    配置
     *
     *    @author    Garbin
     *    @param     string $key 配置项名称
     *               array  $key 配置项数组
     *    @param     mixed  $value 配置项值
     *    @return    void
     */
    function _config($key, $value = '')
    {
        if (is_array($key))
        {
            $this->_config = array_merge($this->_config, $key);
        }
        else
        {
            $this->_config[$key] = $value;
        }
    }

    /**
     *    初始化任务
     *
     *    @author    Garbin
     *    @return    void
     */
    function _init_tasks()
    {
        if (empty($this->_config['task_list']))
        {
            return;
        }

        $this->_tasks = include($this->_config['task_list']);
        if (empty($this->_tasks))
        {
            return;
        }
        $update = false;
        foreach ($this->_tasks as $task => $config)
        {
            if (empty($config['due_time']))
            {
                $update = true;
                $this->_tasks[$task]['due_time'] = $this->get_due_time($config);
            }
        }
        $update && $this->update();
    }

    /**
     *    执行
     *
     *    @author    Garbin
     *    @param    none
     *    @return    void
     */
    function execute()
    {
        //此处的锁定机制可能存在冲突问题
        /* 被锁定 */
        if ($this->is_lock())
        {
            return;
        }

        /* 在运行结束前锁定 */
        _at('set_time_limit', 1800);      //半个小时
        _at('ignore_user_abort', true);   //忽略用户退出
        $this->lock();
        $this->_init_tasks();
        /* 获取到期的任务列表 */
        $due_tasks = $this->get_due_tasks();
        /* 没有到期的任务 */
        if (empty($due_tasks))
        {
            $this->unlock();
            return;
        }

        /* 执行任务 */
        $this->run_task($due_tasks);

        /* 更新任务列表 */
        $this->update_tasks($due_tasks);

        /* 解锁 */
        $this->unlock();
    }

    /**
     *    获取进程锁定状态
     *
     *    @author    Garbin
     *    @return    int
     *               0  未锁定状态
     *               1  锁定状态
     */
    function is_lock()
    {
        if (is_writable($this->_config['lock_file']) && (filemtime($this->_config['lock_file']) + 900) > time())
        {
            return 1;
        }
        else
        {
            return 0;
        }

        /*
        if (!is_file($this->_config['lock_file']))
        {
            return 0;
        }
        $status = intval(file_get_contents($this->_config['lock_file']));

        return $status;
        */
    }

    /**
     *    锁定进程
     *
     *    @author    Garbin
     *    @return    void
     */
    function lock()
    {
        _at('touch', $this->_config['lock_file']);
        //file_put_contents($this->_config['lock_file'], 1, LOCK_EX);
    }

    /**
     *    解锁
     *
     *    @author    Garbin
     *    @return    void
     */
    function unlock()
    {
        _at('unlink', $this->_config['lock_file']);
        //file_put_contents($this->_config['lock_file'], 0, LOCK_EX);
    }

    /**
     *    获取到期的任务列表
     *
     *    @author    Garbin
     *    @param    none
     *    @return    array
     */
    function get_due_tasks()
    {
        $tasks = array();
        if (empty($this->_tasks))
        {
            return $tasks;
        }
        foreach ($this->_tasks as $task => $config)
        {
            if ($this->is_due($config))
            {
                $tasks[] = $task;
            }
        }

        return $tasks;
    }

    /**
     *    执行任务列表
     *
     *    @author    Garbin
     *    @param     array $tasks
     *    @return    void
     */
    function run_task($tasks)
    {
        if (empty($tasks))
        {
            return;
        }
        foreach ($tasks as $task)
        {
            $this->_run_task($task);
        }
    }

    /**
     *    更新任务列表
     *
     *    @author    Garbin
     *    @param     array $tasks
     *    @return    void
     */
    function update_tasks($tasks)
    {
        if (empty($tasks))
        {
            return;
        }
        foreach ($tasks as $task)
        {
            $this->_update_task($task);
        }
        $this->update();
    }

    /**
     *    判断计划是否到期
     *
     *    @author    Garbin
     *    @param     array $task_config
     *    @return    bool
     */
    function is_due($task_config)
    {
        if ($task_config['cycle'] == 'none' && $task_config['last_time'])
        {
            return false;
        }
        $due_time = $task_config['due_time'];

        return ($this->_now >= $due_time);
    }

    /**
     *    获取下次到期时间
     *
     *    @author    Garbin
     *    @param     array $config
     *    @return    int
     */
    function get_due_time($config)
    {
        $due_time = 0;
        switch ($config['cycle'])
        {
            /* 自定义的以当前时间为下次到期时间 */
            case 'custom':
                $due_time = $this->_now + $config['interval'];
            break;

            /* 每日定点 */
            case 'daily':
                /* 获取当日的时间点 */
                $today_due_time = strtotime(date('Y-m-d', $this->_now) . " {$config['hour']}:{$config['minute']}");

                if ($this->_now >= $today_due_time)
                {
                    /* 如果已过这个时间点，则下次到期时间+周期1天 */
                    $due_time = $today_due_time + 3600 * 24;
                }
                else
                {
                    /* 否则就以当日的到期时间点为下次到期时间 */
                    $due_time = $today_due_time;
                }
            break;

            /* 每周定点 */
            case 'weekly':
                $next_week_due_time = strtotime(date('Y-m-d', strtotime("next {$config['day']}")) . " {$config['hour']}:{$config['minute']}");
                $this_week_due_time = $next_week_due_time - 7 * 24 * 3600;
                if ($this->_now >= $this_week_due_time)
                {
                    /* 若已过了本周的时间点，则下次到期是下周的时间点 */
                    $due_time = $next_week_due_time;
                }
                else
                {
                    /* 否则为本周的时间点 */
                    $due_time = $this_week_due_time;
                }
            break;

            /* 每月定点 */
            case 'monthly':
                $this_month_time = date('Y-m', $this->_now) . "-{$config['day']} {$config['hour']}:{$config['minute']}";
                $this_month_due_time = strtotime($this_month_time);                 //本月到期时间
                $next_month_due_time = strtotime($this_month_time . ' +1 month');   //下月到期时间
                if ($this->_now >= $this_month_due_time)
                {
                    /* 已过本月时间点 */
                    $due_time = $next_month_due_time;
                }
                else
                {
                    /* 未过本月时间点 */
                    $due_time = $this_month_due_time;
                }
            break;

            default:
                return false;
            break;
        }

        return $due_time;
    }

    /**
     *    运行指定任务
     *
     *    @author    Garbin
     *    @param     string $task_name
     *    @return    bool
     */
    function _run_task($task_name)
    {
        $task_file = $this->_config['task_path'] . '/' . $task_name . '.task.php';
        include_once($task_file);
        $task_config = empty($this->_tasks[$task_name]['config']) ? array() : $this->_tasks[$task_name]['config'];
        $task_class_name = ucfirst($task_name) . 'Task';
        $task  = new $task_class_name($task_config);
        $task->run();
    }

    /**
     *    更新任务列表
     *
     *    @author    Garbin
     *    @return    void
     */
    function update()
    {
        file_put_contents($this->_config['task_list'], "<?php\r\n\r\nreturn " . var_export($this->_tasks, true) . ";\r\n\r\n?>", LOCK_EX);
    }

    /**
     *    更新上次执行时间
     *
     *    @author    Garbin
     *    @param     string $task_name
     *    @return    void
     */
    function _update_task($task)
    {
        if (!isset($this->_tasks[$task]))
        {
            return;
        }

        /* 更新上次执行时间 */
        $this->_tasks[$task]['last_time'] = $this->_now;

        /* 更新下次到期时间 */
        $this->_tasks[$task]['due_time']  = $this->get_due_time($this->_tasks[$task]);
    }
}

/**
 *    任务基础类
 *
 *    @author    Garbin
 *    @usage    none
 */
class BaseTask extends Object
{
    var $_config = null;

    function __construct($config)
    {
        $this->BaseTask($config);
    }

    function BaseTask($config)
    {
        $this->_config = $config;
    }

    /**
     *    运行任务
     *
     *    @author    Garbin
     */
    function run() {}
}

?>
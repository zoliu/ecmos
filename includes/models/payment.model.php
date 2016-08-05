<?php

/* 支付方式 payment */
class PaymentModel extends BaseModel
{
    var $table  = 'payment';
    var $prikey = 'payment_id';
    var $_name  = 'payment';

    var $_autov     =   array(
        'store_id'  =>  array(
//            'required'  =>  true,
            'filter'    => 'intval',
        ),
        'payment_code'  => array(
            'required'  => true,
            'filter'    => 'trim',
        ),
        'payment_name'  =>  array(
            'required'  => true,
            'filter'    => 'trim',
        ),
        'payment_desc'  => array(
            'filter'    => 'trim',
        ),
        'config'        => array(
            'filter'    => 'serialize',
        ),
        'enabled'       => array(
            'filter'    => 'intval',
        ),
        'sort_order'       => array(
            'filter'    => 'intval',
        ),
    );

    var $_relation  =   array(
        // 一个支付方式只能属于一个店铺
//        'belongs_to_store' => array(
//            'model'         => 'store',
//            'type'          => BELONGS_TO,
//            'foreign_key'   => 'store_id',
//            'reverse'       => 'has_payment',
//        ),
    );


    /* 对店铺支付方式的操作 */
    /**
     *    安装支付方式
     *
     *    @author    Garbin
     *    @param     array $payment
     *    @return    bool
     */
    function install($payment)
    {
        if (!$this->in_white_list($payment['payment_code']))
        {
            $this->_error('system_disabled_payment');

            return;
        }

        return $this->add($payment);
    }

    /**
     *    卸载支付方式
     *
     *    @author    Garbin
     *    @param     int $store_id
     *    @param     int $payment_id
     *    @return    bool
     */
    function uninstall($store_id, $payment_id)
    {
        return $this->drop("store_id = {$store_id} AND payment_id={$payment_id}");
    }

    /**
     *    获取已安装的支付方式
     *
     *    @author    Garbin
     *    @param     int $store_id
     *    @return    array
     */
    function get_installed($store_id)
    {
        return $this->find(array(
            'conditions'    => "store_id={$store_id}",
            'order'         => 'sort_order',
        ));
    }

    /**
     *    获取已启用的
     *
     *    @author    Garbin
     *    @param     int $store_id
     *    @return    array
     */
    function get_enabled($store_id)
    {
        return $this->find(array(
            'conditions'    => "store_id={$store_id} AND enabled=1 AND payment_code" . db_create_in($this->get_white_list()),
            'order'         => 'sort_order',
        ));
    }

    /*---------对内置支付方式的操作---------*/

    /**
     *    获取内置支付方式
     *
     *    @author    Garbin
     *    @param     array $withe_list 白名单
     *    @return    array
     */
    function get_builtin($white_list = null)
    {
        static $payments = null;
        if ($payments === null)
        {
            $payment_dir = ROOT_PATH . '/includes/payments';
            $dir = dir($payment_dir);
            $payments = array();
            while (false !== ($entry = $dir->read()))
            {
                /* 隐藏文件，当前目录，上一级，排除 */
                if ($entry{0} == '.')
                {
                    continue;
                }

                if (is_array($white_list) && !in_array($entry, $white_list))
                {
                    continue;
                }

                /* 获取支付方式信息 */
                $payments[$entry] = $this->get_builtin_info($entry);
            }
        }
        if (is_array($payments))
        {
            uksort($payments, "cmp_payment");
        }

        return $payments;
    }

    /**
     *    获取内置支付方式的配置信息
     *
     *    @author    Garbin
     *    @param     string $code
     *    @return    array
     */
    function get_builtin_info($code)
    {
        Lang::load(lang_file('payment/' . $code));
        $payment_path = ROOT_PATH . '/includes/payments/' . $code . '/payment.info.php';

        return include($payment_path);
    }

    /**
     *    获取支付方式白名单
     *
     *    @author    Garbin
     *    @return    array
     */
    function get_white_list()
    {
        $file = ROOT_PATH . '/data/payments.inc.php';
        if (!is_file($file))
        {
            return array();
        }

        return include($file);
    }

    /**
     *    启用内置支付方式
     *
     *    @author    Garbin
     *    @param     string $code
     *    @return    bool
     */
    function enable_builtin($code)
    {
        $white_list = $this->get_white_list();
        $white_list[] = $code;
        $white_list = array_unique($white_list);
        return $this->save_white_list($white_list);
    }

    /**
     *    禁用内置支付方式
     *
     *    @author    Garbin
     *    @param     string $code
     *    @return    void
     */
    function disable_builtin($code)
    {
        $white_list = $this->get_white_list();
        $index = array_search($code, $white_list);
        if (false !== $index)
        {
            unset($white_list[$index]);

            return $this->save_white_list($white_list);
        }

        return false;
    }

    /**
     *    保存白名单
     *
     *    @author    Garbin
     *    @param     array $white_list
     *    @return    bool
     */
    function save_white_list($white_list)
    {
        $payments_inc_file = ROOT_PATH . '/data/payments.inc.php';
        $php_data = "<?php\n\nreturn " . var_export($white_list, true) . ";\n\n?>";

        return file_put_contents($payments_inc_file, $php_data, LOCK_EX);
    }

    /**
     *    判断指定code的payment是否在白名单中
     *
     *    @author    Garbin
     *    @param     string $code
     *    @return    bool
     */
    function in_white_list($code)
    {
        if (!$code)
        {
            return;
        }
        $white_list = $this->get_white_list();

        return in_array($code, $white_list);
    }
}

/* 比较函数，实现支付方式排序 */
function cmp_payment($a, $b)
{
    if ($b == 'alipay')
    {
        return 1;
    }
    elseif ($b == 'tenpay2' && $a != 'alipay')
    {
        return 1;
    }
    elseif ($b == 'tenpay' && $a != 'alipay' && $a != 'tenpay2')
    {
        return 1;
    }
    else
    {
        return -1;
    }
}

?>
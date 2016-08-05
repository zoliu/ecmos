<?php
/**
 * 后台团购管理控制器
 *
 */

class GroupbuyApp extends BackendApp
{
    var $_groupbuy_mod;

    function __construct()
    {
        $this->GroupbuyApp();
    }

    function GroupbuyApp()
    {
        parent::BackendApp();
        $this->_groupbuy_mod =& m('groupbuy');
    }

    function index()
    {
        $conditions = $this->_get_query_conditions(array(
            array(
                'field' => 'gb.group_name',
                'equal' => 'LIKE',
                'assoc' => 'AND',
                'name'  => 'group_name',
                'type'  => 'string',
            ),
            array(
                'field' => 'gb.state',
                'name'  => 'type',
                'assoc' => 'AND',
                'handler' => 'groupbuy_state_translator',
            ),
        ));
        $page = $this->_get_page(10);
        $groupbuys_list = $this->_groupbuy_mod->find(array(
            'conditions' => "1 = 1" . $conditions,
            'join'  => 'belong_store',
            'fields'=> 'this.*,s.store_name',
            'limit' => $page['limit'],
            'order' => 'group_id DESC',
            'count' => true
        ));
        $groupbuys = array();
        if ($ids = array_keys($groupbuys_list))
        {
            $quantity = $this->_groupbuy_mod->db->getAllWithIndex("SELECT group_id, sum(quantity) as quantity FROM ". DB_PREFIX ."groupbuy_log  WHERE group_id " . db_create_in($ids) . "GROUP BY group_id", array('group_id'));
        }
        foreach ($groupbuys_list as $key => $val)
        {
            $groupbuys[$key] = $val;
            $groupbuys[$key]['count'] = empty($quantity[$key]['quantity']) ? 0 : $quantity[$key]['quantity'];
        }
        $page['item_count'] = $this->_groupbuy_mod->getCount();
        $this->_format_page($page);
        $this->assign('types', array(
            'all'       => Lang::get('group_all'),
            'pending'   => Lang::get('group_pending'),
            'on'        => Lang::get('group_on'),
            'end'       => Lang::get('group_end'),
            'finished'  => Lang::get('group_finished'),
            'canceled'  => Lang::get('group_canceled')
        ));
        $this->import_resource(array(
            'script' => 'inline_edit.js',
        ));
        $this->assign('type', $_GET['type']);
        $this->assign('filtered', $conditions? 1 : 0); //是否有查询条件
        $this->assign('page_info', $page);   //将分页信息传递给视图，用于形成分页条
        $this->assign('groupbuys', $groupbuys);
        $this->display('groupbuy.index.html');
    }

    function recommended()
    {
        $id = trim($_GET['id']);
        $ids = explode(',', $id);
        $this->_groupbuy_mod->edit(db_create_in($ids, 'group_id') . ' AND state = ' . GROUP_ON, array('recommended' => 1));
        if ($this->_groupbuy_mod->has_error())
        {
            $this->show_warning($this->_groupbuy_mod->get_error());
            exit;
        }
        $this->show_warning('recommended_success', 'back_list' , 'index.php?app=groupbuy');
    }

    function drop()
    {
        $id = trim($_GET['id']);
        $ids = explode(',', $id);
        if (empty($ids))
        {
            $this->show_warning("no_valid_data");
            exit;
        }
        $this->_groupbuy_mod->drop(db_create_in($ids, 'group_id'));
        if ($this->_groupbuy_mod->has_error())
        {
            $this->show_warning($this->_groupbuy_mod->get_error());
            exit;
        }
        $this->show_warning('drop_success',
            'back_list' , 'index.php?app=groupbuy');
    }

   function ajax_col()
   {
       $id     = empty($_GET['id']) ? 0 : intval($_GET['id']);
       $column = empty($_GET['column']) ? '' : trim($_GET['column']);
       $value  = isset($_GET['value']) ? trim($_GET['value']) : '';
       $data   = array();

       if (in_array($column ,array('recommended')))
       {
           $data[$column] = $value;
           $this->_groupbuy_mod->edit("group_id = " . $id . " AND state = " . GROUP_ON, $data);
           if(!$this->_groupbuy_mod->has_error())
           {
               echo ecm_json_encode(true);
           }
       }
       else
       {
           return ;
       }
       return ;
   }
}



?>
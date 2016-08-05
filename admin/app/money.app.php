<?php

/**
 * 后台钱包中心
 * 
 * @author Mosquito
 * @link www.360cd.cn
 */
class MoneyApp extends BackendApp {
    
    protected $money_model;
    protected $money_log_model;
    
    function __construct() {
        parent::__construct();
        $this->MoneyApp();
    }
    
    function MoneyApp() {
        $this->money_model = &m('money');
        $this->money_log_model = &m('money_log');
    }
    
    function index() {
        
        $query = array();
        $conditions = '1 = 1';
        if (trim($_GET['user_name'])) {
            $query['user_name'] = trim($_GET['user_name']);
            $conditions .= " AND member.user_name LIKE '%{$query['user_name']}%'";
        }
        if (trim($_GET['status'])) {
            $query['status'] = intval($_GET['status']);
            $conditions .= " AND money.status = {$query['status']}";
        }
        if ($query) {
            $this->assign('filter', true);
        }
        $this->assign('query', $query);
        
        $page = $this->_get_page();
        $joinstr = $this->money_model->parseJoin('user_id', 'user_id', 'member');
        $money_list = $this->money_model->find(array(
            'joinstr' => $joinstr,
            'fields' => 'this.*, member.user_name, member.real_name',
            'conditions' => $conditions,
            'limit' => $page['limit'],
            'count' => true,
        ));
        $this->assign('money_list', $money_list);
        
        $page['item_cout'] = $this->money_model->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);
        
        //
        $this->assign('money_status_options', Money::get_money_status_options());
        
        $this->display('money.index.html');
    }
    
    /**
     * 重置
     */
    function reset() {
        $id = intval($_GET['id']);
        if (!$id) {
            show_warning('操作失败');
            exit();
        }
        
        $this->money_model->edit($id, array(
            'password' => '',
			'money' => 0,
			'money_dj' => 0,
			'status' => MONEY_S_OPEN,
        ));
        
        //Money::init()->isset_account($user_id);
        show_message('操作成功');
    }
    
    function drop() {
        $id_arr = trim($_GET['id']) ? explode(',', trim($_GET['id'])) : '';
        if (!$id_arr) {
            show_warning('操作失败');
            exit();
        }
        
        $this->money_model->drop(db_create_in($id_arr, 'id'));
        show_message('操作成功');
    }
    
    function recharge() {
        
        $id = intval($_GET['id']);
        
        $joinstr = $this->money_model->parseJoin('user_id', 'user_id', 'member');
        $money_info = $this->money_model->get(array(
            'joinstr' => $joinstr,
            'fields' => 'this.*, member.user_name, member.real_name',
            'conditions' => "money.id = $id",
        ));
        if (!$money_info) {
            show_warning('账户错误');
            exit();
        }
        
        if (IS_POST) {
            $money = floatval($_POST['money']);
            
            if ($money <= 0) {
                show_warning('充值金额错误');
                exit();
            }
            
            $temp = Money::init()->recharge_admin($money_info['user_id'], $this->visitor->get('user_id'), $money);
            $temp ? show_message('操作成功', '账户列表', url('app=money')) : show_warning('操作失败');
        }
        else {
            $this->assign('money_info', $money_info);
            
            $this->display('money.recharge.html');
        }
    }
    
    function log() {
        $query = array();
        $conditions = '1 = 1';
        if (trim($_GET['user_name']) != '') {
            $query['user_name'] = trim($_GET['user_name']);
            $conditions .= " AND u_m.user_name LIKE '%{$query['user_name']}%'";
        }
        if (trim($_GET['start_time']) != '' ) {
            $query['start_time'] = trim($_GET['start_time']);
            $conditions .= " AND money_log.add_time >= " . gmstr2time($query['start_time']);
        }
        if (trim($_GET['end_time']) != '' ) {
            $query['end_time'] = trim($_GET['end_time']);
            $conditions .= " AND money_log.add_time < " . gmstr2time($query['end_time']);
        }
        if (trim($_GET['type']) != '' ) {
            $query['type'] = intval($_GET['type']);
            $conditions .= " AND money_log.type = {$query['type']}";
        }
        if (trim($_GET['status']) != '' ) {
            $query['status'] = intval($_GET['status']);
            $conditions .= " AND money_log.status = {$query['status']}";
        }
         
        if ($query) {
            $this->assign('filter', true);
        }
        $this->assign('query', $query);
         
        $page = $this->_get_page();
        $joinstr = $this->money_log_model->parseJoin('user_id', 'user_id', 'member', '', 'left', 'u_m');
        $joinstr .= $this->money_log_model->parseJoin('party_id', 'user_id', 'member', '', 'left', 'p_m');
        $money_log_list = $this->money_log_model->find(array(
            'joinstr' => $joinstr,
            'fields' => 'this.*,u_m.user_name AS user_name, p_m.user_name AS party_name',
            'conditions' => $conditions,
            'order' => 'add_time DESC',
            'limit' => $page['limit'],
            'count' => true,
        ));
        $this->assign('money_log_list', $money_log_list);
         
        $page['item_count'] = $this->money_log_model->getCount();
        $this->_format_page($page);
        $this->assign('page_info', $page);
         
        //
        $this->assign('money_log_type_options', Money::get_money_log_type_options());
        $this->assign('flow_options', Money::get_flow_options());
        $this->assign('money_log_status_options', Money::get_money_log_status_options());
         
        //
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'jquery.ui/jquery.ui.js','attr' => ''
                ),array(
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js','attr' => ''
                )
            ),'style' => 'jquery.ui/themes/ui-lightness/jquery.ui.css'
        ));
        
        $this->display('money.log.html');
    }
    
    function log_view() {
        $id = intval($_GET['id']);
        
        $joinstr = $this->money_log_model->parseJoin('user_id', 'user_id', 'member', '', 'left', 'u_m');
        $joinstr .= $this->money_log_model->parseJoin('party_id', 'user_id', 'member', '', 'left', 'p_m');
        $joinstr .= $this->money_log_model->parseJoin('order_id', 'order_id', 'order');
        $joinstr .= $this->money_log_model->parseJoin('bank_id', 'id', 'bank');
        $joinstr .= $this->money_log_model->parseJoin('pay_id', 'payment_id', 'payment');
        
        $money_log_info = $this->money_log_model->get(array(
            'joinstr' => $joinstr,
            'fields' => 'this.*, u_m.user_name AS user_name, p_m.user_name AS party_name, bank.card_number, bank.cardholder, bank.bank_name, bank.bank_address, payment.payment_code, payment.payment_name',
            'conditions' => "money_log.id = $id",
        ));
        $this->assign('money_log_info', $money_log_info);
        
        //
        $this->assign('money_log_type_options', Money::get_money_log_type_options());
        $this->assign('flow_options', Money::get_flow_options());
        $this->assign('money_log_status_options', Money::get_money_log_status_options());
        
        $this->display('money.log_view.html');
    }
    
    function log_drop() {
        $id_arr = trim($_GET['id']) ? explode(',', trim($_GET['id'])) : '';
        if (!$id_arr) {
            show_warning('操作失败');
            exit();
        }
        
        $this->money_log_model->drop(db_create_in($id_arr, 'id'));
        show_message('操作成功');
    }
    
    function audit() {
        $id = intval($_GET['id']);
        $type = trim($_GET['type']);
        if ($type == 'no') {
            //拒绝提现变更资金
            Money::init()->withdrawal_update_status($id, MONEY_L_S_NO);
        }
        else {
            //允许提现变更资金
            Money::init()->withdrawal_update_status($id, MONEY_L_S_OK);
        }
        
        show_message('操作成功');
    }
}
<?php
/* 买家咨询管理控制器 */
class My_questionApp extends MemberbaseApp
{
    var $my_qa_mod;
    function __construct()
    {
        $this->My_questionApp();
    }
    function My_questionApp()
    {
        parent::__construct();
        $this->my_qa_mod = & m('goodsqa');
    }
    function index()
    {
        $page =$this->_get_page(8);
        $type = (isset($_GET['type']) && $_GET['type'] != '') ? trim($_GET['type']) : 'all_qa';
        $conditions = '1=1 AND goods_qa.user_id = '.$_SESSION['user_info']['user_id'] ;
        if ($type == 'reply_qa')
        {
            $conditions .= ' AND reply_content !="" ';
        }
        $my_qa_data = $this->my_qa_mod->find(array(
            'fields' => 'ques_id,question_content,reply_content,time_post,time_reply,goods_qa.user_id,goods_qa.item_name,goods_qa.item_id,goods_qa.email,goods_qa.type,if_new,user_name',
            'join' => 'belongs_to_store,belongs_to_user',
            'count' => true,
            'conditions' => $conditions,
            'limit' => $page['limit'],
            'order' => 'if_new desc,time_post desc',
        ));
                /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                         LANG::get('my_question'), 'index.php?app=my_question',
                         LANG::get('my_question_list'));

        /* 当前用户中心菜单 */
        $this->_curitem('my_question');

        /* 当前所处子菜单 */
        $this->_curmenu('my_qa_list');
        $page['item_count'] = $this->my_qa_mod->getCount();   //获取统计的数据
        $this->_format_page($page);
        $this->assign('_curmenu',$type);
        $this->assign('page_info',$page);
        $this->assign('my_qa_data',$my_qa_data);
        if ($type == 'reply_qa')
        {
            $update_data = array(
                'if_new' => '0',
            );
            $this->my_qa_mod->edit($my_qa_data['ques_id'],$update_data);
        }
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_question'));
        $this->display('my_question.index.html');
    }
    //三级菜单:
    function _get_member_submenu()
    {
        return array(
            array(
                'name' => 'all_qa',
                'url' => 'index.php?app=my_question&amp;type=all_qa',
            ),
            array(
                'name' => 'reply_qa',
                'url' => 'index.php?app=my_question&amp;type=reply_qa',
            ),            
        );
    }
}

?>
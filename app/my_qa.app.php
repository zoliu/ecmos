<?php
/* 商品咨询管理控制器 */
class My_qaApp extends StoreadminbaseApp
{
    var $my_qa_mod;
    function __construct()
    {
        $this->My_qaApp();
    }
    function My_qaApp()
    {
        parent::__construct();
        $this->my_qa_mod = & m('goodsqa');
    }
    function index()
    {
        //dump($_SESSION['user_info']);
        $type = (isset($_GET['type']) && $_GET['type'] != '') ? trim($_GET['type']) : 'all_qa';
        $conditions = ' AND goods_qa.store_id = '.$_SESSION['user_info']['user_id'];
        switch ($type)
        {
            case 'all_qa':
                $conditions .= ' ';
                break;
            case 'to_reply_qa' :
                $conditions .= ' AND reply_content = " " ';
                break;
            case 'replied' :
                $conditions .= ' AND reply_content != " " ';
                break;
        };
        $page = $this->_get_page(8);
        $my_qa_data = $this->my_qa_mod->find(array(
            'fields' => 'ques_id,question_content,reply_content,goods_qa.user_id,goods_qa.email,time_post,time_reply,user_name,goods_qa.item_id,goods_qa.item_name,goods_qa.type',
            'join' => 'belongs_to_store,belongs_to_user',
            'conditions' => '1=1 '.$conditions,
            'count' => true,
            'limit' => $page['limit'],
            'order' => 'time_post desc',
        ));
        $page['item_count'] = $this->my_qa_mod->getCount();
        $this->_format_page($page);
                /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                         LANG::get('my_qa'), 'index.php?app=my_qa',
                         LANG::get('my_qa_list'));

        /* 当前用户中心菜单 */
        $this->_curitem('my_qa');

        /* 当前所处子菜单 */
        $this->_curmenu('my_qa_list');
        $this->assign('_curmenu',$type);
        $this->import_resource(array(
            'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js"',
                ),
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => '',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));
        $this->assign('page_info',$page);
        $this->assign('my_qa_data',$my_qa_data);
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_qa'));
        $this->display('my_qa.index.html');
    }
    function reply()
    {
        if (!IS_POST)
        {
            $ques_id = (isset($_GET['ques_id']) && $_GET['ques_id'] !='') ? intval($_GET['ques_id']) : 0;
            $conditions = ' AND goods_qa.store_id = '. $_SESSION['user_info']['user_id'] . ' AND ques_id = '.$ques_id;
            $my_qa_data = $this->my_qa_mod->get(array(
                'fields' => 'question_content,reply_content,goods_qa.user_id,goods_qa.email,time_post,user_name,goods_qa.item_id,goods_qa.item_name,goods_qa.type',
                'join' => 'belongs_to_store,belongs_to_user',
                'conditions' => '1=1 '.$conditions,
            ));
            if ($my_qa_data['reply_content'] != '')
            {
                echo Lang::get('already_replied');
                return;
            }
                    /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                             LANG::get('my_qa'), 'index.php?app=my_qa',
                             LANG::get('reply'));

            /* 当前用户中心菜单 */
            $this->_curitem('my_qa');

            /* 当前所处子菜单 */
            $this->_curmenu('reply');
            $this->assign('_curmenu','reply');
            $this->assign('page_info',$page);
            $this->assign('my_qa_data',$my_qa_data);
            $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('reply'));
            header('Content-Type:text/html;charset=' . CHARSET);
            $this->display('my_qa.form.html');
        }
        else
        {
            $act = (isset($_POST['act']) && $_POST['act'] != '') ? trim($_POST['act']) : '';
            $ques_id = (isset($_POST['ques_id']) && $_POST['ques_id'] != '') ? intval($_POST['ques_id']) : '';
            $content = (isset($_POST['content']) && $_POST['content'] != '') ? trim($_POST['content']) : '';
            if ($act != 'reply' || $ques_id =='')
            {
                $this->pop_warning('Hacking Attempt');
                return;
            }
            if ($content == '')
            {
                $this->pop_warning('content_not_null');
                return;
            }

            $user_info = $this->my_qa_mod->get(array(
                    //'join' => 'belongs_to_goods',
                    'conditions' => '1 = 1 AND ques_id = '.$ques_id,
                    'fields' => 'user_id,email,item_id,item_name,type'));
                extract($user_info);
                $data = array(
                    'reply_content' => $content,
                    'time_reply' => gmtime(),
                    'if_new' => '1',
                    );
                if ($this->my_qa_mod->edit($ques_id,$data))
                {                    
                    $url = '';
                    switch ($type)
                    {
                        case 'goods' : $url = SITE_URL . "/index.php?app={$type}&act=qa&id={$item_id}&amp;ques_id={$ques_id}&amp;new=yes";
                        break;
                        case 'groupbuy' : $url = SITE_URL . "/index.php?app={$type}&id={$item_id}&amp;ques_id={$ques_id}&amp;new=yes";
                        break;
                    }

                    $mail = get_mail('tobuyer_question_replied', array(
                        'item_name'  => $item_name,
                        'type'       => Lang::get($type),
                        'url'        => $url
                    ));
                    $this->_mailto($email, addslashes($mail['subject']), addslashes($mail['message']));
                    $this->pop_warning('ok', 'my_qa_reply');
                }
                else
                {
                    $this->pop_warning('reply_failed');
                    return;
                }
        }
    }
    
    function edit_reply()
    {
        $ques_id = (isset($_GET['ques_id']) && $_GET['ques_id'] !='') ? intval($_GET['ques_id']) : 0;
        if (empty($ques_id))
        {
            echo Lang::get('no_data');
        }
        if (!IS_POST)
        {
            
            $conditions = ' AND goods_qa.store_id = '. $_SESSION['user_info']['user_id'] . ' AND ques_id = '.$ques_id;
            $my_qa_data = $this->my_qa_mod->get(array(
                'fields' => 'question_content,reply_content,goods_qa.user_id,goods_qa.email,time_post,user_name,goods_qa.item_id,goods_qa.item_name,goods_qa.type',
                'join' => 'belongs_to_store,belongs_to_user',
                'conditions' => '1=1 '.$conditions,
            ));
            $this->assign('ques_id', $ques_id);
            $this->assign('my_qa_data',$my_qa_data);
            header('Content-Type:text/html;charset=' . CHARSET);
            $this->display('my_qa.form.html');
        }
        else
        {
            $act = (isset($_POST['act']) && $_POST['act'] != '') ? trim($_POST['act']) : '';
            $ques_id = (isset($_POST['ques_id']) && $_POST['ques_id'] != '') ? intval($_POST['ques_id']) : '';
            $content = (isset($_POST['content']) && $_POST['content'] != '') ? trim($_POST['content']) : '';
            if (empty($content))
            {
                $this->pop_warning('content_not_null');
                return;
            }

            $user_info = $this->my_qa_mod->get(array(
                    'conditions' => '1 = 1 AND ques_id = '.$ques_id,
                    'fields' => 'user_id,email,item_id,item_name,type'));
                extract($user_info);
                $data = array(
                    'reply_content' => $content,
                    'time_reply' => gmtime(),
                    'if_new' => '1',
                    );
                if ($this->my_qa_mod->edit($ques_id,$data))
                {
                    $this->pop_warning('ok', 'my_qa_edit_reply');
                    $mail = get_mail('tobuyer_question_replied', array('id' => $goods_id, 'ques_id' => $ques_id, 'goods_name' => $goods_name));
                    $this->_mailto($email, addslashes($mail['subject']), addslashes($mail['message']));

                }
                else
                {
                    $this->pop_warning('reply_failed');
                    return;
                }
        }
    }
    //删除咨询
    function drop()
    {
        $id = (isset($_GET['id']) && $_GET['id'] != '') ? trim($_GET['id']) : '';
        if (!$id)
        {
            $this->show_warning('no_qa_to_drop');
            return;
        }
        $ids = explode(',', $id);
        if (!$this->my_qa_mod->drop($ids))
        {
            $this->show_warning('drop_failed');
            return;
        }
        $this->show_message('drop_successful');
    }
    //三级菜单:
    function _get_member_submenu()
    {
        $array = array(
            array(
                'name' => 'all_qa',
                'url' => 'index.php?app=my_qa&amp;type=all_qa',
            ),
            array(
                'name' => 'to_reply_qa',
                'url' => 'index.php?app=my_qa&amp;type=to_reply_qa',
            ),
            array(
                'name' => 'replied',
                'url' => 'index.php?app=my_qa&amp;type=replied',
            ),
/*            array(
                'name' => 'reply',
                'url' => 'index.php?app=my_qa&amp;type=reply',
            ),*/
        );
        if (ACT == 'index')
        {
            unset($array[3]);
        }
        return $array;
    }
}

?>
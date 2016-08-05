<?php



class Member_photoApp extends MemberbaseApp

{

    function index()

    {

            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'), url('app=member'),
                         LANG::get('my_profile'), url('app=member&act=profile'),
                         LANG::get('member_photo'));
            $this->_curitem('my_profile');
            $this->_curmenu('member_photo');

       $user_id=$this->visitor->get('user_id');
        $savePath = 'data/files/mall/portrait/1/';  //图片存储路径
 

        $file_src = $savePath.$user_id.".gif"; //图片名称
     
       // $src=base64_decode($_POST['pic1']);
        $src = file_get_contents('php://input');
        if($src) {
             $src = explode('--------------------', $src);
            file_put_contents($file_src,$src[0]);
        }

        $rs['status'] = 1;
        $rs['picUrl'] = $savePath.$savePicName;

        $data = array(
            'portrait' =>$file_src,               
            );

        $model_user =& m('member');
        $model_user->edit($user_id , $data);
        print json_encode($rs);
    }

    function add_photo()
    {
      $this->_member_mod =& m('member');
      $conditions ='';

        /* 取得列表数据 */
      $conditions.=" and user_id=".$this->visitor->get('user_id');
      $members = $this->_member_mod->get(array(
        'conditions'  => '1=1 '.$conditions,
        'fields'=>'member.portrait',
        ));
    $this->assign('members',$members);
        if (!IS_POST)
        {

            /* 当前位置 */
            $this->_curlocal(LANG::get('member_center'),  'index.php?app=member_photo',
                             LANG::get('add_photo'));
            /* 当前用户中心菜单 */
            $this->_curitem('my_profile');
            /* 当前所处子菜单 */
            $this->_curmenu('add_photo');

            $this->import_resource(array(
             'script' => array(
                array(
                    'path' => 'dialog/dialog.js',
                    'attr' => 'id="dialog_js"',
                ),
                array(
                    'path' => 'mlselection.js',
                    'attr' =>'',
                ),array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' =>'',
                ),
                array(
                    'path' => 'jquery.ui/jquery.ui.js',
                    'attr' => '',
                ), array(
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                    'attr' => '',
                ),
                 array(
                    'path' => 'utils.js',
                    'attr' => '',
                ),array(
                    'path' => 'inline_edit.js',
                    'attr' => '',
                ),
                array(
                    'path' => 'jquery.plugins/jquery.validate.js',
                    'attr' => '',
                ),
                ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',

            ));
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('add_photo'));
        $this->display('member.photo.html');
        }      

    }

    function _get_member_submenu()
    {
           $submenus =  array(
            array(
                'name'  => 'basic_information',
                'url'   => 'index.php?app=member&amp;act=profile',
            ),
            array(
                'name'  => 'edit_password',
                'url'   => 'index.php?app=member&amp;act=password',
            ),
            array(
                'name'  => 'edit_email',
                'url'   => 'index.php?app=member&amp;act=email',
            ),
            /**array(

                'name'  => 'edit_phoneflag',

                'url'   => 'index.php?app=member&amp;act=phone_flag',

            ),**/
           array(

                'name'  => 'edit_phone_mob',

                'url'   => 'index.php?app=member&amp;act=edit_phone_mob',

            ),
            //360cd.cn 个人头像管理 fay
            array(
                'name'  => 'add_photo',
                'url'   => 'index.php?app=member_photo&amp;act=add_photo',
            ),
        );
        if ($this->_feed_enabled)
        {
            $submenus[] = array(
                'name'  => 'feed_settings',
                'url'   => 'index.php?app=member&amp;act=feed_settings',
            );
        }

        return $submenus;
    }

}



?>
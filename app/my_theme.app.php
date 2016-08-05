<?php

/**
 *    主题设置控制器
 *
 *    @author    Garbin
 *    @usage    none
 */
class My_themeApp extends StoreadminbaseApp
{
    function index()
    {
        extract($this->_get_themes());

        if (empty($themes))
        {
            $this->show_warning('no_themes');

            return;
        }

        /* 当前位置 */
        $this->_curlocal(LANG::get('member_center'),    'index.php?app=member',
                         LANG::get('theme_list'));

        /* 当前用户中心菜单 */
        $this->_curitem('my_theme');
        $this->_curmenu('theme_config');
        $this->assign('themes', $themes);
        $this->assign('curr_template_name', $curr_template_name);
        $this->assign('curr_style_name', $curr_style_name);
        $this->assign('manage_store', $this->visitor->get('manage_store'));
        $this->assign('id',$this->visitor->get('user_id'));
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
                array(
                    'path' => 'jquery.ui/i18n/' . i18n_code() . '.js',
                    'attr' => '',
                ),
            ),
            'style' =>  'jquery.ui/themes/ui-lightness/jquery.ui.css',
        ));
        $this->_config_seo('title', Lang::get('member_center') . ' - ' . Lang::get('my_theme'));
        $this->display('my_theme.index.html');
    }
    function set()
    {
        $template_name = isset($_GET['template_name']) ? trim($_GET['template_name']) : null;
        $style_name = isset($_GET['style_name']) ? trim($_GET['style_name']) : null;
        if (!$template_name)
        {
            $this->json_error('no_such_template');

            return;
        }
        if (!$style_name)
        {
            $this->json_error('no_such_style');

            return;
        }
        extract($this->_get_themes());
        $theme = $template_name . '|' . $style_name;

        /* 检查是否可以选择此主题 */
        if (!isset($themes[$theme]))
        {
            $this->json_error('no_such_theme');

            return;
        }
        $model_store =& m('store');
        $model_store->edit($this->visitor->get('manage_store'), array('theme' => $theme));

        $this->json_result('', 'set_theme_successed');
    }

    function _get_themes()
    {
        /* 获取当前所使用的风格 */
        $model_store =& m('store');
        $store_info  = $model_store->get($this->visitor->get('manage_store'));
        $theme = !empty($store_info['theme']) ? $store_info['theme'] : 'default|default';
        list($curr_template_name, $curr_style_name) = explode('|', $theme);

        /* 获取待选主题列表 */
        $model_grade =& m('sgrade');
        $grade_info  =  $model_grade->get($store_info['sgrade']);
        $skins = explode(',', $grade_info['skins']);
        $themes = array();
        foreach ($skins as $skin)
        {
            list($template_name, $style_name) = explode('|', $skin);
            $themes[$skin] = array('template_name' => $template_name, 'style_name' => $style_name);
        }

        return array(
            'curr_template_name' => $curr_template_name,
            'curr_style_name'    => $curr_style_name,
            'themes'             => $themes
        );
    }
}
/*三级菜单*/
    function _get_member_submenu()
    {
        $menus = array(
            array(
                'name' => 'theme_config',
                'url'  => 'index.php?app=my_theme',
            ),
            );
        return $menus;
    }
?>
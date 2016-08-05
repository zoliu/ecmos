<?php

/**
 *    主题设置控制器
 *
 *    @author    Garbin
 *    @usage    none
 */
class ThemeApp extends BackendApp
{
    /* 列表 */
    function index()
    {
        $themes = list_template('mall');
        $theme_list = array();
        foreach ($themes as $theme)
        {
            $theme_list[$theme] = list_style('mall', $theme);
        }
        $this->assign('curr_template_name', Conf::get('template_name'));
        $this->assign('curr_style_name', Conf::get('style_name'));
        $this->assign('theme_list', $theme_list);

        $this->display('theme.index.html');
    }
    function set()
    {
        $template_name = isset($_GET['template_name']) ? trim($_GET['template_name']) : null;
        $style_name = isset($_GET['style_name']) ? trim($_GET['style_name']) : null;
        if (!$template_name)
        {
            $this->show_warning('no_such_template');

            return;
        }
        if (!$style_name)
        {
            $this->show_warning('no_such_style');

            return;
        }
        $af_setting =& af('settings');
        $af_setting->setAll(array('template_name' => $template_name, 'style_name' => $style_name));

        $this->show_message('set_theme_successed');
    }
    function preview()
    {
        $template_name = isset($_POST['template_name']) ? trim($_POST['template_name']) : null;
        $style_name = isset($_POST['style_name']) ? trim($_POST['style_name']) : null;
        if (!$template_name)
        {
            $this->show_warning('no_such_template');

            return;
        }
        if (!$style_name)
        {
            $this->show_warning('no_such_style');

            return;
        }
        header('Location:' . SITE_URL . '/themes/mall/' .  $template_name . '/styles/' . $style_name . '/screenshot.jpg');
    }
}

?>
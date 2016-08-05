<?php
class ShareArrayfile extends BaseArrayfile
{

    function __construct()
    {
        $this->ShareArrayfile();
    }

    function ShareArrayfile()
    {
        $this->_filename = ROOT_PATH . '/data/share.inc.php';
    }

    function get_default()
    {
        return array (
          1 => array (
            'title' => Lang::get('baidu_shoucang'),
            'link' => 'http://cang.baidu.com/do/add?it={$title}++++++&iu={$link}&fr=ien#nw=1',
            'type' => 'collect',
            'sort_order' => 255,
            'logo' => 'data/system/baidushoucang.gif',
          ),
          2 => array (
            'title' => Lang::get('renrenwang'),
            'link' => 'http://share.renren.com/share/buttonshare.do?link={$link}&title={$title}',
            'type' => 'share',
            'sort_order' => 255,
            'logo' => 'data/system/renren.gif',
          ),
          3 => array (
            'title' => Lang::get('qq_shuqian'),
            'link' => 'http://shuqian.qq.com/post?from=3&title={$title}++++++&uri={$link}&jumpback=2&noui=1',
            'type' => 'collect',
            'sort_order' => 255,
            'logo' => 'data/system/qqshuqian.gif',
          ),
          4 => array (
            'title' => Lang::get('kaixinwang'),
            'link' => 'http://www.kaixin001.com/repaste/share.php?rtitle={$title}&rurl={$link}',
            'type' => 'share',
            'sort_order' => 255,
            'logo' => 'data/system/kaixin001.gif',
          ),
        );
    }

    function drop($share_id)
    {
        $share = $this->getOne($share_id);
        if ($share['logo'] && strpos($share['logo'], 'data/system/') === false)
        {
            file_exists(ROOT_PATH . '/' . $share['logo']) && @unlink(ROOT_PATH . '/' . $share['logo']);
        }
        parent::drop($share_id);
    }

    function getAll()
    {
        $data = array();
        if (!file_exists($this->_filename))
        {
            $data = $this->get_default();
        }
        else
        {
            $data = $this->_loadfromfile();
        }
        return $data;
    }

}
?>
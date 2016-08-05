<?php


class Jd_articleWidget extends BaseWidget
{
    var $_name = 'jd_article';
    var $_ttl  = 86400;
    var $_num ;

    function _get_data()
    {
        $cache_server =& cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        if($data === false)
        {
            $acategory_mod =m('acategory');
            $article_mod =m('article');
			$this->_num=$this->options['amount']?$this->options['amount']:5;
			$conditions = Psmb_init()->Jd_article_get_data($this->options);
            $data = $article_mod->find(array(
                'conditions'    => 'if_show = 1 '.$conditions,
                'order'         => 'sort_order ASC, add_time DESC',
                'fields'        => 'article_id, title, add_time',
                'limit'         => $this->_num,
            ));
            $cache_server->set($key, $data, $this->_ttl);
        }
		return array('article'=>$data,'model_name'=>$this->options['model_name']);;
    }

    function parse_config($input)
    {
        return $input;
    }
	function get_config_datasrc()
    {
		// 取得多级文章分类
        $this->assign('acategories', $this->_get_acategory_options(2));
    }
	function _get_acategory_options($layer = 0)
	{
		$acategory_mod =& m('acategory');
        $acategories = $acategory_mod->get_list();
		foreach($acategories as $key=>$val)
		{
			if($val['code'] == ACC_SYSTEM){
				unset($acategories[$key]);
			}
		}

        import('tree.lib');
        $tree = new Tree();
        $tree->setTree($acategories, 'cate_id', 'parent_id', 'cate_name');

        return $tree->getOptions($layer);
	}
}
?>
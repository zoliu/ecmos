<?php

class Jd_integral_articleWidget extends BaseWidget
{
    var $_name = 'jd_integral_article';
    var $_ttl  = 86400;
    var $_num  = 2;

    function _get_data()
    {
        $cache_server =& cache_server();
        $key = $this->_get_cache_id();
        $data = $cache_server->get($key);
        if($data === false)
        {
            $acategory_mod =m('acategory');
            $article_mod =m('article');
			
			$cate_ids = $acategory_mod->get_descendant($this->options['cate_id']);
			if($cate_ids){
				$conditions = ' AND cate_id ' . db_create_in($cate_ids);
			} else {
				$conditions = '';
			}
            $data= $article_mod->find(array(
                'conditions'    => 'if_show = 1 '.$conditions,
                'order'         => 'sort_order ASC, add_time DESC',
                'fields'        => 'article_id, title, add_time',
                'limit'         => $this->_num,
            ));
            $cache_server->set($key, $data, $this->_ttl);
        }
		$user_info=array();
		if(isset($_SESSION['user_info']['user_id']))
		{
				$member_mod=&m('member');
				$user_login_status= true;
				$user_info= $member_mod->get(array(
					'conditions'=>$_SESSION['user_info']['user_id'],
					'join'      =>'has_integral',
					'fields'    =>'user_name,portrait,amount',
				));
		}
		empty($user_info['portrait']) && $user_info['portrait'] = Conf::get('default_user_portrait') ;
		return array(
			'article'			=>$data,
			'user_login_status' =>$user_login_status,
			'user_info'         =>$user_info
		);
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
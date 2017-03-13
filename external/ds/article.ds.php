<?php
/*
 *文章标题数据源
 *{ds name=article type=article return=article}
 *{$article|modifier:var_dump}
 *type:(view：文章内容，pre_article：上一篇文章，next_article：下一篇文章)
 */
class ArticleDs extends baseDs {
	function __construct() {
		parent::__construct();
	}
	/**
	 * 调用文章详细
	 * {ds name=article type=view article_id=?}
	 *
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function dsView($params) {
		$article_id = $params['article_id'];
		if (intval($article_id) < 1) {
			return;
		}
		$data = LM('article')->where($article_id)->get();
		return $data;
	}
	/**
	 * 调用文章最新
	 * {ds name=article type=new num=? cate_id=? code=? store_id=?}
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function dsNew($params) {
		return $this->_list($params, '');
	}

	/**
	 * 按排序调用
	 * {ds name=article type=sort num=? cate_id=? code=? store_id=?}
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function dsSort($params) {
		return $this->_list($params, 'sort_order desc');
	}

	public function _list($params, $order = '') {
		$num = $params['num'];
		$order = empty($order) ? ' add_time desc' : '';
		$store_id = intval($params['store_id']) > 0 ? ' store_id=' . $params['store_id'] : 'store_id=0';
		$cate_id = intval($params['cate_id']) > 0 ? 'cate_id=' . $params['cate_id'] : '';
		$code = !empty($params['code']) ? " code='{$params['code']}'" : "code=''";
		$page = $this->_get_page(16);
		if (isset($params['page'])) {
			$num = $page['limit'];
			$data = LM('article')->where('if_show=1')
				->where($store_id)
				->where($code)
				->where($cate_id)
				->orderBy($order)
				->count()
				->limit($num)->find();
			$page['item_count'] = LM('article')->getCount();
			$this->assign('page', $page);
			return $data;
		} else {
			$data = LM('article')->where('if_show=1')
				->where($store_id)
				->where($code)
				->where($cate_id)
				->orderBy($order)
				->limit($num)->find();
			return $data;
		}

	}

}

?>
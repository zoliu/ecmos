<?php
/**
 *    模板编辑器
 */
class Recom_cateApp extends BackendApp
{
    var $rcate_mob;
    var $recom_mob;

    function __construct()
    {
        $this->Recom_cateApp();
    }

    function Recom_cateApp()
    {
        parent::BackendApp();

        $this->rcate_mob =& m('rcategory');
        $this->recom_mob =& m('recommendation');
    }
    /* 可编辑的页面列表 */
    function index()
    {
        $conditions = $this->_get_query_conditions(array(array(
                'field' => 'cate_name',
                'equal' => 'LIKE',
                'assoc' => 'AND',
                'name'  => 'cate_name',
                'type'  => 'string',
            ),
        ));
        $page   =   $this->_get_page(10);   //获取分页信息
        //更新排序
        if (isset($_GET['sort']) && isset($_GET['order']))
        {
            $sort  = strtolower(trim($_GET['sort']));
            $order = strtolower(trim($_GET['order']));
            if (!in_array($order,array('asc','desc')))
            {
             $sort  = 'sort_order';
             $order = 'asc';
            }
        }
        else
        {
            $sort  = 'sort_order';
            $order = 'asc';
        }
        $verify =  empty($_GET['wait_verify']) ? ' AND if_show = 1' : ' AND if_show = 0';
        $rcate=$this->rcate_mob->find(array(
        'conditions'    => '1=1' . $conditions . $verify,
        'limit'         => $page['limit'],
        'order'         => "$sort $order",
        'count'         => true
        ));
        $page['item_count']=$this->rcate_mob->getCount();   //获取统计数据
        /* 导入jQuery的表单验证插件 */
        $this->import_resource(array(
            'script' => 'jqtreetable.js,inline_edit.js',
            'style'  => 'res:style/jqtreetable.css'
        ));
        $this->_format_page($page);
        $this->assign('filtered', $conditions? 1 : 0); //是否有查询条件
        $this->assign('wait_verify', $_GET['wait_verify']);
        $this->assign('page_info', $page);   //将分页信息传递给视图，用于形成分页条
        $this->assign('rcate', $rcate);
        $this->display('recom_cate.index.html');
    }
    function add(){
        if (!IS_POST)
        {
            $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js'
            ));
            $yes_or_no = array(
                1 => Lang::get('yes'),
                0 => Lang::get('no'),
            );
            $this->assign('yes_or_no', $yes_or_no);
            $this->display('recom_cate.form.html');
        }
        else
        {
            $data = array();
            $data['cate_name']     = $_POST['cate_name'];
            $data['cate_desc']     = $_POST['cate_desc'];
            $data['text']          = $_POST['text'];
            $data['key_words']     = $_POST['key_words'];
            $data['if_show']    = $_POST['if_show'];
            $data['sort_order'] = $_POST['sort_order'];

            $this->rcate_mob->add($data); //

            $this->show_message('add_rcate_successed',
                'back_list',    'index.php?app=recom_cate',
                'continue_add', 'index.php?app=recom_cate&amp;act=add'
            );
        }
    }

    function edit(){
        if (!IS_POST)
        {
            $this->import_resource(array(
                'script' => 'jquery.plugins/jquery.validate.js'
            ));
            $yes_or_no = array(
                1 => Lang::get('yes'),
                0 => Lang::get('no'),
            );
            $this->assign('yes_or_no', $yes_or_no);
            $cate_id=isset($_GET['cate_id']) && !empty($_GET['cate_id'])?trim($_GET['cate_id']):'';
            $recom=$this->rcate_mob->get($cate_id);

            $this->assign('recom', $recom);
            $this->display('recom_cate.form.html');
        }
        else
        {
            $data = array();
            $data['cate_name']     = $_POST['cate_name'];
            $data['cate_desc']     = $_POST['cate_desc'];
            $data['text']          = $_POST['text'];
            $data['key_words']          = $_POST['key_words'];
            $data['if_show']    = $_POST['if_show'];
            $data['sort_order'] = $_POST['sort_order'];
            $cate_id=isset($_POST['cate_id']) && !empty($_POST['cate_id'])?trim($_POST['cate_id']):'';
            $this->rcate_mob->edit($cate_id,$data); //

            $this->show_message('edit_rcate_successed',
                'back_list',    'index.php?app=recom_cate',
                'continue_edit', 'index.php?app=recom_cate&amp;act=edit&amp;cate_id='.$cate_id
            );
        }
    }

    function drop()
    {
        $cate_id = isset($_GET['cate_id']) ? trim($_GET['cate_id']) : '';
        if (!$cate_id)
        {
            $this->show_warning('no_such_brand');

            return;
        }
        $cate_id=explode(',',$cate_id);
        $this->rcate_mob->drop($cate_id);
        if ($this->rcate_mob->has_error())    //删除
        {
            $this->show_warning($this->rcate_mob->get_error());

            return;
        }
        $this->show_message('drop_rcate_successed',
                'back_list',    'index.php?app=recom_cate');
    }
}

?>
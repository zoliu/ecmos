<?php

class DatacallModule extends IndexbaseModule
{
    var $_datacall_mod;         //数据调用模型
    var $_x_mod;                //需要调用的是那种数据，目前只是商品数据，以后可能会有店铺数据等等
    var $_expires;              //缓存到期时间
    var $name_length;           //保留的数据长度，大于此长度截取
    var $charset;               //字符编码
    var $_doc_content;          //输出内容

    function __construct()
    {
        $this->DatacallModule();
    }

    function DatacallModule()
    {
        parent::__construct();
        $this->_datacall_mod = &af("datacall");
    }

    function index()
    {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (!$this->is_cached($id))                         //检查缓存是否过期
        {
            $data = $this->_datacall_mod->getOne($id);
            if (empty($data))
            {
                return;
            }
            $this->name_length = $data['name_length'];
            $this->_expires    = time() + $data['cache_time'];
            $this->charset = in_array($data['content_charset'], array('utf-8', 'gbk', 'big5')) ? $data['content_charset'] : CHARSET;
            if ($data['type'] == 'goods') //调用数据的类型为商品
            {
                $this->_x_mod = &m('goods');
                $conditions = '';
                if (!empty($data['spe_data']['keywords']))    //关键字的SQL
                {
                    if (strpos($data['spe_data']['keywords'], ' ') > 0)
                    {
                        $tmp_str = explode(' ', $data['spe_data']['keywords']);
                        $tmp_con = '';
                        foreach ($tmp_str as $val)
                        {
                            $tmp_con .= "OR g.goods_name LIKE '%{$val}%' OR g.brand LIKE '%{$val}%'";
                        }
                        $tmp_con = substr_replace($tmp_con, '', 0, 2);
                        $conditions .= 'AND ('. $tmp_con . ')';
                    }
                    else
                    {
                        $conditions .= "AND (g.goods_name LIKE '%{$data['spe_data']['keywords']}%' OR g.brand LIKE '%{$data['spe_data']['keywords']}%')";
                    }
                    unset($tmp_con);
                }
                if (!empty($data['spe_data']['cate_id']))   //商品分类的where
                {
                    $gcategory = &m('gcategory');
                    $ids = $gcategory->get_descendant($data['spe_data']['cate_id']);
                    $conditions .= " AND g.cate_id " . db_create_in($ids);
                    unset($ids);
                }

                if (!empty($data['spe_data']['brand_name']))    //品牌where
                {
                    $conditions .= " AND g.brand LIKE '%{$data['spe_data']['brand_name']}%'";
                }

                if (!empty($data['spe_data']['max_price']))      //价格最大值where
                {
                    $conditions .= " AND gs.price < {$data['spe_data']['max_price']}";
                }

                if (!empty($data['spe_data']['min_price']))     //价格最小值where
                {
                    $conditions .= " AND gs.price > {$data['spe_data']['min_price']}";
                }

/*                if (!empty($data['spe_data']['recommend']))     //是否推荐
                {
                    $conditions .= " AND  g.recommended = 1";
                }*/

                $order = '';
                if (!empty($data['spe_data']['sort_order']))   //排序where
                {
                    $order = in_array($data['spe_data']['sort_order'], array('add_time','last_update')) ? "g.".$data['spe_data']['sort_order']." ".$data['spe_data']['asc_desc'] : "gst.".$data['spe_data']['sort_order']." ".$data['spe_data']['asc_desc'];
                }

                $con = array(
                    'conditions' => "1=1 ". $conditions,
                    'order' => $order,
                );
                if (!empty($data['amount']))
                {
                    $con['limit'] = "0, ".$data['amount'];
                }
                $result = $this->_x_mod->get_list($con);
                if (empty($result))
                {
                    return ;
                }
                $this->js_write($data['header']);
                $body = $data['body'];
                foreach ($result as $val)
                {
                    $code = str_replace('{goods_name}', empty($this->name_length) ? $val['goods_name'] : sub_str($val['goods_name'], $this->name_length), $body);
                    $code = str_replace('{goods_full_name}', $val['goods_name'], $code);
                    $code = str_replace('{goods_price}', $val['price'], $code);
                    $code = str_replace('{goods_url}', site_url() . '/index.php?app=goods&amp;id='. $val['goods_id'], $code);
                    $code = str_replace('{goods_image_url}', site_url() . '/' . $val['default_image'], $code);
                    $content .= $code;
                    unset($code);
                }
                $this->js_write($content);
                $this->js_write($data['footer']);
                $this->save_cache($id);
            }
        }
        $this->doc_output();
    }

    function is_cached($id)
    {
        $file_path = ROOT_PATH . '/temp/js/datacallcache'. $id .'.js';

        if (is_file($file_path))
        {
            $content = file_get_contents($file_path);
            $idx = strpos($content , "%^@#!*");
            $str = substr($content, 0 , $idx);

            $arr = explode('|', $str);

            $this->charset = $arr[0];
            $this->_expires  = $arr[1];

            if (time() > $this->_expires)
            {
                return false;
            }
            else
            {
                $this->_doc_contents = substr($content, $idx + 6);
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    function js_write($str)
    {
        $str = str_replace("\r", "", $str);
        $str = str_replace("\n", "", $str);
        $str = str_replace("'", "\\'", $str);
        $this->_doc_contents .= 'document.write(\''. $str .'\');';
    }

    function save_cache($id)
    {
        $file_path = ROOT_PATH . '/temp/js/datacallcache'. $id .'.js';
        ecm_mkdir(dirname($file_path));
        file_put_contents($file_path, "{$this->charset}|{$this->_expires}%^@#!*" . $this->_doc_contents);
    }

    function doc_output()
    {
        header("Content-type:text/html;charset=" . $this->charset , true);
        $tmp_str = ecm_iconv(CHARSET, $this->charset, $this->_doc_contents);
        $output = $this->charset == 'utf-8' ? stripslashes($tmp_str) : $tmp_str;
        echo $output;
    }


}

?>

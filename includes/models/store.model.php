<?php

/* 店铺 store */
class StoreModel extends BaseModel
{
    var $table  = 'store';
    var $prikey = 'store_id';
    var $alias  = 's';
    var $_name  = 'store';

    var $_relation = array(
        // 一个店铺有多个支付方式
        'has_payment' => array(
            'model'         => 'payment',
            'type'          => HAS_MANY,
            'foreign_key'   => 'store_id',
            'dependent'     => true
        ),
        // 一个店铺有多个配送方式
        'has_shipping' => array(
            'model'         => 'shipping',
            'type'          => HAS_MANY,
            'foreign_key'   => 'store_id',
            'dependent'     => true
        ),
        // 一个店铺有多个商品分类
        'has_gcategory' => array(
            'model'         => 'gcategory',
            'type'          => HAS_MANY,
            'foreign_key' => 'store_id',
            'dependent' => true
        ),
        // 一个店铺有多个商品
        'has_goods' => array(
            'model'         => 'goods',
            'type'          => HAS_MANY,
            'foreign_key'   => 'store_id',
            'dependent' => true
        ),
        // 一个店铺有多个订单
        'has_order' => array(
            'model'         => 'order',
            'type'          => HAS_MANY,
            'foreign_key'   => 'seller_id',
            'dependent' => true
        ),
        // 一个店铺有多个推荐类型
        'has_recommend' => array(
            'model'         => 'recommend',
            'type'          => HAS_MANY,
            'foreign_key' => 'store_id',
            'dependent' => true
        ),
        // 一个店铺有多个文章
        'has_article' => array(
            'model'         => 'article',
            'type'          => HAS_MANY,
            'foreign_key' => 'store_id',
            'dependent' => true
        ),
        // 一个店铺有多个pageivew
        'has_pageview' => array(
            'model'         => 'pageview',
            'type'          => HAS_MANY,
            'foreign_key'   => 'store_id',
            'dependent'     => true
        ),
        // 一个店铺有多个友情链接
        'has_partner' => array(
            'model'         => 'partner',
            'type'          => HAS_MANY,
            'foreign_key'   => 'store_id',
            'dependent'     => true
        ),
        'has_cart'    => array(
            'type'          => HAS_MANY,
            'model'         => 'cart',
            'foreign_key'   => 'store_id',
        ),
        // 一个店铺属于一个会员
        'belongs_to_user' => array(
            'model'         => 'member',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'store_id',
            'reverse'       => 'has_store',
        ),
        // 一个店铺属于一个等级
        'belongs_to_sgrade' => array(
            'model'         => 'sgrade',
            'type'          => BELONGS_TO,
            'foreign_key'   => 'store_id',
            'reverse'       => 'has_store',
        ),
        // 店铺和会员是多对多的关系（会员收藏店铺）
        'be_collect' => array(
            'model'         => 'member',
            'type'          => HAS_AND_BELONGS_TO_MANY,
            'middle_table'  => 'collect',
            'foreign_key'   => 'item_id',
            'ext_limit'     => array('type' => 'store'),
            'reverse'       => 'collect_store',
        ),
        // 店铺和分类是多对多的关系
        'has_scategory' => array(
            'model'         => 'scategory',
            'type'          => HAS_AND_BELONGS_TO_MANY,
            'middle_table'  => 'category_store',
            'foreign_key'   => 'store_id',
            'reverse'       => 'belongs_to_store',
        ),
        // 店铺和会员是多对多的关系（会员拥有店铺权限）
        'be_manage' => array(
            'model'        => 'member',
            'type'         => HAS_AND_BELONGS_TO_MANY,
            'middle_table' => 'user_priv',
            'foreign_key'  => 'store_id',
            'reverse'      => 'manage_store',
        ),
         //一个店铺对应多个上传文件
        'has_uploadedfile' => array(
            'model'             => 'uploadedfile',
            'type'              => HAS_MANY,
            'foreign_key'       => 'store_id',
            'dependent'         => true
        ),
        //一个店铺对应多个商品咨询
        'has_question' => array(
            'model'       =>'goodsqa',
            'type'        => HAS_MANY,
            'foreign_key'     => 'store_id',
            'dependent' => true,
        ),
        // 一个店铺可以有多个优惠券
        'has_coupon' => array(
            'model'       =>'coupon',
            'type'        => HAS_MANY,
            'foreign_key' => 'store_id',
            'dependent'   => true,
        ),
        //店铺和团购活动是一对多关系
        'has_groupbuy' => array(
            'model' => 'groupbuy',
            'type' => HAS_MANY,
            'foreign_key' => 'store_id',
            'dependent'   => true, // 依赖
        ),
    );

    var $_autov = array(
        'owner_name' => array(
            'required'  => true,
            'filter'    => 'trim',
        ),
        'store_name' => array(
            'required'  => true,
            'filter'    => 'trim',
        ),
    );

    /*
     * 判断名称是否唯一
     */
    function unique($store_name, $store_id = 0)
    {
        $conditions = "store_name = '" . $store_name . "'";
        $store_id && $conditions .= " AND store_id <> '" . $store_id . "'";
        return count($this->find(array('conditions' => $conditions))) == 0;
    }

    /**
     * 取得信息
     */
    function get_info($store_id)
    {
        $info = $this->get(array(
            'conditions' => $store_id,
            'join'       => 'belongs_to_user',
            'fields'     => 'this.*,member.user_name, member.email',
        ));
        if (!empty($info['certification']))
        {
            $info['certifications'] = explode(',', $info['certification']);
        }
        return $info;
    }

    /* 新增 */
    function add($data, $compatible = false)
    {
        $res = parent::add($data, $compatible);
        if ($res === false)
        {
            return false;
        }
        $store_id = $data['store_id'];
        $userpriv_mod =& m('userpriv');
        $userpriv_mod->add(array(
            'store_id' => $store_id,
            'user_id'  => $store_id,
            'privs'    => 'all',
        ));

        return $res;
    }

    /**
     * 获取地区检索菜单数据
     *
     */
    function list_regions()
    {
        $data = array();
        $sql = "SELECT region_id, region_name, count(*) as count FROM {$this->table} WHERE region_id > 0 GROUP BY region_id ORDER BY count DESC LIMIT 50";
        $res = $this->db->query($sql);
        while ($row = $this->db->fetchRow($res))
        {
            $data[$row['region_id']] = $row['region_name'];
        }
        return $data;
    }

    /**
     *    重新计算信用度
     *
     *    @author    Garbin
     *    @param     int $store_id
     *    @return    int
     */
    function recount_credit_value($store_id)
    {
        $credit_value = 0;
        $model_ordergoods =& m('ordergoods');
        /* 找出所有is_valid为1的商品评价记录，计算他们的credit_value的和 */
        $info = $model_ordergoods->get(array(
            'join'          => 'belongs_to_order',
            'conditions'    => "seller_id={$store_id} AND evaluation_status=1 AND is_valid = 1",
            'fields'        => 'SUM(credit_value) AS credit_value',
            'index_key'     => false,   /* 不需要索引 */
        ));
        $credit_value = $info['credit_value'];

        return $credit_value;
    }

    /**
     *    重新计算好评率
     *
     *    @author    Garbin
     *    @param     int $store_id
     *    @return    float
     */
    function recount_praise_rate($store_id)
    {
        $praise_rate = 0.00;
        $model_ordergoods =& m('ordergoods');

        /* 找出所有is_valid为1的商品中的商品评价记录总数 */
        $info  = $model_ordergoods->get(array(
            'join'          => 'belongs_to_order',
            'conditions'    => "seller_id={$store_id} AND evaluation_status=1 AND is_valid=1",
            'fields'        => 'COUNT(*) as evaluation_count',
            'index_key'     => false,   /* 不需要索引 */
        ));
        $evaluation_count = $info['evaluation_count'];
        if (!$evaluation_count)
        {
            return $praise_count;
        }

        /* 找出所有的evaluation为3的记录总数 */
        $info = $model_ordergoods->get(array(
            'join'          => 'belongs_to_order',
            'conditions'    => "seller_id={$store_id} AND evaluation_status=1 AND is_valid=1 AND evaluation=3",
            'fields'        => 'COUNT(*) as praise_count',
            'index_key'     => false,   /* 不需要索引 */
        ));
        $praise_count = $info['praise_count'];
        /* 计算好评数占总数的百分比 */
        $praise_rate = round(($praise_count / $evaluation_count), 4) * 100;

        return $praise_rate;
    }

    /**
     * 取得店铺设置信息：包括允许发布商品数，上传空间大小，店铺过期时间等等
     */
    function get_settings($store_id)
    {
        return $this->get(array(
            'conditions' => $store_id,
            'fields' => 'sgrade.*',
            'join' => 'belongs_to_sgrade',
        ));
    }

    /**
     * 根据信用值计算图标
     *
     * @param   int     $credit_value   信用值
     * @param   int     $step           最低等级升级所需信用值
     * @return  string  图片文件名
     */
    function compute_credit($credit_value, $step = 5)
    {
        $level_1 = $step * 5;
        $level_2 = $level_1 * 6;
        $level_3 = $level_2 * 6;
        $level_4 = $level_3 * 6;
        $level_5 = $level_4 * 6;
        if ($credit_value < $level_1)
        {
            return 'heart_' . (floor($credit_value / $step) + 1) . '.gif';
        }
        elseif ($credit_value < $level_2)
        {
            return 'diamond_' . (floor(($credit_value - $level_1) / $level_1) + 1) . '.gif';
        }
        elseif ($credit_value < $level_3)
        {
            return 'crown_' . (floor(($credit_value - $level_2) / $level_2) + 1) . '.gif';
        }
//        elseif ($credit_value < $level_4)
//        {
//            return (floor(($credit_value - $level_3) / $level_3) + 1) . 'level4' . '.gif';
//        }
//        elseif ($credit_value < $level_5)
//        {
//            return (floor(($credit_value - $level_4) / $level_4) + 1) . 'level5' . '.gif';
//        }
        else
        {
            return 'level_end.gif';
        }
    }

    /**
     *    检查二级域名是否存在
     *
     *    @author    Garbin
     *    @param     string $subdomain  要注册的二级域名
     *    @param     string $reserved   系统保留的域名
     *    @param     string $length     系统限制的注册长度
     *    @return    bool
     */
    function check_domain($subdomain, $reserved, $length)
    {
        if (!$subdomain)
        {
            return true;
        }
        if (!preg_match("/^[a-z0-9]+$/i", $subdomain))
        {
            $this->_error('domain_format_error');

            return false;
        }

        /* 检查是否是保留域名 */
        if ($reserved)
        {
            if (in_array($subdomain, explode(',', $reserved)))
            {
                $this->_error('reserved_domain');

                return false;
            }
        }

        /* 检查长度是否合法 */
        if ($length)
        {
            list($min, $max) = explode('-', $length);
            if (strlen($subdomain) < $min || strlen($subdomain) > $max)
            {
                $this->_error('domain_length_error', $length);

                return false;
            }
        }

        /* 检查唯一性 */
        if ($this->get("domain='{$subdomain}'"))
        {
            $this->_error('domain_exists');

            return false;
        }

        return true;
    }

    function clear_cache($store_id)
    {
        $cache_server =& cache_server();
        $keys = array('function_get_store_data_' . $store_id);
        foreach ($keys as $key)
        {
            $cache_server->delete($key);
        }
    }

    function edit($conditions, $edit_data)
    {
        $store_list = $this->find(array(
            'fields'     => 'store_id',
            'conditions' => $conditions,
        ));
        foreach ($store_list as $store)
        {
            // 清除缓存
            $this->clear_cache($store['store_id']);
        }

        return parent::edit($conditions, $edit_data);
    }

    function drop($conditions, $fields = '')
    {
        /* 清除缓存 */
        $store_list = $this->find(array(
            'fields'     => 'store_id',
            'conditions' => $conditions,
        ));
        foreach ($store_list as $store)
        {
            $this->clear_cache($store['store_id']);
        }

        return parent::drop($conditions, $fields);
    }

    /* 取得本店所有商品分类 */
    function get_sgcategory_options($store_id)
    {
        $mod =& bm('gcategory', array('_store_id' => $store_id));
        $gcategories = $mod->get_list();
        import('tree.lib');
        $tree = new Tree();
        $tree->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');
        return $tree->getOptions();
    }
}

?>
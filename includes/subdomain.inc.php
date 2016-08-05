<?php

/**
 *    二级域名解析
 *
 *    @author    Garbin
 */

/* 获取二级域名 */
$subdomain = get_subdomain();

/* 没有二级域名，不解析 */
if ($subdomain === false)
{
    return;
}

/* 二级域名功能未开启，不解析 */
if (!ENABLED_SUBDOMAIN)
{
    //header('Location:' . SITE_URL);
    return;
}

/* 解析对应的二级域名到对应的店铺上 */
$store_id = get_subdomain_store_id($subdomain);
if ($store_id === false)
{
    /* 无效的二级域名 */
    //header('Location:' . SITE_URL);
    return;
}

/* 目前只支持店铺首页二级域名 */
define('SUBDOMAIN', $subdomain);
$_GET['app'] = $_REQUEST['app'] = 'store';
$_GET['act'] = $_REQUEST['act'] = 'index';
$_GET['id'] = $_REQUEST['id'] = $store_id;


/**
 *    获取自定义二级域名
 *
 *    @author    Garbin
 *    @return    string     成功
 *               false      失败
 */
function get_subdomain()
{
    $curr_url_info = parse_url(get_domain());
    $main_url_info = parse_url(SITE_URL);
    $curr_domain = strtolower($curr_url_info['host']);
    $main_domain = strtolower($main_url_info['host']);
    if ($curr_domain == $main_domain)
    {
        /* 当前域名不是二级域名 */
        return false;
    }
    $tmp = explode('.', $curr_domain);

    return $tmp[0];
}

/**
 *    获取二级域名对应的店铺ID
 *
 *    @author    Garbin
 *    @param     string $subdomain
 *    @return    int    成功
 *               false  失败
 */
function get_subdomain_store_id($subdomain)
{
    #TODO 获取对应的店铺ID
    $model_store =& m('store');
    $store_info = $model_store->get(array(
        'conditions'    => "domain='{$subdomain}'",
        'join'          => 'belongs_to_sgrade',
        'fields'        => 'store_id, functions',
    ));
    if (empty($store_info))
    {
        return false;
    }
    /* 等级不允许使用二级域名 */
    if (!in_array('subdomain', explode(',', $store_info['functions'])))
    {
        return false;
    }

    return $store_info['store_id'];
}

?>
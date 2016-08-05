<?php

define('UC_CLIENT_VERSION', '1.5.0');    //note UCenter 版本标识
define('UC_CLIENT_RELEASE', '20081031');

define('API_DELETEUSER', 1);            //note 用户删除 API 接口开关
define('API_RENAMEUSER', 1);            //note 用户改名 API 接口开关
define('API_GETTAG', 1);                //note 获取标签 API 接口开关
define('API_SYNLOGIN', 1);              //note 同步登录 API 接口开关
define('API_SYNLOGOUT', 1);             //note 同步登出 API 接口开关
define('API_UPDATEPW', 0);              //note 更改用户密码 开关
define('API_UPDATEBADWORDS', 0);        //note 更新关键字列表 开关
define('API_UPDATEHOSTS', 0);           //note 更新域名解析缓存 开关
define('API_UPDATEAPPS', 1);            //note 更新应用列表 开关
define('API_UPDATECLIENT', 1);          //note 更新客户端缓存 开关
define('API_UPDATECREDIT', 0);          //note 更新用户积分 开关
define('API_GETCREDITSETTINGS', 0);     //note 向 UCenter 提供积分设置 开关
define('API_GETCREDIT', 0);             //note 获取用户的某项积分 开关
define('API_UPDATECREDITSETTINGS', 0);  //note 更新应用积分设置 开关

define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');

class UcApp extends ApiApp
{
    var $user_mod;

    function __construct()
    {
        $this->UcApp();
    }

    function _serialize($arr, $htmlon = 0) {
        if(!function_exists('xml_serialize')) {
            include_once ROOT_PATH . './uc_client/lib/xml.class.php';
        }
        return xml_serialize($arr, $htmlon);
    }

    function UcApp()
    {
        parent::__construct();
        $this->appdir   = ROOT_PATH . '/';
        $this->user_mod =& m('member');
    }

    function index()
    {
        /* 只提供普通的http通知方式 */
        error_reporting(0);
        set_magic_quotes_runtime(0);

        $_DCACHE = $get = $post = array();

        $code = @$_GET['code'];
        parse_str(_authcode($code, 'DECODE', UC_KEY), $get);
        $get = _stripslashes($get);

        $timestamp = time();
        if($timestamp - $get['time'] > 3600) {
            exit('Authracation has expiried');
        }
        if(empty($get)) {
            exit('Invalid Request');
        }
        $action = $get['action'];

        include(ROOT_PATH . '/uc_client/lib/xml.class.php');
        $post = xml_unserialize(file_get_contents('php://input'));

        if(in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcreditsettings', 'updatecreditsettings'))) {
            exit($this->$get['action']($get, $post));
        } else {
            exit(API_RETURN_FAILED);
        }
    }

    /* 测试能否连接 */
    function test($get, $post)
    {
        return API_RETURN_SUCCEED;
    }
    function gettag($get, $post)
    {
        $name = $get['id'];
        if(!API_GETTAG) {
            return API_RETURN_FORBIDDEN;
        }

        $name = trim($name);
        if(empty($name) || !preg_match('/^([\x7f-\xff_-]|\w|\s)+$/', $name) || strlen($name) > 20) {
            return API_RETURN_FAILED;
        }
        $m_goods = &m('goods');
        $tmp = $m_goods->db->getAll("SELECT g.goods_id, g.goods_name, g.add_time, g.default_image, g.price, m.user_id, m.user_name FROM `ecm_goods` g LEFT JOIN `ecm_member` m ON g.store_id=m.user_id WHERE g.closed=0 AND if_show=1 AND tags LIKE '%,{$name},%' ORDER BY add_time DESC LIMIT 10");
        $goods_list = array();
        if ($tmp)
        {
            foreach ($tmp as $goods)
            {
                $goods_list[] = array(
                    'goods_name'    => $goods['goods_name'],
                    'uid'    => $goods['user_id'],
                    'user_name'    => $goods['user_name'],
                    'dateline'    => $goods['add_time'],
                    'url'    => SITE_URL . '/' . url('app=goods&id=' . $goods['goods_id']),
                    'image'    => $goods['default_image'] ? SITE_URL . '/' . $goods['default_image'] : SITE_URL . '/' . Conf::get('default_goods_image'),
                    'goods_price'    => $goods['price'],
                );
            }
        }
        $return = array($name, $goods_list);

        return $this->_serialize($return, 1);
    }

    /* 删除用户 */
    function deleteuser($get, $post)
    {
        if (!API_DELETEUSER)
        {
            return API_RETURN_FORBIDDEN;
        }

        /* 同步删除本地用户 */
        $this->user_mod->drop('user_id IN (' . $get['ids'] . ')');
        if ($this->user_mod->has_error())
        {
            return API_RETURN_FAILED;
        }
        return API_RETURN_SUCCEED;
    }

    /* 修改用户名 */
    function renameuser($get, $post) {
        if(!API_RENAMEUSER) {
            return API_RETURN_FORBIDDEN;
        }
        $uid = $get['uid'];
        $usernameold = $get['oldusername'];
        $usernamenew = $get['newusername'];

        /* 修改本地用户名 */
        $this->user_mod->edit($uid, array('user_name' => $usernamenew));

        /* 更新订单中的买家用户名 */
        $model_order =& m('order');
        $model_order->edit("buyer_id={$uid}", array('buyer_name' => $usernamenew));

        return API_RETURN_SUCCEED;
    }

    /* 同步登陆 */
    function synlogin($get, $post) {
        $uid = $get['uid'];
        $username = $get['username'];
        if(!API_SYNLOGIN) {
            return API_RETURN_FORBIDDEN;
        }

        //note 同步登录 API 接口
        $ec_user = $this->user_mod->get($uid);
        if ($ec_user)
        {
            $this->_do_login($ec_user['user_id']);
        }

        return API_RETURN_SUCCEED;
    }

    /* 同步退出 */
    function synlogout($get, $post) {
        if(!API_SYNLOGOUT) {
            return API_RETURN_FORBIDDEN;
        }

        $this->_do_logout();

        return API_RETURN_SUCCEED;
    }

    /* 更新应用列表 */
    function updateapps($get, $post) {
        if(!API_UPDATEAPPS) {
            return API_RETURN_FORBIDDEN;
        }
        $UC_API = $post['UC_API'];

        //note 写 app 缓存文件
        $cachefile = $this->appdir.'./uc_client/data/cache/apps.php';
        $fp = fopen($cachefile, 'w');
        $s = "<?php\r\n";
        $s .= '$_CACHE[\'apps\'] = '.var_export($post, TRUE).";\r\n";
        fwrite($fp, $s);
        fclose($fp);

        //note 写配置文件
        if(is_writeable($this->appdir.'./config.inc.php')) {
            $configfile = trim(file_get_contents($this->appdir.'./config.inc.php'));
            $configfile = substr($configfile, -2) == '?>' ? substr($configfile, 0, -2) : $configfile;
            $configfile = preg_replace("/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$UC_API');", $configfile);
            if($fp = @fopen($this->appdir.'./config.inc.php', 'w')) {
                @fwrite($fp, trim($configfile));
                @fclose($fp);
            }
        }

        return API_RETURN_SUCCEED;
    }

    /* 更新客户端缓存 */
    function updateclient($get, $post) {
        if(!API_UPDATECLIENT) {
            return API_RETURN_FORBIDDEN;
        }
        $cachefile = $this->appdir.'./uc_client/data/cache/settings.php';
        $fp = fopen($cachefile, 'w');
        $s = "<?php\r\n";
        $s .= '$_CACHE[\'settings\'] = '.var_export($post, TRUE).";\r\n";
        fwrite($fp, $s);
        fclose($fp);

        return API_RETURN_SUCCEED;
    }
    /* 修改密码 */
    /*
    function updatepw($get, $post) {
        if(!API_UPDATEPW) {
            return API_RETURN_FORBIDDEN;
        }
        $username = $get['username'];
        $password = $get['password'];

        $newpw = md5(time().rand(100000, 999999)); // 随便设置了一个密码，登陆时再同步
        $this->user_mod->edit("user_name = '$username'", array('password' => $newpw));

        return API_RETURN_SUCCEED;
    }
    */
}


function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    $ckey_length = 4;

    $key = md5($key ? $key : UC_KEY);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if($operation == 'DECODE') {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
                return '';
            }
    } else {
        return $keyc.str_replace('=', '', base64_encode($result));
    }

}

function _stripslashes($string) {
    if(is_array($string)) {
        foreach($string as $key => $val) {
            $string[$key] = _stripslashes($val);
        }
    } else {
        $string = stripslashes($string);
    }
    return $string;
}

?>

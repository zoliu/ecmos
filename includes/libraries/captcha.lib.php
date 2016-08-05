<?php

/**
 * ECMALL: 验证码类
 * ============================================================================
 * 版权所有 (C) 2005-2008 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.shopex.cn
 * -------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Id: captcha.lib.php 7840 2009-05-21 06:14:05Z lizhaosheng $
 */
if (!defined('IN_ECM'))
{
    trigger_error('Hacking attempt', E_USER_ERROR);
}

/**
 * 用例如下
完整写法
include_once(ROOT_PATH.'/includes/cls.captcha.new.php');
$seccode = 'asdf';
$code = new Captcha();
$code->code = $seccode;
$code->width = 150;
$code->height = 60;
$code->background = 1;
$code->adulterate = 1;
$code->ttf = 1;
$code->angle = 1;
$code->color = 1;
$code->size = 1;
$code->shadow = 1;
$code->animator = 0;
$code->display();
$code->fontpath = ROOT_PATH.'/includes/captcha/fonts/';
$code->imagepath = ROOT_PATH.'/includes/captcha/';

简单用法
include_once(ROOT_PATH.'/includes/cls.captcha.new.php');
$seccode = 'asdf';
$code = new Captcha();
$code->code = $seccode;
$code->display();
 */

class Captcha
{

    var $code;            //a-z 范围内随机
    var $width     = 150;        //宽度
    var $height     = 60;        //高度
    var $background    = 1;        //随机图片背景
    var $adulterate    = 1;        //随机背景图形
    var $ttf     = 1;        //随机 TTF 字体
    var $angle     = 0;        //随机倾斜度
    var $color     = 1;        //随机颜色
    var $size     = 0;        //随机大小
    var $shadow     = 1;        //文字阴影
    var $animator     = 0;        //GIF 动画
    var $fontpath    = '';        //TTF字库目录
    var $imagepath    = '';        //图片目录

    var $fontcolor;
    var $im;

    function __construct($options = array())
    {
        $this->Captcha($options);
    }
    function Captcha($options = array())
    {
        $this->fontpath = ROOT_PATH . '/includes/captcha/fonts/';
        $this->imagepath = ROOT_PATH . '/includes/captcha/';
        isset($options['width']) && $this->width = $options['width'];
        isset($options['height'])&& $this->height= $options['height'];
    }

    function display($code)
    {
        session_cache_limiter('nocache');

        $this->code = strtoupper($code);
        if (function_exists('imagecreate') && function_exists('imagecolorset') && function_exists('imagecopyresized') && function_exists('imagecolorallocate') && function_exists('imagechar') && function_exists('imagecolorsforindex') && function_exists('imageline') && function_exists('imagecreatefromstring') && (function_exists('imagegif') || function_exists('imagepng') || function_exists('imagejpeg')))
        {
            $this->image();
        }
        else
        {
            $this->bitmap();
        }
    }

    function image()
    {
        $bgcontent = $this->background();
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

        if ($this->animator == 1 && function_exists('imagegif'))
        {
            import('gifmerge.lib');
            $trueframe = mt_rand(1, 9);

            for ($i = 0; $i <= 9; $i++)
            {
                $this->im = imagecreatefromstring($bgcontent);
                $x[$i] = $y[$i] = 0;
                $this->adulterate && $this->adulterate();
                if ($i == $trueframe)
                {
                    $this->ttf && function_exists('imagettftext') ? $this->ttffont() : $this->giffont();
                    $d[$i] = mt_rand(250, 400);
                }
                else
                {
                    $this->adulteratefont();
                    $d[$i] = mt_rand(5, 15);
                }
                ob_start();
                imagegif($this->im);
                imagedestroy($this->im);
                $frame[$i] = ob_get_contents();
                ob_end_clean();
            }
            $anim = new GifMerge($frame, 255, 255, 255, 0, $d, $x, $y, 'C_MEMORY');
            header('Content-type: image/gif');
            echo $anim->getAnimation();
        }
        else
        {
            $this->im = imagecreatefromstring($bgcontent);
            $this->adulterate && $this->adulterate();
            $this->ttf && function_exists('imagettftext') ? $this->ttffont() : $this->giffont();

            if (function_exists('imagepng'))
            {
                header('Content-type: image/png');
                imagepng($this->im);
            }
            else
            {
                header('Content-type: image/jpeg');
                imagejpeg($this->im, '', 100);
            }
            imagedestroy($this->im);
        }
    }

    function background() {
        $this->im = imagecreatetruecolor($this->width, $this->height);
        $backgroundcolor = imagecolorallocate($this->im, 255, 255, 255);
        $backgrounds = $c = array();
        if ($this->background && function_exists('imagecreatefromjpeg') && function_exists('imagecolorat') &&    function_exists('imagecopymerge') && function_exists('imagesetpixel') && function_exists('imageSX') && function_exists('imageSY'))
        {
            if ($handle = @opendir($this->imagepath.'background/'))
            {
                while ($bgfile = @readdir($handle))
                {
                    if (preg_match('/\.jpg$/i', $bgfile))
                    {
                        $backgrounds[] = $this->imagepath.'background/'.$bgfile;
                    }
                }
                @closedir($handle);
            }
            if ($backgrounds)
            {
                $imwm = imagecreatefromjpeg($backgrounds[array_rand($backgrounds)]);
                $colorindex = imagecolorat($imwm, 0, 0);
                $this->c = imagecolorsforindex($imwm, $colorindex);
                $colorindex = imagecolorat($imwm, 1, 0);
                imagesetpixel($imwm, 0, 0, $colorindex);
                $c[0] = $c['red'];$c[1] = $c['green'];$c[2] = $c['blue'];
                imagecopymerge($this->im, $imwm, 0, 0, mt_rand(0, 200 - $this->width), mt_rand(0, 80 - $this->height), imageSX($imwm), imageSY($imwm), 100);
                imagedestroy($imwm);
            }
        }
        if (!$this->background || !$backgrounds)
        {
            for ($i = 0; $i < 3; $i++)
            {
                $start[$i] = mt_rand(200, 255);$end[$i] = mt_rand(100, 150);$step[$i] = ($end[$i] - $start[$i]) / $this->width;$c[$i] = $start[$i];
            }
            for ($i = 0; $i < $this->width; $i++)
            {
                $color = imagecolorallocate($this->im, $c[0], $c[1], $c[2]);
                imageline($this->im, $i, 0, $i-$angle, $this->height, $color);
                $c[0] += $step[0];$c[1] += $step[1];$c[2] += $step[2];
            }
            $c[0] -= 20;$c[1] -= 20;$c[2] -= 20;
        }
        ob_start();
        if (function_exists('imagepng'))
        {
            imagepng($this->im);
        }
        else
        {
            imagejpeg($this->im, '', 100);
        }
        imagedestroy($this->im);
        $bgcontent = ob_get_contents();
        ob_end_clean();
        $this->fontcolor = $c;
        return $bgcontent;
    }

    function adulterate()
    {
        $linenums = $this->height / 10;
        for ($i=0; $i <= $linenums; $i++)
        {
            $color = $this->color ? imagecolorallocate($this->im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)) : imagecolorallocate($this->im, $this->fontcolor[0], $this->fontcolor[1], $this->fontcolor[2]);
            $x = mt_rand(0, $this->width);
            $y = mt_rand(0, $this->height);
            if (mt_rand(0, 1))
            {
                imagearc($this->im, $x, $y, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, 360), mt_rand(0, 360), $color);
            }
            else
            {
                imageline($this->im, $x, $y, $linex + mt_rand(0, $linemaxlong), $liney + mt_rand(0, mt_rand($this->height, $this->width)), $color);
            }
        }
    }

    function adulteratefont()
    {
        $seccodeunits = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $x = $this->width / 4;
        $y = $this->height / 10;
        $text_color = imagecolorallocate($this->im, $this->fontcolor[0], $this->fontcolor[1], $this->fontcolor[2]);
        for ($i = 0; $i <= 3; $i++)
        {
            $adulteratecode = $seccodeunits{mt_rand(0, 26)};
            imagechar($this->im, 5, $x * $i + mt_rand(0, $x - 10), mt_rand($y, $this->height - 10 - $y), $adulteratecode, $text_color);
        }
    }

    function ttffont()
    {
        $seccode = $this->code;
        $charset = $GLOBALS['charset'];
        $seccoderoot = $this->fontpath;
        $dirs = opendir($seccoderoot);
        $seccodettf = array();
        while ($entry = readdir($dirs))
        {
            if ($entry != '.' && $entry != '..' && in_array(strtolower(file_ext($entry)), array('ttf', 'ttc')))
            {
                $seccodettf[] = $entry;
            }
        }
        $seccodelength = 4;

        $widthtotal = 0;
        for ($i = 0; $i < $seccodelength; $i++)
        {
            $font[$i]['font'] = $seccoderoot.$seccodettf[array_rand($seccodettf)];
            $font[$i]['angle'] = $this->angle ? mt_rand(-30, 30) : 0;
            $font[$i]['size'] = $this->width / 4;
            $this->size && $font[$i]['size'] = mt_rand($font[$i]['size'] - $this->width / 40, $font[$i]['size'] + $this->width / 20);
            $box = imagettfbbox($font[$i]['size'], 0, $font[$i]['font'], $seccode[$i]);
            $font[$i]['zheight'] = max($box[1], $box[3]) - min($box[5], $box[7]);
            $box = imagettfbbox($font[$i]['size'], $font[$i]['angle'], $font[$i]['font'], $seccode[$i]);
            $font[$i]['height'] = max($box[1], $box[3]) - min($box[5], $box[7]);
            $font[$i]['hd'] = $font[$i]['height'] - $font[$i]['zheight'];
            $font[$i]['width'] = (max($box[2], $box[4]) - min($box[0], $box[6])) + mt_rand(0, $this->width / 8);
            $font[$i]['width'] = $font[$i]['width'] > $this->width / $seccodelength ? $this->width / $seccodelength : $font[$i]['width'];
            $widthtotal += $font[$i]['width'];
        }
        $x = mt_rand($font[0]['angle'] > 0 ? cos(deg2rad(90 - $font[0]['angle'])) * $font[0]['zheight'] : 1, $this->width - $widthtotal);
        !$this->color && $text_color = imagecolorallocate($this->im, $this->fontcolor[0], $this->fontcolor[1], $this->fontcolor[2]);
        for ($i = 0; $i < $seccodelength; $i++)
        {
            if ($this->color)
            {
                $this->fontcolor = array(mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
                $this->shadow && $text_shadowcolor = imagecolorallocate($this->im, 255 - $this->fontcolor[0], 255 - $this->fontcolor[1], 255 - $this->fontcolor[2]);
                $text_color = imagecolorallocate($this->im, $this->fontcolor[0], $this->fontcolor[1], $this->fontcolor[2]);
            }
            elseif ($this->shadow)
            {
                $text_shadowcolor = imagecolorallocate($this->im, 255 - $this->fontcolor[0], 255 - $this->fontcolor[1], 255 - $this->fontcolor[2]);
            }
            $y = $font[0]['angle'] > 0 ? mt_rand($font[$i]['height'], $this->height) : mt_rand($font[$i]['height'] - $font[$i]['hd'], $this->height - $font[$i]['hd']);
            $this->shadow && imagettftext($this->im, $font[$i]['size'], $font[$i]['angle'], $x + 1, $y + 1, $text_shadowcolor, $font[$i]['font'], $seccode[$i]);
            imagettftext($this->im, $font[$i]['size'], $font[$i]['angle'], $x, $y, $text_color, $font[$i]['font'], $seccode[$i]);
            $x += $font[$i]['width'];
        }
    }

    function giffont()
    {
        $seccode = $this->code;
        $seccodedir = array();
        if (function_exists('imagecreatefromgif'))
        {
            $seccoderoot = $this->imagepath.'gif/';
            $dirs = opendir($seccoderoot);
            while ($dir = readdir($dirs))
            {
                if ($dir != '.' && $dir != '..' && is_file($seccoderoot.$dir.'/9.gif'))
                {
                    $seccodedir[] = $dir;
                }
            }
        }
        $widthtotal = 0;
        for ($i = 0; $i <= 3; $i++)
        {
            $this->imcodefile = $seccodedir ? $seccoderoot.$seccodedir[array_rand($seccodedir)].'/'.strtolower($seccode[$i]).'.gif' : '';
            if (!empty($this->imcodefile) && is_file($this->imcodefile))
            {
                $font[$i]['file'] = $this->imcodefile;
                $font[$i]['data'] = getimagesize($this->imcodefile);
                $font[$i]['width'] = $font[$i]['data'][0] + mt_rand(0, 6) - 4;
                $font[$i]['height'] = $font[$i]['data'][1] + mt_rand(0, 6) - 4;
                $font[$i]['width'] += mt_rand(0, $this->width / 5 - $font[$i]['width']);
                $widthtotal += $font[$i]['width'];
            }
            else
            {
                $font[$i]['file'] = '';
                $font[$i]['width'] = 8 + mt_rand(0, $this->width / 5 - 5);
                $widthtotal += $font[$i]['width'];
            }
        }
        $x = mt_rand(1, $this->width - $widthtotal);
        for ($i = 0; $i <= 3; $i++)
        {
            $this->color && $this->fontcolor = array(mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            if ($font[$i]['file'])
            {
            $this->imcode = imagecreatefromgif($font[$i]['file']);
                if ($this->size)
                {
                    $font[$i]['width'] = mt_rand($font[$i]['width'] - $this->width / 20, $font[$i]['width'] + $this->width / 20);
                    $font[$i]['height'] = mt_rand($font[$i]['height'] - $this->width / 20, $font[$i]['height'] + $this->width / 20);
                }
                $y = mt_rand(0, $this->height - $font[$i]['height']);
                if ($this->shadow)
                {
                    $this->imcodeshadow = $this->imcode;
                    imagecolorset($this->imcodeshadow, 0 , 255 - $this->fontcolor[0], 255 - $this->fontcolor[1], 255 - $this->fontcolor[2]);
                    imagecopyresized($this->im, $this->imcodeshadow, $x + 1, $y + 1, 0, 0, $font[$i]['width'], $font[$i]['height'], $font[$i]['data'][0], $font[$i]['data'][1]);
                }
                imagecolorset($this->imcode, 0 , $this->fontcolor[0], $this->fontcolor[1], $this->fontcolor[2]);
                imagecopyresized($this->im, $this->imcode, $x, $y, 0, 0, $font[$i]['width'], $font[$i]['height'], $font[$i]['data'][0], $font[$i]['data'][1]);
            }
            else
            {
                $y = mt_rand(0, $this->height - 20);
                if ($this->shadow)
                {
                    $text_shadowcolor = imagecolorallocate($this->im, 255 - $this->fontcolor[0], 255 - $this->fontcolor[1], 255 - $this->fontcolor[2]);
                    imagechar($this->im, 5, $x + 1, $y + 1, $seccode[$i], $text_shadowcolor);
                }
                $text_color = imagecolorallocate($this->im, $this->fontcolor[0], $this->fontcolor[1], $this->fontcolor[2]);
                imagechar($this->im, 5, $x, $y, $seccode[$i], $text_color);
            }
            $x += $font[$i]['width'];
        }
    }

    function bitmap()
    {
        $numbers = array
            (
            '2' => array('fc','c0','60','30','18','0c','cc','cc','78','00'),
            '3' => array('78','8c','0c','0c','38','0c','0c','8c','78','00'),
            '4' => array('00','3e','0c','fe','4c','6c','2c','3c','1c','1c'),
            '5' => array('78','8c','0c','0c','4c','78','60','60','7c','00'),
            '7' => array('30','30','38','18','18','18','1c','8c','fc','00'),
            'A' => array('00','EE','6c','7c','6c','6c','28','38','f8','00'),
            'C' => array('00','38','64','c0','c0','c0','c4','64','3c','00'),
            'E' => array('00','fe','62','62','68','78','6a','62','fe','00'),
            'F' => array('00','f8','60','60','68','78','6a','62','fe','00'),
            'H' => array('00','e7','66','66','66','7e','66','66','e7','00'),
            'K' => array('00','f3','66','66','7c','78','6c','66','f7','00'),
            'M' => array('00','f7','63','6b','6b','77','77','77','e3','00'),
            'N' => array('00','ec','4c','54','54','54','64','64','ee','00'),
            'P' => array('00','f8','60','60','7c','66','66','66','fc','00'),
            'R' => array('00','f3','66','6c','7c','66','66','66','fc','00'),
            'T' => array('00','78','30','30','30','30','b4','b4','fc','00'),
            'V' => array('00','1c','1c','36','36','36','63','63','f7','00'),
            'W' => array('00','36','36','36','77','7f','6b','63','f7','00'),
            'X' => array('00','f7','66','3c','18','18','3c','66','ef','00'),
            'Y' => array('00','7e','18','18','18','3c','24','66','ef','00'),
            );

        foreach ($numbers as $i => $number)
        {
            for ($j = 0; $j < 6; $j++)
            {
                $a1 = substr('012', mt_rand(0, 2), 1).substr('012345', mt_rand(0, 5), 1);
                $a2 = substr('012345', mt_rand(0, 5), 1).substr('0123', mt_rand(0, 3), 1);
                mt_rand(0, 1) == 1 ? array_push($numbers[$i], $a1) : array_unshift($numbers[$i], $a1);
                mt_rand(0, 1) == 0 ? array_push($numbers[$i], $a1) : array_unshift($numbers[$i], $a2);
            }
        }

        $bitmap = array();
        for ($i = 0; $i < 20; $i++)
        {
            for ($j = 0; $j <= 3; $j++)
            {
                $bytes = $numbers[$this->code[$j]][$i];
                $a = mt_rand(0, 14);
                array_push($bitmap, $bytes);
            }
        }

        for ($i = 0; $i < 8; $i++)
        {
            $a = substr('012345', mt_rand(0, 2), 1) . substr('012345', mt_rand(0, 5), 1);
            array_unshift($bitmap, $a);
            array_push($bitmap, $a);
        }

        $image = pack('H*', '424d9e000000000000003e000000280000002000000018000000010001000000'.
                '0000600000000000000000000000000000000000000000000000FFFFFF00'.implode('', $bitmap));

        header('Content-Type: image/bmp');
        echo $image;
    }

}

/**
 * 生成随机串
 *
 * @param   int     $len
 * @return  string
 */
function generate_code($len = 4)
{
    $chars = '23457acefhkmprtvwxy';
    for ($i = 0, $count = strlen($chars); $i < $count; $i++)
    {
        $arr[$i] = $chars[$i];
    }

    mt_srand((double) microtime() * 1000000);
    shuffle($arr);

    $code = substr(implode('', $arr), 5, $len);

    return $code;
}

?>
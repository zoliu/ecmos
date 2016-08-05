<?php
/**
 * ECMALL: 图片处理函数库 水印 缩略图
 * ============================================================================
 * 版权所有 (C) 2005-2008 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.shopex.cn
 * -------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Id: image.func.php 7715 2009-05-07 06:56:11Z yelin $
 */

/**
 * 利用gd库生成缩略图
 *
 * @author  weberliu
 * @param   string      $src            原图片路径
 * @param   string      $dst            缩略图保存路径
 * @param   int         $thumb_width    缩略图高度
 * @param   int         $thumb_height   缩略图高度 可选
 * @param   int         $quality        缩略图品质 100之内的正整数
 * @return  boolean     成功返回 true 失败返回 false
 */
function make_thumb($src, $dst, $thumb_width, $thumb_height = 0, $quality = 85)
{
    if (function_exists('imagejpeg'))
    {
        $func_imagecreate = function_exists('imagecreatetruecolor') ? 'imagecreatetruecolor' : 'imagecreate';
        $func_imagecopy = function_exists('imagecopyresampled') ? 'imagecopyresampled' : 'imagecopyresized';
        $dirpath = dirname($dst);
        if (!ecm_mkdir($dirpath, 0777))
        {
            return false;
        }

        $data = getimagesize($src);
        $src_width = $data[0];
        $src_height = $data[1];
        if ($thumb_height == 0)
        {
            if ($src_width > $src_height)
            {
                $thumb_height = $src_height * $thumb_width / $src_width;
            }
            else
            {
                $thumb_height = $thumb_width;
                $thumb_width = $src_width * $thumb_height / $src_height;
            }
            $dst_x = 0;
            $dst_y = 0;
            $dst_w = $thumb_width;
            $dst_h = $thumb_height;
        }
        else
        {
            if ($src_width / $src_height > $thumb_width / $thumb_height)
            {
                $dst_w = $thumb_width;
                $dst_h = ($dst_w * $src_height) / $src_width;
                $dst_x = 0;
                $dst_y = ($thumb_height - $dst_h) / 2;
            }
            else
            {
                $dst_h = $thumb_height;
                $dst_w = ($src_width * $dst_h) / $src_height;
                $dst_y = 0;
                $dst_x = ($thumb_width - $dst_w) / 2;
            }
        }

        switch ($data[2])
        {
            case 1:
                $im = imagecreatefromgif($src);
                break;
            case 2:
                $im = imagecreatefromjpeg($src);
                break;
            case 3:
                $im = imagecreatefrompng($src);
                break;
            default:
                trigger_error("Cannot process this picture format: " .$data['mime']);
                break;
        }
        $ni = $func_imagecreate($thumb_width, $thumb_height);
        if ($func_imagecreate == 'imagecreatetruecolor')
        {
            imagefill($ni, 0, 0, imagecolorallocate($ni, 255, 255, 255));
        }
        else
        {
            imagecolorallocate($ni, 255, 255, 255);
        }
        $func_imagecopy($ni, $im, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $src_width, $src_height);
        imagejpeg($ni, $dst, $quality);
        return is_file($dst) ? $dst : false;
    }
    else
    {
        trigger_error("Unable to process picture.", E_USER_ERROR);
    }
}

/**
 * 给图片添加水印
 * @param filepath $src 待处理图片
 * @param filepath $mark_img 水印图片路径
 * @param string $position 水印位置 lt左上  rt右上  rb右下  lb左下 其余取值为中间
 * @param int $quality jpg图片质量，仅对jpg有效 默认85 取值 0-100之间整数
 * @param int $pct 水印图片融合度(透明度)
 *
 * @return void
 */
function water_mark($src, $mark_img, $position = 'rb', $quality = 85, $pct = 80) {
    if(function_exists('imagecopy') && function_exists('imagecopymerge')) {
        $data = getimagesize($src);
        if ($data[2] > 3)
        {
            return false;
        }
        $src_width = $data[0];
        $src_height = $data[1];
        $src_type = $data[2];

        $data = getimagesize($mark_img);
        $mark_width = $data[0];
        $mark_height = $data[1];
        $mark_type = $data[2];

        if ($src_width < ($mark_width + 20) || $src_width < ($mark_height + 20))
        {
            return false;
        }
        switch ($src_type)
        {
            case 1:
                $src_im = imagecreatefromgif($src);
                $imagefunc = function_exists('imagejpeg') ? 'imagejpeg' : '';
                break;
            case 2:
                $src_im = imagecreatefromjpeg($src);
                $imagefunc = function_exists('imagegif') ? 'imagejpeg' : '';
                break;
            case 3:
                $src_im = imagecreatefrompng($src);
                $imagefunc = function_exists('imagepng') ? 'imagejpeg' : '';
                break;
        }
        switch ($mark_type)
        {
            case 1:
                $mark_im = imagecreatefromgif($mark_img);
                break;
            case 2:
                $mark_im = imagecreatefromjpeg($mark_img);
                break;
            case 3:
                $mark_im = imagecreatefrompng($mark_img);
                break;
        }

        switch ($position)
        {
            case 'lt':
                $x = 10;
                $y = 10;
                break;
            case 'rt':
                $x = $src_width - $mark_width - 10;
                $y = 10;
                break;
            case 'rb':
                $x = $src_width - $mark_width - 10;
                $y = $src_height - $mark_height - 10;
                break;
            case 'lb':
                $x = 10;
                $y = $src_height - $mark_height - 10;
                break;
            default:
                $x = ($src_width - $mark_width - 10) / 2;
                $y = ($src_height - $mark_height - 10) / 2;
                break;
        }

        if (function_exists('imagealphablending')) imageAlphaBlending($mark_im, true);
        imageCopyMerge($src_im, $mark_im, $x, $y, 0, 0, $mark_width, $mark_height, $pct);

        if ($src_type == 2)
        {
            $imagefunc($src_im, $src, $quality);
        }
        else
        {
            $imagefunc($dst_photo, $src);
        }
    }
}
?>
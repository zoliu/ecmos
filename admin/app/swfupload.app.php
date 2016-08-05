<?php

/**
 *    swfupload批量上传控制器
 *
 *    @author    Hyber
 *    @usage    none
 */

class SwfuploadApp extends BackendApp
{
    var $belong; // 上传的文件所属的模型
    var $mod_uploadedfile; //上传文件模型
    var $item_id = 0; //所属模型的ID
    var $save_path; // 储存路径
    function __construct()
    {
        $this->SwfuploadApp();
    }
    function SwfuploadApp()
    {
        /* 建立会话 */
        if (isset($_POST["ECM_ID"]) && isset($_POST['HTTP_USER_AGENT']))
        {
            $_COOKIE['ECM_ID'] = $_POST["ECM_ID"];
            $_SERVER['HTTP_USER_AGENT'] = $_POST['HTTP_USER_AGENT'];
        }
        else
        {
            $this->json_error('no_post_params_authorize');
            exit();
        }

        parent::__construct();

        /* 初始化 */
        if (isset($_POST['belong']))
        {
            $this->belong = $_POST['belong'];
        }
        else
        {
            $this->json_error('no_post_param_belong');
            exit();
        }
        if (isset($_POST['item_id']))
        {
            $this->item_id = $_POST['item_id'];
        }
        else
        {
            $this->json_error('no_post_param_item_id');
            exit();
        }
        switch ($this->belong)
        {
            case BELONG_ARTICLE :   $this->save_path = 'data/files/mall/article';
            break;
        }
        $this->mod_uploadedfile = &m('uploadedfile');
    }
    function index()
    {
        $this->_upload_file();
    }
    function _upload_file()
    {
        $ret_info = array(); // 返回到客户端的信息
        $file = $_FILES['Filedata'];
        if ($file['error'] == UPLOAD_ERR_NO_FILE) // 没有文件被上传
        {
            $this->json_error('no_upload_file');
            exit();
        }
        import('uploader.lib'); // 导入上传类
        import('image.func');
        $uploader = new Uploader();
        $uploader->allowed_type(IMAGE_FILE_TYPE); // 限制文件类型
        $uploader->allowed_size(2048000); // 限制单个文件大小2M
        $uploader->addFile($_FILES['Filedata']);
        if (!$uploader->file_info())
        {
            $this->json_error($uploader->get_error());
            exit();
        }

        /* 指定保存位置的根目录 */
        $uploader->root_dir(ROOT_PATH);
        $filename  = $uploader->random_filename();
        /* 上传 */
        $file_path = $uploader->save($this->save_path, $filename);   // 保存到指定目录
        if (!$file_path)
        {
            $this->json_error('file_save_error');
            exit();
        }
        $file_type = $this->_return_mimetype($file_path);
        /* 文件入库 */
        $data = array(
            'store_id'  => 0,
            'file_type' => $file_type,
            'file_size' => $file['size'],
            'file_name' => $file['name'],
            'file_path' => $file_path,
            'belong'    => $this->belong,
            'item_id'   => $this->item_id,
            'add_time'  => gmtime(),
        );
        $file_id = $this->mod_uploadedfile->add($data);
        if (!$file_id)
        {
            $this->json_error('file_add_error');
            return false;
        }

        /* 返回客户端 */
        $ret_info =array(
            'file_id'   => $file_id,
            'file_path' => $file_path
        );
        $this->json_result($ret_info);
    }

    function _return_mimetype($filename)
    {
        preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);
        switch(strtolower($fileSuffix[1]))
        {
            case "js" :
                return "application/x-javascript";

            case "json" :
                return "application/json";

            case "jpg" :
            case "jpeg" :
            case "jpe" :
                return "image/jpeg";

            case "png" :
            case "gif" :
            case "bmp" :
            case "tiff" :
                return "image/".strtolower($fileSuffix[1]);

            case "css" :
                return "text/css";

            case "xml" :
                return "application/xml";

            case "doc" :
            case "docx" :
                return "application/msword";

            case "xls" :
            case "xlt" :
            case "xlm" :
            case "xld" :
            case "xla" :
            case "xlc" :
            case "xlw" :
            case "xll" :
                return "application/vnd.ms-excel";

            case "ppt" :
            case "pps" :
                return "application/vnd.ms-powerpoint";

            case "rtf" :
                return "application/rtf";

            case "pdf" :
                return "application/pdf";

            case "html" :
            case "htm" :
            case "php" :
                return "text/html";

            case "txt" :
                return "text/plain";

            case "mpeg" :
            case "mpg" :
            case "mpe" :
                return "video/mpeg";

            case "mp3" :
                return "audio/mpeg3";

            case "wav" :
                return "audio/wav";

            case "aiff" :
            case "aif" :
                return "audio/aiff";

            case "avi" :
                return "video/msvideo";

            case "wmv" :
                return "video/x-ms-wmv";

            case "mov" :
                return "video/quicktime";

            case "rar" :
            return "application/x-rar-compressed";

            case "zip" :
                return "application/zip";

            case "tar" :
                return "application/x-tar";

            case "swf" :
                return "application/x-shockwave-flash";

            default :
            if(function_exists("mime_content_type"))
            {
                $fileSuffix = mime_content_type($filename);
            }
            return "unknown/" . trim($fileSuffix[0], ".");
        }
    }
}

?>
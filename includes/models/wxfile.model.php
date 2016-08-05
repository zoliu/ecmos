<?php

/* 微信公众平台上传图片文件 uploadedfile */

class WxfileModel extends BaseModel
{
    var $table  = 'wxfile';
    var $prikey = 'file_id';
    var $_name  = 'wxfile';
	
	/* 自动删除附件表中的文件 */
    function drop($file_id)
    {
        $files = $this->find($file_id);
        foreach ($files as $file)
        {
            $file_path = ROOT_PATH . '/' . $file['file_path'];
            file_exists($file_path) && unlink($file_path);
        }
        return parent::drop($file_id);
    }
  
	
}

?>
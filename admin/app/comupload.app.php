<?php
class ComuploadApp extends BackendApp
{
    var $id = 0;
    var $belong = 0 ;
    function __construct()
    {
        $this->ComuploadApp();
    }

    function ComuploadApp()
    {
        parent::__construct();
        if (isset($_REQUEST['id']))
        {
             $this->id = intval($_REQUEST['id']);
        }
        if (isset($_REQUEST['belong']))
        {
            $this->belong = intval($_REQUEST['belong']);
        }
    }

    function view_iframe()
    {
        $this->assign("id", $this->id);
        $this->assign("belong", $this->belong);
        $this->display("image.html");
    }

    function uploadedfile()
    {
            import('image.func');
            import('uploader.lib');
            $uploader = new Uploader();
            $uploader->allowed_type(IMAGE_FILE_TYPE);
            $uploader->allowed_size(2097152); // 2M
            $upload_mod =& m('uploadedfile');

            $files = $_FILES['file'];
            if ($files['error'] == UPLOAD_ERR_OK)
            {
                /* 处理文件上传 */
                $file = array(
                    'name'      => $files['name'],
                    'type'      => $files['type'],
                    'tmp_name'  => $files['tmp_name'],
                    'size'      => $files['size'],
                    'error'     => $files['error']
                );
                $uploader->addFile($file);
                if (!$uploader->file_info())
                {
                    $data = current($uploader->get_error());
                    $res = Lang::get($data['msg']);
                    $this->view_iframe();
                    echo "<script type='text/javascript'>alert('{$res}');</script>";
                    return false;
                }

                $uploader->root_dir(ROOT_PATH);
                $dirname = '';
                if ($this->belong == BELONG_ARTICLE)
                {
                    $dirname = 'data/files/mall/article';
                }
		if ($this->belong == BELONG_GCATEGORY)
                {
                    $dirname = 'data/files/mall/gads';
                }

                $filename  = $uploader->random_filename();
                $file_path = $uploader->save($dirname, $filename);
                /* 处理文件入库 */
                $data = array(
                    'store_id'  => $this->visitor->get('manage_store'),
                    'file_type' => $file['type'],
                    'file_size' => $file['size'],
                    'file_name' => $file['name'],
                    'file_path' => $file_path,
                    'belong'    => $this->belong,
                    'item_id'   => $this->id,
                    'add_time'  => gmtime(),
                );
                $file_id = $upload_mod->add($data);
                if (!$file_id)
                {
                    $this->_error($uf_mod->get_error());
                    return false;
                }
                $data['file_id'] = $file_id;
                $res = "{";
                foreach ($data as $key => $val)
                {
                    $res .= "\"$key\":\"$val\",";
                }
                $res = substr($res, 0, strrpos($res, ','));
                $res .= '}';
                $this->view_iframe();
                echo "<script type='text/javascript'>window.parent.add_uploadedfile($res);</script>";
            }
            elseif ($files['error'] == UPLOAD_ERR_NO_FILE)
            {
                $res = Lang::get('file_empty');
                $this->view_iframe();
                echo "<script type='text/javascript'>alert('{$res}');</script>";
                return false;  
            } 
            else
            {
                $res = Lang::get('sys_error');
                $this->view_iframe();
                echo "<script type='text/javascript'>alert('{$res}');</script>";
                return false;
            }

    }
    
    function remote_image()
    {
        import('image.func');
        import('uploader.lib');
        $uploader = new Uploader();
        $uploader->allowed_type(IMAGE_FILE_TYPE);
        $uploader->allowed_size(2097152); // 400KB
        $upload_mod =& m('uploadedfile');   
        $uploader->root_dir(ROOT_PATH);
        $dirname = '';
        $remote_url = trim($_POST['remote_url']);
        if (!empty($remote_url))
        {
            if(preg_match("/^(http:\/\/){1,1}.+(gif|png|jpeg|jpg){1,1}$/i", $remote_url))
            {
                $result = $this->url_exist($remote_url, 2097152, 0);
                if ($result === 1)
                {
                    $this->view_iframe();
                    $res = Lang::get("url_invalid");
                    echo "<script type='text/javascript'>alert('{$res}');</script>";
                    return false;
                }
                elseif ($result === 2)
                {
                    $this->view_iframe();
                    $res = Lang::get("not_allowed_size");
                    echo "<script type='text/javascript'>alert('{$res}');</script>";
                    return false;
                }
                $img_url = @file_get_contents($remote_url);
                $dirname = '';
                if ($this->belong == BELONG_GOODS)
                {
                    $dirname = 'data/files/store_' . $this->visitor->get('manage_store') . '/goods_' . (time() % 200);
                }
                elseif ($this->belong == BELONG_STORE)
                {
                    $dirname = 'data/files/store_' . $this->visitor->get('manage_store') . '/other';
                }
                elseif ($this->belong == BELONG_ARTICLE)
                {
                    $dirname = 'data/files/mall/store_' . $this->visitor->get('manage_store').'/article';
                }
                $filename  = $uploader->random_filename();
                $new_url = $dirname . '/' . $filename . '.' . substr($remote_url, strrpos($remote_url, '.')+1);
                ecm_mkdir(ROOT_PATH . '/' . $dirname);
                $fp = @fopen(ROOT_PATH . '/' . $new_url, "w"); 
                @fwrite($fp, $img_url); 
                @fclose($fp);
                if(!file_exists(ROOT_PATH . '/' . $new_url))
                {
                    $this->view_iframe();
                    $res = Lang::get("system_error");
                    echo "<script type='text/javascript'>alert({$res});</script>";
                    return false;
                } 
                /* 处理文件入库 */
                $data = array(
                    'store_id'  => $this->visitor->get('manage_store'),
                    'file_type' => filetype(ROOT_PATH . '/' . $new_url),
                    'file_size' => filesize(ROOT_PATH . '/' . $new_url),
                    'file_name' => substr($remote_url, strrpos($remote_url, '/')+1),
                    'file_path' => $new_url,
                    'belong'    => $this->belong,
                    'item_id'   => $this->id,
                    'add_time'  => gmtime(),
                );
                $file_id = $upload_mod->add($data);
                if (!$file_id)
                {
                    $this->_error($uf_mod->get_error());
                    return false;
                }
                $data['file_id'] = $file_id;
                $res = "{";
                foreach ($data as $key => $val)
                {
                    $res .= "\"$key\":\"$val\",";
                }
                $res = substr($res, 0, strrpos($res, ','));
                $res .= '}';
                $this->view_iframe();
                echo "<script type='text/javascript'>window.parent.add_uploadedfile($res);</script>";
            }
            else 
            {
               $res = Lang::get('url_invalid');
               $this->view_iframe();

               echo "<script type='text/javascript'>alert('{$res}');</script>"; 
               return false;
            }
        }
        else 
        {
            $res = Lang::get('remote_empty');
            $this->view_iframe();  
            echo "<script type='text/javascript'>alert('{$res}');</script>";
            return false;
        }
    }
    
    function url_exist($url, $allow_size, $remain)
    {
           $head = @get_headers($url);
           if(is_array($head))
           {
                
                foreach ($head as $key => $val)
                {
                    $pos = strpos($val, 'Content-Length');
                    if($key == 0)
                    {
                        $hhttp = explode(' ', $val);
                        if ($hhttp[count($hhttp) - 1] != "OK")
                        {
                            return 1;
                        }
                    }
                    elseif ( $pos === false)
                    {
                        continue;
                    }
                    elseif ($pos >= 0)
                    {
                        $size = explode(' ', $val);
                        $count = count($size);
                        $count = $count - 1;
                        $res = intval($size[$count]);
                        if ($res > $allow_size)
                        {
                            return 2;
                        }
                        if (!empty($remain) && $res > $remain)
                        {
                            return 3;
                        }
                    }
                }   
               
           }
           else 
           {
               return 1;
           }
           return true;
    }
}
?>
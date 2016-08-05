<?php

/* 商家微信公众平台信息keyword */

class WxkeyModel extends BaseModel {

    var $table = 'wxkeyword';
    var $prikey = 'kid';
    var $_name = 'wxkeyword';

    function getInfoByKeyword($keyword,$user_id=0)
    {
        if($data=$this->_get_guajiang($keyword,$user_id))
        {
            return $data;
        }
         if($data=$this->_get_lottery($keyword,$user_id))
        {
            return $data;
        }
         if($data=$this->_get_survey($keyword,$user_id))
        {
            return $data;
        }

        if($data=$this->_get_keyword($keyword,$user_id))
        {
            return $data;
        }

        return ;
    }

    function _get_guajiang($keyword,$user_id)
    {   
       $data_list=comGetListData('guajiang',"wxkeyword like '%" . $keyword . "%'");
       $data_list=isset($data_list)?$data_list['data']:null;
       $datalist=array();
       if($data_list)
       {
        if(count($data_list)==1)
        {
            $tmp=array();
            $data=current($data_list);
            $tmp['title']=$data['title'];
            $tmp['picurl']=site_url().'/'.$data['default_image'];
            $tmp['description']=$data['remark'];
            $tmp['url']=site_url().'/?app=activily&act=guajiang&id='.$data['id'];
            $datalist[]=$tmp;
        }else{
            foreach($data_list as $data)
            {
                $datalist['title']=$data['title'];
                $datalist['picurl']=site_url().'/'.$data['default_image'];
                $datalist['description']=$data['remark'];
                $datalist['url']=site_url().'/?app=activily&act=guajiang&id='.$data['id'];

            }
        }       
       }
        return $datalist;
    }

    function _get_lottery($keyword,$user_id)
    {   
       $data_list=comGetListData('lottery',"wxkeyword like '%" . $keyword . "%'");
       $data_list=isset($data_list)?$data_list['data']:null;
       $datalist=array();
       if($data_list)
       {
        if(count($data_list)==1)
        {
            $data=current($data_list);
            $tmp=array();
            $tmp['title']=$data['title'];
            $tmp['picurl']=site_url().'/'.$data['default_image'];
            $tmp['description']=$data['remark'];
            $tmp['url']=site_url().'/?app=activily&act=lottery&id='.$data['id'];
            $datalist[]=$tmp;

        }else{
            foreach($data_list as $data)
            {
                $datalist['title']=$data['title'];
                $datalist['picurl']=site_url().'/'.$data['default_image'];
                $datalist['description']=$data['remark'];
                $datalist['url']=site_url().'/?app=activily&act=lottery&id='.$data['id'];

            }
        }       
       }
      
        return $datalist;
    }

    function _get_survey($keyword,$user_id)
    {   
       $data_list=comGetListData('survey',"survey_keyword like '%" . $keyword . "%'");
       $data_list=isset($data_list)?$data_list['data']:null;
       $datalist=array();
       if($data_list)
       {
        if(count($data_list)==1)
        {
            $data=current($data_list);
            $tmp=array();
            $tmp['title']=$data['title'];
            $tmp['picurl']=site_url().'/'.$data['default_image'];
            $tmp['description']=$data['remark'];
            $tmp['url']=site_url().'/?app=activily&act=survey&id='.$data['id'];
            $datalist[]=$tmp;
        }else{
            foreach($data_list as $data)
            {
                $datalist['title']=$data['title'];
                $datalist['picurl']=site_url().'/'.$data['default_image'];
                $datalist['description']=$data['remark'];
                $datalist['url']=site_url().'/?app=activily&act=survey&id='.$data['id'];
            }
        }       
       }     
        return $datalist;
    }

    function _get_keyword($keyword,$user_id)
    {      

        $data=$this->get("  kyword like '%" . $keyword . "%'");
        if(!$data)
        {
            return;
        }
        if(empty($data['kecontent']) && $data['type'] == 1)
        {
            return $data['kecontent'];
        }else{
            $titles = unserialize($data['titles']);
            $imageinfo = unserialize($data['imageinfo']);
            $linkinfo = unserialize($data['linkinfo']);
            $datalist = array();
            for ($i = 0; $i < count($titles); $i++) {
                $datalist[$i]['title'] = $titles[$i];
                if (stristr($imageinfo[$i], $_SERVER['SERVER_NAME'])) {
                    $datalist[$i]['picurl'] = $imageinfo[$i];
                } else {
                    $datalist[$i]['picurl'] = site_url() . '/' . $imageinfo[$i];
                }
                $datalist[$i]['description']='';
                $datalist[$i]['url'] = $linkinfo[$i];
            }
            return $datalist;

        }       
        
    }

}

?>
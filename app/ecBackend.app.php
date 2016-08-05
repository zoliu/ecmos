<?php
class ecBackendApp extends baseApp{
	
	private $path="build/ecmall/backend/";
	function init(){
		$name=get('name');
		if(!isNotEmpty($name))
		{
			echo 'config file name not exists';exit;
		}
	}

		
	function index()
	{
		clear_dir($this->path);
	
		$dir_list=array('admin/',"includes/models/","admin/app/","admin/templates/","languages/sc-utf-8/admin/");
		foreach ($dir_list as $dir)
		{
			file::makeDir($this->path.$dir,true);
		}
		$this->cmodel();
		$this->capp();
		global $smarty;
		$smarty->left_delimiter="{%";
		$smarty->right_delimiter="%}";
		$this->cform();
		$this->clist();
		$this->clang();
		$zip_path="files/update.zip";
		$is_zip=addZip($zip_path,$this->path,$this->path);
		if($is_zip)
		{
			download(ROOT_PATH.'/files/','update.zip');
		}else{
			exit('Error: zip error');
		}
	}
	
	function cmodel()
	{
		run(get('name'),'ecmall/cmodel.html',$this->path."includes/models/".get('name').".model.php");
	}
	
	function capp()
	{
		run(get('name'),'ecmall/capp.html',$this->path."/admin/app/".get('name').".app.php");
	}
	
	function cform()
	{
		run(get('name'),'ecmall/cform.html',$this->path."/admin/templates/".get('name').".form.html");
	}
    function clist()
	{
		run(get('name'),'ecmall/clist.html',$this->path."/admin/templates/".get('name').".index.html");
	}

	 function clang()
	{
		run(get('name'),'ecmall/clang.html',$this->path."languages/sc-utf-8/admin/".get('name').".lang.php");
	}
}
<?php
import('zxlib/upgrade/upgrade.lib');
class UpgradeApp extends BackendApp
{
	public $upgrade;
	function __construct() {
		$this->UpgradeApp();
	}
	
	function UpgradeApp() {
		parent::__construct();
        $this->upgrade=new Upgrade();       
	}

	function checkUpgrade()
	{
		$result=array(
			'result'=>$this->upgrade->hasUpgrade(),
			'nextVersion'=>$this->upgrade->nextVersion(),
			'baseUri'=>$this->upgrade->getBaseUri(),
			);
		echo json_encode($result);
	}

	function update()
	{
		$res=$this->upgrade->down();
		if($res['status'])
		{
			$result=$this->upgrade->down_file($res['result']);
			if($result>0)
			{
				$this->show_message('升级成功');
				return;
			}
			$this->show_warning('升级失败，请将错误信息反馈到http://bug.360cd.cn');
			return;
		}else{
			$this->show_warning($res['msg']);
			return;
		}
		
	}




}


?>
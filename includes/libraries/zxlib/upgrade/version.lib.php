<?php
import('zxlib/public/curl.lib');
class Version{
	protected $configPath;
	protected $remoteUri="?app=upgrade&act=checkVersion";
	protected $confVer;
	function __construct($baseUri)
	{
		$this->remoteUri=$baseUri.$this->remoteUri;
		$this->configPath=ROOT_PATH."/data/upgrade.inc.php";
		$this->_initVersion();
		$this->confVer=$this->readVersion();
		$this->_getRemoteVersion();
	}
	//得到所有配置信息
	function getConf()
	{
		return $this->confVer;
	}
	/**读取版本信息**/
	function readVersion()
	{	
		$path=$this->configPath;
		if(file_exists($path))
		{
			return include($path);
		}
		return null;
	}
	/**保存当前版本号**/
	function saveVersion($ver)
	{
		$this->confVer['version']=$ver;
		$this->confVer['upgrade_time']=gmtime();
		$this->saveConfig($this->configPath,$this->confVer);
	}
	/**保存下一个版本号**/
	function saveNextVersion($ver)
	{
		$this->confVer['next_version']=$ver;
		$this->confVer['upgrade_time']=gmtime();
		$this->saveConfig($this->configPath,$this->confVer);
	}

	function getCurrentVersion()
	{
		return $this->confVer['version'];
	}

	function getSystemRemark()
	{
		return !empty($this->confVer['system_remark'])?base64_decode($this->confVer['system_remark']):'';
	}
	/**检查是否需要升级版本**/
	function checkVersion()
	{
		return $this->confVer['version']==$this->confVer['next_version']?0:1;
	}

	function _initVersion()
	{
		if(!file_exists($this->configPath))
		{
			$data=array(
				'appid'=>'',
				'appkey'=>'',
				'system'=>'',
				'version'=>0,
				'next_version'=>0,
				'upgrade_time'=>gmtime(),
				'remark'=>'',//360cd.cn
				'system_remark'=>'',//360cd.cn
				);
			$this->saveConfig($this->configPath,$data);
		}
	}

	function saveSystemRemark($remark)
	{
		$this->confVer['system_remark']=$remark;

	}

	function _getRemoteVersion()
	{
		$url=$this->remoteUri.'&appid='.$this->confVer['appid'].'&appkey='.$this->confVer['appkey'].'&version='.$this->confVer['system'].'&verno='.base64_encode($this->confVer['version']);
		$result=getUri($url);
		if($result)
		{
			$result=json_decode($result,1);
			if(isset($result['version']))
			{
				$this->saveSystemRemark($result['system_remark']);
				$this->saveVersionRemark($result['remark']);
				$this->saveNextVersion($result['version']);
			}
		}
	}

	function saveVersionRemark($remark)
	{
		$this->confVer['remark']=$remark;

	}

	function readVersionRemark()
	{
		return !empty($this->confVer['remark'])?base64_decode($this->confVer['remark']):'';
	}

	function saveConfig($file,$data=array())
	{
		file_put_contents($file,'<?php'.chr(9).chr(13).' return '.var_export($data,1).';'.chr(9).chr(13).'?>');
	}



}
?>
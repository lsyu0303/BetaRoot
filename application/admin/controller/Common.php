<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;

class Common extends Controller
{
	public function _initialize()
	{
		$this->getConfig();
	}


	public $config;

	public function getConfig()
	{
		$_configs=db('config')->field('name,value')->select();
		$configs=array();
		foreach ($_configs as $key => $value) {
			$configs[$value['name']]=$value['value'];
		}
		$this->config=$configs;
	}
}

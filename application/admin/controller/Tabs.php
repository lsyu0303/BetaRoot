<?php
namespace app\admin\controller;
use think\Controller;

class Tabs extends Controller
{
	public function index()
	{
		$tabs=db('tab')->field('id,title,group,value,status,sort')->select();
		$groups=db('tabs')->field('name,title')->select();
		$this->assign([
			'tabs'		=> $tabs,
			'groups'	=> $groups,
			]);
		return view();
	}


}

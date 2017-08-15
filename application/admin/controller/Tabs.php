<?php
namespace app\admin\controller;
use think\Controller;

class Tabs extends Controller
{
	public function index()
	{
		$tab_lists=db('tab')->field('id,title,group,value,status,sort')->select();
		$tabs_lists=db('tabs')->field('name,title')->select();
		$this->assign('tab_lists',$tab_lists);
		$this->assign('tabs_lists',$tabs_lists);
		return view();
	}

	public function system()
	{
		$config_groups=db('tab')->field('value,title')->where('group','configgroup')->select();
		$config_lists=db('system')->select();
		$this->assign('config_groups',$config_groups);
		$this->assign('config_lists',$config_lists);
		if(request()->isPost()){
			$data=input('post.');
			$name_Array=db('system')->column('name');
			$config_Array=array();

			// 附件类型数据处理
			$file_column=db('system')->where('typeid',6)->column('name');
			foreach ($file_column as $key => $value){
				if($_FILES[$value]['tmp_name'] !=''){
					$file=request()->file($value);
					$info=$file->move(ROOT_PATH . 'public' . DS . 'uploads');
					$file_src=$info->getSaveName();
					db('system')->where('name',$value)->update(['value'=>$file_src]);
				}
			}

			// 全局数据处理
			foreach ($data as $key => $value){
				$config_Array[]=$key;
				if(is_array($value)){
					$value=implode('|',$value);
				}
				db('system')->where('name',$key)->update(['value'=>$value]);
			}
			foreach ($name_Array as $key => $value){
				if(!in_array($value,$config_Array) && !in_array($value,$file_column)){
					db('system')->where('name',$value)->update(['value'=>'']);
				}
			}
			$this->success('修改配置成功！', url('system'));
		}
		return view();
	}

}

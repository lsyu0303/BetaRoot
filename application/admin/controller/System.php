<?php
namespace app\admin\controller;
use think\Controller;

class System extends Controller
{
	public function index()
	{
		$config_lists=db('system')->field('id,name,title,typeid,groupid,status,sort')->paginate(10);
		$config_types=db('tab')->field('value,title')->where('group','configtype')->select();
		$config_groups=db('tab')->field('value,title')->where('group','configgroup')->select();
		$this->assign('config_lists',$config_lists);
		$this->assign('config_types',$config_types);
		$this->assign('config_groups',$config_groups);
		return view();
	}

	public function insert()
	{
		$config_types=db('tab')->field('value,title')->where('group','configtype')->select();
		$this->assign('config_types',$config_types);
		if(request()->isPost())
		{
			$data=input('post.');
			$validate=validate('system');
			if(!$validate->scene('insert')->check($data)){
				$this->error($validate->getError());
			}
			$insert=db('system')->insert($data);
			if($insert){
				$this->success('添加配置成功！', url('index'));
			}
			else{
				$this->error('添加配置失败！');
			}
		}
		return view();
	}

	public function redact()
	{
		$id=input('id');
		$config_list=db('system')->find($id);
		$config_types=db('tab')->field('value,title')->where('group','configtype')->select();
		$this->assign('config_list',$config_list);
		$this->assign('config_types',$config_types);
		if(request()->isPost()){
			$data=input('post.');
			$validate=validate('system');
			if(!$validate->scene('redact')->check($data)){
				$this->error($validate->getError());
			}
			$redact=db('system')->update($data);
			if($redact!==null){
				$this->success('修改配置成功！', url('index'));
			}
			else{
				$this->error('修改配置失败！');
			}
		}
		return view();
	}

	public function delete()
	{
		$id=input('id');
		$data=db('system')->delete($id);
		if($data){
			$this->success('删除配置成功！', url('index'));
		}
		else{
			$this->error('删除配置失败！');
		}
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

	// Ajax 异步转换状态
	public function change(){
		if(request()->isAjax()){
			$id=input('id');
			$status=db('system')->field('status')->where('id',$id)->find();
			$status=$status['status'];
			if($status==1){
				db('system')->where('id',$id)->update(['status'=>0]);
				echo 0; //由启用转换为禁用
			}
			else{
				db('system')->where('id',$id)->update(['status'=>1]);
				echo 1; //由禁用转换为启用
			}
		}
		else{
			$this->error('非法操作！');
		}
	}


}

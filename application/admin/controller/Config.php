<?php
namespace app\admin\controller;
use app\admin\controller\Common;

class Config extends Common
{
	public function index()
	{
		$configs=db('config')->field('id,name,title,type,group,status,sort')->paginate(10);
		$types=db('tag')->field('value,title')->where('group',"configtype")->select();
		$groups=db('tag')->field('value,title')->where('group','configgroup')->select();
		$this->assign([
			'configs'	=> $configs,
			'types'		=> $types,
			'groups'	=> $groups,
			]);
		return view();
	}


	public function insert()
	{
		if(request()->isPost())
		{
			$data=input('post.');
			$validate=validate('config');
			if(!$validate->scene('insert')->check($data)){
				$this->error($validate->getError());
			}
			else{
				$insert=db('config')->insert($data);
				if($insert){
					$this->success('添加配置成功！', url('index'));
				}
				else{
					$this->error('添加配置失败！');
				}	
			}
			return;
		}

		$types=db('tag')->field('value,title')->where('group','configtype')->select();
		$groups=db('tag')->field('value,title')->where('group','configgroup')->select();
		$this->assign([
			'types'		=> $types,
			'groups'	=> $groups,
			]);
		return view();
	}


	public function redact()
	{
		if(request()->isPost()){
			$data=input('post.');
			$validate=validate('config');
			if(!$validate->scene('redact')->check($data)){
				$this->error($validate->getError());
			}
			else{
				$redact=db('config')->update($data);
				if($redact!==false){
					$this->success('修改配置成功！', url('index'));
				}
				else{
					$this->error('修改配置失败！');
				}
			}
			return;
		}

		$id=input('id');
		$config=db('config')->find($id);
		$types=db('tag')->field('value,title')->where('group','configtype')->select();
		$groups=db('tag')->field('value,title')->where('group','configgroup')->select();
		$this->assign([
			'config'	=> $config,
			'types'		=> $types,
			'groups'	=> $groups,
			]);
		return view();
	}


	public function config()
	{
		if(request()->isPost()){
			$data=input('post.');

			// 附件类型数据处理
			$file_column=db('config')->where('type',9)->column('name');
			foreach ($file_column as $key => $value){
				if($_FILES[$value]['tmp_name'] !=''){
					$file=request()->file($value);
					$info=$file->move(ROOT_PATH . 'public' . DS . 'uploads/image');
					$file_src=$info->getSaveName();
					db('config')->where('name',$value)->update(['value'=>$file_src]);
				}
			}

			// 全局数据处理
			$config=array();
			foreach ($data as $key => $value){
				$config[]=$key;
				if(is_array($value)){
					$value=implode('|',$value);
				}
				db('config')->where('name',$key)->update(['value'=>$value]);
			}

			$name=db('config')->column('name');
			foreach ($name as $key => $value){
				if(!in_array($value,$config) && !in_array($value,$file_column)){
					db('config')->where('name',$value)->update(['value'=>'']);
				}
			}
			$this->success('修改设置成功！', url('config'));
		}

		$groups=db('tag')->field('value,title')->where('group','configgroup')->select();
		$configs=db('config')->select();
		$this->assign([
			'groups'	=> $groups,
			'configs'	=> $configs,
			]);
		return view();
	}


	// Ajax 异步删除单条记录及子记录
	public function remove()
	{
		if(request()->isAjax()){
			$id=input('id');
			$data=db('config')->delete($id);
			if($data){
				echo 1;
			}
			else{
				echo 0;
			}
		}
		else{
			$this->error('非法操作！');
		}
	}


	// Ajax 异步转换状态
	public function change(){
		if(request()->isAjax()){
			$id=input('id');
			$status=db('config')->field('status')->where('id',$id)->find();
			$status=$status['status'];
			if($status==1){
				db('config')->where('id',$id)->update(['status'=>0]);
				echo 0; //由启用转换为禁用
			}
			else{
				db('config')->where('id',$id)->update(['status'=>1]);
				echo 1; //由禁用转换为启用
			}
		}
		else{
			$this->error('非法操作！');
		}
	}


}

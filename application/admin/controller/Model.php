<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use think\Db;

class Model extends Common
{
	public function index()
	{
		$models=db('model')->order('sort asc')->paginate(10);
		$this->assign('models',$models);
		return view();
	}


	public function insert()
	{
		if(request()->isPost()){
			$data=input('post.');
			$tablename=config('database.prefix').$data['tablename'];
			$sql="Create Table {$tablename} (aid int(10) unsigned not null auto_increment primary key) Engine=InnoDB default Charset=utf8mb4";
			$validate=validate('model');
			if(!$validate->scene('insert')->check($data)){
				$this->error($validate->getError());
			}
			else{
				$insert=db('model')->insert($data);
				if($insert){
					Db::execute($sql);
					$this->success('添加模型成功！', url('index'));
				}
				else{
					$this->error('添加模型失败！');
				}
			}
			return;
		}
		return view();
	}


	public function redact()
	{
		if(request()->isPost()){
			$data=input('post.');
			$old_tablename=db('model')->field('tablename')->find($data['id']);
			$old_tablename=$old_tablename['tablename'];
			$old_tablename=config('database.prefix').$old_tablename;
			$tablename=config('database.prefix').$data['tablename'];
			$sql="Alter Table {$old_tablename} Rename {$tablename}";
			$validate=validate('model');
			if(!$validate->scene('redact')->check($data)){
				$this->error($validate->getError());
			}
			else{
				$redact=db('model')->update($data);
				if($old_tablename!==$tablename){
					Db::execute($sql);
				}
				if($redact!==false){
					$this->success('修改模型成功！', url('index'));
				}
				else{
					$this->error('修改模型失败！');
				}
			}
			return;
		}

		$id=input('id');
		$model=db('model')->where('id',$id)->find();
		$this->assign('model',$model);
		return view();
	}


	// Ajax 异步删除单条记录
	public function remove()
	{
		if(request()->isAjax()){
			$id=input('id');
			$tablename=config('database.prefix').input('tablename');
			$sql="Drop Table {$tablename}";
			$remove=db('model')->delete($id);
			if($remove){
				Db::execute($sql);
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


	// 表单批量删除与排序
	public function global()
	{
		// 排序循环
		$data=input('post.');
		foreach ($data['sort'] as $key => $value) {
			db('model')->where('id',$key)->update(['sort'=>$value]);
		}
		// 查询选中数据的附加表
		$ids=input('post.id/a');
		$remove=db('model')->where('id', 'in', $ids)->select();
		static $tablename=array();
		foreach ($remove as $key => $value) {
			$tablename[]=config('database.prefix').$value['tablename'];
		}
		$tables=implode(',', $tablename);
		$sql="Drop Table {$tables}";
		if($ids){
			Db::execute($sql);
			db('model')->where('id', 'in', $ids)->delete();
		}
		$this->success('数据处理成功！', url('index'));
	}


	// Ajax 异步转换状态
	public function change()
	{
		if(request()->isAjax()){
			$id=input('id');
			$status=db('model')->field('status')->where('id',$id)->find();
			$status=$status['status'];
			if($status==1){
				db('model')->where('id',$id)->update(['status'=>0]);
				echo 0; //由显示转换为隐藏
			}
			else{
				db('model')->where('id',$id)->update(['status'=>1]);
				echo 1; //由隐藏转换为显示
			}
		}
		else{
			$this->error('非法操作！');
		}
	}


}

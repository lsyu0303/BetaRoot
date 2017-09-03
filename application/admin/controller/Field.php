<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use think\Db;

class Field extends Common
{
	public function index()
	{
		$fields=db('field')->field('id,name,title,type,modelid,value,status,sort')->order('sort asc')->paginate(10);
		$models=db('model')->where(['status'=>'1'])->field('id,title')->select();
		$types=db('tag')->where(['group'=>'fieldtype','status'=>'1'])->field('title,value')->select();
		$this->assign([
			'fields'	=> $fields,
			'models'	=> $models,
			'types'		=> $types,
			]);
		return view();
	}


	public function insert()
	{
		if(request()->isPost()){
			$data=input('post.');
			$tablename=db('model')->where(['id'=>$data['modelid']])->field('tablename')->find();
			$tablename=config('database.prefix').$tablename['tablename'];
			// 判断字段类型及参数
			switch ($data['type']) {
				case 1:
				case 2:
				case 6:
				case 7:
				case 8:
				case 9:
					$fieldtype="varchar(255) not null default ''";
					break;
				case 3:
					$fieldtype="text";
					break;
				case 4:
					$fieldtype="int not null default '0'";
					break;
				case 5:
					$fieldtype="float not null default '0.0'";
					break;
				default:
					$fieldtype="varchar(255) not null default ''";
					break;
			}
			$sql="Alter Table {$tablename} Add {$data['name']} {$fieldtype}";
			$validate=validate('field');
			if(!$validate->scene('insert')->check($data)){
				$this->error($validate->getError());
			}
			else{
				$insert=db('field')->insert($data);
				if($insert){
					Db::execute($sql);
					$this->success('添加字段成功！', url('index'));
				}
				else{
					$this->error('添加字段失败！');
				}
			}
			return;
		}

		$models=db('model')->field('id,title')->select();
		$types=db('tag')->where(['group'=>'fieldtype','status'=>'1'])->field('title,value')->select();
		$this->assign([
			'models'	=> $models,
			'types'		=> $types,
			]);
		return view();
	}


	public function redact()
	{
		if(request()->isPost()){
			$data=input('post.');
			$tablename=db('model')->where(['id'=>$data['modelid']])->field('tablename')->find();
			$tablename=config('database.prefix').$tablename['tablename'];
			$old_fieldname=db('field')->where(['id'=>$data['id']])->field('name')->find();
			$old_fieldname=$old_fieldname['name'];
			// 判断字段类型及参数
			switch ($data['type']) {
				case 1:
				case 2:
				case 6:
				case 7:
				case 8:
				case 9:
					$fieldtype="varchar(255) not null default ''";
					break;
				case 3:
					$fieldtype="text";
					break;
				case 4:
					$fieldtype="int not null default '0'";
					break;
				case 5:
					$fieldtype="float not null default '0.0'";
					break;
				default:
					$fieldtype="varchar(255) not null default ''";
					break;
			}
			$sql="Alter Table {$tablename} Change {$old_fieldname} {$data['name']} {$fieldtype}";
			$validate=validate('field');
			if(!$validate->scene('redact')->check($data)){
				$this->error($validate->getError());
			}
			else{
				$redact=db('field')->update($data);
				if($redact!==false){
					Db::execute($sql);
					$this->success('修改字段成功！', url('index'));
				}
				else{
					$this->error('修改字段失败！');
				}	
			}
			return;
		}

		$id=input('id');
		$field=db('field')->where('id',$id)->find();
		$models=db('model')->field('id,title')->select();
		$types=db('tag')->where(['group'=>'fieldtype','status'=>'1'])->field('title,value')->select();
		$this->assign([
			'field'		=> $field,
			'models'	=> $models,
			'types'		=> $types,
			]);
		return view();
	}


	// Ajax 异步删除单条记录
	public function remove()
	{
		if(request()->isAjax()){
			$id=input('id');
			$modelid=input('modelid');
			$tablename=db('model')->where('id',$modelid)->field('tablename')->find();
			$tablename=config('database.prefix').$tablename['tablename'];
			$fieldname=db('field')->where('id',$id)->field('name')->find();
			$fieldname=$fieldname['name'];
			$sql="Alter Table {$tablename} drop column {$fieldname}";
			$remove=db('field')->delete($id);
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


	// 表单排序循环
	public function global()
	{
		// 排序循环
		$data=input('post.');
		foreach ($data['sort'] as $key => $value) {
			db('field')->where('id',$key)->update(['sort'=>$value]);
		}
		$this->success('数据处理成功！', url('index'));
	}


	// Ajax 异步转换状态
	public function change()
	{
		if(request()->isAjax()){
			$id=input('id');
			$status=db('field')->field('status')->where('id',$id)->find();
			$status=$status['status'];
			if($status==1){
				db('field')->where('id',$id)->update(['status'=>0]);
				echo 0; //由启用转换为禁用
			}
			else{
				db('field')->where('id',$id)->update(['status'=>1]);
				echo 1; //由禁用转换为启用
			}
		}
		else{
			$this->error('非法操作！');
		}
	}


}

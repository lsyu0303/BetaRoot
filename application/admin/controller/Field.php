<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Field extends Controller
{
	public function index()
	{
		return 111;
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
				case 3:
				case 4:
				case 5:
				case 6:
					$fieldtype="varchar(255) not null default ''";
					break;
				case 2:
					$fieldtype="text";
					break;
				case 7:
					$fieldtype="float not null default '0.0'";
					break;
				case 8:
					$fieldtype="int not null default '0'";
					break;
				default:
					$fieldtype="varchar(255) not null default ''";
					break;
			}
			$sql="Alter table {$tablename} add {$data['name']} {$fieldtype}";
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
		$types=db('tab')->where(['group'=>'fieldtype','status'=>'1'])->field('title,value')->select();
		$this->assign([
			'models'	=> $models,
			'types'		=> $types,
			]);
		return view();
	}


	public function redact()
	{
		return view();
	}




}

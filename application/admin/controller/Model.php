<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Model extends Controller
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
			$sql="Create Table {$tablename} (id int(10) unsigned not null auto_increment primary key) Engine=InnoDB default Charset=utf8mb4";
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
		return view();
	}


	// Ajax 异步删除单条记录
	public function remove()
	{
		if(request()->isAjax()){
			$id=input('id');
			$tablename=config('database.prefix').input('tablename');
			$sql="drop table {$tablename}";
			$delete=db('model')->delete($id);
			if($delete){
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

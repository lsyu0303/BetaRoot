<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Model extends Controller
{
	public function index()
	{
		$model_lists=db('model')->order('sort asc')->paginate(10);
		$this->assign('model_lists',$model_lists);
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
			$insert=db('model')->insert($data);
			if($insert){
				Db::execute($sql);
				$this->success('添加模型成功！', url('index'));
			}
			else{
				$this->error('添加模型失败！');
			}
			return;
		}
		return view();
	}

	public function redact()
	{
		if(request()->isPost()){
			$data=input('post.');
			$validate=validate('category');
			if(!$validate->scene('redact')->check($data)){
				$this->error($validate->getError());
			}
			$redact=db('category')->update($data);
			if($redact!==null){
				$this->success('修改模块成功！', url('index'));
			}
			else{
				$this->error('修改模块失败！');
			}
			return;
		}

		$id=input('id');
		$category_list=db('category')->where('id',$id)->find();
		$category_lists=model('category')->tree();
		$model_lists=db('model')->field('id,title')->select();
		$category_types=db('tab')->where(['group'=>'categorytype','status'=>'1'])->field('title,value')->select();
		$this->assign([
			'category'=>$category_list,
			'category_lists'=>$category_lists,
			'model_lists'=>$model_lists,
			'category_types'=>$category_types,
			]);
		return view();
	}

	// Ajax 异步删除单条记录
	public function ajaxdel()
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

	// 表单批量删除与排序
	public function global()
	{
		$data=input('post.');
		// 排序循环
		foreach ($data['sort'] as $key => $value) {
			db('category')->where('id',$key)->update(['sort'=>$value]);
		}
		// 查询所有选中记录及子级记录
		$ids=input('post.id/a');
		if($ids){
			model('category')->batch($ids);
		}
		$this->success('数据处理成功', url('index'));
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

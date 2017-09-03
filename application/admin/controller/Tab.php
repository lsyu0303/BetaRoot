<?php
namespace app\admin\controller;
use app\admin\controller\Common;

class Tab extends Common
{
	public function index()
	{
		$tabs=db('tab')->order('sort asc')->paginate(10);
		$this->assign('tabs',$tabs);
		return view();
	}


	public function insert()
	{
		if(request()->isPost()){
			$data=input('post.');
			$validate=validate('tab');
			if(!$validate->scene('insert')->check($data)){
				$this->error($validate->getError());
			}
			else{
				$insert=db('tab')->insert($data);
				if($insert){
					$this->success('添加分组成功！', url('index'));
				}
				else{
					$this->error('添加分组失败！');
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
			$validate=validate('tab');
			if(!$validate->scene('redact')->check($data)){
				$this->error($validate->getError());
			}
			else{
				$redact=db('tab')->update($data);
				if($redact!==false){
					$this->success('修改分组成功！', url('index'));
				}
				else{
					$this->error('修改分组失败！');
				}	
			}
			return;
		}

		$id=input('id');
		$tab=db('tab')->where('id',$id)->find();
		$this->assign('tab',$tab);
		return view();
	}


	// Ajax 异步删除单条记录
	public function remove()
	{
		if(request()->isAjax()){
			$id=input('id');
			$remove=db('tab')->delete($id);
			if($remove){
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


	// 表单批量删除记录
	public function global()
	{
		// 排序循环
		$data=input('post.');
		foreach ($data['sort'] as $key => $value) {
			db('tab')->where('id',$key)->update(['sort'=>$value]);
		}
		// 批量删除
		$ids=input('post.id/a');
		if($ids){
			db('tab')->where('id', 'in', $ids)->delete();
		}
		$this->success('数据处理成功！', url('index'));
	}


	// Ajax 异步转换状态
	public function change()
	{
		if(request()->isAjax()){
			$id=input('id');
			$status=db('tab')->field('status')->where('id',$id)->find();
			$status=$status['status'];
			if($status==1){
				db('tab')->where('id',$id)->update(['status'=>0]);
				echo 0; //由启用转换为禁用
			}
			else{
				db('tab')->where('id',$id)->update(['status'=>1]);
				echo 1; //由禁用转换为启用
			}
		}
		else{
			$this->error('非法操作！');
		}
	}


}

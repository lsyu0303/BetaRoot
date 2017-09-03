<?php
namespace app\admin\controller;
use app\admin\controller\Common;

class Tag extends Common
{
	public function index()
	{
		$tags=db('tag')->order('sort asc')->paginate(10);
		$groups=db('tab')->field('name,title')->select();
		$this->assign([
			'tags'		=> $tags,
			'groups'	=> $groups,
			]);
		return view();
	}


	public function insert()
	{
		if(request()->isPost()){
			$data=input('post.');
			$validate=validate('tag');
			if(!$validate->scene('insert')->check($data)){
				$this->error($validate->getError());
			}
			else{
				$insert=db('tag')->insert($data);
				if($insert){
					$this->success('添加标签成功！', url('index'));
				}
				else{
					$this->error('添加标签失败！');
				}
			}
			return;
		}
		$groups=db('tab')->field('name,title')->select();
		$this->assign('groups',$groups);
		return view();
	}


	public function redact()
	{
		if(request()->isPost()){
			$data=input('post.');
			$validate=validate('tag');
			if(!$validate->scene('redact')->check($data)){
				$this->error($validate->getError());
			}
			else{
				$redact=db('tag')->update($data);
				if($redact!==false){
					$this->success('修改标签成功！', url('index'));
				}
				else{
					$this->error('修改标签失败！');
				}	
			}
			return;
		}

		$id=input('id');
		$tag=db('tag')->where('id',$id)->find();
		$groups=db('tab')->field('name,title')->select();
		$this->assign([
			'tag'		=> $tag,
			'groups'	=> $groups,
			]);
		return view();
	}


	// Ajax 异步删除单条记录
	public function remove()
	{
		if(request()->isAjax()){
			$id=input('id');
			$remove=db('tag')->delete($id);
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
			db('tag')->where('id',$key)->update(['sort'=>$value]);
		}
		// 批量删除
		$ids=input('post.id/a');
		if($ids){
			db('tag')->where('id', 'in', $ids)->delete();
		}
		$this->success('数据处理成功！', url('index'));
	}


	// Ajax 异步转换状态
	public function change()
	{
		if(request()->isAjax()){
			$id=input('id');
			$status=db('tag')->field('status')->where('id',$id)->find();
			$status=$status['status'];
			if($status==1){
				db('tag')->where('id',$id)->update(['status'=>0]);
				echo 0; //由启用转换为禁用
			}
			else{
				db('tag')->where('id',$id)->update(['status'=>1]);
				echo 1; //由禁用转换为启用
			}
		}
		else{
			$this->error('非法操作！');
		}
	}


}

<?php
namespace app\admin\controller;
use think\Controller;
use think\Model;

class Category extends Controller
{
	public function index()
	{
		$category_lists=model('category')->trees();
		$this->assign('category_lists',$category_lists);
		return view();
	}

	public function insert()
	{
		if(request()->isPost()){
			$data=input('post.');
			$validate=validate('category');
			if(!$validate->scene('insert')->check($data)){
				$this->error($validate->getError());
			}
			$insert=db('category')->insert($data);
			if($insert){
				$this->success('添加模块成功！', url('index'));
			}
			else{
				$this->error('添加模块失败！');
			}
			return;
		}

		$pid=input('pid');
		$category_lists=model('category')->tree();
		$model_lists=db('model')->field('id,title')->select();
		$category_types=db('tab')->where(['group'=>'categorytype','status'=>'1'])->field('title,value')->select();
		$this->assign([
			'pid'=>$pid,
			'category_lists'=>$category_lists,
			'model_lists'=>$model_lists,
			'category_types'=>$category_types,
			]);
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

	// 单条记录及子级记录并删除
	public function delete()
	{
		$id=input('id');
		$childrens=model('category')->childrens($id);
		$childrens[]=(int)$id;
		$data=db('category')->delete($childrens);
		if($data){
			$this->success('删除模块成功！', url('index'));
		}
		else{
			$this->error('删除模块失败！');
		}
		return;

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


	// Uploadify 插件文件上传
	public function uploadify()
	{
		$file = request()->file('image');
		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
		if($info){
			echo $info->getSaveName();
		}
		else{
			echo $file->getError();
		}
		return;
	}

	// Ajax 异步转换状态
	public function change()
	{
		if(request()->isAjax()){
			$sid=input('sid');
			$status=db('category')->field('status')->where('id',$sid)->find();
			$status=$status['status'];
			if($status==1){
				db('category')->where('id',$sid)->update(['status'=>0]);
				echo 0; //由显示转换为隐藏
			}
			else{
				db('category')->where('id',$sid)->update(['status'=>1]);
				echo 1; //由隐藏转换为显示
			}
		}
		else{
			$this->error('非法操作！');
		}
	}

	// Ajax 异步删除文件
	public function deletefile()
	{
		$id=input('id');
		$source=input('source');
		$source=UPLOADS.$source;
		$results=@unlink($source);
		if($id){
			db('category')->where('id',$id)->setField('image','');
		}
		if($results){
			echo 1; //删除文件成功
		}
		else{
			echo 0; //删除文件失败
		}
	}


}
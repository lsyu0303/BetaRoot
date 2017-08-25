<?php
namespace app\admin\controller;
use think\Controller;

class Category extends Controller
{
	public function index()
	{
		$categorys=model('category')->trees();
		$models=db('model')->field('id,title')->select();
		$types=db('tab')->where(['group'=>'categorytype','status'=>'1'])->field('title,value')->select();
		$this->assign([
			'categorys'	=> $categorys,
			'models'	=> $models,
			'types'		=> $types,
			]);
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
			else{
				$insert=db('category')->insert($data);
				if($insert){
					$this->success('添加模块成功！', url('index'));
				}
				else{
					$this->error('添加模块失败！');
				}
			}
			return;
		}

		$pid=input('pid');
		$categorys=model('category')->tree();
		$models=db('model')->field('id,title')->select();
		$types=db('tab')->where(['group'=>'categorytype','status'=>'1'])->field('title,value')->select();
		$this->assign([
			'pid'		=> $pid,
			'categorys'	=> $categorys,
			'models'	=> $models,
			'types'		=> $types,
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
			else{
				$redact=db('category')->update($data);
				if($redact!==false){
					$this->success('修改模块成功！', url('index'));
				}
				else{
					$this->error('修改模块失败！');
				}	
			}
			return;
		}

		$id=input('id');
		$category=db('category')->where('id',$id)->find();
		$trees=model('category')->tree();
		$models=db('model')->field('id,title')->select();
		$types=db('tab')->where(['group'=>'categorytype','status'=>'1'])->field('title,value')->select();
		$this->assign([
			'category'	=> $category,
			'trees'		=> $trees,
			'models'	=> $models,
			'types'		=> $types,
			]);
		return view();
	}


	// Ajax 异步删除单条记录及子记录
	public function remove()
	{
		if(request()->isAjax()){
			$id=input('id');
			$childrens=model('category')->childrens($id);
			$childrens[]=(int)$id;
			$remove=db('category')->delete($childrens);
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

	// 表单批量删除与排序
	public function global()
	{
		// 排序循环
		$data=input('post.');
		foreach ($data['sort'] as $key => $value) {
			db('category')->where('id',$key)->update(['sort'=>$value]);
		}
		// 查询所有选中记录及子级记录
		$ids=input('post.id/a');
		if($ids){
			model('category')->batch($ids);
		}
		$this->success('数据处理成功！', url('index'));
	}


	// Uploadify 插件文件上传
	public function uploadify()
	{
		$file=request()->file('image');
		$info=$file->move(ROOT_PATH . 'public' . DS . 'uploads');
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
			$id=input('id');
			$status=db('category')->field('status')->where('id',$id)->find();
			$status=$status['status'];
			if($status==1){
				db('category')->where('id',$id)->update(['status'=>0]);
				echo 0; //由显示转换为隐藏
			}
			else{
				db('category')->where('id',$id)->update(['status'=>1]);
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
		if(request()->isAjax()){
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
		else{
			$this->error('非法操作！');
		}
	}


	// Ajax 异步模块伸缩
	public function flex()
	{
		if(request()->isAjax()){
			$id=input('id');
			$childrens=model('category')->childrens($id);
			echo json_encode($childrens);
		}
		else{
			$this->error('非法操作！');
		}
	}


	// Ajax 异步模板切换
	public function template()
	{
		$id=input('id');
		$data=db('category')->find($id);
		echo json_encode($data);
	}


}

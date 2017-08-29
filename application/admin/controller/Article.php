<?php
namespace app\admin\controller;
use think\Controller;

class Article extends Controller
{
	public function index()
	{
		$modelid=input('mid');
		$categoryid=input('cid');
		if(!$modelid){
			$modelid=0;
		}
		$articles=db('article')->paginate(10);
		$this->assign([
			'modelid'	=> $modelid,
			'categoryid'=> $categoryid,
			'articles'	=> $articles,
			]);
		return view();
	}


	public function insert()
	{
		if(request()->isPost()){
			$data=input('post.');
			$validate=validate('article');
			if(!$validate->scene('insert')->check($data)){
				$this->error($validate->getError());
			}
			else{
				$insert=db('article')->insert($data);
				if($insert){
					$this->success('添加文章成功！', url('index'));
				}
				else{
					$this->error('添加文章失败！');
				}
			}
			return;
		}
		
		$modelid=input('mid');
		$categoryid=input('cid');
		$categorys=model('category')->tree();
		$types=db('tag')->where(['group'=>'flagtype','status'=>'1'])->field('title,value')->select();

		// 获取模型自定义字段
		$fields=db('field')->where('modelid',$modelid)->order('sort asc')->select();
		$longtexts=db('field')->where(['modelid'=>$modelid,'type'=>'9'])->order('sort asc')->select();

		$this->assign([
			'modelid'	=> $modelid,
			'categoryid'=> $categoryid,
			'categorys'	=> $categorys,
			'types'		=> $types,
			'fields'	=> $fields,
			'longtexts'	=> $longtexts,
			]);
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
		$groups=db('tabs')->field('name,title')->select();
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


	// Uploadify 插件文件上传
	public function uploadify()
	{
		$file=request()->file('image');
		$info=$file->move(ROOT_PATH . 'public' . DS . 'uploads/image');
		if($info){
			echo $info->getSaveName();
		}
		else{
			echo $file->getError();
		}
		return;
	}


	// Ajax 异步删除文件
	public function deletefile()
	{
		if(request()->isAjax()){
			$id=input('id');
			$source=input('source');
			$upload=ROOT_PATH . 'public' . DS . 'uploads/image';
			$source=$upload . DS . $source;
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

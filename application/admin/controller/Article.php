<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use think\Db;

class Article extends Common
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


	// 多文件上传处理
	public function upload($fieldName)
	{
		$file=request()->file($fieldName);
		$info=$file->move(ROOT_PATH . 'public' . DS . 'uploads/image');
		if($info){
			return $info->getSaveName();
		}
		else{
			echo $file->getError();
		}
	}


	public function insert()
	{
		$modelid=input('mid');
		$categoryid=input('cid');
		if(!$modelid){
			$modelid=0;
		}
		if(request()->isPost()){
			$data=input('post.');
			$data['modelid']=$modelid;
			$data['publishtime']=time();
			$data['updatetime']=time();

			// 获取主表名及附表名
			$ArticleTable=config('database.prefix').'article';
			$AppendTable=db('model')->field('name')->find($modelid);
			$AppendTable=$AppendTable['name'];

			// 获取主表字段名称
			$columns=array();
			$sql="Show Columns From {$ArticleTable}";
			$_columns=Db::query($sql);
			foreach ($_columns as $value) {
				$columns[]=$value['Field'];
			}

			// 分配提交的数据到主表或附表
			$articles=array();
			$appends=array();
			foreach ($data as $key => $value) {
				if(in_array($key, $columns)){
					if(is_array($value)){
						$value=implode('|', $value);
					}
					$articles[$key]=$value;
				}
				else{
					if(is_array($value)){
						$value=implode('|', $value);
					}
					$appends[$key]=$value;
				}
			}

			// 附表多文件上传
			foreach ($_FILES as $key => $value) {
				if($_FILES[$key]['tmp_name'] !=''){
					$appends[$key]=$this->upload($key);
				}
			}

			// 添加数据并返回主表 ID 给附表
			$insertArticle=db('article')->insertGetId($articles);
			$appends['aid']=$insertArticle;
			$insertAppends=db($AppendTable)->insert($appends);

			if($insertArticle&&$insertAppends){
				$this->success('添加文章成功！', url('index',array('cid'=>$categoryid,'mid'=>$modelid)));
			}
			else{
				$this->error('添加文章失败！');
			}
			return;
		}
		
		$categorys=model('category')->tree();
		$types=db('tag')->where(['group'=>'flagtype','status'=>'1'])->field('title,value')->select();

		// 获取模型自定义字段
		$fields=db('field')->where('modelid',$modelid)->order('sort asc')->select();
		$longtexts=db('field')->where(['modelid'=>$modelid,'type'=>'3'])->order('sort asc')->select();

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


	// Uploadify 插件上传图片并添加水印
	public function uploadify()
	{
		$file=request()->file('image');
		$info=$file->move(ROOT_PATH . 'public' . DS . 'uploads/image');
		if($info){
			// 判断是否开启图片上传
			if($this->config['cfg_system_thumb']=='开启'){
				// 图片上传参数
				$width=$this->config['cfg_thumb_width'];
				$height=$this->config['cfg_thumb_height'];
				$alpha=$this->config['cfg_water_alpha'];
				$water=$this->config['cfg_water_image'];

				// 缩略图裁剪方式
				switch ($this->config['cfg_thumb_crop']) {
					case '等比例缩放':
						$crop = 1;
						break;
					case '缩放后填充':
						$crop = 2;
						break;
					case '居中位裁剪':
						$crop = 3;
						break;
					case '左上角裁剪':
						$crop = 4;
						break;
					case '右下角裁剪':
						$crop = 5;
						break;
					case '固定尺寸缩放':
						$crop = 6;
						break;
					default:
						$crop = 1;
						break;
				}

				// 水印图片位置
				switch ($this->config['cfg_water_pos']) {
					case '左上角':
						$pos = 1;
						break;
					case '上居中':
						$pos = 2;
						break;
					case '右上角':
						$pos = 3;
						break;
					case '左居中':
						$pos = 4;
						break;
					case '中居中':
						$pos = 5;
						break;
					case '右居中':
						$pos = 6;
						break;
					case '左下角':
						$pos = 7;
						break;
					case '下居中':
						$pos = 8;
						break;
					case '右下角':
						$pos = 9;
						break;
					default:
						$pos = 9;
						break;
				}

				$upload=ROOT_PATH . 'public' . DS . 'uploads/image';
				$source=$upload . DS . $info->getSaveName();
				$water=$upload . DS . $water;
				$image=\think\Image::open($source);
				if($this->config['cfg_system_water']=='开启'){
					$image->thumb($width,$height,$crop)->water($water,$pos,$alpha)->save($source);
				}
				else{
					$image->thumb($width,$height,$crop)->save($source);
				}
			}
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

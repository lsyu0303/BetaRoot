<?php
namespace app\admin\model;
use think\Model;

class Category extends Model
{
	// 获取完整的记录信息
	public function trees()
	{
		$data=db('category')->field('id,name,title,parentid,modelid,type,status,sort')->order('sort asc')->select();
		return $this->sort($data);
	}


	// 获取简洁的记录信息
	public function tree()
	{
		$data=db('category')->field('id,parentid,title')->order('id asc')->select();
		return $this->sort($data);
	}


	// 执行无限级排序循环
	public function sort($data,$parentid=0,$level=0,$delimiter='&emsp;')
	{
		static $array=array();
		foreach ($data as $key => $value) {
			if($value['parentid']==$parentid){
				$value['level']=$level;
				$value['delimiter']=str_repeat($delimiter, $level*2);
				$array[]=$value;
				$this->sort($data,$value['id'],$level+1,$delimiter);
			}
		}
		return $array;
	}


	// 获取子级记录信息
	public function childrens($id)
	{
		$data=db('category')->field('id,parentid')->select();
		return $this->_childrens($data,$id);

	}


	// 循环查找子级记录信息
	private function _childrens($data,$id)
	{
		static $array=array();
		foreach ($data as $key => $value) {
			if($value['parentid']==$id){
				$array[]=$value['id'];
				$this->_childrens($data,$value['id']);
			}
		}
		return $array;
	}


	// 批量删除记录及子记录
	public function batch($ids)
	{
		foreach ($ids as $key => $value) {
			$array[]=$this->childrens($value);
			$array[]=(int)$value;
		}
		// 将所有查询到的记录转换为一维数组
		$_array=array();
		foreach ($array as $key => $value) {
			if(is_array($value)){
				foreach ($value as $k => $v) {
					$_array[]=$v;
				}
			}
			else{
				$_array[]=$value;
			}
		}
		$_array=array_unique($_array);	//去除重复记录
		$this::destroy($_array);		//执行删除操作
	}


}

<?php
namespace app\admin\validate;
use think\Validate;

class Tag extends Validate
{
	protected $rule = [
		'title'		=>	'chsDash|require|unique:system|max:30',
		'group'		=>	'alphaDash|require',
		'value'		=>	'number',
		'parentid'	=>	'number',
		'sort'		=>	'number',
	];

	protected $message =[
		'title.chsDash'		=>	'标题只能为 汉字、字母、数字、下划线_及破折号-！',
		'title.require'		=>	'标题不能为空！',
		'title.unique'		=>	'标题已经存在！',
		'title.max'			=>	'标题不能超过 30 个字符！',
		'group.alphaDash'	=>	'分组只能为 字母、数字、下划线_及破折号-！',
		'group.require'		=>	'分组不能为空！',
		'value.number'		=>	'默认值只能为数字！',
		'parentid.number'	=>	'父级只能为数字！',
		'sort.number'		=>	'排序只能为数字！',
	];

	protected $scene = [
		'insert'	=>	['title'],
		'redact'	=>	['title'],
	];

}

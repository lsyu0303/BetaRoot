<?php
namespace app\admin\validate;
use think\Validate;

class Field extends Validate
{
	protected $rule = [
		'name'		=>	'alphaDash|require|unique:system|max:30',
		'title'		=>	'chsDash|require|unique:system|max:30',
		'groupid'	=>	'number',
		'value'		=>	'chsDash',
		'optional'	=>	'chsDash',
		'sort'		=>	'number',
	];

	protected $message =[
		'name.alphaDash'	=>	'标识只能为 字母、数字、下划线_及破折号-！',
		'name.require'		=>	'标识不能为空！',
		'name.unique'		=>	'标识已经存在！',
		'name.max'			=>	'标识不能超过 30 个字符！',
		'title.chsDash'		=>	'标题只能为 汉字、字母、数字、下划线_及破折号-！',
		'title.require'		=>	'标题不能为空！',
		'title.unique'		=>	'标题已经存在！',
		'title.max'			=>	'标题不能超过 30 个字符！',
		'groupid.number'	=>	'分组只能为数字！',
		'value.chsDash'		=>	'默认值只能为 汉字、字母、数字、下划线_及破折号-！',
		'optional.chsDash'	=>	'可选值只能为 汉字、字母、数字、下划线_及破折号-！',
		'sort.number'		=>	'排序只能为数字！',
	];

	protected $scene = [
		'insert'	=>	['name'],
		'redact'	=>	['name'],
	];

}

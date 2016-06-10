<?php
/**
 * 微博用户视图模型
 */
namespace Home\Model;
use Think\Model\ViewModel;
class WeiboViewModel extends ViewModel {
	public  $viewFields = array(
		'weibo'=>array(
			'id','content','isturn','time','turn','keep','comment','uid','_type'=>'LEFT'
		),
		'picture'=>array(
			'max'=>'pic','_on'=>'weibo.id=picture.wid'
		),
		'userinfo'=>array(
			'username','_on'=>'weibo.uid=userinfo.uid'
		)
	);
}
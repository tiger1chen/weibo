<?php
/**
 * 评论视图模型
 */
namespace Home\Model;
use Think\Model\ViewModel;
class CommentViewModel extends ViewModel {
	protected $viewFields = array(
	 	'comment'=>array(
	 		'id','content','time','wid',
	 		'_type'=>'left'
	 	),
	 	'userinfo'=>array(
	 		'username','_on'=>'comment.uid = userinfo.uid'
	 	)
	);
}
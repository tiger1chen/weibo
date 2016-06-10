<?php
	namespace Home\Model;
	use Think\Model\ViewModel;
	//私信视图模型查询
	class CommentViewModel extends ViewModel {
		public $viewFields = array(
			'comment'=>array('id','content','time','wid','_type'=>'LEFT'),
			'userinfo'=>array('username','face50'=>'face','uid','_on'=>' comment.uid=userinfo.uid')
		);
		
		public function getAll($where,$limit){
			$result = $this->where($where)->order('time DESC')->limit($limit)->select();
			return $result;
		}
	}
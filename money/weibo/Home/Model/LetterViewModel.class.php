<?php
	namespace Home\Model;
	use Think\Model\ViewModel;
	//私信视图模型查询
	class LetterViewModel extends ViewModel {
		public $viewFields = array(
			'letter'=>array('id','content','time','_type'=>'LEFT'),
			'userinfo'=>array('username','face50'=>'face','uid','_on'=>' letter.from=userinfo.uid')
		);
		
		public function getAll($where,$limit){
			$result = $this->where($where)->order('time DESC')->limit($limit)->select();
			return $result;
		}
	}
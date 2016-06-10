<?php
	namespace Home\Model;
	use Think\Model\ViewModel;
	//首页数据视图模型查询
	class WeiboViewModel extends ViewModel {
		public $viewFields = array(
			'weibo'=>array('id','content','isturn','time','turn','keep','comment','uid','_type'=>'LEFT'),
			'userinfo'=>array('username','face50'=>'face','_on'=>'weibo.uid = userinfo.uid','_type'=>'LEFT'),
			'picture'=> array('mini','medium','max','_on'=>'weibo.id=picture.wid')
		);
		
		//获取视图模型的所有变量
		public function getAll($where,$limit){
		 	$result = $this->where($where)->order('time desc')->limit($limit)->select();
		 	if($result){
		 		foreach ($result as $k=>$v){
		 			if($v['isturn']){
		 				$where = array('id'=>$v['id']);
		 				$tmp = $this->find($v['isturn']);
		 				$result[$k]['isturn'] = $tmp?$tmp:-1;
		 			}
		 		}
		 	}else{
		 		$result = '';
		 	}
		 	return $result;
		}
	}
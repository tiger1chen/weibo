<?php
	namespace Home\Model;
	use Think\Model\ViewModel;
	//收藏微博数据视图模型查询
	class KeepViewModel extends ViewModel {
		protected  $viewFields = array(
			'keep'=>array('id'=>'kid','time'=>'ktime','_type'=>'INNER'),
			'weibo'=>array('id','content','isturn','time','turn','keep','comment','uid','_on'=>'keep.wid=weibo.id','_type'=>'LEFT'),
			'userinfo'=>array('username','face50'=>'face','_on'=>'weibo.uid = userinfo.uid','_type'=>'LEFT'),
			'picture'=> array('mini','medium','max','_on'=>'weibo.id=picture.wid')
		);
		
		
		//获取收藏微博
		public function getAll($where,$limit){
			$result = $this->where($where)->order('ktime DESC')->limit($limit)->select();
			$db = D('WeiboView');
			foreach ($result as $k=>$v){
				if($v['isturn']){
					$result[$k]['isturn'] = $db->find($v['isturn']);
				}
			}
			return $result;
		}
	}
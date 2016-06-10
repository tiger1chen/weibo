<?php
namespace Home\Controller;
use Think\Controller;
class SearchController extends Controller {
	public function __construct(){
		//父类的方法不能丢
		parent::__construct();
		//判断是否进行自动登录到首页
		if(isset($_COOKIE['auto']) && !isset($_SESSION['uid'])){
			$value = encryption($_COOKIE['auto'],1);
			$value = explode('|',$value);
			$ip = get_client_ip();//获取客户端ip
			if($value[1] == $ip){
				$account = $value[0];
				$where = array('account'=>$account);
				$user = M('user')->where($where)->field('lock,id')->find();
				if($user && !$user['lock']){
					session('uid',$user['id']);
				}
			}
		}

		//获取用户信息id
		if(!isset($_SESSION['uid'])){
			$url = U('Login/login');
			redirect($url);
		}
	}
	
	
	//找微博控制器
	public function searchWeibo(){
		$keyword = $this->_getKeyWord($_GET['keyword']);	
		if($keyword){
			//设置条件
			$where['content']  = array('like','%'.$keyword.'%');
			$db = D('WeiboView');
			//分页		
			$count = M('weibo')->where($where)->count();
			$page = new \Think\Page($count,2);
			$limit =  $page->firstRow.','.$page->listRows;
			$show       = $page->show();// 分页显示输出		
			$indexInfo = $db->getAll($where,$limit);
			$this->assign('show',$show);
			$this->assign('count',$count);
			$this->assign('indexInfo',$indexInfo);
		}else{
			$keyword="搜索微博、找人";
		}
		$this->assign('keyword',$keyword);//关键字
		$this->display();
	}
	
	
	//找人的控制器
	public function searchUser(){
		$keyword = $this->_getKeyWord($_GET['keyword']);
		if($keyword){	
			//条件:搜索出自己外的所有符合的
			$where['username'] = array('like','%'.$keyword.'%');
			$where['uid'] = array('NEQ',session('uid'));
			//要取出的数据
			$field = array('username','sex','location','face80','fans','follow','weibo','uid');
			//分页		
			$count = M('userinfo')->where($where)->count();
			$this->assign('count',$count);
			$page = new \Think\Page($count,2);
			$info = M('userinfo')->where($where)->field($field)->limit($page->firstRow.','.$page->listRows)->select();
			$show = $page->show();
			//搜索到的人与用户之间的关系：未关注、已关注、相互关注
			$info = $this->_attention($info);     
			$this->assign('show',$show);//分页样式显示
			$this->assign('page',$page);// 赋值分页输出
			$this->assign('info',$info);//用户信息
		}else{
			$keyword="搜索微博、找人";
		}
		$this->assign('keyword',$keyword);//关键字
		$this->display();
	}
	
	//对传递过来的keyword进行处理
	private function _getKeyWord($keyword){
		return $keyword == '搜索微博、找人'?NULL:htmlspecialchars($keyword);
	}
	
	private function _attention($info){
		if(!info){
			return false;
		}
		//为每个用户加特殊标记：来表示用户与搜索到的用户之间的关系0：未关注1:已关注2:相互关注
		foreach($info as $k=>&$v) {
			$sql = 'select follow from wei_follow where follow='.session('uid').' and fans='.$v['uid'].' union select follow from wei_follow where follow='.$v['uid'].' and fans='.session('uid');
			$mutual =M('follow')->query($sql);
			if(count($mutual) == 2) {
				$v['mutual'] =2;
			}else if ($mutual[0]['follow'] == $v['uid']){
				$v['mutual'] =1;
			}else{
				$v['mutual'] =0;
			}
		}
		return $info;
	}
}
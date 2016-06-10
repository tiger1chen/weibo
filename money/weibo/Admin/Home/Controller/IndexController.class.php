<?php
namespace Home\Controller;
use  Home\Controller;
class IndexController extends CommonController {
		
	//后台首页视图
    public function index(){
		$this->display();
    }
    
    //后台信息页
    public function copy(){
    	
    	//用户信息
    	$db = M('user');
    	$this->user = $db->count();//总共注册的用户
    	$this->lock =  $db->where(array('lock'=>1))->count();//总共被锁定的用户
    	
    	//微博信息
    	$db = M('weibo');
    	$this->weibo = $db->where(array('isturn'=>0))->count();//原创的微博
    	$this->turn = $db->where(array('isturn'=>array('gt',0)))->count();//转发的微博
     	//评论总条数
     	$this->comment = M('comment')->count();
    	$this->display();
    }
    
    //退出登陆
    public function loginOut(){
    	session_unset();//释放当前内存存储的session
    	session_destroy();//删除session数据
    	redirect(U('Login/index'));
    }
    
    
}
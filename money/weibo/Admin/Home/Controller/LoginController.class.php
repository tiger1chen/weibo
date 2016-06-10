<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
		$this->display();
    }
    
    //后台验证码
    public function verify(){
    	$Verify =     new \Think\Verify();
    	$Verify->useImgBg = true; 
    	$Verify->entry();
    }
    
    //登陆操作处理
   	public function login(){
   		if(IS_POST !=1){
   			E('页面不存在');
   		}
   		$code = I('post.verify','','htmlspecialchars');//验证码
   		$password = I('post.pwd','','htmlspecialchars');//密码
   		$username = I('post.uname','','htmlspecialchars');//用户名
   		$verify = new \Think\Verify();    
 		if(!$verify->check($code)){
   			$this->error('验证码错误');
   		}
   		if($row = M('admin')->where(array('username'=>$username))->find()){//查看验证码是否正确
   			if($row['lock']){
   				$this->error('用户已经被锁定');
   			}
   			
   			if($row['password'] != md5($password)){
   				$this->error('用户名或密码错误');
   			}else{//用户验证成功 跟新记录时间、记录ip地址
   				$data = array(
   					'id'=>$row['id'],
   					'logintime'=>time(),
   					'loginip'=>get_client_ip()
   				);
   				M('admin')->save($data);//更新地址和登陆时间
   				
   				//保存一些数据在session中
   				session('uid',$row['id']);
   				session('username',$row['username']);
   				session('logintime',date('Y-m-d H:i',$row['logintime']));//上次登陆时间
   				session('now',date('Y-m-d H:i',time()));//当前登陆时间
   				session('loginip',$row['loginip']);//上次ip的登陆
   				session('admin',$row['admin']);//查看是否是超级管理员
   				$this->success('登陆成功正在为你跳转',U('Index/index'));
   			}
   		}else{
   			$this->error('用户或密码错误');
   		}
   	}
}
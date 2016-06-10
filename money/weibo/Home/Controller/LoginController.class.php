<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 登陆与注册控制器控制器
 *
 */
class LoginController extends Controller {
	/**
	 * 登陆页面
	 */
	public function login(){
		$this->display();
	}
	
	
	/**
	 * 登陆处理表单
	 */
	public function loginAct(){
		if(IS_POST !=1) {
			E('该页面不存在');
		}
		$account = I('post.account','','htmlspecialchars');
		$where = array('account'=>$account);
		$pwd = I('post.pwd','','md5');
		$user = M('user')->where($where)->find();
		if(!$user || $pwd!=$user['password']){
			$this->error('用户或密码错误');
		}
		//查看用户是否被锁定
		if($user['lock']){
			$this->error('该用户已经被锁定');
		}
		
		//处理下次自动登录
		if(isset($_POST['auto'])){
			$ip = get_client_ip();
			$account = $user['account'];
			$value=$account.'|'.$ip;
			$value = encryption($value);
			@setcookie('auto',$value,C('AUTO_LOGIN_TIME'),'/');
		}
		
		//登陆成功，跳转到首页，并写入到session中
		session('uid',$user['id']);
		header('Content-Type:text/html;charset=UTF-8');
		redirect(__APP__,3,'正在为你跳转......');
	}
	
	/**
	 * 注册页面
	 */	
	public function register(){
		if(!C(REGIS_ON)){
			$this->error('注册被封锁，请联系管理员');
		}
		$this->display();
	}
	
	
	/**
	 * 验证码
	 */
	public function verify() {
		$image = new \Think\Verify;
		$image->entry();
	}
	
	/**
	 * 异步判断账号是否存在
	 */
	public function checkAccount(){
		if(IS_AJAX !=1) {
			E('该页面不存在');
		}
		$account = I('post.account','0','htmlspecialchars');
		$where = array('account'=>$account);
		$info = M('user')->where($where)->field('id')->select();
		if($info) {
			echo  "false";
		}else{
			echo "true";
		}
		

	}
	
	/**
	 * 异步检验验证码是否正确
	 */
	
	public function checkVerify(){
		if(IS_AJAX !=1) {
			E('该页面不存在');
		}
		$verify = I('post.verify','0','htmlspecialchars');
		session('verify',$verify);	
		$image = new \Think\Verify;
		if($image->check($verify,$id='')){
			echo "true";
		}else{
			echo "false";
		}
	}
	
	
	/**
	 * 接受表单的数据
	 *
	 */
	public function runRegis(){
		if(IS_POST !=1) {
			E('该页面不存在');
		}
		//前端虽然有数据验证，但是可以修改，so后端也需要验证
		//验证码验证
		$image = new \Think\Verify;
		$verify = I('post.verify','0','htmlspecialchars');
		if($verify != session('verify')){
			$this->error('验证码错误');
		}
		$data= array(
			'account' => I('post.account','','htmlspecialchars'),
			'password' => I('post.pwd','','md5'),
			'registime' => $_SERVER['REQUEST_TIME'],
			'userinfo' => array(
				'username' => I('post.uname','','htmlspecialchars')
				)
		);
		$id = D('User')->insert($data);
		if($id) {
			//插入数据成功后把id写入session中
			session('uid',$id);
			//注册成功后跳转到首页面
			header("Content-Type:text/html;charset=UTF-8");
			redirect(__APP__,3,'注册成功，正在为你跳转.....');
		}else{
			$this->error('注册失败请重试......');
		}
	}
}
<?php
namespace Home\Controller;
use  Home\Controller;
/**
 * 用户管理控制器
 *
 */
class UserController extends CommonController {
	public function index(){
		
		//分页
		$count = D('UserView')->count();
		$Page       = new \Think\Page($count,2);
		$limit =  $Page->firstRow.','.$Page->listRows;
		$this->page = $Page->show();// 分页显示输出
		$this->users = D('UserView')->limit($limit)->select();
		$this->display();
	}
	
	//微博用户的解锁与加锁
	public function lockUser(){
		$id = I('get.id','','intval');
		$data = array(
			'id'=>$id,
			'lock'=>I('get.lock','','intval')
		);
		
		$msg = $data['lock']?'锁定':'解锁';
		if(M('user')->save($data)){
			$this->success($msg.'成功',$_SERVER['HTTP_REFERER']);
		}else{
			$this->error($msg.'失败');
		}
	}
	
	//检索微博用户
	public function sechUser(){
		if(isset($_GET['sech']) && isset($_GET['type'])){//搜索提交过来的处理
			$where = $_GET['type']?array('id'=>I('get.sech','','intval')):array('username'=>array('like','%'.I('get.sech','','htmlspecialchars').'%'));
			$user = D('UserView')->where($where)->select();
			$this->user = $user?$user:false;
		}
		$this->display();
	}
	
	
	/**
	 * 后台管理员列表
	 */
	public function admin(){
		$this->admin = M('admin')->select();
		$this->display();
	}
	
	/**
	 * 添加后台管理员
	 */
	public function addAdmin(){

		$this->display();
	}
	
	/**
	 * 锁定后台管理员
	 */
	public function lockAdmin(){
		$data = array(
			'id'=>I('get.id','','intval'),
			'lock'=>I('get.lock','','intval')		
		);
		$msg = $data['lock']?'锁定':'解锁';
		if(M('admin')->save($data)){
			$this->success($msg.'成功',U('admin'));
		}else{
			$this->error($msg.'失败');
		}
	}
	
	/**
	 * 删除后台管理员
	 */
	public function delAdmin(){
		
		$id = I('get.id','','intval');
		if(M('admin')->delete($id)){
			$this->success('删除成功',U('admin'));
		}else{
			$this->error('删除失败');
		}
	}

	
	/**
	 * 执行添加操作
	 */
	public function runAddAdmin(){
		if($_POST['pwd'] != $_POST['pwded']){
			$this->error('两次密码不一致');
		}
		$data = array(
			'username'=>I('post.username','','htmlspecialchars'),
			'password'=>I('post.pwd','','md5'),
			'logintime'=>time(),
			'loginip'=>get_client_ip(),
			'admin'=>I('post.admin','','intval')
		);
		
		if(M('admin')->data($data)->add()){
			$this->success('添加成功',U('admin'));
		}else{
			$this->error('添加失败，请重试');
		}
	}
	
	/**
	 * 修改密码视图
	 */
	public function editPwd(){
		$this->display();
	}
	
	/**
	 * 修改密码操作
	 */
	public function runEditPwd(){
		$db = M('admin');
		$old = $db->where(array('id'=>session('uid')))->getField('password');
		if($old != md5($_POST['old'])){
			$this->error('旧密码错误');
		}
		
		if($_POST['pwd'] != $_POST['pwded']){
			$this->error('两次密码不一致');
		}
		$data = array(
			'id'=>session('uid'),
			'password'=>I('post.pwd','','md5')
		);
		if(M('admin')->save($data)){
			$this->success('修改成功',U('Index/copy'));
		}else{
			$this->error('修改密码失败');
		}
	}
}
<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 用户设置个人信息控制器
 */
class UserSettingController extends Controller {
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
	
	/**
	 * 用户设置信息
	 */
	public function index(){
		$where = array('uid'=>session('uid'));
		$field = array('username','truename','sex','location','constellation','intro','face180','face80','face50');
		$userinfo = M('userinfo')->field($field)->where($where)->find();
		$this->assign('userinfo',$userinfo);
		$this->display();
	}
	
	/**
	 * 修改用户个人信息
	 */
	public function editBasic(){
		if(IS_POST !=1) {
			E('该页面不存在');
		}
		$where = array('uid'=>session('uid'));
		$data = array(
			'username' =>$_POST['nickname'],
			'truename' =>$_POST['truename'],
			'sex' => (int)$_POST['sex'],
			'location' => $_POST['province'].'|'.$_POST['city'],
			'constellation' => $_POST['night'],
			'intro' => $_POST['intro']
		);
		if(M('userinfo')->where($where)->save($data)){
			$this->success('修改成功',U('index'));
		}else{
			$this->error('修改失败',U('index'));
		}
	}
	
	/**
	 * 头像上传处理
	 */
	public function uploadFace(){
		if(IS_POST !=1) {
			E('该页面不存在');
		}
		//上传文件
		$url = $this->_post();
		//图片处理
		$data = $this->_img($url);
		echo json_encode($data);
		
	}
	
	//文件上传处理函数
	private function _post(){
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     C(UPLOAD_PIC_SIZE) ;// 设置附件上传大小
		$upload->exts      =     C(UPLOAD_PIC_EXTS);// 设置附件上传类型
		$upload->savePath  =     C(UPLOAD_PIC_PATH); // 设置附件上传目录
		$upload->autoSub = true;
		$upload->subName = date("Ymd");
		//上传文件
		$info = $upload->upload();
		if(!$info) {
			$this->error($upload->getError());
		}else{
			return './Uploads'.trim($info['Filedata']['savepath'],'.').$info['Filedata']['savename'];
		}		
	}
	
	//图片处理生成缩略图
	private function _img($url,$width=array(180,80,50),$height=array(180,80,50)){
		$image = new \Think\Image(); 
		$image->open($url);
		$dir = dirname($url);//文件路径
		$name = basename($url);//文件名
		$image->thumb($width[0],$height[0])->save($dir.'/max_'.$name);
		$image->thumb($width[1],$height[1])->save($dir.'/medium_'.$name);
		$image->thumb($width[2],$height[2])->save($dir.'/min_'.$name);
		return array(
			'status' =>1,
			'path' =>array(
				'max' =>$dir.'/max_'.$name,
				'medium'=>$dir.'/medium_'.$name,
				'min'=>$dir.'/min_'.$name
			)
		);
	}
	
	//保存修改图片
	public function editFace(){
		if(IS_POST == 1){
			$data = array(
				'face180'=>$_POST['face180'],
				'face80'=>$_POST['face80'],
				'face50'=>$_POST['face50']			
			);
			$where = array('uid'=>session('uid'));
			$info = M('userinfo')->field('face180,face80,face50')->where($where)->find();
			if(!empty($info['face180'])){
				//如果图片存在删除原图
				@unlink($info['face180']);
				@unlink($info['face80']);
				@unlink($info['face50']);		
			}
			if(M('userinfo')->where($where)->save($data)){
				$this->success('修改成功!');
			}else{
				$this->error('修改失败！');
			}
		}
	}
	
	//修改密码
	public function editPwd(){
		//判断是否是提交过来的
		if(IS_POST !=1) {
			E('页面不存在');
		}
		$oldpwd = I('post.old','','md5');
		$where = array('id'=>session('uid'));
		$myoldpwd = M('user')->where($where)->getField('password');
		if($myoldpwd != $oldpwd) {
			$this->error('原密码输入错误!');
		}
		if($_POST['new'] != $_POST['newed']) {
			$this->error('修改密码失败');
		}
		
		$data = array('password'=>I('post.new','','md5'));
		if(M('user')->where($where)->save($data)){
			$this->success('密码修改成功',U('index'));
		}else{
			$this->success('密码修改失败，请重试....');
		}
	}
	
	/**
	 * 创建新分组
	 */	
	public  function addGroup(){
		if(IS_AJAX !=1){
			E('页面不存在！');
		}
		$data = array('name'=>I('post.name','','htmlspecialchars'),
					'uid'=>session('uid')
				);
		if (M('group')->data($data)->add()){
			echo json_encode(array('status'=>1,'msg'=>'插入成功'));
		}else{
			echo json_encode(array('status'=>0,'msg'=>'数据插入失败'));
		}
	}
	
	/**
	 * 对用户进行关注
	 */
	public function addFollow(){
		if(IS_AJAX != 1){
			E('页面不存在');
		}
		$data = array(
			'follow' => I('post.follow','','intval'),
			'fans' => session('uid'),
			'gid'  => I('post.group','','intval')
		);
		if(M('follow')->data($data)->add()){
			//对应的关注数加1
			M('userinfo')->where(array('uid'=>session('uid')))->setInc('follow',1);
			M('userinfo')->where(array('uid'=>$data['follow']))->setInc('fans',1);
			echo json_encode(array(
				'status' => 1,
				'message' => '用户关注成功'
			));
			
		}else{
			echo 222;
			echo json_encode(array(
				'status' => 0,
				'message' => '用户关注失败'
			));
		}
	}
}						
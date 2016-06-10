<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
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
	

	
	
	//登陆个人首页之首页视图
	public function index(){
		header('Content-Type:text/html;charset=UTF-8');
		$Model = D('WeiboView');
		$where = array('fans'=>session('uid'));
		$gid = I('get.gid','','intval');//获取组id
		if($gid){
			$where['gid'] = $gid;//分组限制
		}
		$result = M('follow')->field('follow')->where($where)->select();
		//获取跟随者id
		$uid = array();
		if($result){
			foreach ($result as $k=>$v) {
				$uid[] = $v['follow'];
			}
		}
		if(!$gid){//如果不存在分组则，显示自己的微博
			$uid[] = session('uid');//看的微博包括自己
		}
		if($uid){
			$where2['uid'] = array('in',$uid);
			//分页
			$count = $Model->where($where2)->count();
			$Page       = new \Think\Page($count,2);
			$limit =  $Page->firstRow.','.$Page->listRows;
			$show       = $Page->show();// 分页显示输出
			$indexInfo = $Model->getAll($where2,$limit);
		}else{
			$indexInfo = false;
		}
		$this->assign('show',$show);
		$this->assign('indexInfo',$indexInfo);
		$this->display();
	}
	
	//退出登录
	public function loginOut(){
		session_unset();
		session_destroy();
		setcookie('auto','',time()-3600,'/');
		$this->display('Login/login');
	}
	
	//上传微博图片
	public function uploadPic(){
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
	private function _img($url,$width=array(800,380,120),$height=array(800,380,120)){
		$image = new \Think\Image(); 
		$image->open($url);
		$dir = dirname($url);//文件路径
		$name = basename($url);//文件名
		$image->thumb($width[0],$height[0])->save($dir.'/max_'.$name);
		$image->thumb($width[1],$height[1])->save($dir.'/medium_'.$name);
		$image->thumb($width[2],$height[2])->save($dir.'/min_'.$name);
		$dir = ltrim($dir,'.');//为了兼容linux 去除'.'
		return array(
			'status' =>1,
			'path' =>array(
				'max' =>$dir.'/max_'.$name,
				'medium'=>$dir.'/medium_'.$name,
				'min'=>$dir.'/min_'.$name
			)
		);
	}
	
	
	//处理微博信息
	public function sendWeibo(){
		if(IS_POST !=1) {
			E('该页面不存在');
		}


		$data = array(
			'content' => I('post.content','','htmlspecialchars'),
			'uid' => session('uid'),
			'time' => time()
		);
		$wid = M('weibo')->data($data)->add();
		//对于内容中的@进行出俩
		$this->_handleAtme($data['content'],$wid);	
		if($wid){
			if(!empty($_POST['medium'])){
				$data = array(
					'wid' => $wid ,
					'mini' => I('post.mini','','htmlspecialchars'),
					'medium' => I('post.medium','','htmlspecialchars'),
					'max' => I('post.max','','htmlspecialchars')
				);
				M('picture')->data($data)->add();
			}
			M('userinfo')->where(array('uid'=>session('uid')))->setInc('weibo');		
			$this->success('微博发布成功，正在为您跳转.....',U('Index/index'));
		}else{
			$this->error('微博发布失败,请重试.......');
		}
	}
	
	//提交的内容中的@进行出俩
	private function _handleAtme($content,$wid) {
		$pattern = '/@(\S+?)\s/';
		preg_match_all($pattern,$content,$arr);
		foreach($arr[1] as $k=>$v){
			$uid = M('userinfo')->where(array('username'=>$v))->getField('uid');
			if($uid){
				M('atme')->add(array('uid'=>$uid,'wid'=>$wid));
				set_msg('3',$uid);//@我推送
			}
		}
	}
	
	
	
	//提交的评论
	public  function Comment(){
		if(IS_POST !=1){
			E('无法找到相应的页面');
		}
		
		$data = array(
			'content'=> I('post.data','','htmlspecialchars'),
			'uid' => session('uid'),
			'wid' => I('post.wid','','intval'),
			'time'=>time()
		);
		//评论内容添加到数据库
		if(M('comment')->add($data)){
			//是否转发到自己的微博
			if($_POST['isturn']){
				//查看微博的信息
				$weibo = M('weibo')->where(array('id'=>I('post.wid','','intval')))->find();
				$username = M('userinfo')->field('username')->where(array('uid'=>$weibo['uid']))->find();
				$content = I('post.data','','htmlspecialchars');
				//本身是否是转发微博
				if($weibo['isturn']){
					$id = $weibo['isturn'];
					$content = $content.'//@'.trim($username['username']).' :'.$weibo['content'];	
					//寻找最原始微博内容
					$weibo = M('weibo')->where(array('id'=>$id))->find();
				}
				$data = array(
					'uid'=>session('uid'),
					'content'=>$content,
					'isturn'=>$weibo['id'],
					'time'=>time()
				);
				//添加微博
				if(M('weibo')->add($data)){
				//给原微博转发+1
					M('weibo')->where(array('id'=>I('post.wid','','intval')))->setInc('turn');
				}

			}
			
			//给该微博评论+1
			M('weibo')->where(array('id'=>I('post.wid','','intval')))->setInc('comment');
			
			//查询用户信息
			$field = array('username','face50');
			$userinfo = M('userinfo')->where(array('uid'=>session('uid')))->field($field)->find();
			$face30 = empty($userinfo['face50'])?__ROOT__.'/Public/Images/noface.gif':__ROOT__.ltrim($userinfo['face50'],'\.');
			
			//组合评论字符串返回字符串
			$str = '';
			$str .=	'<dl class="comment_content">';
			$str .= '<dt>';
			$str .= '<a href="">';
			$str .= '<img src=" '.$face30.'" alt="'.$userinfo['username'].'" width="30" height="30"/>';
			$str .= '</a>';
			$str .= '</dt>';
			$str .= '<dd>';
			$str .= '<a href="'.u('User/'.session('uid')).'" class="comment_name">'.$userinfo['username'].'</a>:';
			$str .= replace_weibo(I('post.data','','htmlspecialchars'));
			$str .= '('.format_time($data['time']).')';
			$str .= '<div class="reply">';
			$str .= '<a href="">回复</a>';
			$str .= '</div>';
			$str .= '</dd>';
			$str .= '</dl>';
			
			echo $str;
		}
	}
	
	//获取评论
	public function getComment(){
		if(IS_AJAX != 1){
			E('访问的页面不存在');
		}
		$wid = I('post.wid','','intval');
		$str = '';
		
		//评论分页
		$count = M('comment')->where(array('wid'=>$wid))->count();//数据总条数
		$page = isset($_POST['page'])?$_POST['page']:1;//当前页
		$perpage =2;//每页有多少条
		$total = ceil($count/2);//总共有多少页
		$show = '';
		$show = '<dl class="comment-page" wid="'.I('post.wid','','intval').'">';//分页的样式
		switch($page){
			case $page >1 && $page <$total:
				$show .= '<dd page="'.($page-1).'">上一页</dd><dd page="'.($page+1).'">下一页</dd>';
				break;
			case $page < $total:
				$show .= '<dd page="'.($page+1).'">下一页</dd>';
				break;
			case $page == $total:
				$show .= '<dd page="'.($page-1).'">上一页</dd>';
				break;
		}
		$show .= '</dl>';
		$pg = ($page-1)*$perpage;
		//获取评论
		if($comment = M('comment')->where(array('wid'=>$wid))->order('time desc')->limit($pg.','.$perpage)->select()){
			foreach ($comment as $k=>$v){
				$userinfo = M('userinfo')->field('username,face50')->where(array('uid'=>$v['uid']))->find();
				$face30 = empty($userinfo['face50'])?__ROOT__.'/Public/Images/noface.gif':__ROOT__.ltrim($userinfo['face50'],'\.');
				$str .=	'<dl class="comment_content">';
				$str .= '<dt>';
				$str .= '<a href="">';
				$str .= '<img src=" '.$face30.'" alt="'.$userinfo['username'].'" width="30" height="30"/>';
				$str .= '</a>';
				$str .= '</dt>';
				$str .= '<dd>';
				$str .= '<a href="'.u('User/'.session('uid')).'" class="comment_name">'.$userinfo['username'].'</a>:';
				$str .= replace_weibo($v['content']);
				$str .= '('.format_time($v['time']).')';
				$str .= '<div class="reply">';
				$str .= '<a href="">回复</a>';
				$str .= '</div>';
				$str .= '</dd>';
				$str .= '</dl>';
			}

			
			echo $str."<br/>".$show;
		}else{
			echo false;
		}
	}
	
	
	//微博转发
	public function turn(){
		if(IS_POST !=1) {
			E('页面不存在');
		}
		//原微博id
		$tid = I('post.tid','','intval')?I('post.tid','','intval'):I('post.id','','intval');
		//转发的内容
		$content = I('post.content','','htmlspecialchars');
		
		//插入数据
		$data = array(
			'content' =>$content,
			'isturn' => $tid,
			'time' => time(),
			'uid'=>session('uid')
		);
		
		if($wid = M('weibo')->add($data)){
			//@用户处理
			$this->_handleAtme($data['content'],$wid);				
			//转发原微博+1
			M('weibo')->where(array('id'=>$tid))->setInc('turn');
			//如果不是第一次微博转发
			if($_POST['id'] !=$tid){
				//微博数也加1
				M('weibo')->where(array('id'=>I('post.id','','intval')))->setInc('turn');
			}
			//用户发布微博数+1
			M('userinfo')->where(array('uid'=>session('uid')))->setInc('weibo');
			//如果点击了同时给评论
			if(isset($_POST['becomment'])){
				$data = array(
					'content'=>$content,
					'time' =>time(),
					'uid'=>session('uid'),
					'wid'=>$tid
				);
				if(M('comment')->add($data)){
					//微博数+1
					M('weibo')->where(array('id'=>$tid))->setInc('comment');
				}
			}
			
			$this->success('转发成功');
		}else{
			$this->error('转发失败请重试......');
		}
	}  
	
	//查看fans和follow
	public function faf(){
		$id = I('get.id','','intval');//获取用户id
		if($_GET['type'] == 1){//查看本用户的关注者
			$where = array('fans'=>$id);
			$field = 'follow';
		}else{//用户fans
			$where = array('follow'=>$id);
			$field = 'fans';
		}
		//分页
		$count = M('follow')->where($where)->count();
		$page = new \Think\Page($count,2);
		$info = M('follow')->field($field)->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$show = $page->show();
		//获取相应用户的uid		
		foreach ($info as $k=>$v){
			$info[$k] = $v[$field];
		}
		$info = implode(',',$info);
		$field = array(
			'face50'=>'face','username','sex','location','follow','fans','weibo','uid'
		);//获取信息
		$faf = M('userinfo')->field($field)->where(array('uid'=>array('IN',$info)))->select();
		
		if($follow = M('follow')->where(array('fans'=>session('uid')))->select()){
			foreach($follow as $k=>$v){
				$follow[$k] = $v['follow'];
			}
		}
		if($fans = M('follow')->where(array('follow'=>session('uid')))->select()){
			foreach ($fans as $k=>$v){
				$fans[$k] = $v['fans'];       
			}
		}
		$this->assign('fans',$fans);//获取fans的id组
		$this->assign('show',$show);//展示分页
		$this->assign('follow',$follow);//获取follow的id组
		$this->assign('type',$_GET['type']);//区分fans还是
		$this->assign('count',$count);//一共关注或者fans有多少人
		$this->assign('faf',$faf);
		$this->display('followList');
	}
	
	
	//微博收藏
	public function keep(){
		if(IS_POST !=1){
			E('该页面不存在');
		}
		
		$wid = I('post.wid','','intval');
		$where = array('wid'=>$wid,'uid'=>session('uid'));
		$data = array('wid'=>$wid,
					  'uid'=>session('uid'),
					  'time'=>time()
				);
		if(M('keep')->where($where)->select()){
			echo json_encode('已收藏');
		}else if(M('keep')->where($where)->add($data)){
			M('weibo')->where(array('id'=>$wid))->setInc('keep');
			echo json_encode('收藏成功');
		}else{
			echo json_encode('收藏失败');
		}
	}
	
	
	//删除微博
	public function del(){
		if(IS_POST != 1) {
			E('页面不存在');
		}
		
		$wid = I('post.wid','','intval');
		$where = array('id'=>$wid);
		if(M('weibo')->where($where)->delete()){
			if($img = M('picture')->where(array('wid'=>$wid))->find()){//删除w微博对应的图片
				M('picture')->where(array('wid'=>$wid))->delete();//删除对应的数据
				//删除实际对应的本地图片
				@unlink($img['mini']);
				@unlink($img['max']);
				@unlink($img['medium']);
			}
			
			//对应用户的微博数目减一
			M('userinfo')->where(array('uid'=>session('uid')))->setDec('weibo');
			echo 1;
		}else{
			echo 0;
		}
	}
	
	
	
}
<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 用户个人信息控制器
 *
 */
class UserController extends Controller {
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
	
	
	public function index(){
		$id = I('get.id','','intval');
		$where = array(
			'uid'=>$id
		);
		$user = M('userinfo')->where($where)->find();
		if(!$user){
			header('Content-Type:text/html;Charset=UTF-8');
			redirect(__ROOT__,3,'用户不存在,正在为你跳转......');
			exit();
			
		}
		$Model = D('WeiboView');
			//分页
		$count = $Model->where($where)->count();
		$Page       = new \Think\Page($count,2);
		$limit =  $Page->firstRow.','.$Page->listRows;
		$show       = $Page->show();// 分页显示输出
		//读取用户发布的微博
		$weibo = $Model->getAll($where,$limit);
		
		//获取关注用户的信息
		
		if(!S('follow_'.$id)){
			$file = array('username','face50'=>'face','uid');
			$fwhere = array('fans'=>$id);
			$follow = M('follow')->where($fwhere)->select();
			if($follow) {
				foreach ($follow as $k=>$v){
					$follow[$k] = $v['follow'];
				}
				$follow = implode(',',$follow);
			}else{
				$follow = '';
			}
			if($follow){
				$follow = M('userinfo')->where(array('uid'=>array('in',$follow)))->field($file)->limit(8)->select();
				S('follow_'.$id,$follow,array('type'=>'file','expire'=>10));//进行缓存
			}
		}
		
		//获取fans用户的信息
		
		if(!S('fans_'.$id)){
			$file = array('username','face50'=>'face','uid');
			$fwhere = array('follow'=>$id);
			$fans = M('follow')->where($fwhere)->select();
			if($fans) {
				foreach ($fans as $k=>$v){
					$fans[$k] = $v['fans'];
				}
				$fans = implode(',',$fans);
			}else{
				$fans = '';
			}
			if($fans){
				$fans = M('userinfo')->where(array('uid'=>array('in',$fans)))->field($file)->limit(8)->select();
				S('fans_'.$id,$fans,array('type'=>'file','expire'=>10));//进行缓存
			}
		}
		
		
		$this->assign('follow',S('follow_'.$id));//关注的人
		$this->assign('fans',S('fans_'.$id));//fans
		$this->assign('show',$show);//分页显示
		$this->assign('indexInfo',$weibo);
		$this->assign('user',$user);
		$this->display();
	}
	
	//对URL进行优化，使用空操作:对@用户进行 个人信息查看
	public function _empty($name){
		//生成相应的URL
		$this->_getUrl($name);
	}
	
	private  function _getUrl($name){
		$name = htmlspecialchars($name);//过滤用户传递过来的字符串
		$uid = M('userinfo')->where(array('username'=>$name))->getField('uid');
		if($uid){
			$url = 'User/'.$uid;
			redirect($url);
		}else{
			$this->error('该用户不存在...');
		}
	}
	
	
	
	//用户收藏
	public function keep(){
		$db = D('KeepView');
		$where = array('keep.uid'=>session('uid'));
		//分页
		$count = $db->where($where)->count();
		$Page       = new \Think\Page($count,2);
		$limit =  $Page->firstRow.','.$Page->listRows;
		$show       = $Page->show();// 分页显示输出
		$indexInfo = $db->getAll($where,$limit);
		$this->assign('show',$show);
		$this->assign('indexInfo',$indexInfo);
		$this->display();
	}
	
	//异步取消收藏
	public function delCancel(){
		if(IS_POST !=1){
			E('该页面不存在');
		}
		$kid = I('post.kid','','intval');
		$wid = I('post.wid','','intval');
		
		if(M('keep')->where('id='.$kid)->delete()){
			M('weibo')->where('id='.$wid)->setDec('keep');//该微博收藏减1
			echo 1;
		}else{
			echo 0;
		}
	}
	
	//我的私信
	public function letter(){
		set_msg(2,session('uid'),1);//私信我的推送置为0
		$where = array('uid'=>session('uid'));
		//分页
		$count = M('letter')->where($where)->count();//私信的总数
		$Page       = new \Think\Page($count,2);
		$limit =  $Page->firstRow.','.$Page->listRows;
		$show       = $Page->show();// 分页显示输出	
		$where = array('letter.uid'=>session('uid'));
		$db = D('LetterView');
		$letter = $db->getAll($where,$limit);
		$this->assign('count',$count);//总数
		$this->assign('letter',$letter);//信件查询
		$this->assign('show',$show);
		$this->display();
	}
	
	
	//删除私信
	public function delLetter(){
		if(IS_POST !=1){
			E('页面不存在');
		}	
		$lid = I('post.lid','','intval');
		if(M('letter')->where('id='.$lid)->delete()){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	//发送私信
	public function sendLetter(){
		if(IS_POST !=1){
			E('页面不存在');
		}
		$name = I('post.name','','htmlspecialchars');
		$content = I('post.content','','htmlspecialchars');
		$uid = M('userinfo')->where(array('username'=>$name))->getField('uid');//接受者用户id
		if( !$uid){
			$this->error('用户不存在');
		}
		$from = session('uid');
		$data = array(
			'from'=>$from,
			'content'=>$content,
			'time'=>time(),
			'uid'=>$uid
		);
		if(M('letter')->add($data)){
			set_msg(2,$data['uid']);//私信推送
			$this->success('发送私信成功',U('letter'));
		}else{
			$this->error('发送私信失败');
		}
	}
	
	//我的评论
	public function comment(){
		$where = array('uid'=>session('uid'));
		//分页
		$count = M('comment')->where($where)->count();//私信的总数
		$Page       = new \Think\Page($count,2);
		$limit =  $Page->firstRow.','.$Page->listRows;
		$show       = $Page->show();// 分页显示输出	
		$where = array('comment.uid'=>session('uid'));
		$db = D('CommentView');
		$comment = $db->getAll($where,$limit);
		$this->assign('count',$count);//总数
		$this->assign('comment',$comment);//信件查询
		$this->assign('show',$show);
		$this->display();
	}
	
	//回复评论
	public function reply(){
		if(IS_POST !=1){
			E('页面不存在');
		}
		
		$content = I('post.content','','htmlspecialchars');
		$wid = I('post.wid','','intval');
		$data = array(
			'content'=>$content,
			'time'=>time(),
			'uid'=>session('uid'),
			'wid'=>$wid
		);
		if(M('comment')->add($data)){
			M('weibo')->where(array('id'=>$wid))->setInc('comment');
			echo 1;
		}else{
			echo 0;
		}
	}
	
	//删除评论
	public function delComment(){
		if(IS_POST !=1){
			E('页面不存在');
		}	
		
		$cid = I('post.cid','','intval');
		$wid = I('post.wid','','intval');
		
		if(M('comment')->where('id='.$cid)->delete()){
			M('weibo')->where('id='.$wid)->setDec('comment');
			echo 1;
		}else{
			echo 0;
		}
	}
	
	
	
	//@我
	public function atme(){
		$where = array('uid'=>session('uid'));
		$wid = M('atme')->where($where)->field('wid')->select();
		set_msg(3,session('uid'),1);//at我推送设置为0
		foreach($wid as $k=>$v){
			$wid[$k] = $v['wid'];
		}
		if(!empty($wid)){
			$count = count($wid);
			$wid = implode(',',$wid);	
			$db = D('WeiboView');
			$where2['id'] = array('in',$wid);
			$Page       = new \Think\Page($count,2);
			$limit =  $Page->firstRow.','.$Page->listRows;
			$show       = $Page->show();// 分页显示输出				
			$atme = $db->getAll($where2,$limit);
		}
		$this->assign('show',$show);
		$this->assign('atme',$atme);
		$this->display();
	}
	
	
	//移除fans或者关注者
	public function delFaf(){
		if(IS_POST != 1){
			E('页面不存在');
		}
		$faf = I('post.faf','','intval');
		if($_POST['type'] == 1){//删除跟随者
			if(M('follow')->where('follow='.$faf.' and fans='.session('uid'))->delete()){
				M('userinfo')->where('uid='.session('uid'))->setDec('follow');//用户关注数减一
				M('userinfo')->where('uid='.$faf)->setDec('fans');//原关注者fans数减一
				echo json_encode(array('status'=>1,'msg'=>'移除关注者成功'));
			}else{
				json_encode(array('status'=>0,'msg'=>'移除关注者失败'));
			}
		}else{//移除fans
			if(M('follow')->where('fans='.$faf.' and follow='.session('uid'))->delete()){
				M('userinfo')->where('uid='.session('uid'))->setDec('fans');
				M('userinfo')->where('uid='.$faf)->setDec('follow');
				echo json_encode(array('status'=>1,'msg'=>'移除fans成功'));
			}else{
				json_encode(array('status'=>0,'msg'=>'移除fans失败'));
			}			
		}
	}
	
	
	//保存个人模板风格
	public function editStyle(){
		if(IS_POST !=1){
			E('页面不存在');
		}
		$style = I('post.style','','htmlspecialchars');
		if(M('userinfo')->where('uid='.session('uid'))->save(array('style'=>$style))){
			echo 1;
		}else{
			echo 0;
		}
	}
	
	
	
	//轮询是否有私信，评论，@我
	public function getMsg(){
		if(IS_AJAX !=1){
			E('页面不存在');
		}
		
		$data = S('msg'.session('uid'));
		if($data){//如果存在分批处理
			if($data['comment']['status']){			
				echo json_encode(array(
					'status'=>1,
					'total'=>$data['comment']['total'],
					'type'=>1
				));
				exit();
			}
			
			if($data['letter']['status']){			
				echo json_encode(array(
					'status'=>1,
					'total'=>$data['letter']['total'],
					'type'=>2
				));
				exit();
			}	
			
			if($data['atme']['status']){
				echo json_encode(array(
					'status'=>1,
					'total'=>$data['atme']['total'],
					'type'=>3
				));
				exit();
			}			
		}
		echo 0;//没有值返回个0
	}
}
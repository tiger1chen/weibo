<?php
namespace Home\Controller;
use  Home\Controller;
/**
 * 微博管理控制器
 *
 */
class WeiboController extends CommonController {
	//原创微博
	public function index(){
		$where = array('isturn'=>0);//原创的微博
		
		//分页
		$count = D('WeiboView')->where($where)->count();
		$Page       = new \Think\Page($count,2);
		$limit =  $Page->firstRow.','.$Page->listRows;
		$this->page = $Page->show();// 分页显示输出
		$this->weibo = D('WeiboView')->where($where)->limit($limit)->order('time DESC')->select();
		$this->display();
	}
	
	//转发微博列表
	public function turn(){
		$where = array('isturn'=>array('gt',0));//原创的微博
		$db = D('WeiboView');
		//分页
		$count = $db->where($where)->count();
		$Page       = new \Think\Page($count,2);
		$limit =  $Page->firstRow.','.$Page->listRows;
		$this->page = $Page->show();// 分页显示输出
		unset($db->viewFields['picture']);//去除不需要展示的图片表
		$this->turn = $db->where($where)->limit($limit)->order('time DESC')->select();		
		$this->display();
	}
	
	//微博检索
	public function sechWeibo(){
		if(isset($_GET['sech'])){//搜索提交过来的处理
			$where =array('content'=>array('like','%'.I('get.sech','','htmlspecialchars').'%'));
			$weibo = D('WeiboView')->where($where)->select();
			$this->weibo = $weibo?$weibo:false;
		}		
		$this->display();
	}
	
	//删除微博
	public function delWeibo(){
		$id= I('get.id','','intval');//微博的ID
		$uid = I('get.uid',''.'intval');//发微博的用户id
		
		if(D('WeiboRelation')->relation(true)->delete($id)){
			//用户发布的微博数减1
			M('userinfo')->where(array('uid'=>$uid))->setDec('weibo');
			$this->success('删除成功',$_SERVER['HTTP_REFERER']);
		}else{
			D('WeiboRelation')->getLastSql();
			$this->error('删除失败，请重试.....');
		}
	}
	
	
	/**
	 * 评论列表
	 */
	public function comment(){
		
		$count = M('comment')->count();
		$Page       = new \Think\Page($count,2);
		$limit =  $Page->firstRow.','.$Page->listRows;
		$this->page = $Page->show();// 分页显示输出

			
		$comment = D('CommentView')->limit($limit)->order('time DESC')->select();
		$this->comment = $comment;
		$this->display();
	}
	
	
	/**
	 * 删除评论
	 */
	public function delComment(){
		$id = I('get.id','','intval');
		$wid = I('get.wid','','intval');
		
		if(M('comment')->delete($id)){
			
			M('weibo')->where(array('id'=>$wid))->setDec('comment');//微博的评论数减少1
			$this->success('删除评论成功',$_SERVER['HTTP_REFERER']);
		}else{
			$this->error('删除评论失败');
		}
	}	
	
	/**
	 * 评论检索
	 */
	public function sechComment(){
		if(isset($_GET['sech'])){//搜索提交过来的处理
			$where =array('content'=>array('like','%'.I('get.sech','','htmlspecialchars').'%'));
			$comment = D('CommentView')->where($where)->select();
			$this->comment = $comment?$comment:false;
		}				
		$this->display();
	}
}
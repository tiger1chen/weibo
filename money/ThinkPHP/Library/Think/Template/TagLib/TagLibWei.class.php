<?php
namespace Think\Template\Taglib;
use Think\Template\TagLib;
Class TagLibWei extends TagLib{
	protected $tags = array(
		'test' => array('attr'=>'name,id,value','close'=>0),
		'userinfo' => array('attr'=>'id'),
		'maybeLike' => array('attr'=>'id')
		
	);
	
	public function _test($tag,$content){
		$name = $tag['name'];
		$id = $tag['id'];
		$type = $tag['type'];
		$value = $tag['value'];
		$str = "<input type='".$type."' name='".$name."' id='".$id."' value='".$value."' />";
		return $str;
	}
	
	/**
	 * 读取用户信息标签
	 *
	 * @param unknown_type $tag
	 * @param unknown_type $content
	 * @return unknown
	 */
	
	public function _userinfo($tag,$content) {
		$id = $tag['id'];
		$str = '<?php ';
		$str .= '$userinfo = M("userinfo")->where(array("uid"=>'.$id.'))->find();';
		$str .= 'extract($userinfo);';
		$str .= ' ?>';
		$str .=$content;
		return $str;
	}

	/**
	 * 可能感兴趣的人的标签
	 */
	public function _maybeLike($tag,$content){
		$id = $tag['id'];
		$str = '<?php ';
		$str .= '$follow = M("follow")->where(array("fans"=>'.$id.'))->field("follow")->select();';
		$str .= 'foreach($follow as $k=>$v):';
		$str .= '$follow[$k] = $v["follow"];';
		$str .= 'endforeach;';
		$str .= 'if(!empty($follow)):';
		$str .= '$arr = implode(",",$follow);';
		$str .= '					$sql = "select b.face50,b.username,b.uid,count(a.follow) f from wei_follow as a left join  wei_userinfo as b  on a.follow=b.uid where a.fans in (".$arr.") and a.follow not in (".$arr.") and a.follow <> ".session("uid")." group by a.follow order by f desc  limit 3";';
		$str .='$row = M("follow")->query($sql);';
		$str .='endif;';
		$str .= 'foreach($row as $r):';
		$str .= 'extract($r);';
		$str .= ' ?>';
		$str .= $content;
		$str .= '<?php endforeach; ?>';
		return $str;
	}
	
	
/*	            <?php
            	$follow = M('follow')->where(array('fans'=>session('uid')))->field('follow')->select();
            	foreach($follow as $k=>$v){
            		$follow[$k] = $v['follow'];
            	}
            	if(!empty($follow))<literal>{//如果存在关注的人
	            	$arr = implode(',',$follow);
					$sql = 'select b.face50,b.username,b.uid,count(a.follow) f from wei_follow as a left join  wei_userinfo as b  on a.follow=b.uid where a.fans in ('.$arr.') and a.follow not in ('.$arr.') and a.follow <> '.session('uid').' group by a.follow order by f desc  limit 3';
				 	$row = M('follow')->query($sql);
				 
            	}</literal>
            ?>*/

}
?>
<?php

//简化打印
 function p($arr){
 	echo "<pre>";
 	print_r($arr);
 	echo "</pre>";
 }
 /**
  * 异位或加密字符串
  * 0:加密。1：解密。
  * 返回 加密或解密字符串
  */
 function encryption($value,$type=0){
 	$key = md5(C('ENCTYPTION_KEY'));
 	//加密
 	if(!$type){
 		$value = $key ^ $value;
 		return str_replace('=','',base64_encode($value));
 	}
 	$value = base64_decode($value);
 	return $value ^ $key;
 }
 
 /**
  * 格式化时间：前台有用到
  */
 function format_time($time){
 	$now = time();//当前时间
 	$diff = $now-$time;//时间差
 	$today = strtotime(date('Y-m-d',$now));//今天零时零分的时候
 	$str = '';
 	switch($time) {
 		case $diff <60:
 			$str = $diff.'秒前';
 			break;
 		case $diff <(60*60):
 			$str = floor($diff/60).'分钟前';
 			break;
 		case $diff <60*60*8:
 			$str = floor($diff/3600).'小时前';
 			break;
 		case $time > $today:
 			$str = '今天'.date('H:i'.$time);
 			break;
 		default:
 			$str = date('Y-m-d H:i:s',$time);
 	}
 	
 	return $str;
 }
 
 /**
  * 微博内容替换URL地址、@用户与表情
  */
 function replace_weibo($content){

 	//URL地址:htttp://www.baidu.com.cn  www.baidu.com/Index/index.php  www.baidu.com?a=m&n=index
 	$pattern='/(?:http:\/\/)?(www\.[\w]+\.[a-zA-Z]+[\.a-zA-Z\/]*[\w]*\??[\w=\&\+\%]*)/is';
 	$content = preg_replace($pattern,'<a href="http://\\1" target="_blank">\\1</a>',$content);

 	//@用户的替换
 	$pattern = '/@(\S+)\s/is';
 	$content= preg_replace($pattern,'<a href="'.__APP__.'/Home/User/\\1">@\\1</a>',$content);
 	
 	//表情的替换
 	$phiz = include'./Public/Data/phiz.php';
 	$pattern = '/\[(\S*?)\]/';
 	preg_match_all($pattern,$content,$arr);
 	if(!empty($arr[1])){
		foreach ($arr[1] as $k=>$v) {
			$name = array_search($v,$phiz);
			if(!empty($name)){
				$content = str_replace($arr[0][$k],'<img  title="'.$arr[0][$k].'" alt="'.$arr[0][$k].'" src="'.__ROOT__.'/Public/Images/phiz/'.$name.'.gif"/>',$content);
			}
		}
 	}
 	return str_replace(C('filter'),'**',$content);
 }
 
 
 /**
  * 消息推送之缓存
  * type:（1：评论，2：私信，3：@我）   //此程序中没有commnet推送功能
  * flag是否清楚缓存，1：清除，0：不清除
  */
 function set_msg($type,$uid,$flag =0){
 	switch ($type){
 		case 1:
 			$type = 'comment';
 			break;
 		case 2:
 			$type = 'letter';
 			break;
 		case 3:
 			$type = 'atme';
 			break;
 	}
 	
 	if($flag){//如果查看了，相应缓存设置为0
 		if($data = S('msg'.$uid)){//存在才置0否则就直接退出
	 		$data[$type]['total'] = 0;
	 		$data[$type]['status'] = 0;
	 		S('msg'.$uid,$data);
 		}
 		return;
 	}
 	if($data = S('msg'.$uid)){
		$data[$type]['total'] ++;
 		$data[$type]['status'] = 1;
 		S('msg'.$uid,$data);	
 	}else{
 	 	$data = array(
	 		'comment'=>array('total'=>0,'status'=>0,'type'=>1),
	 		'letter'=>array('total'=>0,'status'=>0,'type'=>2),
	 		'atme'=>array('total'=>0,'status'=>0,'type'=>3),
	 	);
 		$data[$type]['total'] ++;
 		$data[$type]['status'] = 1;
 		S('msg'.$uid,$data);		
 	}
 	
 }
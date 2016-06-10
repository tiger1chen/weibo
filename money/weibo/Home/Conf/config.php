<?php
return array(
	//'配置项'=>'配置值'
	//用于异位或加密的key
	'ENCTYPTION_KEY' => 'chenriwei',
	//自动登录保持时间
	'AUTO_LOGIN_TIME'=> time()+3600*24*7,//一个星期
	//文件上传路径
	'UPLOAD_PIC_PATH'=>'./Uploads',
	//文件上传大小
	'UPLOAD_PIC_SIZE'=>100000,//大约10兆
	//文件上传类型
	'UPLOAD_PIC_EXTS'=> array('jpg', 'gif', 'png', 'jpeg'),
	//开启路由模式
	'URL_ROUTER_ON'   => true, 
	//路由规则定义
	'URL_ROUTE_RULES'=>array(
		'/User\/([0-9]+)/'=>'User/index?id=:1',
		'/Follow\/([0-9]+)/'=>'Index/faf?id=:1&type=1',
		'/Fans\/([0-9]+)/'=>'Index/faf?id=:1&type=2',
	),
);
<?php
return array(
	//'配置项'=>'配置值'
	
	//创建数据库
	'DB_TYPE'               =>  'mysql', 
	'DB_HOST'               =>  SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT, // 服务器地址
    'DB_NAME'               =>  SAE_MYSQL_DB,          // 数据库名
    'DB_PORT'               =>  SAE_MYSQL_PORT,        // 端口
    'DB_USER'               =>  SAE_MYSQL_USER,      // 用户名
    'DB_PWD'                =>  SAE_MYSQL_PASS,          // 密码
    'DB_PREFIX'             =>  'wei_',    // 数据库表前缀
    'DEFAULT_THEME'         =>  'default',	// 默认模板主题名称
  //  'SHOW_PAGE_TRACE' 		=>	true, //页面trace显示
    'TAGLIB_BUILD_IN'    =>    'cx,TagLibWei',//模板标签设置
    //缓存设置
    'DATA_CACHE_SUBDIR'     =>  true,    // 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
    'DATA_PATH_LEVEL'       =>  2,        // 子目录缓存级别
    'DATA_CACHE_PREFIX'     =>  'wei_',     // 缓存前缀
/*    'DATA_CACHE_TYPE'       =>  'Memcache', */
    'LOAD_EXT_CONFIG' => 'system', //加载扩展配置文件
    
   
);
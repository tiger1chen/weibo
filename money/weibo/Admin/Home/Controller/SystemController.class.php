<?php
namespace Home\Controller;
use  Home\Controller;
/**
 * 系统设置控制器
 *
 */
class SystemController extends CommonController {
	
	//网站设置展示
	public function index(){
		$config = include '../Home/Conf/system.php';
		$this->assign('config',$config);//分配
		$this->display();
	}
	
	//网站设置
	public function runEdit(){
		$url = '../Home/Conf/system.php';
		$config = include $url;
		$config['WEBNAME'] = I('post.webname','','htmlspecialchars');
		$config['COPY'] = I('post.copy','','htmlspecialchars');
		$config['REGIS_ON'] = I('post.regis_on','','intval');
		$filter  ='<?php '.PHP_EOL.' return '.PHP_EOL.' ';
		$filter .= var_export($config,true);
		$filter .= ';'.PHP_EOL.'?>';
		if(file_put_contents($url,$filter)){
			$this->success('网站设置成功',U('index'));
		}else{
			$this->error('网站设置失败,请修改'.$url.'的权限');
		}		
		
	}
	
	//关键字过滤视图
	public function filter(){
		$config = include '../Home/Conf/system.php';
		$this->filter = implode('|',$config['FILTER']);
		$this->display();
	}
	
	//非法关键字设置保存
	public function runEditFilter(){
		$url = '../Home/Conf/system.php';//配置文件地址
		$config = include $url;
		$filter = I('post.filter','','htmlspecialchars');
		$config['FILTER'] = explode('|',$filter);
		$filter  ='<?php '.PHP_EOL.' return '.PHP_EOL.' ';
		$filter .= var_export($config,true);
		$filter .= ';'.PHP_EOL.'?>';
		if(file_put_contents($url,$filter)){
			$this->success('关键字修改成功',U('filter'));
		}else{
			$this->error('关键字修改失败,请修改'.$url.'的权限');
		}
	}
}
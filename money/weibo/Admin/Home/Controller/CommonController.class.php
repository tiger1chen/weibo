<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
	public function __construct(){
		//父类的方法不能丢
		parent::__construct();
		if(!isset($_SESSION['uid']) && !isset($_SESSION['username'])){
			redirect(U('Login/index'));
		}
	}
}
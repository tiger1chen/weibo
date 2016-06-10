<?php
/**
 * 用户与用户信息处表关联
 */
namespace Home\Model;
use Think\Model\RelationModel;
class UserModel extends RelationModel {
	//定义主表名称
	protected $tableName='user';
	
	//定义用户与用户信息处的关联属性
	protected $_link = array(
		"userinfo" =>array(
			'mapping_type' => self::HAS_ONE,
			'foreign_key' => 'uid'
		)
	);
	
	//自动方法插入
	public function insert($data=NULL) {
		$data = is_null($data)?$_POST:$data;
		return $this->relation(true)->data($data)->add();
	}
}
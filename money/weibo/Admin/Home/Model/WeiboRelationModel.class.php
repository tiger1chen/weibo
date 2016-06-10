<?php
/**
 * 用户与用户信息处表关联
 */
namespace Home\Model;
use Think\Model\RelationModel;
class WeiboRelationModel extends RelationModel {
	protected $tableName = 'weibo';//定义主表
	//定义与微博相关的关联表
	protected $_link = array(
		'picture'=>array(
			'mapping_type' => self::HAS_ONE,
			'foreign_key' => 'wid'
		),
		'comment'=>array(
			'mapping_type'=> self::HAS_MANY ,
			'foreign_key'=>'wid'
		),
		'keep'=>array(
			'mapping_type'=>self::HAS_MANY ,
			'foreign_key'=>'wid'
		),
		'atme'=>array(
			'mapping_type'=>self::HAS_MANY ,
			'foreign_key'=>'wid'
		)
	);
}
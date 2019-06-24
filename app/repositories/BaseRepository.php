<?php 

/**
 * Repository Pattern —— 资源库模式
 * Repository 是一个独立的层，介于领域层与数据映射层（数据访问层）之间。它的存在让领域层感觉不到数据访问层的存在，它提供一个类似集合的接口提供给领域层进行领域对象的访问。
 */
class BaseReposotiry
{
	
	function __construct()
	{
		# code...
	}

	// TODO:
	public function all($columns = array('*'));
	public function paginate($perPage = 15, $columns = array('*'));
	public function create(array $data);
	public function update(array $data, $id);
	public function delete($id);
	public function find($id, $columns = array('*'));
	public function findBy($field, $value, $columns = array('*'));
}
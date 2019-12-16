<?php


namespace link\hefang\cms\content\controllers;


use link\hefang\cms\content\models\TagModel;
use link\hefang\cms\HeFangCMS;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\views\BaseView;

class TagController extends BaseController
{

	/**
	 * 删除数据
	 * @return BaseView
	 */
	public function delete(): BaseView
	{
		// TODO: Implement delete() method.
	}

	/**
	 * 更新数据
	 * @return BaseView
	 */
	public function update(): BaseView
	{
		// TODO: Implement update() method.
	}

	/**
	 * 查询数据列表
	 * @return BaseView
	 */
	public function list(): BaseView
	{
		$search = $this->_request(HeFangCMS::searchKey());
		$type = $this->_request("type");
		$where = null;
		if (!StringHelper::isNullOrBlank($type)) {
			$where = "`type` = '{$type}'";
		}
		try {
			return $this->_restApiOk(TagModel::pager(
				$this->_pageIndex(),
				$this->_pageSize(100),
				$search,
				$where
			));
		} catch (SqlException $e) {
			return $this->_restApiServerError($e);
		}
	}

	/**
	 * 获取一条数据详情
	 * @param string|null $id 要获取详情的数据的主键
	 * @return BaseView
	 */
	public function get(string $id = null): BaseView
	{
		// TODO: Implement get() method.
	}
}

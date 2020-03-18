<?php


namespace link\hefang\cms\content\controllers;


use link\hefang\cms\common\controllers\BaseCmsController;
use link\hefang\cms\content\models\ContentTagModel;
use link\hefang\cms\HeFangCMS;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\views\BaseView;

class TagController extends BaseCmsController
{

	/**
	 * 查询数据列表
	 * @param string|null $cmd
	 * @return BaseView
	 */
	public function list(string $cmd = null): BaseView
	{
		$query = $this->_request(HeFangCMS::queryKey());
		$type = $this->_request("type", $cmd);
		$where = [];
		$querySql = ContentTagModel::query2sql($query);

		if (!StringHelper::isNullOrBlank($type)) {
			$where[] = "`type` = '{$type}'";
		}

		if ($querySql) {
			$where[] = $querySql;
		}

		$where = empty($where) ? null : join(" AND ", $where);

		try {
			return $this->_restApiOk(ContentTagModel::pager(
				$this->_pageIndex(),
				$this->_pageSize(),
				$where
			));
		} catch (SqlException $e) {
			return $this->_restApiServerError($e);
		}
	}
}

<?php


namespace link\hefang\cms\content\controllers;


use link\hefang\cms\content\models\CategoryModel;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\views\BaseView;

class CategoryController extends BaseController
{
	public function list(): BaseView
	{
		try {
			return $this->_restApiOk(CategoryModel::pager(
				$this->_pageIndex(),
				$this->_pageSize(),
				null,
				"enable = TRUE"
			));
		} catch (SqlException $e) {
			return $this->_restApiServerError($e);
		}
	}
}

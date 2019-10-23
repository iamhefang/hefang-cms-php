<?php


namespace link\hefang\cms\common\controllers;


use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\views\BaseView;

abstract class GrudController extends BaseController
{
	protected function _list(string $class): BaseView
	{
		try {
			$pager = $class::pager(
				$this->_pageIndex(),
				$this->_pageSize(),
				$this->_request("search"),
				$this->_sort()
			);
			return $this->_apiSuccess($pager);
		} catch (\Exception $e) {
			return $this->_apiCodeServerError($e, "服务器端出错");
		}
	}
}

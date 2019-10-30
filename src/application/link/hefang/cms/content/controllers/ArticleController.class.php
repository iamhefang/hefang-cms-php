<?php


namespace link\hefang\cms\content\controllers;

use link\hefang\cms\content\models\ArticleModel;
use link\hefang\helpers\ParseHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;

class ArticleController extends BaseController
{
	public function list(): BaseView
	{
		$login = $this->_getLogin();
		$search = $this->_request(Mvc::getProperty("project.search.field.name", "search"));
		$category = $this->_request("category");

		$tag = $this->_request("tag");

		if ($login->isAdmin()) {
			$enable = ParseHelper::parseBoolean($this->_request("showDisabled", "false"));
			$author = $this->_request("author");
			$where = "enable = " . ($enable ? "TRUE" : "FALSE");
			if (!StringHelper::isNullOrBlank($author) && strlen($author) === 40) {
				$where .= " AND `author` = '{$author}'";
			}
		} else {
			$where = "enable = TRUE AND `author`='{$login->getId()}'";
		}

		if (!StringHelper::isNullOrBlank($category)) {
			$where .= " AND `category` = '{$category}'";
		}

		if (!StringHelper::isNullOrBlank($tag)) {
			$where .= " AND `id` IN (SELECT content_id FROM `tags` WHERE `tag` = '{$tag}')";
		}


		try {
			$data = ArticleModel::pager(
				$this->_pageIndex(),
				$this->_pageSize(),
				$search,
				$where
			);
			return $this->_restApiOk($data);
		} catch (\Exception $exception) {
			return $this->_restApiServerError($exception);
		}
	}
}

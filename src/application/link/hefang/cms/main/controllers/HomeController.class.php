<?php

namespace link\hefang\cms\main\controllers;

use Exception;
use link\hefang\cms\content\models\ArticleModel;
use link\hefang\helpers\CollectionHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;

class HomeController extends BaseController
{
	/**
	 * 首页
	 * @return BaseView
	 */
	public function index(): BaseView
	{
		return $this->_template($this->makeTplData([
			"title" => "首页"
		]), "index");
	}

	/**
	 * 文章详情页
	 * @param string|null $idOrPath 文章的id或者路径
	 * @return BaseView
	 */
	public function article(string $idOrPath = null): BaseView
	{
		try {
			$article = ArticleModel::find("id = '{$idOrPath}' OR path = '{$idOrPath}'");
			if (!($article instanceof ArticleModel) || !$article->isExist() || !$article->isEnable()) {
				return $this->_errorView("访问的文章不存在");
			}

			if ($article->isDraft() || !$article->isPrivate()) {
				return $this->_errorView("访问的文章还未发表");
			}

			return $this->_template($this->makeTplData([
				"article" => $article->toMap(),
				"title" => $article->getTitle(),
				"keywords" => $article->getKeywords(),
				"description" => $article->getDescription(),
				"author" => $article->getAuthorName()
			]), "article");
		} catch (Exception $e) {
			return $this->_errorView("出现错误");
		}
	}

	private function _errorView(string $msg): BaseView
	{
		return $this->_template($this->makeTplData([
			"title" => "出现错误",
			"message" => $msg
		]), "error");
	}

	private function makeTplData(array $data): array
	{
		$name = Mvc::getConfig("site|name");
		$title = CollectionHelper::getOrDefault($data, "title", "无标题");
		return array_merge([
			"keywords" => Mvc::getConfig("site|keywords"),
			"description" => Mvc::getConfig("site|description"),
			"author" => "",
		], $data, [
			"title" => "{$title} - {$name}",
		]);
	}
}

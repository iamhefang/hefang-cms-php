<?php

namespace link\hefang\cms\application\main\controllers;

use Exception;
use link\hefang\cms\application\content\models\ArticleModel;
use link\hefang\cms\core\controllers\BaseCmsController;
use link\hefang\cms\core\helpers\CacheHelper;
use link\hefang\helpers\CollectionHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\helpers\TimeHelper;
use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\databases\SqlSort;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;
use Throwable;

class HomeController extends BaseCmsController
{
	private $templateFunc;

	public function __construct()
	{
		$this->templateFunc = join(DS, [HEFANG_CMS_ROOT, "link", "hefang", "cms", "core", "functions", "template.func.php"]);
		include $this->templateFunc;
	}

	/**
	 * 首页
	 * @return BaseView
	 */
	public function index(): BaseView
	{
		return $this->_template($this->makeTplData([
			"title" => "首页",
			"pageIndex" => $this->_pageIndex(),
			"pageSize" => $this->_pageSize()
		]), "index");
	}

	/**
	 * 渲染器
	 * @param string $template 要渲染的模板名
	 * @return BaseView
	 */
	public function render(string $template): BaseView
	{
		return $this->_template($this->makeTplData([
			"title" => "首页",
			"pageIndex" => $this->_pageIndex(),
			"pageSize" => $this->_pageSize()
		]), $template);
	}

	private function makeTplData(array $data): array
	{
		$name = Mvc::getConfig("site|name");
		$title = CollectionHelper::getOrDefault($data, "title", "无标题");
		return array_merge([
			"keywords" => Mvc::getConfig("site|keywords"),
			"description" => Mvc::getConfig("site|description"),
			"author" => "",
			"icp" => Mvc::getConfig("site|icp")
		], $data, [
			"title" => "{$title} - {$name}",
		]);
	}

	public function tools(): BaseView
	{
		return $this->_template($this->makeTplData([]));
	}

	/**
	 * 文章详情页
	 * @param string|null $id 文章的id或者路径
	 * @return BaseView
	 */
	public function article(string $id = null): BaseView
	{
		try {
			$article = CacheHelper::cacheOrFetch($id, function () use ($id) {
				return ArticleModel::find(new Sql("id = :id", ['id' => $id]));
			});
			if (!($article instanceof ArticleModel) || !$article->isExist() || !$article->isEnable()) {
				return $this->_errorView("访问的文章不存在");
			}

			if ($article->isDraft() || $article->isPrivate()) {
				return $this->_errorView("访问的文章还未发表");
			}

			$article->readCountPlus();

			return $this->_template($this->makeTplData([
				"article" => $article->toMap(),
				"title" => $article->getTitle(),
				"keywords" => $article->getKeywords(),
				"description" => $article->getDescription(),
				"author" => $article->getAuthorName()
			]), "article");
		} catch (Exception $e) {
			Mvc::getLogger()->error($e->getMessage(), null, $e);
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

	public function search(): BaseView
	{
		$keywords = $this->_request("search");
		if (StringHelper::isNullOrBlank($keywords)) {
			return $this->_errorView("请输入搜索关键字");
		}
		try {
			$pager = ArticleModel::pager(
				$this->_pageIndex(),
				$this->_pageSize(),
				new Sql(
					"(is_draft = FALSE AND enable = TRUE AND `type`='article') AND (`title` LIKE :search1 OR `keywords` LIKE :search2 OR `description` LIKE :search3 OR `content` LIKE :search4)",
					[
						"search1" => "$keywords",
						"search2" => "$keywords%",
						"search3" => "%$keywords",
						"search4" => "%$keywords%"
					]
				), [new SqlSort("readCount")]
			);
			return $this->_template($this->makeTplData([
				"pager" => $pager,
				"message" => "搜索：{$keywords}",
				"search" => $keywords,
				"pageIndex" => $this->_pageIndex(),
				"pageSize" => $this->_pageSize()
			]), "list");
		} catch (Throwable $e) {
			return $this->_errorView($e->getMessage());
		}
	}

	public function tags(string $tag): BaseView
	{
		try {

			$pageIndex = $this->_pageIndex();
			$pageSize = $this->_pageSize();
			$pager = CacheHelper::cacheOrFetch(
				"tag-articles-$tag-$pageIndex-$pageSize",
				function () use ($tag, $pageIndex, $pageSize) {
					$tablePrefix = Mvc::getTablePrefix();
					return ArticleModel::pager(
						$pageIndex, $pageSize,
						new Sql(
							"(is_draft = FALSE AND enable = TRUE AND `type`='article') AND (`id` IN (SELECT `content_id` FROM `{$tablePrefix}content_tag` WHERE tag=:tag))",
							["tag" => $tag]
						), [new SqlSort("readCount")]
					);
				}, TimeHelper::millisOfDays(7));
			return $this->_template($this->makeTplData([
				"pager" => $pager,
				"message" => "标签：{$tag}",
				"pageIndex" => $this->_pageIndex(),
				"pageSize" => $this->_pageSize()
			]), "list");
		} catch (Throwable $e) {
			return $this->_errorView($e->getMessage());
		}
	}

	public function category(string $cateId): BaseView
	{
		return $this->_template($this->makeTplData([
			"cateId" => $cateId
		]));
	}
}

<?php


namespace link\hefang\cms;


use link\hefang\cms\admin\models\SettingModel;
use link\hefang\cms\common\helpers\CacheHelper;
use link\hefang\cms\content\models\ArticleModel;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\entities\Router;
use link\hefang\mvc\entities\StatusResult;
use link\hefang\mvc\exceptions\MethodNotAllowException;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\SimpleApplication;
use link\hefang\mvc\views\BaseView;
use link\hefang\mvc\views\StatusView;
use Throwable;

class HeFangCMS extends SimpleApplication
{
	const PREFIX_APIS = "/apis/";

	public static function queryKey(): string
	{
		return Mvc::getProperty("project.query.field.name", "query");
	}

	public static function sortKey(): string
	{
		return Mvc::getProperty("project.sort.field.name", "sort");
	}

	public function onInit()
	{
		$settings = SettingModel::allValues();
		Mvc::getLogger()->debug(print_r($settings, true), "初始化时读取到配置项");
		return $settings;
	}

	/**
	 * @param Throwable $e
	 * @return BaseView|null
	 */
	public function onException(Throwable $e)
	{
		if ($e instanceof MethodNotAllowException) {
			if ($e->getRouter()->getFormat() === "json") {
				return new StatusView(new StatusResult(
					405, StatusView::HTTP_STATUS_CODE[405], $e->getMessage()
				));
			}
		}
		return null;
	}

	public function onRequest(string $path)
	{
		Mvc::getLogger()->debug("Request: " . $path);
		if (StringHelper::startsWith($path, true, self::PREFIX_APIS)) {
			$path = substr($path, strlen(self::PREFIX_APIS) - 1);
			return Router::parsePath($path);
		}
		// /.html
		if (strlen($path) > 6) {
			$article = CacheHelper::cacheOrFetch($path, function () use ($path) {
				return ArticleModel::find("`path` = '{$path}'");
			});
			if ($article instanceof ArticleModel && $article->isExist() && $article->isEnable()) {
				return new Router("main", "home", "article", $article->getId());
			}
		}
		return parent::onRequest($path);
	}
}

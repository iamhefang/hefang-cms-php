<?php


namespace link\hefang\cms;

use link\hefang\cms\application\admin\models\SettingModel;
use link\hefang\cms\application\content\models\ArticleModel;
use link\hefang\cms\core\helpers\CacheHelper;
use link\hefang\cms\core\plugin\PluginManager;
use link\hefang\helpers\ClassHelper;
use link\hefang\helpers\CollectionHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\entities\Router;
use link\hefang\mvc\exceptions\ActionNotFoundException;
use link\hefang\mvc\exceptions\ControllerNotFoundException;
use link\hefang\mvc\exceptions\MethodNotAllowException;
use link\hefang\mvc\helpers\DebugHelper;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\results\StatusResult;
use link\hefang\mvc\SimpleApplication;
use link\hefang\mvc\views\BaseView;
use link\hefang\mvc\views\ErrorView;
use link\hefang\mvc\views\StatusView;
use Throwable;

class HeFangCMS extends SimpleApplication
{
	const PREFIX_APIS = "/apis/";
	const PREFIX_TAGS = "/tags/";
	const PREFIX_CATEGORY = "/category/";
	const PATH_SEARCH = "/search.html";


	public function __construct()
	{
		$plugins = PluginManager::listPlugins();
		foreach ($plugins as $pluginEntry) {
			if (!$pluginEntry->isEnable()) continue;
			DebugHelper::addPlugin($pluginEntry);
			ClassHelper::loader($pluginEntry->getPluginDir());
			if (!empty($pluginEntry->getControllers())) {
				Mvc::addControllers($pluginEntry->getControllers(), "plugin-{$pluginEntry->getId()}");
			}
			$pluginClass = $pluginEntry->getClassName();
			$manager = new PluginManager($pluginEntry);
			new $pluginClass($manager);
		}
	}

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
		$event = PluginManager::executeHooks(HEFANG_CMS_EVENT_INIT, $settings);
		Mvc::getLogger()->debug(print_r($event->getData(), true), "插件返回新的配置项");
		return $event->getData();
	}

	/**
	 * @param Throwable $e
	 * @return BaseView|null
	 */
	public function onException(Throwable $e)
	{
		$event = PluginManager::executeHooks(HEFANG_CMS_EVENT_EXCEPTION, $e);
		$data = $event->getData();
		if ($data instanceof BaseView) {
			return $data;
		}
		if ($e instanceof MethodNotAllowException) {
			if ($e->getRouter()->getFormat() === "json") {
				return new StatusView(new StatusResult(
					405, StatusView::HTTP_STATUS_CODE[405], $e->getMessage()
				));
			}
		}
		if ($e instanceof ControllerNotFoundException || $e instanceof ActionNotFoundException) {
			return new ErrorView(404, "Not Found");
		}
		return null;
	}

	public function onRequest(string $path)
	{
		Mvc::getLogger()->debug("Request: " . $path);
		$event = PluginManager::executeHooks(HEFANG_CMS_EVENT_REQUEST, $path);
		$router = $event->getData();
		if ($router instanceof Router) {
			$pluginEntry = CollectionHelper::last($event->getPluginPath());
			return $router->setModule("plugin-{$pluginEntry->getId()}");
		}
		if (StringHelper::startsWith($path, true, self::PREFIX_APIS)) {
			$path = substr($path, strlen(self::PREFIX_APIS) - 1);
			return Router::parsePath($path);
		}
		if (StringHelper::startsWith($path, true, self::PREFIX_CATEGORY)) {
			$id = CollectionHelper::last(explode("/", $path), "");
			return new Router("main", "home", "category", explode(".", $id)[0]);
		}
		if (StringHelper::startsWith($path, true, self::PREFIX_TAGS)) {
			$tag = CollectionHelper::last(explode("/", $path), "");
			return new Router("main", "home", "tags", explode(".", $tag)[0]);
		}
		if (strcasecmp($path, self::PATH_SEARCH) == 0) {
			return new Router("main", "home", "search");
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

<?php

namespace link\hefang\cms\application\plugin\models;

defined(PHP_MVC) or defined("Access Refused");

use link\hefang\cms\core\helpers\CacheHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\helpers\TimeHelper;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\models\BaseModel;
use link\hefang\mvc\models\ModelField;
use link\hefang\mvc\Mvc;

class PluginModel extends BaseModel
{

	//等待初始化
	const STATUS_WAIT_FOR_INSTALL = "wait-for-install";
	//等待升级
	const STATUS_WAIT_FOR_UPGRADE = "wait-for-upgrade";
	//已就绪
	const STATUS_READY = "ready";

	private $id;
	private $name;
	private $version;
	private $supportVersion;
	private $className;
	private $description;
	private $tags;
	private $homepage;
	private $issues;
	private $author;
	private $installTime;
	private $updateTime;
	private $installFiles;
	private $status;
	private $enable = true;
	private $dependsOn;
	private $namespace;
	private $controllers;
	private $settingDefines;
	private $scripts;

	public static function fromJson(string $json): PluginModel
	{
		$model = new PluginModel();

		$data = json_decode($json, true);

		if (isset($data["supportVersion"]) && !is_array($data["supportVersion"])) {
			$data["supportVersion"] = [$data["supportVersion"]];
		}

		if (isset($data["namespace"])) {
			$data["className"] = str_replace(".", "\\", "{$data["namespace"]}.{$data["className"]}");
		}
		if (isset($data["controllers"])) {
			$data["controllers"] = array_map(function (string $controller) {
				return str_replace(".", "\\", $controller);
			}, $data["controllers"]);
		}

		foreach ($data as $prop => $value) {
			$model->setValue2Prop(is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : $value, $prop);
		}

		return $model;
	}

	public static function fields(): array
	{
		return [
			ModelField::prop("id")->primaryKey()->trim(),
			ModelField::prop("name"),
			ModelField::prop("version"),
			ModelField::prop("supportVersion")->type(ModelField::TYPE_JSON_STRING),
			ModelField::prop("className"),
			ModelField::prop("description"),
			ModelField::prop("tags")->type(ModelField::TYPE_JSON_STRING),
			ModelField::prop("homepage"),
			ModelField::prop("issues"),
			ModelField::prop("author")->type(ModelField::TYPE_JSON_STRING),
			ModelField::prop("installTime"),
			ModelField::prop("updateTime"),
			ModelField::prop("installFiles")->type(ModelField::TYPE_JSON_STRING),
			ModelField::prop("status"),
			ModelField::prop("enable")->type(ModelField::TYPE_BOOL),
			ModelField::prop("dependsOn")->type(ModelField::TYPE_JSON_STRING),
			ModelField::prop("namespace"),
			ModelField::prop("controllers")->type(ModelField::TYPE_JSON_STRING),
			ModelField::prop("settingDefines")->type(ModelField::TYPE_JSON_STRING),
			ModelField::prop("scripts")->type(ModelField::TYPE_JSON_STRING)
		];
	}

	/**
	 * @param bool $useCache
	 * @return PluginModel[]
	 */
	public static function allPlugins(bool $useCache = true): array
	{
		try {
			return CacheHelper::cacheOrFetch(CacheHelper::KEY_ALL_PLUGINS, function () {
				return PluginModel::pager(1, 100)->getData();
			}, -1, $useCache);
		} catch (SqlException $e) {
			Mvc::getLogger()->error($e->getMessage(), "获取全部插件时异常", $e);
			return [];
		}
	}

	/**
	 * 插件唯一ID
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * 插件唯一ID
	 * @param string $id
	 * @return PluginModel
	 */
	public function setId(string $id): PluginModel
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * 插件名称
	 * @return string|null
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * 插件名称
	 * @param string|null $name
	 * @return PluginModel
	 */
	public function setName($name): PluginModel
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * 版本号
	 * @return string|null
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * 版本号
	 * @param string|null $version
	 * @return PluginModel
	 */
	public function setVersion($version): PluginModel
	{
		$this->version = $version;
		return $this;
	}

	/**
	 *
	 * @return string|null
	 */
	public function getClassName()
	{
		return $this->className;
	}

	/**
	 *
	 * @param string|null $className
	 * @return PluginModel
	 */
	public function setClassName($className): PluginModel
	{
		$this->className = $className;
		return $this;
	}

	/**
	 * 插件描述
	 * @return string|null
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * 插件描述
	 * @param string|null $description
	 * @return PluginModel
	 */
	public function setDescription($description): PluginModel
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * 插件主页
	 * @return string|null
	 */
	public function getHomepage()
	{
		return $this->homepage;
	}

	/**
	 * 插件主页
	 * @param string|null $homepage
	 * @return PluginModel
	 */
	public function setHomepage($homepage): PluginModel
	{
		$this->homepage = $homepage;
		return $this;
	}

	/**
	 * 插件反馈页
	 * @return string|null
	 */
	public function getIssues()
	{
		return $this->issues;
	}

	/**
	 * 插件反馈页
	 * @param string|null $issues
	 * @return PluginModel
	 */
	public function setIssues($issues): PluginModel
	{
		$this->issues = $issues;
		return $this;
	}

	/**
	 * 插件安装时间
	 * @return string|null
	 */
	public function getInstallTime()
	{
		return $this->installTime;
	}

	/**
	 * 插件安装时间
	 * @param string|null $installTime
	 * @return PluginModel
	 */
	public function setInstallTime($installTime): PluginModel
	{
		$this->installTime = $installTime;
		return $this;
	}

	/**
	 * 插件上次更新时间
	 * @return string|null
	 */
	public function getUpdateTime()
	{
		return $this->updateTime;
	}

	/**
	 * 插件上次更新时间
	 * @param string|null $updateTime
	 * @return PluginModel
	 */
	public function setUpdateTime($updateTime): PluginModel
	{
		$this->updateTime = $updateTime;
		return $this;
	}

	/**
	 * 插件状态
	 * wait-for-install:等待执行安装脚本
	 * wait-for-upgrade:等待执行升级脚本
	 * wait-for-uninstall:等待执行卸载脚本
	 * ready:已安装或升级完成，可加载使用
	 * @return string|null
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * 插件状态
	 * wait-for-install:等待执行安装脚本
	 * wait-for-upgrade:等待执行升级脚本
	 * wait-for-uninstall:等待执行卸载脚本
	 * ready:已安装或升级完成，可加载使用
	 * @param string|null $status
	 * @return PluginModel
	 */
	public function setStatus($status): PluginModel
	{
		$this->status = $status;
		return $this;
	}

	/**
	 * 是否已启用插件
	 * @return bool|null
	 */
	public function isEnable()
	{
		return $this->enable;
	}

	/**
	 * 是否已启用插件
	 * @param bool|null $enable
	 * @return PluginModel
	 */
	public function setEnable($enable): PluginModel
	{
		$this->enable = $enable;
		return $this;
	}

	/**
	 * 依赖的其他插件
	 * @return string|null
	 */
	public function getDependsOn()
	{
		return $this->dependsOn;
	}

	/**
	 * 依赖的其他插件
	 * @param string|null $dependsOn
	 * @return PluginModel
	 */
	public function setDependsOn($dependsOn): PluginModel
	{
		$this->dependsOn = $dependsOn;
		return $this;
	}

	/**
	 * 插件命名空间
	 * @return string|null
	 */
	public function getNamespace()
	{
		return $this->namespace;
	}

	/**
	 * 插件命名空间
	 * @param string|null $namespace
	 * @return PluginModel
	 */
	public function setNamespace($namespace): PluginModel
	{
		$this->namespace = $namespace;
		return $this;
	}

	public function toMap(): array
	{
		$map = parent::toMap();
		$map["supportVersion"] = $this->getSupportVersion();
		$map["controllers"] = $this->getControllers();
		$map["installFiles"] = $this->getInstallFiles();
		$map["tags"] = $this->getTags();
		$map["author"] = $this->getAuthor();
		return $map;
	}

	/**
	 * 支持的cms版本，内容为json数组字符串
	 * @return array
	 */
	public function getSupportVersion(): array
	{
		return self::_array($this->supportVersion);
	}

	/**
	 * 支持的cms版本，内容为json数组字符串
	 * @param array $supportVersion
	 * @return PluginModel
	 */
	public function setSupportVersion(array $supportVersion): PluginModel
	{
		$this->supportVersion = json_encode($supportVersion, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		return $this;
	}

	/**
	 * @param string|null $data
	 * @return array
	 */
	private static function _array($data): array
	{
		return StringHelper::isNullOrBlank($data) ? [] : json_decode($data, true);
	}

	/**
	 * 插件提供的控制器名，带命名空间
	 * @return array
	 */
	public function getControllers(): array
	{
		return self::_array($this->controllers);
	}

	/**
	 * 插件提供的控制器名，带命名空间
	 * @param string|null $controllers
	 * @return PluginModel
	 */
	public function setControllers($controllers): PluginModel
	{
		$this->controllers = $controllers;
		return $this;
	}

	/**
	 * 插件安装的文件，格式为{"文件路径":"md5值"}
	 * @return array
	 */
	public function getInstallFiles(): array
	{
		return self::_array($this->installFiles);
	}

	/**
	 * 插件安装的文件，格式为{"文件路径":"md5值"}
	 * @param array $installFiles
	 * @return PluginModel
	 */
	public function setInstallFiles(array $installFiles): PluginModel
	{
		$this->installFiles = json_encode($installFiles, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		return $this;
	}

	/**
	 * 插件标签，内容为json数组字符串
	 * @return array
	 */
	public function getTags(): array
	{
		return self::_array($this->tags);
	}

	/**
	 * 插件标签，内容为json数组字符串
	 * @param array $tags
	 * @return PluginModel
	 */
	public function setTags(array $tags): PluginModel
	{
		$this->tags = json_encode($tags, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		return $this;
	}

	/**
	 * 插件作者信息，格式为{"name":"称呼","email":"邮箱","site":"网站"}
	 * @return array
	 */
	public function getAuthor(): array
	{
		return self::_array($this->author);
	}

	/**
	 * 插件作者信息，格式为{"name":"称呼","email":"邮箱","site":"网站"}
	 * @param array $author
	 * @return PluginModel
	 */
	public function setAuthor(array $author): PluginModel
	{
		$this->author = json_encode($author, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		return $this;
	}

	public function insert(): bool
	{
		$this->setInstallTime(TimeHelper::formatMillis());
		return parent::insert();
	}

	/**
	 * @return array
	 */
	public function getSettingDefines(): array
	{
		return self::_array($this->settingDefines);
	}

	/**
	 * @param array $settingDefines
	 */
	public function setSettingDefines(array $settingDefines)
	{
		$this->settingDefines = json_encode($settingDefines, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	}

	/**
	 * @return array
	 */
	public function getScripts(): array
	{
		return self::_array($this->scripts);
	}

	/**
	 * @param array $scripts
	 */
	public function setScripts(array $scripts)
	{
		$this->scripts = json_encode($scripts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
}

<?php


namespace link\hefang\cms\core\plugin\entities;


use link\hefang\helpers\CollectionHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\interfaces\IMapObject;

class PluginEntry implements IMapObject
{
	private $id = "";
	private $name = "";
	private $version = "";
	private $supportVersion = [];
	private $dependsOn = [];
	private $namespace = "";
	private $className = "";
	private $hooks = [];
	private $scripts = [];
	private $controllers = [];
	private $models = [];
	private $description = "";
	private $tags = [];
	private $homepage = "";
	private $issues = "";
	private $author = [];
	private $settings = [];

	private $enable = false;

	private $pluginDir = "";

	public function __construct(array $data = null)
	{
		isset($data["id"]) and $this->id = $data["id"];
		isset($data["name"]) and $this->name = $data["name"];
		isset($data["version"]) and $this->version = $data["version"];
		isset($data["supportVersion"]) and $this->supportVersion = $data["supportVersion"];
		isset($data["dependsOn"]) and $this->dependsOn = $data["dependsOn"];
		isset($data["hooks"]) and $this->hooks = $data["hooks"];
		isset($data["scripts"]) and $this->scripts = $data["scripts"];
		isset($data["models"]) and $this->models = $data["models"];
		isset($data["description"]) and $this->description = $data["description"];
		isset($data["tags"]) and $this->tags = $data["tags"];
		isset($data["homepage"]) and $this->homepage = $data["homepage"];
		isset($data["issues"]) and $this->issues = $data["issues"];
		isset($data["author"]) and $this->author = $data["author"];
		isset($data["settings"]) and $this->settings = $data["settings"];
		isset($data["enable"]) and $this->enable = $data["enable"];
		isset($data["className"]) and $this->className = $data["className"];
		if (isset($data["namespace"]) && !StringHelper::isNullOrBlank($data["namespace"])) {
			$this->namespace = str_replace(".", "\\", $data["namespace"]);
			$this->className = "{$this->namespace}\\${data["className"]}";
		}
		if (isset($data["controllers"]) && !empty($data["controllers"])) {
			$this->controllers = array_map(function (string $name) {
				return str_replace(".", "\\", $name);
			}, $data["controllers"]);
		}
		$this->setPluginDir($data["pluginDir"]);
	}

	/**
	 * @return string
	 */
	public function getPluginDir(): string
	{
		return $this->pluginDir;
	}

	/**
	 * @param string $pluginDir
	 * @return PluginEntry
	 */
	public function setPluginDir(string $pluginDir): PluginEntry
	{
		$this->pluginDir = $pluginDir;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 * @return PluginEntry
	 */
	public function setId(string $id): PluginEntry
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return PluginEntry
	 */
	public function setName(string $name): PluginEntry
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getVersion(): string
	{
		return $this->version;
	}

	/**
	 * @param string $version
	 * @return PluginEntry
	 */
	public function setVersion(string $version): PluginEntry
	{
		$this->version = $version;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getSupportVersion(): array
	{
		return $this->supportVersion;
	}

	/**
	 * @param array $supportVersion
	 * @return PluginEntry
	 */
	public function setSupportVersion(array $supportVersion): PluginEntry
	{
		$this->supportVersion = $supportVersion;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getDependsOn(): array
	{
		return $this->dependsOn;
	}

	/**
	 * @param array $dependsOn
	 * @return PluginEntry
	 */
	public function setDependsOn(array $dependsOn): PluginEntry
	{
		$this->dependsOn = $dependsOn;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNamespace(): string
	{
		return $this->namespace;
	}

	/**
	 * @param string $namespace
	 * @return PluginEntry
	 */
	public function setNamespace(string $namespace): PluginEntry
	{
		$this->namespace = $namespace;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getClassName(): string
	{
		return $this->className;
	}

	/**
	 * @param string $className
	 * @return PluginEntry
	 */
	public function setClassName(string $className): PluginEntry
	{
		$this->className = $className;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getHooks(): array
	{
		return $this->hooks;
	}

	/**
	 * @param array $hooks
	 * @return PluginEntry
	 */
	public function setHooks(array $hooks): PluginEntry
	{
		$this->hooks = $hooks;
		return $this;
	}

	/**
	 *
	 * @param string|null $name
	 * @return array|string|null
	 */
	public function getScripts(string $name = null)
	{
		return $name ? CollectionHelper::getOrDefault($this->scripts, $name, null) : $this->scripts;
	}

	/**
	 * @param array $scripts
	 * @return PluginEntry
	 */
	public function setScripts(array $scripts): PluginEntry
	{
		$this->scripts = $scripts;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getControllers(): array
	{
		return $this->controllers;
	}

	/**
	 * @param array $controllers
	 * @return PluginEntry
	 */
	public function setControllers(array $controllers): PluginEntry
	{
		$this->controllers = $controllers;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getModels(): array
	{
		return $this->models;
	}

	/**
	 * @param array $models
	 * @return PluginEntry
	 */
	public function setModels(array $models): PluginEntry
	{
		$this->models = $models;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return PluginEntry
	 */
	public function setDescription(string $description): PluginEntry
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getTags(): array
	{
		return $this->tags;
	}

	/**
	 * @param array $tags
	 * @return PluginEntry
	 */
	public function setTags(array $tags): PluginEntry
	{
		$this->tags = $tags;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getHomepage(): string
	{
		return $this->homepage;
	}

	/**
	 * @param string $homepage
	 * @return PluginEntry
	 */
	public function setHomepage(string $homepage): PluginEntry
	{
		$this->homepage = $homepage;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getIssues(): string
	{
		return $this->issues;
	}

	/**
	 * @param string $issues
	 * @return PluginEntry
	 */
	public function setIssues(string $issues): PluginEntry
	{
		$this->issues = $issues;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getAuthor(): array
	{
		return $this->author;
	}

	/**
	 * @param array $author
	 * @return PluginEntry
	 */
	public function setAuthor(array $author): PluginEntry
	{
		$this->author = $author;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isEnable(): bool
	{
		return $this->enable;
	}

	/**
	 * @param bool $enable
	 * @return PluginEntry
	 */
	public function setEnable(bool $enable): PluginEntry
	{
		$this->enable = $enable;
		return $this;
	}

	public function toMap(): array
	{
		return [
			"id" => $this->id,
			"name" => $this->name,
			"version" => $this->version,
			"supportVersion" => $this->supportVersion,
			"dependsOn" => $this->dependsOn,
			"namespace" => $this->namespace,
			"className" => $this->className,
			"hooks" => $this->hooks,
			"scripts" => $this->scripts,
			"controllers" => $this->controllers,
			"models" => $this->models,
			"description" => $this->description,
			"tags" => $this->tags,
			"homepage" => $this->homepage,
			"issues" => $this->issues,
			"author" => $this->author,
			"settings" => $this->settings,
			"enable" => $this->enable
		];
	}
}

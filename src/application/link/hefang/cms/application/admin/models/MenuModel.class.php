<?php


namespace link\hefang\cms\application\admin\models;


use link\hefang\cms\core\helpers\CacheHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\databases\SqlSort;
use link\hefang\mvc\models\BaseModel;
use link\hefang\mvc\models\ModelField as MF;


class MenuModel extends BaseModel
{
	const CACHE_KEY_ALL_MENUS = "all-menus";

	private $id = "";
	private $parentId;
	private $name = "";
	private $path = "";
	private $icon;
	private $sort = 0;
	private $enable = true;
	private $children = [];

	/**
	 * @return array
	 */
	public static function fields(): array
	{
		return [
			MF::prop("id")->primaryKey()->trim(),
			MF::prop("parentId")->trim(),
			MF::prop("name")->trim(),
			MF::prop("path")->trim(),
			MF::prop("icon")->trim(),
			MF::prop("sort")->type(MF::TYPE_INT),
			MF::prop("enable")->type(MF::TYPE_BOOL),
		];
	}

	/**
	 * @param bool $useCache
	 * @param bool $onlyEnable
	 * @return array
	 */
	public static function all(bool $onlyEnable = false, bool $useCache = true): array
	{
		return CacheHelper::cacheOrFetch(self::CACHE_KEY_ALL_MENUS, function () use ($onlyEnable) {
			$functions = [];
			$pager = MenuModel::pager(
				1, 1000,
				$onlyEnable ? "enable = TRUE" : null,
				[new SqlSort("sort", SqlSort::TYPE_ASC)]);
			foreach ($pager->getData() as $item) {
				if (!($item instanceof MenuModel)) continue;
				if (StringHelper::isNullOrBlank($item->getParentId())) {
					if (array_key_exists($item->getId(), $functions)) {
						$item->children = $functions[$item->getId()]->children;
					}
					$functions[$item->getId()] = $item;
				} else {
					$functions[$item->getParentId()]->children[] = $item;
				}
			}
			$all = array_values($functions);
			return $all;
		}, -1, $useCache);
	}

	/**
	 * @return string|null
	 */
	public function getParentId()
	{
		return $this->parentId;
	}

	/**
	 * @param string|null $parentId
	 * @return MenuModel
	 */
	public function setParentId($parentId): MenuModel
	{
		$this->parentId = $parentId;
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
	 * @return MenuModel
	 */
	public function setId(string $id): MenuModel
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
	 * @return MenuModel
	 */
	public function setName(string $name): MenuModel
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->path;
	}

	/**
	 * @param string $path
	 * @return MenuModel
	 */
	public function setPath(string $path): MenuModel
	{
		$this->path = $path;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * @param string|null $icon
	 * @return MenuModel
	 */
	public function setIcon($icon): MenuModel
	{
		$this->icon = $icon;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSort(): int
	{
		return $this->sort;
	}

	/**
	 * @param int $sort
	 * @return MenuModel
	 */
	public function setSort(int $sort): MenuModel
	{
		$this->sort = $sort;
		return $this;
	}

	public function toMap(): array
	{
		$map = parent::toMap();
		if (!empty($this->children)) {
			$map["children"] = $this->children;
		}
		$map["enable"] = $this->isEnable();
		return $map;
	}

	/**
	 * @return bool
	 */
	public function isEnable(): bool
	{
		return !!$this->enable;
	}

	/**
	 * @param bool $enable
	 * @return MenuModel
	 */
	public function setEnable(bool $enable): MenuModel
	{
		$this->enable = $enable;
		return $this;
	}
}

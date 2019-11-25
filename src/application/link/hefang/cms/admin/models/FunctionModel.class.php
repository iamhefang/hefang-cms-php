<?php


namespace link\hefang\cms\admin\models;


use link\hefang\helpers\StringHelper;
use link\hefang\mvc\databases\SqlSort;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\models\BaseModel;

class FunctionModel extends BaseModel
{
	private $id = "";
	private $parentId = "";
	private $name = "";
	private $path = "";
	private $icon = "";
	private $sort = 0;
	private $enable = true;
	private $children = [];

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 * @return FunctionModel
	 */
	public function setId(string $id): FunctionModel
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getParentId()
	{
		return $this->parentId;
	}

	/**
	 * @param string $parentId
	 * @return FunctionModel
	 */
	public function setParentId(string $parentId): FunctionModel
	{
		$this->parentId = $parentId;
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
	 * @return FunctionModel
	 */
	public function setName(string $name): FunctionModel
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
	 * @return FunctionModel
	 */
	public function setPath(string $path): FunctionModel
	{
		$this->path = $path;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getIcon(): string
	{
		return $this->icon;
	}

	/**
	 * @param string $icon
	 * @return FunctionModel
	 */
	public function setIcon(string $icon): FunctionModel
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
	 * @return FunctionModel
	 */
	public function setSort(int $sort): FunctionModel
	{
		$this->sort = $sort;
		return $this;
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
	 * @return FunctionModel
	 */
	public function setEnable(bool $enable): FunctionModel
	{
		$this->enable = $enable;
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
	 * 返回主键
	 * @return array
	 */
	public static function primaryKeyFields(): array
	{
		return ["id"];
	}

	/**
	 * 返回模型和数据库对应的字段
	 * key 为数据库对应的字段名, value 为模型字段名
	 * key 不写或为数字时将被框架忽略, 使用value值做为key
	 * @return array
	 */
	public static function fields(): array
	{
		return [
			"id",
			"parent_id" => "parentId",
			"name",
			"path",
			"icon",
			"sort",
			"enable",
		];
	}

	/**
	 * @throws SqlException
	 */
	public static function all()
	{
		$functions = [];
		$pager = FunctionModel::pager(1, 1000, null, "enable = TRUE", [new SqlSort("sort")]);
		foreach ($pager->getData() as $item) {
			if (!($item instanceof FunctionModel)) continue;
			if (StringHelper::isNullOrBlank($item->getParentId())) {
				if (array_key_exists($item->getId(), $functions)) {
					$item->children = $functions[$item->getId()]->children;
				}
				$functions[$item->getId()] = $item;
			} else {
				$functions[$item->getParentId()]->children[] = $item;
			}
		}
		return array_values($functions);
	}
}

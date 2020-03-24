<?php


namespace link\hefang\cms\admin\models;


use link\hefang\mvc\databases\SqlSort;
use link\hefang\mvc\models\BaseModel2;
use link\hefang\mvc\models\ModelField as MF;
use Throwable;

class SettingCategoryModel extends BaseModel2
{
	private $id = "";
	private $name = "";
	private $description = "";
	private $sort = 0;

	private $settings = null;

	/**
	 * 返回模型和数据库对应的字段
	 * key 为数据库对应的字段名, value 为模型字段名
	 * key 不写或为数字时将被框架忽略, 使用value值做为key
	 * @return array
	 */
	public static function fields(): array
	{
		return [
			MF::prop("id")->primaryKey()->trim(),
			MF::prop("name")->trim(),
			MF::prop("description")->trim(),
			MF::prop("sort")->type(MF::TYPE_INT)
		];
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
	 * @return SettingCategoryModel
	 */
	public function setId(string $id): SettingCategoryModel
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
	 * @return SettingCategoryModel
	 */
	public function setName(string $name): SettingCategoryModel
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param string|null $description
	 * @return SettingCategoryModel
	 */
	public function setDescription($description): SettingCategoryModel
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return SettingModel[]
	 */
	public function getSettings(): array
	{
		if ($this->settings == null) {
			try {
				$this->settings = SettingModel::pager(
					1,
					200,
					"category='{$this->getId()}' AND `enable`=TRUE",
					[new SqlSort("sort")]
				)->getData();
			} catch (Throwable $e) {
				$this->settings = [];
			}
		}
		return $this->settings;
	}

	public function toMap(): array
	{
		$map = parent::toMap();
		$map["settings"] = $this->getSettings();
		return $map;
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
	 */
	public function setSort(int $sort): void
	{
		$this->sort = $sort;
	}
}

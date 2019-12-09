<?php


namespace link\hefang\cms\admin\models;


use link\hefang\cms\common\helpers\CacheHelper;
use link\hefang\helpers\ParseHelper;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\models\BaseModel;
use link\hefang\mvc\Mvc;

class SettingModel extends BaseModel
{
	const CACHE_KEY_ALL_SETTINGS = "all-settings";

	private $category = "";
	private $key = "";
	private $name = "";
	private $value = null;
	private $type = "";
	private $description = null;
	private $attribute = null;
	private $nullable = true;
	private $showInCenter = true;
	private $sort = 0;

	/**
	 * @return int
	 */
	public function getSort(): int
	{
		return $this->sort;
	}

	/**
	 * @param int $sort
	 * @return SettingModel
	 */
	public function setSort(int $sort): SettingModel
	{
		$this->sort = $sort;
		return $this;
	}

	private $enable = true;

	/**
	 * @return string
	 */
	public function getCategory(): string
	{
		return $this->category;
	}

	/**
	 * @param string $category
	 * @return SettingModel
	 */
	public function setCategory(string $category): SettingModel
	{
		$this->category = $category;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getKey(): string
	{
		return $this->key;
	}

	/**
	 * @param string $key
	 * @return SettingModel
	 */
	public function setKey(string $key): SettingModel
	{
		$this->key = $key;
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
	 * @return SettingModel
	 */
	public function setName(string $name): SettingModel
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return null|string|bool|float|int
	 */
	public function getValue()
	{
		switch ($this->getType()) {
			case "bool":
				return ParseHelper::parseBoolean($this->value);
			case "int":
				return intval($this->value);
			case "float":
				return floatval($this->value);
			default:
				return $this->value;
		}
	}

	/**
	 * @param null|string|int|bool|float|array $value
	 * @return SettingModel
	 */
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return SettingModel
	 */
	public function setType(string $type): SettingModel
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @return null
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param null|string $description
	 * @return SettingModel
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getAttribute(): array
	{
		return json_decode($this->attribute ?: "{}");
	}

	/**
	 * @param array $attribute
	 * @return SettingModel
	 */
	public function setAttribute(array $attribute)
	{
		$this->attribute = json_encode($attribute, JSON_UNESCAPED_UNICODE);
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isNullable(): bool
	{
		return $this->nullable;
	}

	/**
	 * @param bool $nullable
	 * @return SettingModel
	 */
	public function setNullable(bool $nullable): SettingModel
	{
		$this->nullable = $nullable;
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
	 * @return SettingModel
	 */
	public function setEnable(bool $enable): SettingModel
	{
		$this->enable = $enable;
		return $this;
	}

	/**
	 * @param bool $useCache
	 * @return array
	 */
	public static function allModels(bool $useCache = true): array
	{
		return CacheHelper::cacheOrFetch(self::CACHE_KEY_ALL_SETTINGS, function () {
			$data = SettingModel::pager(1, 1000, null, "enable = TRUE")->getData();
			Mvc::getCache()->set(self::CACHE_KEY_ALL_SETTINGS, $data);
			return $data;
		}, -1, $useCache);
	}

	/**
	 * @param bool $useCache
	 * @return array
	 */
	public static function allValues(bool $useCache = true): array
	{
		try {
			$models = self::allModels($useCache);

			$values = [];
			foreach ($models as $model) {
				if (!($model instanceof SettingModel)) continue;
				$values[$model->getCategory() . "|" . $model->getKey()] = $model->getValue();
			}
			return $values;
		} catch (SqlException $e) {
			Mvc::getLogger()->error($e->getMessage(), "获取全部配置时出现异常", $e);
			return [];
		}
	}

	/**
	 * 返回主键
	 * @return array
	 */
	public static function primaryKeyFields(): array
	{
		return ["category", "key"];
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
			"category",
			"key",
			"name",
			"value",
			"type",
			"description",
			"attribute",
			"nullable",
			"show_in_center" => "showInCenter",
			"enable",
		];
	}

	/**
	 * @return bool
	 */
	public function isShowInCenter(): bool
	{
		return $this->showInCenter;
	}

	/**
	 * @param bool $showInCenter
	 */
	public function setShowInCenter(bool $showInCenter): void
	{
		$this->showInCenter = $showInCenter;
	}
}

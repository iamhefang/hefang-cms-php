<?php


namespace link\hefang\cms\admin\models;


use link\hefang\mvc\models\BaseModel2;
use link\hefang\mvc\models\ModelField as MF;

class SettingCategoryModel extends BaseModel2
{
	private $id = "";
	private $name = "";
	private $description = "";

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
			MF::prop("description")->trim()
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
}

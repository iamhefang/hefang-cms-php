<?php


namespace link\hefang\cms\content\models;


use link\hefang\mvc\models\BaseModel;

class CategoryModel extends BaseModel
{
	private $id = "";
	private $name = "";
	private $keywords = "";
	private $description = "";
	private $enable = true;

	/**
	 * @return string
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 * @return CategoryModel
	 */
	public function setId(string $id): CategoryModel
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
	 * @return CategoryModel
	 */
	public function setName(string $name): CategoryModel
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getKeywords(): string
	{
		return $this->keywords;
	}

	/**
	 * @param string $keywords
	 * @return CategoryModel
	 */
	public function setKeywords(string $keywords): CategoryModel
	{
		$this->keywords = $keywords;
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
	 * @return CategoryModel
	 */
	public function setDescription(string $description): CategoryModel
	{
		$this->description = $description;
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
	 * @return CategoryModel
	 */
	public function setEnable(bool $enable): CategoryModel
	{
		$this->enable = $enable;
		return $this;
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
			"name",
			"keywords",
			"description",
			"enable"
		];
	}
}

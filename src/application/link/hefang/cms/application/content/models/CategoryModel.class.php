<?php


namespace link\hefang\cms\application\content\models;


use link\hefang\mvc\models\BaseModel;
use link\hefang\mvc\models\ModelField as MF;

class CategoryModel extends BaseModel
{
	private $id = "";
	private $name = "";
	private $keywords = "";
	private $description = "";
	private $enable = true;
	private $type = "article";
	private $parentId;

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
			MF::prop("keywords")->trim(),
			MF::prop("description")->trim(),
			MF::prop("parentId")->trim(),
			MF::prop("enable")->type(MF::TYPE_BOOL)
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
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return CategoryModel
	 */
	public function setType(string $type): CategoryModel
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getParentId()
	{
		return $this->parentId;
	}

	/**
	 * @param string|null $parentId
	 * @return CategoryModel
	 */
	public function setParentId($parentId): CategoryModel
	{
		$this->parentId = $parentId;
		return $this;
	}
}

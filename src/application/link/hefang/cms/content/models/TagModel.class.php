<?php


namespace link\hefang\cms\content\models;


use link\hefang\mvc\models\BaseModel;
use link\hefang\mvc\Mvc;

class TagModel extends BaseModel
{
	private $contentId = "";
	private $tag = "";
	private $type = null;

	/**
	 * @return null|string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param null|string $type
	 * @return TagModel
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}


	public static function table(): string
	{
		return Mvc::getTablePrefix() . "content_tag";
	}

	/**
	 * @return string
	 */
	public function getContentId(): string
	{
		return $this->contentId;
	}

	/**
	 * @param string $contentId
	 * @return class
	 */
	public function setContentId(string $contentId): TagModel
	{
		$this->contentId = $contentId;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTag(): string
	{
		return $this->tag;
	}

	/**
	 * @param string $tag
	 * @return class
	 */
	public function setTag(string $tag): TagModel
	{
		$this->tag = $tag;
		return $this;
	}

	/**
	 * 返回主键
	 * @return array
	 */
	public static function primaryKeyFields(): array
	{
		return ["content_id", "tag"];
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
			"content_id" => "contentId", "tag"
		];
	}
}

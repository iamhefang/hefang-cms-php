<?php


namespace link\hefang\cms\application\content\models;


use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\models\BaseModel2;
use link\hefang\mvc\models\ModelField as MF;
use link\hefang\mvc\Mvc;
use Throwable;

class ContentTagModel extends BaseModel2
{
	private $contentId = "";
	private $tag = "";
	private $type = null;

	private $contentCount = 0;

	public static function saveTag(string $id, string $type, array $tags): int
	{
		$table = ContentTagModel::table();
		$sqls = [new Sql("delete from `{$table}` where `content_id`=:id", ["id" => $id])];
		foreach ($tags as $tag) {
			$sqls[] = new Sql("insert into `{$table}`(`content_id`,`tag`,`type`) values (:contentId,:tag,:type)", [
				"contentId" => $id,
				"tag" => $tag,
				"type" => $type
			]);
		}
		try {
			return ContentTagModel::database()->transaction($sqls);
		} catch (SqlException $e) {
			return 0;
		}
	}

	public static function table(): string
	{
		return Mvc::getTablePrefix() . "content_tag";
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
			MF::prop("contentId")->primaryKey()->trim(),
			MF::prop("tag")->primaryKey()->trim(),
			MF::prop("type")->trim()
		];
	}

	/**
	 * @return null|string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param null|string $type
	 * @return ContentTagModel
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
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
	 * @return ContentTagModel
	 */
	public function setContentId(string $contentId): ContentTagModel
	{
		$this->contentId = $contentId;
		return $this;
	}

	public function toMap(): array
	{
		$map = parent::toMap();
		$map["contentCount"] = $this->getContentCount();
		return $map;
	}

	/**
	 * @return int
	 */
	public function getContentCount(): int
	{
		if (!$this->contentCount) {
			try {
				$this->contentCount = ContentTagModel::database()->count(
					ContentTagModel::table(),
					new Sql("`tag`=:tag", ["tag" => $this->getTag()])
				);
			} catch (Throwable $e) {
				$this->contentCount = 0;
			}
		}
		return $this->contentCount;
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
	 * @return ContentTagModel
	 */
	public function setTag(string $tag): ContentTagModel
	{
		$this->tag = $tag;
		return $this;
	}
}

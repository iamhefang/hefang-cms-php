<?php


namespace link\hefang\cms\content\models;


use link\hefang\cms\user\models\AccountModel;
use link\hefang\mvc\models\BaseModel;
use link\hefang\mvc\Mvc;

class ArticleModel extends BaseModel
{
	private $id = "";
	private $title = "";
	private $path = "";
	private $keywords = "";
	private $description = "";
	private $content = "";
	private $postTime = 0;
	private $lastAlterTime = 0;
	private $authorId = "";
	private $readCount = 0;
	private $approvalCount = 0;
	private $opposeCount = 0;
	private $isDraft = true;
	private $categoryId = "";
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
	 * @return ArticleModel
	 */
	public function setId(string $id): ArticleModel
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return ArticleModel
	 */
	public function setTitle(string $title): ArticleModel
	{
		$this->title = $title;
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
	 * @return ArticleModel
	 */
	public function setPath(string $path): ArticleModel
	{
		$this->path = $path;
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
	 * @return ArticleModel
	 */
	public function setKeywords(string $keywords): ArticleModel
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
	 * @return ArticleModel
	 */
	public function setDescription(string $description): ArticleModel
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getContent(): string
	{
		return $this->content;
	}

	/**
	 * @param string $content
	 * @return ArticleModel
	 */
	public function setContent(string $content): ArticleModel
	{
		$this->content = $content;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPostTime(): int
	{
		return $this->postTime;
	}

	/**
	 * @param int $postTime
	 * @return ArticleModel
	 */
	public function setPostTime(int $postTime): ArticleModel
	{
		$this->postTime = $postTime;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getLastAlterTime(): int
	{
		return $this->lastAlterTime;
	}

	/**
	 * @param int $lastAlterTime
	 * @return ArticleModel
	 */
	public function setLastAlterTime(int $lastAlterTime): ArticleModel
	{
		$this->lastAlterTime = $lastAlterTime;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthorId(): string
	{
		return $this->authorId;
	}

	/**
	 * @param string $authorId
	 * @return ArticleModel
	 */
	public function setAuthorId(string $authorId): ArticleModel
	{
		$this->authorId = $authorId;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getReadCount(): int
	{
		return $this->readCount;
	}

	/**
	 * @param int $readCount
	 * @return ArticleModel
	 */
	public function setReadCount(int $readCount): ArticleModel
	{
		$this->readCount = $readCount;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getApprovalCount(): int
	{
		return $this->approvalCount;
	}

	/**
	 * @param int $approvalCount
	 * @return ArticleModel
	 */
	public function setApprovalCount(int $approvalCount): ArticleModel
	{
		$this->approvalCount = $approvalCount;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getOpposeCount(): int
	{
		return $this->opposeCount;
	}

	/**
	 * @param int $opposeCount
	 * @return ArticleModel
	 */
	public function setOpposeCount(int $opposeCount): ArticleModel
	{
		$this->opposeCount = $opposeCount;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isDraft(): bool
	{
		return $this->isDraft;
	}

	/**
	 * @param bool $isDraft
	 * @return ArticleModel
	 */
	public function setIsDraft(bool $isDraft): ArticleModel
	{
		$this->isDraft = $isDraft;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCategoryId(): string
	{
		return $this->categoryId;
	}

	/**
	 * @param string $categoryId
	 * @return ArticleModel
	 */
	public function setCategoryId(string $categoryId): ArticleModel
	{
		$this->categoryId = $categoryId;
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
	 * @return ArticleModel
	 */
	public function setEnable(bool $enable): ArticleModel
	{
		$this->enable = $enable;
		return $this;
	}

	public function toMap(): array
	{
		$map = parent::toMap();
		$map["tags"] = self::database()->pager(
			Mvc::getTablePrefix() . "content_tag",
			1,
			100,
			null,
			"content_id = '{$this->getId()}'"
		);
		$category = CategoryModel::get($this->getCategoryId());
		if (($category instanceof CategoryModel)) {
			$map["categoryName"] = $category->getName();
		} else {
			$map["categoryName"] = null;
		}

		$author = AccountModel::get($this->getAuthorId());
		if (($author instanceof AccountModel)) {
			$map["authorName"] = $author->getName();
		} else {
			$map["authorName"] = null;
		}
		return $map;
	}

	public static function primaryKeyFields(): array
	{
		return ["id"];
	}

	public static function fields(): array
	{
		return [
			"id",
			"title",
			"path",
			"keywords",
			"description",
			"content",
			"post_time" => "postTime",
			"last_alter_time" => "lastAlterTime",
			"author_id" => "authorId",
			"read_count" => "readCount",
			"approval_count" => "approvalCount",
			"oppose_count" => "opposeCount",
			"is_draft" => "isDraft",
			"category_id" => "categoryId",
			"enable"
		];
	}
}

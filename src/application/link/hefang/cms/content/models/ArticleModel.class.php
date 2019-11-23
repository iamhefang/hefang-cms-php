<?php


namespace link\hefang\cms\content\models;


use link\hefang\mvc\models\BaseModel;

class ArticleModel extends BaseModel
{
	private $id;
	private $title;
	private $path;
	private $keywords;
	private $description;
	private $content;
	private $postTime = null;
	private $lastUpdateTime = null;
	private $author;
	private $password;
	private $category;
	private $approvalCount = 0;
	private $readCount = 0;
	private $oppositionCount = 0;
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
	 * @param int $post_time
	 * @return ArticleModel
	 */
	public function setPostTime(int $post_time): ArticleModel
	{
		$this->postTime = $post_time;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getLastUpdateTime(): int
	{
		return $this->lastUpdateTime;
	}

	/**
	 * @param int $last_update_time
	 * @return ArticleModel
	 */
	public function setLastUpdateTime(int $last_update_time): ArticleModel
	{
		$this->lastUpdateTime = $last_update_time;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthor(): string
	{
		return $this->author;
	}

	/**
	 * @param string $author
	 * @return ArticleModel
	 */
	public function setAuthor(string $author): ArticleModel
	{
		$this->author = $author;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 * @return ArticleModel
	 */
	public function setPassword(string $password): ArticleModel
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCategory(): string
	{
		return $this->category;
	}

	/**
	 * @param string $category
	 * @return ArticleModel
	 */
	public function setCategory(string $category): ArticleModel
	{
		$this->category = $category;
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
	 * @param int $approval_count
	 * @return ArticleModel
	 */
	public function setApprovalCount(int $approval_count): ArticleModel
	{
		$this->approvalCount = $approval_count;
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
	 * @param int $read_count
	 * @return ArticleModel
	 */
	public function setReadCount(int $read_count): ArticleModel
	{
		$this->readCount = $read_count;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getOppositionCount(): int
	{
		return $this->oppositionCount;
	}

	/**
	 * @param int $opposition_count
	 * @return ArticleModel
	 */
	public function setOppositionCount(int $opposition_count): ArticleModel
	{
		$this->oppositionCount = $opposition_count;
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

	public static function table(): string
	{
		return "article";
	}

	public static function primaryKeyFields(): array
	{
		return ["id"];
	}

	/**
	 * 返回模型和数据库对应的字段
	 * key 为数据库对应的字段名, value 为模型字段名
	 * @return array
	 */
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
			"last_update_time" => "lastUpdateTime",
			"author",
			"password",
			"category",
			"enable",
			"approval_count" => "approvalCount",
			"read_count" => "readCount",
			"opposition_count" => "oppositionCount",
		];
	}
}

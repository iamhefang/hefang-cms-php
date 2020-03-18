<?php


namespace link\hefang\cms\content\models;


use link\hefang\cms\user\models\AccountModel;
use link\hefang\mvc\models\BaseModel2;
use link\hefang\mvc\models\ModelField as MF;
use link\hefang\mvc\Mvc;
use Throwable;

class ArticleModel extends BaseModel2
{
	private $id = "";
	private $title = "";
	private $path = "";
	private $keywords = "";
	private $description = "";
	private $content = "";
	private $postTime = null;
	private $lastAlterTime = null;
	private $authorId = "";
	private $readCount = 0;
	private $approvalCount = 0;
	private $opposeCount = 0;
	private $isDraft = true;
	private $categoryId = null;
	private $isPrivate = false;
	private $enable = true;
	private $extra = "";
	private $type = "article";
	private $tags = [];
	private $categoryName = null;
	private $authorName = null;

	public static function fields(): array
	{
		return [
			MF::prop("id")->primaryKey()->trim(),
			MF::prop("title")->trim(),
			MF::prop("path")->trim(),
			MF::prop("keywords")->trim(),
			MF::prop("description")->trim(),
			MF::prop("content"),
			MF::prop("postTime"),
			MF::prop("lastAlterTime"),
			MF::prop("authorId")->trim(),
			MF::prop("readCount")->type(MF::TYPE_INT),
			MF::prop("approvalCount")->type(MF::TYPE_INT),
			MF::prop("opposeCount")->type(MF::TYPE_INT),
			MF::prop("isDraft")->type(MF::TYPE_BOOL),
			MF::prop("categoryId")->trim(),
			MF::prop("isPrivate")->type(MF::TYPE_BOOL),
			MF::prop("enable")->type(MF::TYPE_BOOL),
			MF::prop("extra")
		];
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return ArticleModel
	 */
	public function setType(string $type)
	{
		$this->type = $type;
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
	 * @return string|null
	 */
	public function getPostTime()
	{
		return $this->postTime;
	}

	/**
	 * @param string $postTime
	 * @return ArticleModel
	 */
	public function setPostTime(string $postTime): ArticleModel
	{
		$this->postTime = $postTime;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getLastAlterTime()
	{
		return $this->lastAlterTime;
	}

	/**
	 * @param string $lastAlterTime
	 * @return ArticleModel
	 */
	public function setLastAlterTime(string $lastAlterTime): ArticleModel
	{
		$this->lastAlterTime = $lastAlterTime;
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
	 * @return bool
	 */
	public function isPrivate(): bool
	{
		return $this->isPrivate;
	}

	/**
	 * @param bool $isPrivate
	 * @return ArticleModel
	 */
	public function setIsPrivate(bool $isPrivate): ArticleModel
	{
		$this->isPrivate = $isPrivate;
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
		$map["tags"] = $this->getTags();
		$map["categoryName"] = $this->getCategoryName();
		$map["authorName"] = $this->getAuthorName();
		return $map;
	}

	/**
	 * @return array
	 */
	public function getTags(): array
	{
		if (empty($this->tags)) {
			try {
				$tags = ContentTagModel::pager(1, 100, "`content_id` = '{$this->getId()}'")->getData();
				$this->tags = array_map(function (ContentTagModel $tag) {
					return $tag->getTag();
				}, $tags);
			} catch (Throwable $e) {
				Mvc::getLogger()->error($e->getMessage(), "获取文章标签时异常", $e);
				$this->tags = [];
			}
		}
		return $this->tags;
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
	 * @return ArticleModel
	 */
	public function setId(string $id): ArticleModel
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getCategoryName()
	{
		if (!$this->categoryName && $this->categoryId) {
			try {
				$category = CategoryModel::get($this->getCategoryId());
				if (($category instanceof CategoryModel)) {
					$this->categoryName = $category->getName();
				} else {
					$this->categoryName = null;
				}
			} catch (Throwable $e) {
				$this->categoryName = null;
			}
		}
		return $this->categoryName;
	}

	/**
	 * @return string|null
	 */
	public function getCategoryId()
	{
		return $this->categoryId;
	}

	/**
	 * @param string|null $categoryId
	 * @return ArticleModel
	 */
	public function setCategoryId($categoryId): ArticleModel
	{
		$this->categoryId = $categoryId;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAuthorName(): string
	{
		if (!$this->authorName && $this->authorId) {
			try {
				$author = AccountModel::get($this->getAuthorId());
				if (($author instanceof AccountModel)) {
					$this->authorName = $author->getName();
				} else {
					$this->authorName = null;
				}
			} catch (Throwable $e) {
				$this->authorName = null;
			}
		}
		return $this->authorName;
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
	 * @return string|string
	 */
	public function getExtra()
	{
		return $this->extra;
	}

	/**
	 * @param string|string $extra
	 * @return ArticleModel
	 */
	public function setExtra($extra): ArticleModel
	{
		$this->extra = $extra;
		return $this;
	}
}

<?php


namespace link\hefang\cms\application\content\models;


use link\hefang\mvc\models\BaseModel;
use link\hefang\mvc\models\ModelField as MF;

class CommentModel extends BaseModel
{
	private $id = "";
	private $contentId = "";
	private $replayId = "";
	private $content = null;
	private $floor = 0;
	private $postTime = null;
	private $readTime = null;
	private $enable = true;
	private $authorId = "";

	/**
	 * 返回模型的字段定义
	 * @return MF[]
	 */
	public static function fields(): array
	{
		return [
			MF::prop("id")->primaryKey(),
			MF::prop("contentId")->trim(),
			MF::prop("replayId"),
			MF::prop("content")->trim(),
			MF::prop("floor")->type(MF::TYPE_INT),
			MF::prop("postTime")->trim(),
			MF::prop("enable")->type(MF::TYPE_BOOL),
			MF::prop("readTime")->trim(),
			MF::prop("authorId")->trim()
		];
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
	 * @return CommentModel
	 */
	public function setAuthorId(string $authorId): CommentModel
	{
		$this->authorId = $authorId;
		return $this;
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
	 * @return CommentModel
	 */
	public function setId(string $id): CommentModel
	{
		$this->id = $id;
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
	 * @return CommentModel
	 */
	public function setContentId(string $contentId): CommentModel
	{
		$this->contentId = $contentId;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getReplayId()
	{
		return $this->replayId;
	}

	/**
	 * @param string|null $replayId
	 * @return CommentModel
	 */
	public function setReplayId($replayId): CommentModel
	{
		$this->replayId = $replayId;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param string|null $content
	 * @return CommentModel
	 */
	public function setContent($content): CommentModel
	{
		$this->content = $content;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getFloor(): int
	{
		return $this->floor;
	}

	/**
	 * @param int $floor
	 * @return CommentModel
	 */
	public function setFloor(int $floor): CommentModel
	{
		$this->floor = $floor;
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
	 * @param string|null $postTime
	 * @return CommentModel
	 */
	public function setPostTime($postTime): CommentModel
	{
		$this->postTime = $postTime;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getReadTime()
	{
		return $this->readTime;
	}

	/**
	 * @param string|null $readTime
	 * @return CommentModel
	 */
	public function setReadTime($readTime): CommentModel
	{
		$this->readTime = $readTime;
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
	 * @return CommentModel
	 */
	public function setEnable(bool $enable): CommentModel
	{
		$this->enable = $enable;
		return $this;
	}
}

<?php


namespace link\hefang\cms\content\models;


use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\models\BaseModel2;
use link\hefang\mvc\models\ModelField as MF;

class FileModel extends BaseModel2
{
	private $id = "";
	private $name;
	private $file_name = "";
	private $size = -1;
	private $upload_time = "";
	private $uploader_id = "";
	private $is_public = true;
	private $type;
	private $enable = true;
	private $savePath = "";
	private $hash = "";
	private $tags = [];

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
			MF::prop("fileName")->trim(),
			MF::prop("size")->type(MF::TYPE_INT),
			MF::prop("uploadTime"),
			MF::prop("uploaderId"),
			MF::prop("isPublic")->type(MF::TYPE_BOOL),
			MF::prop("enable")->type(MF::TYPE_BOOL),
			MF::prop("savePath")->trim(),
			MF::prop("hash")->trim()
		];
	}

	/**
	 * @return string
	 */
	public function getHash(): string
	{
		return $this->hash;
	}

	/**
	 * @param string $hash
	 * @return FileModel
	 */
	public function setHash(string $hash): FileModel
	{
		$this->hash = $hash;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param null|string $type
	 * @return FileModel
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param null|string $name
	 * @return FileModel
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFileName(): string
	{
		return $this->file_name;
	}

	/**
	 * @param string $file_name
	 * @return FileModel
	 */
	public function setFileName(string $file_name)
	{
		$this->file_name = $file_name;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSize(): int
	{
		return $this->size;
	}

	/**
	 * @param int $size
	 * @return FileModel
	 */
	public function setSize(int $size): FileModel
	{
		$this->size = $size;
		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getUploadTime()
	{
		return $this->upload_time;
	}

	/**
	 * @param string $upload_time
	 * @return FileModel
	 */
	public function setUploadTime($upload_time)
	{
		$this->upload_time = $upload_time;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUploaderId(): string
	{
		return $this->uploader_id;
	}

	/**
	 * @param mixed $uploader_id
	 * @return FileModel
	 */
	public function setUploaderId(string $uploader_id)
	{
		$this->uploader_id = $uploader_id;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isIsPublic(): bool
	{
		return $this->is_public;
	}

	/**
	 * @param bool $is_public
	 * @return FileModel
	 */
	public function setIsPublic(bool $is_public): FileModel
	{
		$this->is_public = $is_public;
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
	 * @return FileModel
	 */
	public function setEnable(bool $enable): FileModel
	{
		$this->enable = $enable;
		return $this;
	}

	public function toMap(): array
	{
		$map = parent::toMap(); // TODO: Change the autogenerated stub
		$map["tags"] = $this->getTags();
		return $map;
	}

	/**
	 * @return array
	 * @throws SqlException
	 */
	public function getTags(): array
	{
		if (empty($this->tags)) {
			$data = ContentTagModel::pager(
				1,
				1000,
				new Sql("content_id = :contentId", ["contentId" => $this->getId()])
			)->getData();
			$this->tags = array_map(function (ContentTagModel $item) {
				return $item->getTag();
			}, $data);
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
	 * @return FileModel
	 */
	public function setId(string $id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSavePath(): string
	{
		return $this->savePath;
	}

	/**
	 * @param string $savePath
	 */
	public function setSavePath(string $savePath)
	{
		$this->savePath = $savePath;
	}
}

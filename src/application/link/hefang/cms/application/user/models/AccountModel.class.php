<?php


namespace link\hefang\cms\application\user\models;


use link\hefang\helpers\RandomHelper;
use link\hefang\helpers\StringHelper;
use link\hefang\helpers\TimeHelper;
use link\hefang\mvc\controllers\BaseController;
use link\hefang\mvc\exceptions\ModelException;
use link\hefang\mvc\models\BaseModel2;
use link\hefang\mvc\models\ModelField as MF;
use link\hefang\mvc\Mvc;

class AccountModel extends BaseModel2
{
	const ACCOUNT_SESSION_KEY = "ACCOUNT_SESSION";
	const MAX_UNLOCK_TRY = 5;
	private $id = "";
	private $roleId = "";
	private $name = "";
	private $password = "";
	private $registerTime = null;
	private $registerType = "";
	private $email = "";
	private $locked = false;
	private $lockedTime = null;
	private $avatar = null;
	private $enable = true;
	private $screenLockPassword = null;
	private $loginTime = null;
	private $loginIp = null;
	private $token = null;
	private $isLockedScreen = false;
	private $unLockTries = 0;

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
			MF::prop("roleId")->trim(),
			MF::prop("name")->trim(),
			MF::prop("avatar"),
			MF::prop("password")->hide(),
			MF::prop("registerTime"),
			MF::prop("registerType"),
			MF::prop("email")->trim(),
			MF::prop("locked")->type(MF::TYPE_BOOL),
			MF::prop("lockedTime"),
			MF::prop("enable")->type(MF::TYPE_BOOL),
			MF::prop("screenLockPassword")->hide(),
			MF::prop("isLockedScreen")->type(MF::TYPE_BOOL)
		];
	}

	/**
	 * @return null|string
	 */
	public function getScreenLockPassword()
	{
		return $this->screenLockPassword;
	}

	/**
	 * @param null|string $screenLockPassword
	 * @return AccountModel
	 */
	public function setScreenLockPassword($screenLockPassword): AccountModel
	{
		$this->screenLockPassword = $screenLockPassword;
		return $this;
	}

	public function login(BaseController $controller)
	{
		$this->loginTime = TimeHelper::formatMillis();
		$this->loginIp = $controller->_ip();
		$this->updateSession($controller);
	}

	public function updateSession(BaseController $controller): AccountModel
	{
		$authType = strtoupper(Mvc::getProperty("project.auth.type", "SESSION"));
		if ($authType === "TOKEN") {
			if (StringHelper::isNullOrBlank($this->token)) {
				$this->token = RandomHelper::guid();
			}
			Mvc::getCache()->set($this->token, $this);
		} else {
			$controller->_setSession(self::ACCOUNT_SESSION_KEY, $this);
		}
		return $this;
	}

	public function logout()
	{
		$authType = strtoupper(Mvc::getProperty("project.auth.type", "SESSION"));
		if ($authType === "TOKEN" && !StringHelper::isNullOrBlank($this->token)) {
			Mvc::getCache()->remove($this->token);
		} else {
			session_destroy();
		}
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
	 * @return AccountModel
	 */
	public function setName(string $name): AccountModel
	{
		$this->name = $name;
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
	 * @return AccountModel
	 */
	public function setPassword(string $password): AccountModel
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getRegisterTime(): string
	{
		return $this->registerTime;
	}

	/**
	 * @param int $registerTime
	 * @return AccountModel
	 */
	public function setRegisterTime($registerTime): AccountModel
	{
		$this->registerTime = $registerTime;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getRegisterType(): string
	{
		return $this->registerType;
	}

	/**
	 * @param string $registerType
	 * @return AccountModel
	 */
	public function setRegisterType(string $registerType): AccountModel
	{
		$this->registerType = $registerType;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 * @return AccountModel
	 */
	public function setEmail(string $email): AccountModel
	{
		$this->email = $email;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isLocked(): bool
	{
		return $this->locked;
	}

	/**
	 * @param bool $locked
	 * @return AccountModel
	 */
	public function setLocked(bool $locked): AccountModel
	{
		$this->locked = $locked;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLockedTime(): string
	{
		return $this->lockedTime;
	}

	/**
	 * @param string $lockedTime
	 * @return AccountModel
	 */
	public function setLockedTime(string $lockedTime): AccountModel
	{
		$this->lockedTime = $lockedTime;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getAvatar()
	{
		return $this->avatar;
	}

	/**
	 * @param string $avatar
	 * @return AccountModel
	 */
	public function setAvatar(string $avatar): AccountModel
	{
		$this->avatar = $avatar;
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
	 * @return AccountModel
	 */
	public function setEnable(bool $enable): AccountModel
	{
		$this->enable = $enable;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getRoleName()
	{
		return $this->getRoleId();
	}

	/**
	 * @return string
	 */
	public function getRoleId(): string
	{
		return $this->roleId;
	}

	/**
	 * @param string $roleId
	 * @return AccountModel
	 */
	public function setRoleId(string $roleId): AccountModel
	{
		$this->roleId = $roleId;
		return $this;
	}

	public function toMap(): array
	{
		$map = parent::toMap();
		$map["token"] = $this->getToken();
		$map["loginTime"] = $this->getLoginTime();
		$map["loginIp"] = $this->getLoginIp();
		$map["isAdmin"] = $this->isAdmin();
		$map["isSuperAdmin"] = $this->isSuperAdmin();
		$map["isLockedScreen"] = $this->isLockedScreen();

		return $map;
	}

	/**
	 * @return null|string
	 */
	public function getToken()
	{
		return $this->token;
	}

	/**
	 * @return null|string
	 */
	public function getLoginTime()
	{
		return $this->loginTime;
	}

	/**
	 * @return null|string
	 */
	public function getLoginIp()
	{
		return $this->loginIp;
	}

	public function isAdmin(): bool
	{
		return $this->isSuperAdmin() || $this->getRoleId() === "admin";
	}

	public function isSuperAdmin(): bool
	{
		return $this->getId() === "root";
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
	 * @return AccountModel
	 */
	public function setId(string $id): AccountModel
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isLockedScreen(): bool
	{
		return $this->isLockedScreen;
	}

	/**
	 * @param bool $isLockedScreen
	 * @return AccountModel
	 */
	public function setIsLockedScreen(bool $isLockedScreen): AccountModel
	{
		$this->isLockedScreen = $isLockedScreen;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getUnLockTries(): int
	{
		return $this->unLockTries;
	}

	/**
	 * @param int $unLockTries
	 * @return AccountModel
	 */
	public function setUnLockTries(int $unLockTries): AccountModel
	{
		$this->unLockTries = $unLockTries;
		return $this;
	}

	public function insert(): bool
	{
		if ($this->isDebugUser()) {
			throw new ModelException("当前用户是调试用的虚拟用户，不能保存到数据库");
		}
		return parent::insert();
	}

	public function update(array $fields2update = null): bool
	{
		if ($this->isDebugUser()) {
			throw new ModelException("当前用户是调试用的虚拟用户， 不能更新到数据库");
		}
		return parent::update($fields2update);
	}

	/**
	 * 是否是调试用的虚拟用户
	 * @return bool
	 */
	public function isDebugUser(): bool
	{
		return $this->registerType === "debug";
	}

	public static function debugSuperUser(): AccountModel
	{
		$model = new AccountModel();
		return $model
			->setId("root")
			->setName("调试超管")
			->setRoleId("admin")
			->setEnable(true)
			->setRegisterType("debug")
			->setRegisterTime(TimeHelper::formatMillis());
	}

	public static function debugAdminUser(): AccountModel
	{
		$model = new AccountModel();
		return $model
			->setId(RandomHelper::guid())
			->setName("调试普管")
			->setRoleId("admin")
			->setEnable(true)
			->setRegisterType("debug")
			->setRegisterTime(TimeHelper::formatMillis());
	}

	public static function debugUser(): AccountModel
	{
		$model = new AccountModel();
		return $model
			->setId(RandomHelper::guid())
			->setName("调试用户")
			->setRoleId("normal")
			->setEnable(true)
			->setRegisterType("debug")
			->setRegisterTime(TimeHelper::formatMillis());
	}
}

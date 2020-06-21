<?php

use link\hefang\cms\application\content\models\ArticleModel;
use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\entities\Pager;
use link\hefang\mvc\exceptions\SqlException;
use link\hefang\mvc\Mvc;

/**
 * 根据文章或路径获取一条文章数据
 * @param string $idOrPath
 * @return ArticleModel
 */
function article(string $idOrPath): ArticleModel
{
	try {
		$article = ArticleModel::find(new Sql("`id`=:id OR `path`=:path", [
			"id" => $idOrPath,
			"path" => $idOrPath
		]));
		if ($article instanceof ArticleModel) {
			return $article;
		}
	} catch (SqlException $e) {
		Mvc::getLogger()->error($e->getMessage(), "获取文章详情时出错", $e);
	}
	return new ArticleModel();
}

/**
 * 获取文章分页
 * @param int $pageIndex 当前页
 * @param int $pageSize 页大小
 * @param string|null $cateId 分类id
 * @return Pager
 */
function articles(int $pageIndex = 1, int $pageSize = 10, string $cateId = null): Pager
{
	try {
		return ArticleModel::pager(
			$pageIndex,
			$pageSize,
			$cateId ? new Sql(
				"`enable`=TRUE AND `category_id` = :cateId OR `category_id` IN (SELECT `id` FROM `category` WHERE `parent_id` = :cateId)",
				[
					"cateId" => $cateId
				]
			) : "`enable`=TRUE"
		);
	} catch (SqlException $e) {
		Mvc::getLogger()->error($e->getMessage(), "获取文章分页时出错", $e);
	}
	return new Pager(0, $pageIndex, $pageSize, []);
}

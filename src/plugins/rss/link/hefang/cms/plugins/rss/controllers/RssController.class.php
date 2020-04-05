<?php


namespace link\hefang\cms\plugins\rss\controllers;


use DOMDocument;
use link\hefang\cms\common\controllers\BaseCmsController;
use link\hefang\cms\common\helpers\CacheHelper;
use link\hefang\cms\content\models\ArticleModel;
use link\hefang\mvc\databases\Sql;
use link\hefang\mvc\databases\SqlSort;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\views\BaseView;
use link\hefang\mvc\views\TextView;
use Throwable;

class RssController extends BaseCmsController
{
	public function server(): BaseView
	{
		return $this->_text(time());
	}

	public function hot(): BaseView
	{
		return $this->fetchArticle("readCount");
	}

	private function fetchArticle(string $sortKey): BaseView
	{
		$pager = CacheHelper::cacheOrFetch(__FUNCTION__, function () use ($sortKey) {
			return ArticleModel::pager(
				1, 20,
				new Sql("enable = TRUE AND is_draft = FALSE AND is_private = FALSE AND `type`='article'"),
				[new SqlSort($sortKey, SqlSort::TYPE_DESC)]
			);
		});
		$name = Mvc::getConfig("site|name");
		$description = Mvc::getConfig("site|description", "何方博客");
		return $this->_text($this->makeXml(
			"最热文章-{$name}",
			$description ?: "何方博客",
			"zh-hans",
			"https://hefang.link",
			$pager->getData()), TextView::XML
		);
	}

	/**
	 * @param string $title
	 * @param string $description
	 * @param string $language
	 * @param string $link
	 * @param ArticleModel[] $items
	 * @return string
	 */
	private function makeXml(string $title, string $description, string $language, string $link, array $items): string
	{
		$xml = new DOMDocument('1.0', 'utf-8');
		$xml->createElement('rss');

		$rss = $xml->createElement('rss');
		$channel = $xml->createElement('channel');
		$channel->appendChild($xml->createElement('title', $title));
		$channel->appendChild($xml->createElement('description', $description));
		$channel->appendChild($xml->createElement('language', $language));
		$channel->appendChild($xml->createElement('link', $link));
		foreach ($items as $item) {
			if (!($item instanceof ArticleModel)) continue;
			$nodeItem = $xml->createElement('item');
			$nodeItem->appendChild($xml->createElement('title', $item->getTitle()));
			$nodeItem->appendChild($xml->createElement('description', $item->getDescription()));
			$nodeItem->appendChild($xml->createElement('link', "https://hefang.link/{$item->getPath()}"));
			$nodeItem->appendChild($xml->createElement('pubDate', date('r', strtotime($item->getPostTime()))));
			$channel->appendChild($nodeItem);
		}

		$rss->setAttribute('version', '2.0');
		$rss->appendChild($channel);
		$xml->appendChild($rss);
		return $xml->saveXML();
	}

	public function top(): BaseView
	{
		return $this->fetchArticle("postTime");
	}

	public function tag(string $tag = null): BaseView
	{
		$tablePrefix = Mvc::getTablePrefix();
		$name = Mvc::getConfig("site|name");
		$description = Mvc::getConfig("site|description", "何方博客");
		try {
			$pager = ArticleModel::pager(
				$this->_pageIndex(),
				$this->_pageSize(),
				new Sql(
					"(is_draft = FALSE AND enable = TRUE AND `type`='article') AND (`id` IN (SELECT `content_id` FROM `{$tablePrefix}content_tag` WHERE tag=:tag))",
					["tag" => $tag]
				), [new SqlSort("readCount")]
			);
			return $this->_text($this->makeXml(
				"{$tag}-{$name}",
				$description ?: "何方博客",
				"zh-hans",
				$tag ? "https://hefang.link/tags/{$tag}.html" : "https://hefang.link",
				$pager->getData()
			), TextView::XML);
		} catch (Throwable $e) {
			return $this->_text("error");
		}
	}
}

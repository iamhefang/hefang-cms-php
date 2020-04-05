<?php


namespace link\hefang\cms\plugins\rss\controllers;


use DOMDocument;
use link\hefang\cms\common\controllers\BaseCmsController;
use link\hefang\cms\content\models\ArticleModel;
use link\hefang\mvc\views\BaseView;

class RssController extends BaseCmsController
{
	public function server(): BaseView
	{
		return $this->_text(time());
	}

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
			$path = $item->getAlias() ?: $item->getId();
			$nodeItem = $xml->createElement('item');
			$nodeItem->appendChild($xml->createElement('title', $item->getTitle()));
			$nodeItem->appendChild($xml->createElement('description', $item->getDescription()));
			$nodeItem->appendChild($xml->createElement('link', "https://hefang.link/article/{$path}.html"));
			$nodeItem->appendChild($xml->createElement('pubDate', date('r', strtotime($item->getPostTime()))));
			$channel->appendChild($nodeItem);
		}

		$rss->setAttribute('version', '2.0');
		$rss->appendChild($channel);
		$xml->appendChild($rss);
		return $xml->saveXML();
	}
}

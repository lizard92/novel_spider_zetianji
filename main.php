<?php
/**
 * Created by PhpStorm.
 * User: wudanyang
 * Date: 2017/4/4
 * Time: 22:17
 */
set_time_limit(0);
ini_set('display_errors', 0);
error_reporting(0);
$start_index = 0; // 起始章
include 'config.php';

// get list
$site_url = 'http://www.xxbiquge.com';
$zetianji_url = 'http://www.xxbiquge.com/5_5422/';

$doc = new DOMDocument();
$zetianji_url = file_get_contents($zetianji_url);
$doc->loadHTML(mb_convert_encoding($zetianji_url, 'HTML-ENTITIES', 'UTF-8'));
$xpath = new DOMXPath($doc);
$elements = $xpath->query('//div[@id=\'list\']//dl//dd//a/@href');
$article_urls = array();
if (!is_null($elements)) {
    echo 'index elements completed';
    foreach ($elements as $element) {
        $nodes = $element->childNodes;
        foreach ($nodes as $node) {
            $article_urls[] = $site_url.$node->nodeValue;
        }
    }
}

echo 'index completed';

// get article
$articles = array();
if (!empty($article_urls)) {
    foreach ($article_urls as $key => $article_url) {
        if ($key < $start_index) {
            continue;
        }
        $article_url = file_get_contents($article_url);
        $doc->loadHTML(mb_convert_encoding($article_url, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new DOMXPath($doc);
        $elements = $xpath->query('//h1//text()');
        $title = $elements->item(0)->nodeValue;
        $elements = $xpath->query('//div[@id=\'content\']//text()');
        $content = '';
        foreach ($elements as $element) {
            $content .= $element->nodeValue;
        }
        $articles[] = 'title:' . $title . PHP_EOL . 'content:' . $content . PHP_EOL;
        echo PHP_EOL.$title.'-'.$key.PHP_EOL;
    }
}

file_put_contents('zetianji.txt', implode(PHP_EOL, $articles));

echo PHP_EOL.'抓取完成';

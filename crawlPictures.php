<?php

require_once('Crawler.php');

$crawler = new Crawler();

$crawler->crawl(['fr', 'de', 'en']);
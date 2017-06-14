<?php if (php_sapi_name() != "cli") : ?>
<form method="get">
    <input type="submit" name="crawl" value="Crawl Pictures">
</form>
<?php
endif;

require_once('Crawler.php');

$crawler = new Crawler();
if (!empty($_GET['crawl']) || php_sapi_name() == "cli") {
    $crawler->crawl(['fr', 'de', 'en']);
}
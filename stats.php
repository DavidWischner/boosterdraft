<?php

require_once('Crawler.php');

$crawler = new Crawler();

$editions               = $crawler->retrieveAvailableEditions();
$i                      = 0;
$alreadyCrawledEditions = $crawler->getAlreadyCrawledEditions();
$missing                = [];
$alreadyCrawled         = [];
foreach ($editions as $edi) {
    $i++;
    $editionString = $edi['edition_short'] . '/de';
    if (in_array($editionString, $alreadyCrawledEditions)) {
        $alreadyCrawled[] = $edi;
    } else {
        $missing[] = $edi;
    }
}
foreach ($alreadyCrawled as $edition) {
    echo 'Found already crawled edition: ' . $edition['edition_long'] . "\n";
}
foreach ($missing as $edition) {
    echo 'Found missing edition: ' . $edition['edition_long'] . "\n";
}
echo "---------------------\n";
echo "Summary:\n";
echo count($alreadyCrawled) . " editions already crawled\n";
echo count($missing) . " editions missing\n";

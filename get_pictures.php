<?php

require_once('Crawler.php');

$crawler = new Crawler();

$editions               = $crawler->retrieveAvailableEditions();
$i                      = 0;
$alreadyCrawledEditions = $crawler->getAlreadyCrawledEditions();
foreach ($editions as $edi) {
    $i++;
    $editionString = $edi['edition_short'] . '/en';
    if (in_array($editionString, $alreadyCrawledEditions)) {
        echo "$i/" . count($editions) . ' Skipped already crawled edition: ' . $edi['edition_long'] . "\n";
        continue;
    }
    $crawler->saveAllPics($editionString, $crawler->getPictures($editionString));
    echo "$i/" . count($editions) . ' Saved edition: ' . $edi['edition_long'] . "\n";
    echo "Debuginfo: used editionString: $editionString \n";
}
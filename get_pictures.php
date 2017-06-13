<?php

require_once('Crawler.php');

$crawler = new Crawler();

$editions = $crawler->retrieveAvailableEditions();
$i=1;
foreach ($editions as $edi) {
    $editionString = $edi['edition_short'] . '/de';
    $crawler->saveAllPics($editionString, $crawler->getPictures($editionString));
    echo 'Saved Edition: '. $edi['edition_long']." $i/".count($editions)."\n";
    $i++;
}
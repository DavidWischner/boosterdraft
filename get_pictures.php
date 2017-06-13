<?php
require_once('Crawler.php');
// CHANGE HERE TO ADD NEW EDITIONS
$editions = array("edition/language"); //array("roe/de","wwk/de","zen/de","arb/de","cfx/de","ala/de","eve/de","shm/de","mt/de","lw/de","fut/de","pc/de","ts/de","tsts/de","cs/de","ai/de","ia/de","di/de","gp/de","rav/de","sok/de","bok/de","chk/de","5dn/de","ds/de","mi/de","sc/de","le/de","on/de","ju/de","tr/de","od/de","ap/de","ps/de","in/de","pr/de","ne/de","mm/de","ud/de","ul/de","us/de","ex/de","sh/de","tp/de","wl/de","vi/de","mr/de","hl/de","m10/de","10e/de","9e/de","8e/de","7e/de","6e/de","5e/de","4e/de","rv/de","rvb/de","re/de","po2/de","po/de","roe/en","wwk/en","zen/en","arb/en","cfx/en","ala/en","eve/en","shm/en","mt/en","lw/en","fut/en","pc/en","ts/en","tsts/en","cs/en","ai/en","ia/en","di/en","gp/en","rav/en","sok/en","bok/en","chk/en","5dn/en","ds/en","mi/en","sc/en","le/en","on/en","ju/en","tr/en","od/en","ap/en","ps/en","in/en","pr/en","ne/en","mm/en","ud/en","ul/en","us/en","ex/en","sh/en","tp/en","wl/en","vi/en","mr/en","hl/en","fe/en","dk/en","lg/en","aq/en","an/en","m10/en","10e/en","9e/en","8e/en","7e/en","6e/en","5e/en","4e/en","rv/en","un/en","be/en","al/en","me3/en","me2/en","med/en","pvc/en","pds/en","gvl/en","pch/en","dvd/en","jvc/en","ch/en","uh/en","ug/en","st/en","p3k/en","po2/en","po/en");

$crawler = new Crawler();

foreach ($editions as $edi) {
    $crawler->saveAllPics($edi, $crawler->getPictures($edi));
}
    
<?php

//holt alle Bilder der gegebenen Edition - returnvalue: array(array(rares), array(uncommons), array(commons))
function get_pictures($editionshort)
{
    $help         = explode("/", $editionshort);
    $language     = $help[1];
    $editionshort = $help[0];
    $mainlink     = "http://magiccards.info/query?q=#+e%3A$editionshort%2F$language&v=scan&s=issue&p=";
    $rare         = "%28r%3Amythic+or+r%3Arare%29";
    $uncommon     = "r%3Auncommon";
    $common       = "r%3Acommon";
    $edition      = array();
    ini_set('max_execution_time', 0);

    foreach (array($rare, $uncommon, $common) as $rarity) {
        $parselines2 = array();
        for ($page = 1; $page < 20; $page++) {
            $link        = str_replace("#", $rarity, $mainlink . $page);
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, $link);
            curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Boosterdraft');
            $html        = curl_exec($curl_handle);
            curl_close($curl_handle);
            if (strpos($html, "your query did not match any cards.")) {
                echo $link . "<br>";
                break;
            }
            $html       = explode("<table", $html);
            $html       = $html[4];
            $parselines = explode('src="', $html);
            unset($parselines[0]);
            foreach ($parselines as $line) {
                $imageSource = explode('"', $line);
                if (!$imageSource == strpos($imageSource[0], "table")) {
                    // if entry already present -> last page found -> continue with next rarity
                    if (isset($parselines2[$imageSource[0]])) {
                        break 2;
                    }
                    $parselines2[$imageSource[0]] = '';
                }
            }
        }
        $edition[] = array_keys($parselines2);
    }
    return $edition;
}

function save_pictures($editionshort, $pictures, $rarity)
{
    echo "Saving $editionshort - $rarity ".count($pictures)."<br>";
    $table = "cardlinktable";
    $sql   = "INSERT INTO $table (rarity, picturelink, edition) VALUES (?, ?, ?)";
    include ("dbconnect.php");
    $stmt  = $dbc->prepare($sql);
    foreach ($pictures as $picturelink) {
        try {
            $stmt->execute(array($rarity, $picturelink, $editionshort));
        } catch (Exception $ex) {
            // duplicate entries will throw an exception
        }
    }
}

function save_all_pics($editionshort, $edition)
{
    save_pictures($editionshort, $edition[0], "rare");
    save_pictures($editionshort, $edition[1], "uncommon");
    save_pictures($editionshort, $edition[2], "common");
}

// CHANGE HERE TO ADD NEW EDITIONS
$editions = array("edition/language"); //array("roe/de","wwk/de","zen/de","arb/de","cfx/de","ala/de","eve/de","shm/de","mt/de","lw/de","fut/de","pc/de","ts/de","tsts/de","cs/de","ai/de","ia/de","di/de","gp/de","rav/de","sok/de","bok/de","chk/de","5dn/de","ds/de","mi/de","sc/de","le/de","on/de","ju/de","tr/de","od/de","ap/de","ps/de","in/de","pr/de","ne/de","mm/de","ud/de","ul/de","us/de","ex/de","sh/de","tp/de","wl/de","vi/de","mr/de","hl/de","m10/de","10e/de","9e/de","8e/de","7e/de","6e/de","5e/de","4e/de","rv/de","rvb/de","re/de","po2/de","po/de","roe/en","wwk/en","zen/en","arb/en","cfx/en","ala/en","eve/en","shm/en","mt/en","lw/en","fut/en","pc/en","ts/en","tsts/en","cs/en","ai/en","ia/en","di/en","gp/en","rav/en","sok/en","bok/en","chk/en","5dn/en","ds/en","mi/en","sc/en","le/en","on/en","ju/en","tr/en","od/en","ap/en","ps/en","in/en","pr/en","ne/en","mm/en","ud/en","ul/en","us/en","ex/en","sh/en","tp/en","wl/en","vi/en","mr/en","hl/en","fe/en","dk/en","lg/en","aq/en","an/en","m10/en","10e/en","9e/en","8e/en","7e/en","6e/en","5e/en","4e/en","rv/en","un/en","be/en","al/en","me3/en","me2/en","med/en","pvc/en","pds/en","gvl/en","pch/en","dvd/en","jvc/en","ch/en","uh/en","ug/en","st/en","p3k/en","po2/en","po/en");

foreach ($editions as $edi) {
    save_all_pics($edi, get_pictures($edi));
}
//get_pictures("wwk/de");
?>

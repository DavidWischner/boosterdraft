<?php

/**
 * Class to crawl magiccards.info for all known Cards
 */
class Crawler {

    //holt alle Bilder der gegebenen Edition - returnvalue: array(array(rares), array(uncommons), array(commons))
    public function getPictures($editionshort)
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

    public function savePictures($editionshort, $pictures, $rarity)
    {
        echo "Saving $editionshort - $rarity " . count($pictures) . "<br>";
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

    public function saveAllPics($editionshort, $edition)
    {
        $this->savePictures($editionshort, $edition[0], "rare");
        $this->savePictures($editionshort, $edition[1], "uncommon");
        $this->savePictures($editionshort, $edition[2], "common");
    }

}

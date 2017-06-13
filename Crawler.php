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

        foreach (array($rare, $uncommon, $common) as $key => $rarity) {
            $parselines2 = array();
            for ($page = 1; $page < 20; $page++) {
                $link        = str_replace("#", $rarity, $mainlink . $page);
                // TODO curl aus der schleife und wrappen
                $curl_handle = curl_init();
                curl_setopt($curl_handle, CURLOPT_URL, $link);
                curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
                curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Boosterdraft');
                $html        = curl_exec($curl_handle);
                curl_close($curl_handle);
                if (strpos($html, "Your query did not match any cards.")) {
                    echo "$editionshort - No cards found: ";
                    echo $link . "\n";
                    break 2;
                }
                $html       = explode("<table", $html);
                if (!isset($html[4])) {
                    // means there are no cards for the filter -> probably not in that language
                    break 2;
                }
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
            $edition[$key] = array_keys($parselines2);
        }
        return $edition;
    }

    public function savePictures($editionshort, $pictures, $rarity)
    {
        echo "Saving $editionshort - $rarity " . count($pictures) . "\n";
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
        if (!empty($edition[0])) {
            $this->savePictures($editionshort, $edition[0], "rare");
        }
        if (!empty($edition[1])) {
            $this->savePictures($editionshort, $edition[1], "uncommon");
        }
        if (!empty($edition[2])) {
            $this->savePictures($editionshort, $edition[2], "common");
        }
    }

    public function retrieveAvailableEditions()
    {
        $link        = 'http://magiccards.info/search.html';
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $link);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Boosterdraft');
        $html        = curl_exec($curl_handle);
        curl_close($curl_handle);
        $editionOptions = explode('</select>', explode('id="edition"', $html)[1])[0];
        $matches = [];
        preg_match_all('/<option value="(.*)?\/(.*)?">(.*)?<\/option>/U', $editionOptions, $matches);
        $data = [];
        foreach ($matches[1] as $key => $editionShort) {
            if (empty($editionShort)) {
                continue;
            }
            $data[] = [
                'edition_short' => $editionShort,
                'language' => $matches[2][$key],
                'edition_long' => $matches[3][$key],
            ];
        }
        return $data;
    }

    /**
     * Returns all editions found in cardtable
     * @return string[]
     */
    public function getAlreadyCrawledEditions()
    {
        $table = "cardlinktable";
        $sql   = "SELECT DISTINCT(edition) FROM $table";
        include ("dbconnect.php");
        $stmt  = $dbc->prepare($sql);
        $stmt->execute();
        while ($row   = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $editions[] = $row['edition'];
        }
        return $editions;
    }

}

<?php

class Booster {

    /**
     * @var array
     */
    private $cards;
    private $edition;

    public function __construct($edition)
    {
        $this->edition = $edition;
    }

    public function generateBooster()
    {
        $this->cards   = array();
        $this->cards[] = $this->getRandomCard($this->edition, "rare");
        for ($i = 0; $i < 3; $i++) {
            //doppelte verhindern
            $newcard = $this->getRandomCard($this->edition, "uncommon");
            if (!in_array($newcard, $this->cards)) {
                $this->cards[] = $newcard;
            } else {
                $i--;
            }
        }
        for ($i = 0; $i < 11; $i++) {
            //doppelte verhindern
            $newcard = $this->getRandomCard($this->edition, "common");
            if (!in_array($newcard, $this->cards)) {
                $this->cards[] = $newcard;
            } else {
                $i--;
            }
        }
    }

    public function printBooster()
    {
        foreach ($this->cards as $cardlink) {
            echo '<img src="' . $cardlink . '"alt="Proxy" style="margin: 0 1px 1px 0;" width="234">';
        }
    }

    public function getRandomCard($editionshort, $rarity)
    {
        $table    = "cardlinktable";
        $sql      = "SELECT picturelink FROM $table WHERE edition= ? AND rarity = ? ORDER BY RAND() LIMIT 1";
        $result   = $this->ps($sql, array($editionshort, $rarity));
        $cardlink = $result->fetch();
        if ($cardlink === false) {
            throw new \Exception(sprintf('Unable to get card with rarity %s for edition %s', $rarity, $editionshort));
        }
        return $cardlink['picturelink'];
    }

    //Konfigurationen
    private function ps($sql, $param_array = array())
    {
        include ("dbconnect.php");
        $stmt = $dbc->prepare($sql);
        if ($stmt->execute($param_array)) {
            return $stmt;
        } else {
            return $stmt;
        }
    }

}
<?php
//Konfigurationen

function get_pictures($edition_short) {
    $help = explode("/",$edition_short);
    $language=$help[1];
    $edition_short=$help[0];

    $mainlink = "http://magiccards.info/query?q=#+e%3A$edition_short%2F$language&v=scan&s=issue&p=";
    $rare = "%28r%3Amythic+or+r%3Arare%29";
    $uncommon = "r%3Auncommon";
    $common = "r%3Acommon";
    $edition = array();

    foreach(array($rare,$uncommon,$common) as $rarity) {
        $parselines2=array();
        for ($page=1;$page<20;$page++) {
            $html=strtolower(implode('',file(str_replace("#",$rarity,$mainlink.$page))));
            if(strpos($html,"your query did not match any cards.")) {
                break;
            }
            $html = explode("<br>",$html);
            $html = $html[2];
            $parselines=explode('src="',$html);
            foreach ($parselines as $line) {
                $help = explode('"', $line);
                if(!$help==strpos($help[0],"table")) {
                    $parselines2[] = $help[0];
                }
            }
        }
        $edition[] = $parselines2;
    }
    return $edition;
}

function make_booster($rares, $uncommons, $commons) {
    $booster = array();
    $booster[] = $rares[array_rand($rares)];
    for ($i = 0; $i < 3; $i++) {
        //doppelte verhindern
        $cardid = array_rand($uncommons) ;
        if(!in_array($uncommons[$cardid], $booster)) {
            $booster[] = $uncommons[$cardid];
        }
        else {
            $i--;
        }
    }
    for ($i = 0; $i < 11; $i++) {
        //doppelte verhindern
        $cardid = array_rand($commons) ;
        if(!in_array($commons[$cardid], $booster)) {
            $booster[] = $commons[$cardid];
        }
        else {
            $i--;
        }
    }
    return $booster;
}

function print_booster($booster) {
    $i=0;
    foreach ($booster as $cardlink) {
        $i++;
        echo '<img src="'.$cardlink.'"alt="Proxy" style="margin: 0pt 1px 1px 0pt;" height="319" width="222">';
        if($i%3 == 0) {
            echo "<br>";
        }
    }
}

if(isset($_GET['edition'])) {
    $edition = get_pictures($_GET['edition']);
    $booster = make_booster($edition[0], $edition[1], $edition[2]);
    print_booster($booster);
    //echo count($edition[0])+ count($edition[1])+ count($edition[2]);
    //extra select für sprache
}
else {
    ?> <form action method=GET>
    <select name="edition">
        <option value=""></option>
        <optgroup label="Zendikar Cycle (Deutsch)"><option value="roe/de">Aufstieg der Eldrazi</option>
            <option value="wwk/de">Weltenerwachen</option>
            <option value="zen/de">Zendikar</option></optgroup>
        <optgroup label="Shards of Alara (Deutsch)"><option value="arb/de">Alara Reborn</option>
            <option value="cfx/de">Conflux</option>
            <option value="ala/de">Fragmente von Alara</option></optgroup>
    
        <optgroup label="Shadowmoor Cycle (Deutsch)"><option value="eve/de">Abendkühle</option>
            <option value="shm/de">Schattenmoor</option></optgroup>
        <optgroup label="Lorwyn Cycle (Deutsch)"><option value="mt/de">Morgenluft</option>
            <option value="lw/de">Lorwyn</option></optgroup>
        <optgroup label="Time Spiral Cycle (Deutsch)"><option value="fut/de">Blick in die Zukunft</option>
            <option value="pc/de">Weltenchaos</option>
            <option value="ts/de">Zeitspirale</option>
            <option value="tsts/de">Zeitspirale Timeshifted</option></optgroup>
        <optgroup label="Ice Age Cycle (Deutsch)"><option value="cs/de">Kälteeinbruch</option>
    
            <option value="ai/de">Allianzen</option>
            <option value="ia/de">Eiszeit</option></optgroup>
        <optgroup label="Ravnica Cycle (Deutsch)"><option value="di/de">Zwietracht</option>
            <option value="gp/de">Gildenbund</option>
            <option value="rav/de">Ravnica: Stadt der Gilden</option></optgroup>
        <optgroup label="Kamigawa Cycle (Deutsch)"><option value="sok/de">Retter von Kamigawa</option>
            <option value="bok/de">Verräter von Kamigawa</option>
            <option value="chk/de">Meister von Kamigawa</option></optgroup>
        <optgroup label="Mirrodin Cycle (Deutsch)"><option value="5dn/de">Fünfte Morgenröte</option>
    
            <option value="ds/de">Nachtstahl</option>
            <option value="mi/de">Mirrodin</option></optgroup>
        <optgroup label="Onslaught Cycle (Deutsch)"><option value="sc/de">Plagen</option>
            <option value="le/de">Legionen</option>
            <option value="on/de">Aufmarsch</option></optgroup>
        <optgroup label="Odyssey Cycle (Deutsch)"><option value="ju/de">Abrechnung</option>
            <option value="tr/de">Qualen</option>
            <option value="od/de">Odyssee</option></optgroup>
        <optgroup label="Invasion Cycle (Deutsch)"><option value="ap/de">Apokalypse</option>
    
            <option value="ps/de">Weltenwechsel</option>
            <option value="in/de">Invasion</option></optgroup>
        <optgroup label="Masquerade Cycle (Deutsch)"><option value="pr/de">Prophezeihung</option>
            <option value="ne/de">Nemesis</option>
            <option value="mm/de">Merkadische Masken</option></optgroup>
        <optgroup label="Artifacts Cycle (Deutsch)"><option value="ud/de">Urzas Schicksal</option>
            <option value="ul/de">Urzas Vermächtnis</option>
            <option value="us/de">Urzas Saga</option></optgroup>
        <optgroup label="Rath Cycle (Deutsch)"><option value="ex/de">Exodus</option>
    
            <option value="sh/de">Felsenburg</option>
            <option value="tp/de">Sturmwind</option></optgroup>
        <optgroup label="Mirage Cycle (Deutsch)"><option value="wl/de">Wetterlicht</option>
            <option value="vi/de">Visionen</option>
            <option value="mr/de">Trugbilder</option></optgroup>
        <optgroup label="Early Sets (Deutsch)"><option value="hl/de">Heimatländer</option></optgroup>
        <optgroup label="Core Set Editions (Deutsch)"><option value="m10/de">Magic 2010</option>
            <option value="10e/de">Zehnte Edition</option>
            <option value="9e/de">Haupt-Set - Neunte Edition</option>
    
            <option value="8e/de">Haupt-Set - Achte Edition</option>
            <option value="7e/de">Siebte Edition</option>
            <option value="6e/de">Classic Sechste Edition</option>
            <option value="5e/de">Fünfte Edition</option>
            <option value="4e/de">Vierte Edition</option>
            <option value="rv/de">Unlimitierte Auflage</option>
            <option value="rvb/de">Limitierte Auflage</option></optgroup>
        <optgroup label="Reprint Sets (Deutsch)"><option value="re/de">Renaissance</option></optgroup>
        <optgroup label="Beginner Sets (Deutsch)">
            <option value="po2/de">Portal Zweites Zeitalter</option>
            <option value="po/de">Portal</option></optgroup>
        <optgroup label="Zendikar Cycle (English)"><option value="roe/en">Rise of the Eldrazi</option>
    
            <option value="wwk/en">Worldwake</option>
            <option value="zen/en">Zendikar</option></optgroup>
        <optgroup label="Shards of Alara (English)"><option value="arb/en">Alara Reborn</option>
            <option value="cfx/en">Conflux</option>
            <option value="ala/en">Shards of Alara</option></optgroup>
        <optgroup label="Shadowmoor Cycle (English)"><option value="eve/en">Eventide</option>
            <option value="shm/en">Shadowmoor</option></optgroup>
        <optgroup label="Lorwyn Cycle (English)"><option value="mt/en">Morningtide</option>
            <option value="lw/en">Lorwyn</option></optgroup>
    
        <optgroup label="Time Spiral Cycle (English)"><option value="fut/en">Future Sight</option>
            <option value="pc/en">Planar Chaos</option>
            <option value="ts/en">Time Spiral</option>
            <option value="tsts/en">Time Spiral "Timeshifted"</option></optgroup>
        <optgroup label="Ice Age Cycle (English)"><option value="cs/en">Coldsnap</option>
            <option value="ai/en">Alliances</option>
            <option value="ia/en">Ice Age</option></optgroup>
        <optgroup label="Ravnica Cycle (English)"><option value="di/en">Dissension</option>
            <option value="gp/en">Guildpact</option>
    
            <option value="rav/en">Ravnica: City of Guilds</option></optgroup>
        <optgroup label="Kamigawa Cycle (English)"><option value="sok/en">Saviors of Kamigawa</option>
            <option value="bok/en">Betrayers of Kamigawa</option>
            <option value="chk/en">Champions of Kamigawa</option></optgroup>
        <optgroup label="Mirrodin Cycle (English)"><option value="5dn/en">Fifth Dawn</option>
            <option value="ds/en">Darksteel</option>
            <option value="mi/en">Mirrodin</option></optgroup>
        <optgroup label="Onslaught Cycle (English)"><option value="sc/en">Scourge</option>
            <option value="le/en">Legions</option>
    
            <option value="on/en">Onslaught</option></optgroup>
        <optgroup label="Odyssey Cycle (English)"><option value="ju/en">Judgment</option>
            <option value="tr/en">Torment</option>
            <option value="od/en">Odyssey</option></optgroup>
        <optgroup label="Invasion Cycle (English)"><option value="ap/en">Apocalypse</option>
            <option value="ps/en">Planeshift</option>
            <option value="in/en">Invasion</option></optgroup>
        <optgroup label="Masquerade Cycle (English)"><option value="pr/en">Prophecy</option>
            <option value="ne/en">Nemesis</option>
    
            <option value="mm/en">Mercadian Masques</option></optgroup>
        <optgroup label="Artifacts Cycle (English)"><option value="ud/en">Urza's Destiny</option>
            <option value="ul/en">Urza's Legacy</option>
            <option value="us/en">Urza's Saga</option></optgroup>
        <optgroup label="Rath Cycle (English)"><option value="ex/en">Exodus</option>
            <option value="sh/en">Stronghold</option>
            <option value="tp/en">Tempest</option></optgroup>
        <optgroup label="Mirage Cycle (English)"><option value="wl/en">Weatherlight</option>
            <option value="vi/en">Visions</option>
    
            <option value="mr/en">Mirage</option></optgroup>
        <optgroup label="Early Sets (English)"><option value="hl/en">Homelands</option>
            <option value="fe/en">Fallen Empires</option>
            <option value="dk/en">The Dark</option>
            <option value="lg/en">Legends</option>
            <option value="aq/en">Antiquities</option>
            <option value="an/en">Arabian Nights</option></optgroup>
        <optgroup label="Core Set Editions (English)"><option value="m10/en">Magic 2010</option>
            <option value="10e/en">Tenth Edition</option>
    
            <option value="9e/en">Ninth Edition</option>
            <option value="8e/en">Eighth Edition</option>
            <option value="7e/en">Seventh Edition</option>
            <option value="6e/en">Classic Sixth Edition</option>
            <option value="5e/en">Fifth Edition</option>
            <option value="4e/en">Fourth Edition</option>
            <option value="rv/en">Revised Edition</option>
            <option value="un/en">Unlimited Edition</option>
            <option value="be/en">Limited Edition Beta</option>
    
            <option value="al/en">Limited Edition Alpha</option></optgroup>
        <optgroup label="Magic Online (English)"><option value="me3/en">MTGO Masters Edition III</option>
            <option value="me2/en">MTGO Masters Edition II</option>
            <option value="med/en">MTGO Masters Edition</option></optgroup>
        <optgroup label="Reprint Sets (English)"><option value="pvc/en">Duel Decks: Phyrexia vs. The Coalition</option>
            <option value="pds/en">Premium Deck Series: Slivers</option>
            <option value="gvl/en">Duel Decks: Garruk vs. Liliana</option>
            <option value="pch/en">Planechase</option>
            <option value="dvd/en">Duel Decks: Divine vs. Demonic</option>
            <option value="jvc/en">Duel Decks: Jace vs. Chandra</option>
            <option value="ch/en">Chronicles</option></optgroup>
        <optgroup label="Un-Serious Sets (English)"><option value="uh/en">Unhinged</option>
            <option value="ug/en">Unglued</option></optgroup>
        <optgroup label="Beginner Sets (English)">
            <option value="st/en">Starter 1999</option>
            <option value="p3k/en">Portal Three Kingdoms</option>
            <option value="po2/en">Portal Second Age</option>
            <option value="po/en">Portal</option></optgroup>    
    </select>
    <input type=submit value='MACH MIR DAS BOOSTER!!!'>
</form>
    <?php
}
?>
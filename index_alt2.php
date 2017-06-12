<?php
//Konfigurationen

function get_pictures($edition_short) {
    $language="de";
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
        $merk = array();
        $cardid = array_rand($uncommons) ;
        if(!in_array($cardid, $merk)) {
            $merk[]=$cardid;
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
        <optgroup label="Zendikar Cycle"><option value="roe/de">Aufstieg der Eldrazi/Rise of the Eldrazi</option>
            <option value="wwk">Weltenerwachen/Worldwake </option>
            <option value="zen/de">Zendikar</option></optgroup>
        <optgroup label="Shards of Alara"><option value="arb/de">Alara Reborn</option>
            <option value="cfx/de">Conflux</option>
            <option value="ala/de">Fragmente von Alara/Shards of Alara</option></optgroup>
        <optgroup label="Shadowmoor Cycle"><option value="eve/de">Abendkühle/Eventide</option>
            <option value="shm/de">Schattenmoor/Shadowmoor</option></optgroup>
        <optgroup label="Lorwyn Cycle"><option value="mt/de">Morgenluft/Morningtide</option>
            <option value="lw/de">Lorwyn</option></optgroup>
        <optgroup label="Time Spiral Cycle"><option value="fut/de">Blick in die Zukunft/Future Sight</option>
            <option value="pc/de">Weltenchaos/Planar Chaos</option>
            <option value="ts/de">Zeitspirale/Time Spiral</option>
            <option value="tsts/de">Zeitspirale Timeshifted/Time Spiral Timeshifted</option></optgroup>
        <optgroup label="Ice Age Cycle"><option value="cs/de">Kälteeinbruch/Coldsnap</option>
            <option value="ai/de">Allianzen/Alliances</option>
            <option value="ia/de">Eiszeit/Ice Age</option></optgroup>
        <optgroup label="Ravnica Cycle"><option value="di/de">Zwietracht/Dissension</option>
            <option value="gp/de">Gildenbund/Guildpact</option>
            <option value="rav/de">Ravnica: Stadt der Gilden/Ravnica: City of Guilds</option></optgroup>
        <optgroup label="Kamigawa Cycle"><option value="sok/de">Retter von Kamigawa/Saviors of Kamigawa</option>
            <option value="bok/de">Verräter von Kamigawa/Betrayers of Kamigawa</option>
            <option value="chk/de">Meister von Kamigawa/Champions of Kamigawa</option></optgroup>
        <optgroup label="Mirrodin Cycle"><option value="5dn/de">Fünfte Morgenröte/Fifth Dawn</option>
            <option value="ds/de">Nachtstahl/Darksteel</option>
            <option value="mi/de">Mirrodin</option></optgroup>
        <optgroup label="Onslaught Cycle"><option value="sc/de">Plagen/Scourge</option>
            <option value="le/de">Legionen/Legions</option>
            <option value="on/de">Aufmarsch/Onslaught</option></optgroup>
        <optgroup label="Odyssey Cycle"><option value="ju/de">Abrechnung/Judgment</option>
            <option value="tr/de">Qualen/Torment</option>
            <option value="od/de">Odyssee/Odyssey</option></optgroup>
        <optgroup label="Invasion Cycle"><option value="ap/de">Apokalypse/Apocalypse</option>
            <option value="ps/de">Weltenwechsel/Planeshift</option>
            <option value="in/de">Invasion</option></optgroup>
        <optgroup label="Masquerade Cycle"><option value="pr/de">Prophezeihung/Prophecy</option>
            <option value="ne/de">Nemesis</option>
            <option value="mm/de">Merkadische Masken/Mercadian Masques</option></optgroup>
        <optgroup label="Artifacts Cycle"><option value="ud/de">Urzas Schicksal/Urza's Destiny</option>
            <option value="ul/de">Urzas Vermächtnis/Urza's Legacy</option>
            <option value="us/de">Urzas Saga/Urza's Saga</option></optgroup>
        <optgroup label="Rath Cycle"><option value="ex/de">Exodus</option>
            <option value="sh/de">Felsenburg/Stronghold</option>
            <option value="tp/de">Sturmwind/Tempest</option></optgroup>
        <optgroup label="Mirage Cycle"><option value="wl/de">Wetterlicht/Weatherlight</option>
            <option value="vi/de">Visionen/Visions</option>
            <option value="mr/de">Trugbilder/Mirage</option></optgroup>
        <optgroup label="Early Sets"><option value="hl/de">Heimatländer/Homelands</option></optgroup>
        <optgroup label="Core Set Editions"><option value="m10/de">Magic 2010</option>
            <option value="10e/de">Zehnte Edition/Tenth Edition</option>
            <option value="9e/de">Neunte Edition/Ninth Edition</option>
            <option value="8e/de">Achte Edition/Eighth Edition</option>
            <option value="7e/de">Siebte Edition/Seventh Edition</option>
            <option value="6e/de">Sechste Edition/Sixth Edition</option>
            <option value="5e/de">Fünfte Edition/Fifth Edition</option>
            <option value="4e/de">Vierte Edition/Fourth Edition</option>
            <option value="rv/de">Unlimitierte Auflage</option>
            <option value="rvb/de">Limitierte Auflage</option></optgroup>
        <optgroup label="Reprint Sets"><option value="re/de">Renaissance</option></optgroup>
        <optgroup label="Theme Decks"><option value="cstd/de">Kälteeinbruch-Themendecks</option></optgroup>
        <optgroup label="Independent Box Sets"><option value="9eb/de">Neunte Edition Box Set</option>
            <option value="8eb/de">Achte Edition Box Set</option></optgroup>
        <optgroup label="Beginner Sets"><option value="st2k/de">Starter 2000</option>
            <option value="po2/de">Portal Zweites Zeitalter</option>
            <option value="po/de">Portal</option>
            <option value="itp/de">Introductory Two-Player Set</option></optgroup>
        <optgroup label="Tournament Rewards"><option value="grc/de">Gateway</option></optgroup>
        <optgroup label="Media Inserts"><option value="mbp/de">Media Inserts</option></optgroup>

        <!--
                      <optgroup label="Zendikar Cycle">
                      <option value="">Aufstieg der Eldrazi</option>
                      <option value="wwk">Weltenerwachen</option>
                      <option value="">Zendikar</option>
                      </optgroup><optgroup label="Shards of Alara">
                      <option value="">Alara Reborn</option>
                      <option value="">Conflux</option>
                      <option value="">Fragmente von Alara</option>
                      </optgroup><optgroup label="Shadowmoor Cycle">
                      <option value="">Abendk&uuml;hle</option>
                      <option value="">Schattenmoor</option>
                      </optgroup><optgroup label="Lorwyn Cycle">
                      <option value="">Morgenluft</option>
                      <option value="">Lorwyn</option>
                      </optgroup><optgroup label="Time Spiral Cycle">
                      <option value="">Blick in die Zukunft</option>
                      <option value="">Weltenchaos</option>
                      <option value="">Zeitspirale</option>
                      <option value="">Zeitspirale Timeshifted</option>
                      </optgroup><optgroup label="Ice Age Cycle">
                      <option value="">K&auml;lteeinbruch</option>
                      <option value="">Allianzen</option>
                      <option value="">Eiszeit</option>
                      </optgroup><optgroup label="Ravnica Cycle">
                      <option value="">Zwietracht</option>
                      <option value="">Gildenbund/Guildpact</option>
                      <option value="">Ravnica: Stadt der Gilden</option>
                      </optgroup><optgroup label="Kamigawa Cycle">
                      <option value="">Retter von Kamigawa</option>
                      <option value="">Verr&auml;ter von Kamigawa</option>
                      <option value="">Meister von Kamigawa</option>
                      </optgroup><optgroup label="Mirrodin Cycle">
                      <option value="">F&uuml;nfte Morgenr&ouml;te</option>
                      <option value="">Nachtstahl</option>
                      <option value="">Mirrodin</option>
                      </optgroup><optgroup label="Onslaught Cycle">
                      <option value="">Plagen</option>
                      <option value="">Legionen</option>
                      <option value="on">Aufmarsch</option>
                      </optgroup><optgroup label="Odyssey Cycle">
                      <option value="">Abrechnung</option>
                      <option value="">Qualen</option>
                      <option value="">Odyssee</option>
                      </optgroup><optgroup label="Invasion Cycle">
                      <option value="">Apokalypse</option>
                      <option value="">Weltenwechsel</option>
                      <option value="">Invasion</option>
                      </optgroup><optgroup label="Masquerade Cycle">
                      <option value="">Prophezeiung</option>
                      <option value="">Nemesis</option>
                      <option value="">Merkadische Masken</option>
                      </optgroup><optgroup label="Artifacts Cycle">
                      <option value="">Urzas Schicksal</option>
                      <option value="">Ursas Verm&auml;chtnis</option>
                      <option value="">Ursas Saga</option>
                      </optgroup><optgroup label="Rath Cycle">
                      <option value="">Exodus</option>
                      <option value="">Felsenburg</option>
                      <option value="">Sturmwind</option>
                      </optgroup><optgroup label="Mirage Cycle">
                      <option value="">Wetterlicht</option>
                      <option value="">Visionen</option>
                      <option value="">Trugbilder</option>
                      </optgroup><optgroup label="!!!Early Sets!!! von hier an testen">
                      <option value="">Heimatl&auml;nder</option>
                      </optgroup><optgroup label="Core Set Editions">
                      <option value="">Magic 2010</option>
                      <option value="">Zehnte Edition</option>
                      <option value="">Neunte Edition</option>
                      <option value="">Achte Edition</option>
                      <option value="">Siebte Edition</option>
                      <option value="">Sechste Edition</option>
                      <option value="">F&uuml;nfte Edition</option>
                      <option value="">Vierte Edition</option>
                      <option value="">Unlimitierte Auflage</option>
                      <option value="">Limitierte Auflage</option>
                      </optgroup><optgroup label="Reprint Sets">
                      <option value="">Renaissance</option></optgroup>
        <!-- von hier an fehlen einige merkwürdige "Cycles":
        Theme Decks
        Independent Box Sets
        Beginner Sets
        Tournament Rewards
        Media Inserts
        -->
    </select>
    <select name="language">
        <option value="de">Deutsch</option>
        <option value="en">English</option>
    </select>
    <input type=submit value='MACH MIR DAS BOOSTER!!!'>
</form>
    <?php
}
?>
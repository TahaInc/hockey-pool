<?php
        $year = '2020';
        session_start();
        $game_id = $_SESSION['game_id'];

        if(isSet($_POST['status']) && $_POST['status'] == 'loading'){
            if (file_exists('game_list/'.$game_id.'.json')) {
                $game_data = file_get_contents('game_list/'.$game_id.'.json');
                $jsonIterator = new RecursiveIteratorIterator(
                    new RecursiveArrayIterator(json_decode($game_data, TRUE)),
                    RecursiveIteratorIterator::SELF_FIRST);
                foreach ($jsonIterator as $key => $val) {
                    if(is_array($val)) {
                        echo "$key:\n";
                    } else {
                        echo "$key=>$val\n";
                    }
                }
            } else {
                echo "Invalid Game";
            }
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'save'){
            $Users = $_POST['names'];
            $Players = $_POST['players_save'];
            $indexname = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k' ,'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
            $player_list = array();

            if (is_null($Players)){
                for ($i=0; $i < $_POST['player_amount']; $i++) { 
                    $User = array(
                        $Users[$i] => array()
                    );
                    $player_list[$indexname[$i]] = $User;
                }
            } else {
                for ($i=0; $i < $_POST['player_amount']; $i++) { 
                    $User = array(
                        $Users[$i] => $Players[$i]
                    );
                    $player_list[$indexname[$i]] = $User;
                }
            }
            $game_data = fopen('game_list/'.$game_id.'.json', 'w');
            fwrite($game_data, json_encode($player_list, JSON_FORCE_OBJECT));
            fclose($game_data);
            echo "created";
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'PlayerInfo'){
            $player_pts = file_get_contents('https://www.rotowire.com/hockey/tables/stats.php?pos=skater&season='.$year);
            $goalie_pts = file_get_contents('https://www.rotowire.com/hockey/tables/stats.php?pos=goalie&season='.$year);
            $PlayerData = (array_merge(json_decode($player_pts, true),json_decode($goalie_pts, true)));
            foreach($PlayerData as $row)
            {
                foreach($row as $key => $value)
                {
                    if ($key == "player"){
                        //Surname adjustment
                        if ($value == "Mitch Marner") {
                            $PlayerList .= "Mitchell Marner,";
                        } elseif ($value == "Anthony DeAngelo") {
                            $PlayerList .= "Tony DeAngelo,";
                        } else {
                            $PlayerList .= $value.",";
                        }
                    } elseif ($key == "points"){
                        $PointsList .= $value.",";
                    } elseif ($key == "goals"){
                        $GoalsList .= $value.",";
                    } elseif ($key == "assists"){
                        $AssistList .= $value.",";
                    } elseif ($key == "wins"){
                        $WinsList .= $value.",";
                    } elseif ($key == "so"){
                        $SOList .= $value.",";
                    } elseif ($key == "otl"){
                        $OTLList .= $value.",";
                    } elseif ($key == "team"){
                        $team = preg_replace('/[^A-Z ]/', '', $value);
                        if (strlen($team) == 7){
                            $TeamList .= substr($team, 1, 3).",";
                        } else {
                            $TeamList .= substr($team, 1, 2).",";
                        }
                    } elseif ($key == "position"){
                        $PositionList .= $value.",";
                    } elseif ($key == "gp"){
                        $GPList .= $value.",";
                    }
                }
            }
            echo $PlayerList.'*'.$PointsList.'*'.$GoalsList.'*'.$AssistList.'*'.$WinsList.'*'.$SOList.'*'.$OTLList.'*'.$TeamList.'*'.$PositionList.'*'.$GPList;
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'LeagueLeaders'){
            $player_league_leaders = new RecursiveIteratorIterator(new RecursiveArrayIterator(json_decode(file_get_contents('https://api.nhle.com/stats/rest/en/skater/summary?isAggregate=false&isGame=false&sort=%5B%7B%22property%22:%22points%22,%22direction%22:%22DESC%22%7D,%7B%22property%22:%22goals%22,%22direction%22:%22DESC%22%7D,%7B%22property%22:%22assists%22,%22direction%22:%22DESC%22%7D%5D&start=0&limit=100&factCayenneExp=gamesPlayed%3E=1&cayenneExp=gameTypeId=2%20and%20seasonId%3C=20192020%20and%20seasonId%3E=20192020'), true)),RecursiveIteratorIterator::SELF_FIRST);
            $goalie_league_leaders = new RecursiveIteratorIterator(new RecursiveArrayIterator(json_decode(file_get_contents('https://api.nhle.com/stats/rest/en/goalie/summary?isAggregate=false&isGame=false&sort=%5B%7B%22property%22:%22wins%22,%22direction%22:%22DESC%22%7D,%7B%22property%22:%22goals%22,%22direction%22:%22DESC%22%7D,%7B%22property%22:%22assists%22,%22direction%22:%22DESC%22%7D%5D&start=0&limit=100&factCayenneExp=gamesPlayed%3E=1&cayenneExp=gameTypeId=2%20and%20seasonId%3C=20192020%20and%20seasonId%3E=20192020'), true)),RecursiveIteratorIterator::SELF_FIRST);

            foreach($player_league_leaders as $row)
            {
                foreach($row as $key => $value)
                {
                    if ($key == null) {
                    } elseif ($key == "skaterFullName"){
                        $TopPlayerList .= $value.",";
                    } elseif ($key == "points"){
                        $TopPointsList .= $value.",";
                    }
                }
            }
            foreach($goalie_league_leaders as $row)
            {
                foreach($row as $key => $value)
                {
                    if ($key == null) {
                    } elseif ($key == "goalieFullName"){
                        $TopPlayerList .= $value.",";
                    } elseif ($key == "points"){
                        $PointsAmount += $value;
                    } elseif ($key == "otLosses"){
                        $PointsAmount += $value;
                    } elseif ($key == "shutouts"){
                        $PointsAmount += ($value*3);
                    } elseif ($key == "wins"){
                        $PointsAmount += ($value*2);
                        $TopPointsList .= $PointsAmount.",";
                        $PointsAmount = 0;
                    }
                }
            }
            echo $TopPlayerList."*".$TopPointsList;
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'TeamInfo'){
            $team_stats = file_get_contents('https://www.rotowire.com/hockey/tables/standings.php?type=league');
            $TeamData = json_decode($team_stats, true);
            foreach($TeamData as $row)
            {
                foreach($row as $key => $value)
                {
                    if ($key == "gp"){
                        $TeamGPList .= $value.",";
                    } elseif ($key == "statsCode"){
                        $TeamCodeList .= $value.",";
                    }
                }
            }
            echo $TeamGPList.'*'.$TeamCodeList;
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'InjuryInfo'){
            echo file_get_contents('https://www.rotowire.com/hockey/tables/injury-report.php?team=ALL&pos=ALL');
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'ScheduleInfo'){
            echo file_get_contents('https://www.rotowire.com/hockey/nhl-lineups.php');
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'LastNightInfo'){
            $LastNightDate = date('Y-m-d',strtotime("-1 days"));
            $LastNightGames = file_get_contents('https://statsapi.web.nhl.com/api/v1/schedule?date='.$LastNightDate);
            $LastNightGamesJson = json_decode($LastNightGames, TRUE);
            $LastNightGamesAmount = $LastNightGamesJson['totalItems'];

            for ($i = 0; $i < $LastNightGamesAmount; $i++) {
                $GameInfo = file_get_contents('https://statsapi.web.nhl.com/api/v1/game/'.$LastNightGamesJson['dates'][0]['games'][$i]['gamePk'].'/boxscore');
                $GameInfoJson = json_decode($GameInfo, TRUE);
                $GamePlayerInfo = (array_merge($GameInfoJson['teams']['away']['players'],$GameInfoJson['teams']['home']['players']));
                
                $GamePlayerInfoJson = new RecursiveIteratorIterator(
                    new RecursiveArrayIterator($GamePlayerInfo),
                    RecursiveIteratorIterator::SELF_FIRST);

                foreach ($GamePlayerInfoJson as $key => $value) {
                    if ($key === "fullName") {
                        echo $value."\n";
                    } elseif ($key === "assists") {
                        echo $value."\n";
                    } elseif ($key === "goals") {
                        echo $value."\n";
                    }
                }
            }
            echo "name\n0\n";
        }
    
    
    ?>
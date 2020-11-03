<?php
        session_start();
        $game_id = $_SESSION['game_id'];

        $player_pts = file_get_contents('https://www.rotowire.com/hockey/tables/stats.php?pos=skater&season=2019');
        $goalie_pts = file_get_contents('https://www.rotowire.com/hockey/tables/stats.php?pos=goalie&season=2019');

        $PlayerData = (array_merge(json_decode($player_pts, true),json_decode($goalie_pts, true)));

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
            
            $player_list = array();
            for ($i=0; $i < $_POST['player_amount']; $i++) { 
                $User = array(
                    $Users[$i] => $Players[$i]
                );
                array_push($player_list, $User);
            }
            $game_data = fopen('game_list/'.$game_id.'.json', 'w');
            fwrite($game_data, json_encode($player_list, JSON_FORCE_OBJECT));
            fclose($game_data);
            echo "created";
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'get_player_list'){
            foreach($PlayerData as $row)
            {
                foreach($row as $key => $value)
                {
                    if ($key == "player"){
                        echo $value.",";
                    }
                }
            }
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'get_points_list'){
            foreach($PlayerData as $row)
            {
                foreach($row as $key => $value)
                {
                    if ($key == "points"){
                        echo $value.",";
                    }
                }
            }
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'get_goal_list'){
            foreach($PlayerData as $row)
            {
                foreach($row as $key => $value)
                {
                    if ($key == "goals"){
                        echo $value.",";
                    }
                }
            }
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'get_assist_list'){
            foreach($PlayerData as $row)
            {
                foreach($row as $key => $value)
                {
                    if ($key == "assists"){
                        echo $value.",";
                    }
                }
            }
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'get_wins_list'){
            foreach($PlayerData as $row)
            {
                foreach($row as $key => $value)
                {
                    if ($key == "wins"){
                        echo $value.",";
                    }
                }
            }
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'get_team_list'){
            foreach($PlayerData as $row)
            {
                foreach($row as $key => $value)
                {
                    if ($key == "team"){
                        $team = preg_replace('/[^A-Z ]/', '', $value);
                        if (strlen($team) == 7){
                            echo substr($team, 1, 3).",";
                        } else {
                            echo substr($team, 1, 2).",";
                        }
                    }
                }
            }
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'get_position_list'){
            foreach($PlayerData as $row)
            {
                foreach($row as $key => $value)
                {
                    if ($key == "position"){
                        echo $value.",";
                    }
                }
            }
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'get_gp_list'){
            foreach($PlayerData as $row)
            {
                foreach($row as $key => $value)
                {
                    if ($key == "gp"){
                        echo $value.",";
                    }
                }
            }
        } elseif (isSet($_POST['status']) && $_POST['status'] == 'get_injury_list'){
            echo file_get_contents('https://widgets.sports-reference.com/wg.fcgi?css=1&site=hr&url=%2Ffriv%2Finjuries.cgi&div=div_injuries');
        }
    ?>
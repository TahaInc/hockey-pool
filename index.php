<?php
    session_start();
    if (isSet($_POST['status']) && $_POST['status'] == 'create_clicked') {
        if (file_exists('game_list/'.$_POST['game_request'].'.json')) {
            echo "false";
        } else {
            echo "true";
        }
    } elseif(isSet($_POST['status']) && $_POST['status'] == 'create_confirmed'){
        $Players = $_POST['players'];
        $player_list = array();
        $indexname = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k' ,'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        for ($i=1; $i < $_POST['player_amount']; $i++) { 
            $User = array(
                $Players[$i] => array()
            );
            $player_list[$indexname[$i-1]] = $User;
        }
        $game_data = fopen('game_list/'.$_POST['game_id_create'].'.json', 'w');
        fwrite($game_data, json_encode($player_list, JSON_FORCE_OBJECT));
        fclose($game_data);
        $_SESSION['game_id'] = $_POST['game_id_create'];
        echo "created";
    } elseif (isSet($_POST['status']) && $_POST['status'] == 'load_clicked') {
        if (file_exists('game_list/'.$_POST['game_id_login'].'.json')) {
            $_SESSION['game_id'] = $_POST['game_id_login'];
            echo "true";
        } else {
            echo "false";
        }
    }
?>
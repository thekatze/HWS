<?php
    //Creating a PDO Connection
    $config = parse_ini_file("../config/config.ini");
    function create_pdo() {
        $config = $GLOBALS['config'];
        return new PDO('mysql:host='.$config['dbHost'].';dbname='.$config['dbName'].';charset=utf8', $config['dbUser'], $config['dbPass']);
    }
?>

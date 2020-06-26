<?php


/*------------------*/
/*  Connexion BDD   */
/*------------------*/
$pdo = new PDO(
    'mysql:host=localhost;dbname=repertoire;charset=utf8',
    'root',
    '',
    array(
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    )
);

require_once('function.php');
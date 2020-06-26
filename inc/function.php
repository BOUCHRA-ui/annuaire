<?php

function executeRequete($requete, $params = array() ){

    if( !empty($params) ){
        // on neutralise les balises HTML
        foreach($params as $index => $valeur){
            // on réécrit la valeur à la même position dans le tableau
            $params[$index] = htmlspecialchars($valeur);
        }
    }

    global $pdo; // on globalise $pdo dans l'espace de la fonction
    
    $resultats = $pdo->prepare($requete);
    $resultats->execute($params);
    return $resultats; 
}
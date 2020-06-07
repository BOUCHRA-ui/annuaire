<?php

// Inclusion de la connexion
// Fonction executeRequete dispo
require_once('inc/init.php');


/*------------------*/
/*  Suppression     */
/*  action : del    */
/*------------------*/
if (isset($_GET['action']) && $_GET['action'] == 'del' && !empty($_GET['id']) && is_numeric($_GET['id'])) {

    executeRequete("DELETE FROM annuaire WHERE id_annuaire=:id", array('id' => $_GET['id']));
    header('location:' . $_SERVER['PHP_SELF']);
    exit();
}


/*----------------------------------------*/
/* Chargement d'un contact pour édition   */
/* action : edit                          */
/*----------------------------------------*/
if (isset($_GET['action']) && $_GET['action'] == 'edit' && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    // je vais chercher les informations du contact par son id
    $edit = executeRequete("SELECT * FROM annuaire WHERE id_annuaire = :id", array('id' => $_GET['id']));
    // si j'ai bien 1 ligne en retour
    if ($edit->rowCount() == 1) {
        // je charge les infos de la table dans un tableau associatif $contact
        $contact = $edit->fetch();        
    }
}

/*------------------------------------*/
/* Traitement du POST (insert/edit)   */
/*------------------------------------*/
if (!empty($_POST)) {
    // var_dump($_POST);

    // contrôles
    $erreurs = array();

    // champs vides
    $champsvides = 0;
    foreach ($_POST as $valeur) {
        if (empty($valeur)) $champsvides++;
    }

    if ($champsvides > 0) {
        $erreurs[] = "Il manque $champsvides information(s)";
    }

    // longueur et nature du numéro de téléphone
    if (iconv_strlen($_POST['telephone']) != 10 || !is_numeric($_POST['telephone'])) {
        $erreurs[] = "Numéro de téléphone incorrect : 10 chiffres requis";
    }

    // longueur et nature du code postal
    if (iconv_strlen($_POST['codepostal']) != 5 || !is_numeric($_POST['codepostal'])) {
        $erreurs[] = "Code postal incorrect : 5 chiffres requis";
    }

    if (empty($erreurs)) {

        if (!empty($_GET['id'])) {
            // mode update car j'ai un numero de contact dans l'url
            $requete = "UPDATE annuaire SET nom = :nom, prenom = :prenom, telephone = :telephone, profession = :profession,ville = :ville, codepostal = :codepostal, adresse = :adresse, date_de_naissance = :date_de_naissance, sexe = :sexe, description = :description WHERE id_annuaire = :id";
            $_POST['id'] = $_GET['id'];

        } else {
            // mode insertion 
            $requete = "INSERT INTO annuaire VALUES (NULL, :nom, :prenom, :telephone, :profession, :ville, :codepostal, :adresse, :date_de_naissance, :sexe, :description)";
        }

        executeRequete($requete, $_POST);
        header('location:' . $_SERVER['PHP_SELF']);
        exit();
    }
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annuaire</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <header class="bg-dark text-light text-center py-4">
        <h1>Annuaire</h1>
    </header>
    <main class="container py-4">

        <div class="row">
            <div class="col">

                <h2>Ajouter/Editer un contact</h2>

                <!-- Messages d'erreur éventuels -->
                <?php if (!empty($erreurs)) : ?>
                    <div class="alert alert-danger"><?= implode('<br>', $erreurs) ?></div>
                <?php endif ?>

                <!-- FORMULAIRE -->
                <form action="" method="post">

                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="sexe">Sexe</label>
                            <select id="sexe" name="sexe" class="form-control">
                                <option value="m">Monsieur</option>
                                <option <?= (
                                            (isset($_POST['sexe']) && $_POST['sexe'] == 'f')
                                            || (isset($contact['sexe']) && $contact['sexe'] == 'f')) ? 'selected' : '' ?> value="f">Madame</option>
                            </select>

                        </div>
                        <div class="form-group col-md-5">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom" class="form-control" value="<?= $_POST['prenom'] ?? $contact['prenom'] ?? '' ?>"></div>
                        <div class="form-group col-md-5">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" class="form-control" value="<?= $_POST['nom'] ?? $contact['nom'] ?? '' ?>"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="telephone">Téléphone</label>
                            <input type="number" id="telephone" name="telephone" class="form-control" value="<?= $_POST['telephone'] ?? $contact['telephone'] ?? '' ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="profession">Profession</label>
                            <input type="text" id="profession" name="profession" class="form-control" value="<?= $_POST['profession'] ?? $contact['profession'] ?? '' ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="date_de_naissance">Date de naissance</label>
                            <input type="date" id="date_de_naissance" name="date_de_naissance" class="form-control" value="<?= $_POST['date_de_naissance'] ?? $contact['date_de_naissance'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="adresse">Adresse</label>
                            <input type="text" id="adresse" name="adresse" class="form-control" value="<?= $_POST['adresse'] ?? $contact['adresse'] ?? '' ?>">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="codepostal">Code postal</label>
                            <input type="number" id="codepostal" name="codepostal" class="form-control" value="<?= $_POST['codepostal'] ?? $contact['codepostal'] ?? '' ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="ville">Ville</label>
                            <input type="text" id="ville" name="ville" class="form-control" value="<?= $_POST['ville'] ?? $contact['ville'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3" class="form-control"><?= $_POST['description'] ?? $contact['description'] ?? '' ?></textarea>
                    </div>

                    <input type="submit" value="Enregistrer" class="btn btn-primary">
                </form>

                <!-- AFFICHAGE -->
                <h2>Liste des contacts</h2>

                <table class="table table-bordered table-striped table-responsive-md">
                    <tr>
                        <th>Id</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Tél</th>
                        <th>Profession</th>
                        <th>Adresse</th>
                        <th>Date de naissance</th>
                        <th>Sexe</th>
                        <th>Description</th>
                        <th colspan="2">Actions</th>
                    </tr>
                    <?php

                    $resultats = executeRequete("SELECT * FROM annuaire");
                    if ($resultats->rowCount() > 0) :
                        while ($contact = $resultats->fetch()) :
                    ?>
                            <tr>
                                <td><?= $contact['id_annuaire'] ?></td>
                                <td><?= $contact['nom'] ?></td>
                                <td><?= $contact['prenom'] ?></td>
                                <td><?= $contact['telephone'] ?></td>
                                <td><?= $contact['profession'] ?></td>
                                <td><?= $contact['adresse'] . ' ' .  $contact['codepostal'] . ' ' .  $contact['ville'] ?></td>
                                <td><?= $contact['date_de_naissance'] ?></td>
                                <td><?= $contact['sexe'] ?></td>
                                <td><?= $contact['description'] ?></td>
                                <td><a href="?action=edit&id=<?= $contact['id_annuaire'] ?>"> &#9999; </a></td>
                                <td><a class="confirm" href="?action=del&id=<?= $contact['id_annuaire'] ?>"> &#128465; </a></td>
                            </tr>

                        <?php
                        endwhile;
                    else :
                        ?>
                        <tr>
                            <td colspan="13">Pas encore de contacts</td>
                        </tr>
                    <?php
                    endif;
                    ?>
                </table>

            </div>
        </div>
    </main>

    <footer class="bg-dark text-light text-center py-4">
        &copy; <?= date('Y') ?> - Annuaire
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="assets/scripts.js"></script>

</body>

</html>
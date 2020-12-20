<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title> Insertion de de données SQL </title>
</head>

<body class="w3-container w3-border w3-margin">
    <div class="w3-center">
        <h1 class="">Insertion de données SQL</h1>
    </div>

    <br>
    <div class="w3-center">
        <?php

        if (isset($_GET['idCapteur']) and isset($_GET['valeur'])) {
            $mysqli = new mysqli('localhost:3306', 'user', 'password', 'database');
            if ($mysqli->connect_error) {
                exit(1);
            }
            $query = sprintf("INSERT INTO mesure ( idCapteur, valeur, date) VALUES (%d, %d, NOW())", $_GET['idCapteur'], $_GET['valeur']);
            var_dump($query);

            if ($mysqli->query($query)) {
                //$mysqli->query($query);
                printf("<p>Insertion reussie : La base données a bien été mise à jour</p>");
            } else {
                printf("<p>Erreur: le(s) valeur(s) de(s) variable(s) transmise(s) [idCatpeur, valeur et date] sont(est) au mauvais format</p>");
                printf("<p>Exemples: idCapteur = 20, valeur = 90, la date est autmatiquement inserée</p>");
            }
            $mysqli->close();
        } else {
            printf("<p>Erreur: les Variables transmise sont inexistantes dans la base de données</p>");
            printf("<p>Les bonnees variables : idCatpeur et valeur</p>");
        }
        ?>

    </div>
</body>

</html>
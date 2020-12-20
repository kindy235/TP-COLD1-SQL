<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title> Récuperation de données SQL </title>
</head>

<body class="w3-container w3-border w3-margin">
    <div class="w3-center">
        <h1 class="">Récuperation de données SQL</h1>
    </div>

    <br>
    <div class="w3-center">
        <?php

		$mysqli = new mysqli('localhost', 'db_user', 'password', 'database');
		if ($mysqli->connect_error) {
			exit(1);
		}

		$mysqli->set_charset('utf8');
		$entete = true;
		$sql = $_REQUEST['sql'];
		var_dump($sql);
		if ($res = $mysqli->query($sql)) {
			printf("<table class=\"w3-table-all w3-card-4\">");
			while ($row = $res->fetch_assoc()) {
				if ($entete) {
					printf("<tr>");
					foreach ($row as $key => $val) {
						printf("<th>%s &nbsp &nbsp &nbsp <th/>\n", $key);
						$entete = false;
					}
					printf("<tr/>");
				}

				printf("<tr>");
				foreach ($row as $key => $val) {
					printf("<td>%s &nbsp &nbsp &nbsp <td/>\n", $val);
				}
				printf("<tr/>");
			}
			printf("<table/>");
			$res->free();
		} else {
			printf("ERREUR : %s !!!\n", $mysqli->error);
		}
		$mysqli->close();
		?>
    </div>
</body>

</html>
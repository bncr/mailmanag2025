<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Recherche</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<style>
		body {
			background-image: url('image/recherche1.png');
			background-size: cover;
			background-attachment: fixed;
		}
	</style>
</head>
<body>

<?php
$bdd = new PDO('mysql:host=127.0.0.1;dbname=mailmanag2025;charset=utf8','root','');

// Valeur par défaut : départ
$table = "send_mail";
$columns = [
    "id" => "code_send",
    "num" => "num_send",
    "date" => "date_send",
    "etab" => "destina",
    "objet" => "textear",
    "pdf" => "crd_pdf"
];

// Si l'utilisateur choisit "Arrivée"
if (isset($_GET['age']) && $_GET['age'] == "receiv_mail") {
    $table = "receiv_mail";
    $columns = [
        "id" => "code_receiv",
        "num" => "num_receiv",
        "date" => "date_receiv",
        "etab" => "expediteur",
        "objet" => "objet_receiv",
        "pdf" => "cra_pdf"
    ];
}

// Requête par défaut
$sql = "SELECT {$columns['etab']} AS etab,
               {$columns['num']} AS num,
               {$columns['date']} AS datec,
               {$columns['objet']} AS objet,
               {$columns['pdf']} AS pdf
        FROM $table
        ORDER BY {$columns['id']} DESC";
$articles = $bdd->query($sql);

// Recherche
if (isset($_GET['q']) && !empty($_GET['q'])) {
    $q = htmlspecialchars($_GET['q']);
    $sql = "SELECT {$columns['etab']} AS etab,
                   {$columns['num']} AS num,
                   {$columns['date']} AS datec,
                   {$columns['objet']} AS objet,
                   {$columns['pdf']} AS pdf
            FROM $table
            WHERE {$columns['etab']} LIKE ?
            ORDER BY {$columns['id']} DESC";
    $articles = $bdd->prepare($sql);
    $articles->execute(["%$q%"]);

    if ($articles->rowCount() == 0) {
        $sql = "SELECT {$columns['etab']} AS etab,
                       {$columns['num']} AS num,
                       {$columns['date']} AS datec,
                       {$columns['objet']} AS objet,
                       {$columns['pdf']} AS pdf
                FROM $table
                WHERE CONCAT({$columns['etab']}, {$columns['objet']}) LIKE ?
                ORDER BY {$columns['id']} DESC";
        $articles = $bdd->prepare($sql);
        $articles->execute(["%$q%"]);
    }
}
?>

<!-- Formulaire de recherche -->
<form method="GET" action="" class="p-3 bg-light shadow rounded">
   <input type="search" name="q" placeholder="Recherche..." class="form-control mb-2" />

   <div class="form-check form-check-inline">
       <input type="radio" id="age1" name="age" value="receiv_mail"
           class="form-check-input" <?= (isset($_GET['age']) && $_GET['age']=="receiv_mail")?"checked":""; ?>>
       <label for="age1" class="form-check-label">Arriv&eacute;e</label>
   </div>
   <div class="form-check form-check-inline">
       <input type="radio" id="age2" name="age" value="send_mail"
           class="form-check-input" <?= (!isset($_GET['age']) || $_GET['age']=="send_mail")?"checked":""; ?>>
       <label for="age2" class="form-check-label">D&eacute;part</label>
   </div>

   <button type="submit" class="btn btn-primary btn-sm">Valider</button>
</form>

<h2 class="mt-4">R&eacute;sultats de recherche</h2>
<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th><?= ($table=="send_mail") ? "Etablissement" : "Exp&eacute;diteur"; ?></th>
            <th>Code courrier</th>
            <th>Date courrier</th>
            <th>Objet</th>
            <th>Fichier PDF</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($articles->rowCount() > 0): ?>
        <?php while($a = $articles->fetch()): ?>
        <tr>
            <td><?= htmlspecialchars($a['etab']); ?></td>
            <td><?= htmlspecialchars($a['num']); ?></td>
            <td><?= htmlspecialchars($a['datec']); ?></td>
            <td><?= htmlspecialchars($a['objet']); ?></td>
            <td><a href="<?= htmlspecialchars($a['pdf']); ?>" target="_blank">Ouvrir</a></td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="5">Aucun r&eacute;sultat pour: <?= htmlspecialchars($_GET['q'] ?? ""); ?>...</td></tr>
    <?php endif; ?>
    </tbody>
</table>


</html>

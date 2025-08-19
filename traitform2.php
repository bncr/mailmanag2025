<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "mailmanag2025";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

$num_receiv = $_POST['num_receiv'];
$date_receiv = $_POST['date_receiv'];
$expediteur = $_POST['expediteur'];
$objet_receiv = $_POST['objet_receiv'];
$cra_pdf = $_POST['cra_pdf'];

$upload_dir = "uploads/";
function saveFile($file, $namePrefix) {
    global $upload_dir;
    $target_file = $upload_dir . $namePrefix . "_" . basename($file["name"]);
    move_uploaded_file($file["tmp_name"], $target_file);
    return $target_file;
}

$cv = saveFile($_FILES["cra_pdf"], "rcvd");

$sql = "INSERT INTO receiv_mail (num_receiv, date_receiv, expediteur, objet_receiv, cra_pdf)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issss", $num_receiv, $date_receiv, $expediteur, $objet_receiv, $cv);
//sssdd
if ($stmt->execute()) {
	
   // echo "Inscription réussie !";
	header("Location:pageform2.html");
} else {
    echo "Erreur: " . $stmt->error;
}

$stmt->close();
$conn->close();


?>
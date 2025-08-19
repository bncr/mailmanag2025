<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "mailmanag2025";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

$num_send = $_POST['num_send'];
$date_send = $_POST['date_send'];
$destina = $_POST['destina'];
$intention = $_POST['intention'];
$copie = $_POST['copie'];
$textear = $_POST['textear'];
$crd_pdf = $_POST['crd_pdf'];

$upload_dir = "uploads/";
function saveFile($file, $namePrefix) {
    global $upload_dir;
    $target_file = $upload_dir . $namePrefix . "_" . basename($file["name"]);
    move_uploaded_file($file["tmp_name"], $target_file);
    return $target_file;
}

$cv = saveFile($_FILES["crd_pdf"], "send");



$sql = "INSERT INTO send_mail (num_send, date_send, destina, intention, copie, textear, crd_pdf)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issssss", $num_send, $date_send, $destina, $intention, $copie, $textear, $cv);

if ($stmt->execute()) {
   header("Location:pageform1.html");
} else {
    echo "Erreur: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>




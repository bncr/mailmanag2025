
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "mailmanag2025";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

$user_name = $_POST['user_name'];
$pass_word = $_POST['pass_word'];
//* $pass_word = password_hash($_POST['pass_word'], PASSWORD_DEFAULT);
$e_mail = $_POST['e_mail'];



$sql = "INSERT INTO admin_login (user_name, pass_word, e_mail)
        VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $user_name, $pass_word, $e_mail);


if ($stmt->execute()) {
   header("Location:index.html");
} else {
    echo "Erreur: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>


</html> 




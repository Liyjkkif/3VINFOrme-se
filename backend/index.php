<?php
session_start();

$conn = new mysqli("localhost", "root", "", "turma_db");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$matricula = $_POST['matricula'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($matricula) || empty($email) || empty($password)) {
    die("Preencha todos os campos.");
}

$sql = "SELECT * FROM users WHERE matricula = ? AND email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $matricula, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        $_SESSION['user'] = [
            'matricula' => $user['matricula'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        if ($user['role'] === 'representante') {
            header("Location: ../frontend/src/pages/rep_dashboard.php");
            exit();
        } else {
            header("Location: ../frontend/src/pages/student_dashboard.php");
            exit();
        }
    } else {
        echo "Senha incorreta.";
    }
} else {
    echo "Usuário não encontrado.";
}

$conn->close();
?>

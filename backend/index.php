<?php
session_start();

// Conexão com o banco
$conn = new mysqli("localhost", "root", "", "turma_db");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Pega os dados do formulário
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Validação simples
if (empty($email) || empty($password)) {
    die("Preencha todos os campos.");
}

// Busca o usuário pelo username
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verifica a senha
    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        echo "Login realizado com sucesso!";
        // Redirecionar para página protegida (ex: dashboard.php)
        // header("Location: dashboard.php");
        // exit();
    } else {
        echo "Senha incorreta.";
    }
} else {
    echo "Usuário não encontrado.";
}

$conn->close();
?>

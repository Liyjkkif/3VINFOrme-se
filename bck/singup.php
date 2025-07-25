<?php
session_start();

$conn = new mysqli("localhost", "root", "", "turma_db");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$matricula = $_POST['matricula'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['password'] ?? '';

if (empty($matricula) || empty($email) || empty($senha)) {
    die("Preencha todos os campos.");
}

$sql = "SELECT * FROM users WHERE matricula = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $matricula, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die("Usuário com essa matrícula ou e-mail já existe.");
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$role = 'aluno';

$sql = "INSERT INTO users (matricula, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $matricula, $email, $senha_hash, $role);

if ($stmt->execute()) {
    echo "Cadastro realizado com sucesso!";
} else {
    echo "Erro ao cadastrar usuário.";
}

$conn->close();
?>
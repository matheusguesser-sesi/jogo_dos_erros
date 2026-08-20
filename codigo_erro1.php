<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "crud_aula";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nome === '' || $email === '') {
        die("Preencha nome e e-mail.");
    }

    $sql = "INSERT INTO usuarios (nome, email) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação do INSERT: " . $conn->error);
    }

    $stmt->bind_param("ss", $nome, $email);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
}

if (isset($_GET['excluir'])) {
    $id = filter_input(INPUT_GET, 'excluir', FILTER_VALIDATE_INT);

    if ($id === false || $id <= 0) {
        die("ID inválido para exclusão.");
    }

    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação do DELETE: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($id === false || $id <= 0 || $nome === '' || $email === '') {
        die("Dados inválidos para edição.");
    }

    $sql = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação do UPDATE: " . $conn->error);
    }

    $stmt->bind_param("ssi", $nome, $email, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit;
}

$usuarioEditar = null;
if (isset($_GET['editar'])) {
    $idEditar = filter_input(INPUT_GET, 'editar', FILTER_VALIDATE_INT);
    if ($idEditar !== false && $idEditar > 0) {
        $sqlEditar = "SELECT id, nome, email FROM usuarios WHERE id = ?";
        $stmtEditar = $conn->prepare($sqlEditar);
        if ($stmtEditar) {
            $stmtEditar->bind_param("i", $idEditar);
            $stmtEditar->execute();
            $resultadoEditar = $stmtEditar->get_result();
            $usuarioEditar = $resultadoEditar->fetch_assoc();
            $stmtEditar->close();
        }
    }
}

$sql = "SELECT id, nome, email FROM usuarios ORDER BY id DESC";
$resultado = $conn->query($sql);
if ($resultado === false) {
    die("Erro ao buscar usuários: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>CRUD de Usuários</title>
</head>

<body>

<h1>Cadastro de Usuários</h1>

<form method="POST" action="">
    <label>Nome:</label>
    <input type="text" name="nome" required>

    <br><br>

    <label>E-mail:</label>
    <input type="email" name="email" required>

    <br><br>

    <button type="submit" name="cadastrar">Cadastrar</button>
</form>

<?php if ($usuarioEditar): ?>
    <h2>Editar usuário</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?= htmlspecialchars((string) $usuarioEditar['id'], ENT_QUOTES, 'UTF-8') ?>">

        <label>Nome:</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($usuarioEditar['nome'], ENT_QUOTES, 'UTF-8') ?>" required>

        <br><br>

        <label>E-mail:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($usuarioEditar['email'], ENT_QUOTES, 'UTF-8') ?>" required>

        <br><br>

        <button type="submit" name="editar">Salvar edição</button>
    </form>
<?php endif; ?>

<h2>Usuários cadastrados</h2>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>E-mail</th>
        <th>Ações</th>
    </tr>

    <?php while ($usuario = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars((string) $usuario['id'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>
                <a href="index.php?editar=<?= (int) $usuario['id'] ?>">Editar</a>
                |
                <a href="index.php?excluir=<?= (int) $usuario['id'] ?>" onclick="return confirm('Deseja realmente excluir este usuário?');">Excluir</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

</body>

</html>
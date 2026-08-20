<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "crud_aula";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexao: " . $conn->connect_error);
}

// CADASTRAR
if (isset($_POST['cadastrar'])) {

    $nome = $_POST['nome'];
    $email = $_POST['email'];

    $sql = "INSERT INTO usuarios (nome, email) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ss", $nome, $email);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

// EXCLUIR
if (isset($_GET['excluir'])) {

    $id = filter_input(INPUT_GET, 'excluir', FILTER_VALIDATE_INT);

    if ($id) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    header("Location: index.php");
    exit;
}

// EDITAR
if (isset($_POST['editar'])) {

    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    $sql = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ssi", $nome, $email, $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

$usuarioEdicao = null;
if (isset($_GET['editar'])) {

    $idEdicao = filter_input(INPUT_GET, 'editar', FILTER_VALIDATE_INT);

    if ($idEdicao) {
        $sql = "SELECT id, nome, email FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idEdicao);
        $stmt->execute();
        $usuarioEdicao = $stmt->get_result()->fetch_assoc();
    }
}

// BUSCAR USUÁRIOS
$sql = "SELECT id, nome, email FROM usuarios ORDER BY id DESC";
$resultado = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
<meta charset="UTF-8">
<title>CRUD de Usuários</title>
</head>

<body>

<h1>Cadastro de Usuários</h1>

<form method="POST">

<?php if ($usuarioEdicao) { ?>
<input type="hidden" name="id" value="<?= $usuarioEdicao['id'] ?>">
<?php } ?>

<label>Nome:</label>
<input type="text" name="nome" value="<?= $usuarioEdicao ? htmlspecialchars($usuarioEdicao['nome']) : '' ?>" required>

<br><br>

<label>E-mail:</label>
<input type="email" name="email" value="<?= $usuarioEdicao ? htmlspecialchars($usuarioEdicao['email']) : '' ?>" required>

<br><br>

<?php if ($usuarioEdicao) { ?>
<button type="submit" name="editar">
Salvar edição
</button>
<?php } else { ?>
<button type="submit" name="cadastrar">
Cadastrar
</button>
<?php } ?>

</form>

<h2>Usuários cadastrados</h2>

<table border="1">

<tr>
<th>ID</th>
<th>Nome</th>
<th>E-mail</th>
<th>Ações</th>
</tr>

<?php while ($usuario = $resultado->fetch_assoc()) { ?>

<tr>

<td>
<?= $usuario['id'] ?>
</td>

<td>
<?= $usuario['nome'] ?>
</td>

<td>
<?= $usuario['email'] ?>
</td>

<td>

<a href="index.php?editar=<?= $usuario['id'] ?>">
Editar
</a>

<a href="index.php?excluir=<?= $usuario['id'] ?>"
   onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
Excluir
</a>

</td>

</tr>

<?php } ?>

</table>

</body>

</html>
<?php
session_start();

// 1. Inicializa a lista padrão de filmes (com a lista dele + o seu filme novo)
if (!isset($_SESSION['filmes'])) {
    $_SESSION['filmes'] = [
        ["titulo" => "A Origem", "genero" => "Ficção Científica", "ano" => 2010, "diretor" => "Christopher Nolan"],
        ["titulo" => "O Poderoso Chefão", "genero" => "Crime / Drama", "ano" => 1972, "diretor" => "Francis Ford Coppola"],
        ["titulo" => "Interestelar", "genero" => "Ficção Científica", "ano" => 2014, "diretor" => "Christopher Nolan"],
        ["titulo" => "Clube da Luta", "genero" => "Drama / Thriller", "ano" => 1999, "diretor" => "David Fincher"],
        ["titulo" => "Forrest Gump", "genero" => "Drama / Romance", "ano" => 1994, "diretor" => "Robert Zemeckis"],
        ["titulo" => "Matrix", "genero" => "Ficção Científica / Ação", "ano" => 1999, "diretor" => "Lana e Lilly Wachowski"],
        ["titulo" => "O Senhor dos Anéis: O Retorno do Rei", "genero" => "Fantasia / Aventura", "ano" => 2003, "diretor" => "Peter Jackson"],
        ["titulo" => "Pulp Fiction", "genero" => "Crime / Drama", "ano" => 1994, "diretor" => "Quentin Tarantino"],
        ["titulo" => "O Cavaleiro das Trevas", "genero" => "Ação / Crime", "ano" => 2008, "diretor" => "Christopher Nolan"],
        ["titulo" => "Schindler's List", "genero" => "Drama / História", "ano" => 1993, "diretor" => "Steven Spielberg"],
        ["titulo" => "OldBoy", "genero" => "Drama / Ação", "ano" => 2003, "diretor" => "Park Chan-wook"],
        ["titulo" => "Devoradores de Estrelas", "genero" => "Ficção Científica / Aventura", "ano" => 2026, "diretor" => "Phil Lord e Christopher Miller"] // Seu filme adicionado
    ];
}

// 2. Processa o formulário de CADASTRO
if (isset($_POST['btn_enviar'])) {
    $novoFilme = [
        "titulo"  => $_POST['titulo'],
        "genero"  => $_POST['genero'],
        "ano"     => $_POST['ano'],
        "diretor" => $_POST['diretor']
    ];

    $_SESSION['filmes'][] = $novoFilme;

    header("Location: filmes.php");
    exit();
}

// 3. Processa o formulário de EDIÇÃO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $_SESSION['filmes'][$id] = [
        "titulo"  => $_POST['titulo'],
        "genero"  => $_POST['genero'],
        "ano"     => $_POST['ano'],
        "diretor" => $_POST['diretor'],
    ];
    header('Location: filmes.php');
    exit;
}

// 4. Processa o SEU formulário de DELETE (Sua Tarefa)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletar_id'])) {
    unset($_SESSION['filmes'][$_POST['deletar_id']]);
    $_SESSION['filmes'] = array_values($_SESSION['filmes']); // Reorganiza as chaves do array
    header('Location: filmes.php');
    exit;
}

// Sincroniza a variável para renderizar na tabela
$filmes = $_SESSION['filmes'];
$editando = $_GET['id'] ?? null; 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Filmes</title>
</head>
<body>

    <h1>Lista de Filmes</h1>

    <table border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>Título</th>
                <th>Gênero</th>
                <th>Ano</th>
                <th>Diretor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filmes as $index => $filme): ?>
            <tr>
                <?php if ($editando !== null && $editando == $index): ?>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $index ?>">
                        <td><?= $index + 1 ?></td>
                        <td><input type="text" name="titulo" value="<?= htmlspecialchars($filme['titulo']) ?>"></td>
                        <td><input type="text" name="genero" value="<?= htmlspecialchars($filme['genero']) ?>"></td>
                        <td><input type="number" name="ano" value="<?= $filme['ano'] ?>"></td>
                        <td><input type="text" name="diretor" value="<?= htmlspecialchars($filme['diretor']) ?>"></td>
                        <td>
                            <button type="submit">Salvar</button>
                            <a href="filmes.php">Cancelar</a>
                        </td>
                    </form>
                <?php else: ?>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($filme["titulo"]) ?></td>
                    <td><?= htmlspecialchars($filme["genero"]) ?></td>
                    <td><?= htmlspecialchars($filme["ano"]) ?></td>
                    <td><?= htmlspecialchars($filme["diretor"]) ?></td>
                    <td>
                        <a href="filmes.php?id=<?= $index ?>">Editar</a>
                        
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="deletar_id" value="<?= $index ?>">
                            <button type="submit" style="color: red; margin-left: 10px;">Deletar</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <h2>Cadastrar Novo Filme</h2>
    <form method="POST" action="">
        <input type="text" name="titulo" placeholder="Título do Filme" required>
        <input type="text" name="genero" placeholder="Gênero" required>
        <input type="number" name="ano" placeholder="Ano de lançamento" required>
        <input type="text" name="diretor" placeholder="Nome do Diretor" required>
        
        <button type="submit" name="btn_enviar">Adicionar à lista</button>
    </form>
    <hr>
</body>
</html>
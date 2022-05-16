<?php

/**
 * Exemplo de upload de arquivo com PHP.
 * Este arquivo é apenas um exemplo que não faz parte da aplicação completa.
 * Pode ser removido depois que o assunto for entendido.
 * 
 * Referências: https://www.w3schools.com/php/php_file_upload.asp
 */

// Se formulário foi enviado...
if ($_SERVER["REQUEST_METHOD"] == "POST") :

    echo '<pre>';

    // $_POST[] recebe dados dos campos normais do formulário.
    echo "\n" . '$_POST: ';
    print_r($_POST);

    // $_FILES[] recebe os metadados do arquivo enviado.
    echo '<hr>$_FILES: ';
    print_r($_FILES);

    // Se o arquivo for uma imagem, obtemos as dimensões dela com getimagesize()[].
    echo "<hr>getimagesize(): ";
    print_r(getimagesize($_FILES['arquivo']['tmp_name']));

    echo '</pre>';

    // 4) Salva arquivo enviado no servidor, na pasta 'arquivos'.
    move_uploaded_file(
        $_FILES['arquivo']['tmp_name'], // Origem -> pasta 'tmp'
        'arquivos/' . $_FILES['arquivo']['name'] // Destino -> pasta 'arquivos/'
    );

endif;

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Upload de Arquivo</title>
</head>

<body>

    <!-- 1) enctype="..." obrigatório para upload de arquivos. -->
    <form action="upload.php" method="post" enctype="multipart/form-data">

        <!-- 2) Campos normais do formulário, se necessários. -->
        <p>Nome: <input type="text" name="nome" value="joca"></p>
        <p>E-mail: <input type="email" name="email" value="teste@teste"></p>

        <!-- 3) Campo que pesquisa o arquivo a ser enviado (upload) no computador. -->
        <p>Arquivo: <input type="file" name="arquivo"></p>

        <p><button type="submit">Enviar</button></p>

    </form>

</body>

</html>
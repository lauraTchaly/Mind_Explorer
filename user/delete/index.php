<?php

/**
 * Arquivo que faz a configuração incial da página.
 * Por exemplo, conecta-se ao banco de dados.
 * 
 * A superglobal "$_SERVER['DOCUMENT_ROOT']" retorna o caminho da raiz do site no Windows.
 * Ex.: C:\xampp\htdocs 
 *     Referências:
 *     → https://www.w3schools.com/php/php_includes.asp
 *     → https://www.php.net/manual/pt_BR/function.include.php
 *     → https://www.php.net/manual/pt_BR/language.variables.superglobals.php
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

/***********************************************
 * Seus códigos PHP desta página iniciam aqui! *
 ***********************************************/

// Se usuário NÃO está logado, envia para 'login'.
if (!isset($_COOKIE['user'])) header('Location: /user/login/');

// Variáveis principais.
$error = '';
$feedback = false;

// Se formulário foi enviado...
if ($_SERVER["REQUEST_METHOD"] == "POST") :

    // Recebe a 'senha' do formulário, sanitiza e valida.
    $password = trim(htmlspecialchars($_POST['password']));
    if (!preg_match('/(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=\S+$).{7,32}/', $password)) :
        $error .= '<li>A senha atual está fora do padrão;</li>';
    else :
        // Testa a senha no banco de dados.
        $sql = <<<SQL

SELECT user_id FROM users 
WHERE user_id = '{$user['user_id']}' 
    AND user_password = SHA1('{$password}');

SQL;
        $res = $conn->query($sql);
        if ($res->num_rows != 1)
            $error .= '<li>A senha atual não confere;</li>';

    endif;

    // Se não ocorreram erros.
    if ($error === '') :

        // SQL que atualiza o banco de dados.
        $sql = <<<SQL

    UPDATE users SET user_status = 'deleted'
    WHERE user_id = '{$user['user_id']}'
    
    SQL;

        // Executa a query.
        $conn->query($sql);

        // Apaga cookie.
        setcookie('user', '', -1, '/');

        // Redireciona para a home.
        header('Location: /');

    else :

        // Formada mensagem de erro.
        $error = '<h3>Oooops!</h3><p>Ocorreram erros:</p><ul>' . $error . '</ul>';

    endif;

endif;

/************************************************
 * Seus códigos PHP desta página terminam aqui! *
 ************************************************/

/**
 * Variável que define o título desta página.
 * Essa variável é usada no arquivo "_header.php".
 * OBS: para a página inicial (index.php) usaremos o 'slogan' do site.
 *     Referências:
 *     → https://www.w3schools.com/php/php_variables.asp
 *     → https://www.php.net/manual/pt_BR/language.variables.basics.php
 */
$title = "Cancelar Cadastro";

/**
 * Inclui o cabeçalho da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_header.php');

?>

<section>

    <h2>Cancelar Cadastro</h2>
    <p class="text-center" style="color: grey"><i class="fa-solid fa-user-xmark fa-fw fa-4x"></i></p>
    <p>Tem certeza que deseja cancelar seu cadastro? Se cancelar, não será mais possível acessar o conteúdo exclusivo.</p>
    <p>Clique no botão abaixo para cancelar o cadastro.</p>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" name="edituser">

        <?php if ($error != '') echo '<div class="error">' . $error . '</div>'; ?>

        <p>
            <label for="password">Digite a senha atual:</label>
            <input type="password" name="password" id="password" required pattern="^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=\S+$).{7,32}$" class="valid password" autocomplete="off">
            <button type="button" id="passToggle" data-field="password"><i class="fa-solid fa-eye fa-fw"></i></button>
        <div class="form-help">
            <ul>
                <li>A senha é usada para confirmar que a conta é sua.</li>
            </ul>
        </div>
        </p>

        <button class="btn-logout" type="submit">
            <i class="fa-solid fa-user-xmark fa-fw"></i>
            &nbsp;Cancelar cadastro
        </button>

    </form>

</section>

<aside>

    <h3>Barra Lateral</h3>
    <p>Coloque algum conteúdo útil aqui como fizemos <a href="/ler/?id=1">nesta página</a>. Por exemplo:</p>
    <ul>
        <li>Links para a seção "Sobre"</li>
        <li>Etc...</li>
    </ul>

</aside>

<?php

/**
 * Inclui o rodapé da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_footer.php');

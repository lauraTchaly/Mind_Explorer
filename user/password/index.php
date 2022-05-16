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

    // Recebe a 'senha' do formulário, sanitiza e valida usando REGEX.
    $newpassword = trim(htmlspecialchars($_POST['newpassword']));
    if (!preg_match('/(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=\S+$).{7,32}/', $newpassword))
        $error .= '<li>A nova senha está fora do padrão;</li>';

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

        // SQL para atualizar a senha.
        $sql = <<<SQL

UPDATE users SET user_password = SHA1('{$password}')
WHERE user_id = '{$user['user_id']}'
        AND user_status = 'on';

SQL;

        // Executa a query
        $conn->query($sql);

        // Feedback para usuário.
        $feedback = true;

    // Se ocorreram erros...
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
$title = "Trocar senha.";

/**
 * Inclui o cabeçalho da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_header.php');

?>

<section>

    <h2>Trocar senha</h2>

    <?php
    // Se o cadastyro foi finalizado com sucesso...
    if ($feedback) :
    ?>

        <div class="block-center">

            <h3>Oba!</h3>
            <p>Sua senha foi atualizada com sucesso!</p>
            <hr class="divider">
            <div class="user-links">

                <a href="/user/profile/">
                    <i class="fa-solid fa-address-card fa-fw"></i>
                    Ver Perfil
                </a>

                <a href="/">
                    <i class="fa-solid fa-house-chimney fa-fw"></i>
                    Página inicial
                </a>

            </div>

        </div>

        <script>
            // JavaScript que bloqueia reenvio do form caso a página seja recarregada.
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>

    <?php else : ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" name="newpass">

            <?php if ($error != '') echo '<div class="error">' . $error . '</div>'; ?>

            <p>
                <label for="newpassword">Nova senha:</label>
                <input type="password" name="newpassword" id="newpassword" required pattern="^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=\S+$).{7,32}$" class="valid password" autocomplete="off">
                <button type="button" id="newPassToggle" data-field="newpassword"><i class="fa-solid fa-eye fa-fw"></i></button>
            <div class="form-help">
                <ul>
                    <li>Senha de teste (apague isso!) &rarr; Qw3rtyui0P</li>
                    <li>Mínimo de 7 e máximo de 32 caracteres;</li>
                    <li>Pelo menos uma letra maiúscula de A até Z;</li>
                    <li>Pelo menos um número de 0 à 9.</li>
                </ul>
            </div>
            </p>

            <hr class="divider">

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

            <p>
                <button type="submit">Enviar</button>
            </p>

        </form>

    <?php endif; ?>

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

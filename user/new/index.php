<?php

/**
 * Arquivo que faz a configuração incial da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

/***********************************************
 * Seus códigos PHP desta página iniciam aqui! *
 ***********************************************/

// Se usuário já está logado, redireciona para a página de perfil dele.
if (isset($_COOKIE['user'])) header('Location: /user/profile/');

// Cria e inicializa as variáveis usadas no script
$name = $email = $birth = $profile = $password = $photo = $error = '';
$feedback = false;

// Se formulário foi enviado...
if ($_SERVER["REQUEST_METHOD"] == "POST") :

    // Recebe o campo 'nome' do formulário, sanitiza e valida.
    $name = trim(htmlspecialchars($_POST['name']));
    if (strlen($name) < 3)
        $error .= '<li>Seu nome está muito curto;</li>';

    // Recebe o campo 'email' do formulário, sanitiza e valida.
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) :

        $error .= '<li>Seu e-mail está inválido;</li>';

    else :

        // Verifica se e-mail já está cadastrado.
        $res = $conn->query("SELECT user_id FROM users WHERE user_email = '{$email}';");
        if ($res->num_rows != 0)
            $error .= '<li>Este e-mail já está em uso;</li>';

    endif;

    // Recebe a 'data de nascimento' do formulário, sanitiza e valida.
    $birth = trim(htmlspecialchars($_POST['birth']));
    if ($birth > date('Y-m-d', time() - 284018400))
        $error .= '<li>Você deve ter mais de 9 anos;</li>';

    // Recebe o 'perfil' do formulário e sanitiza.
    $profile = trim(htmlspecialchars($_POST['profile']));

    // Recebe a 'senha' do formulário, sanitiza e valida.
    $password = trim(htmlspecialchars($_POST['password']));
    if (!preg_match('/(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=\S+$).{7,32}/', $password))
        $error .= '<li>A senha está fora do padrão;</li>';

    // Se enviou uma foto de perfil...
    if ($_FILES['photo']['size'] > 0) :

        // Recebe a 'foto' do formulário, e faz upload.
        $array_photo = upload_photo('/user/img/');

        // Se ocorreu algum erro com a foto...
        if ($array_photo['error']) {

            // Gera mensagem de erro.
            $error .= '<li>' . $array_photo['error'] . '</li>';

            // Carrega uma foto padrão.
            $photo = '/user/img/generic_user.png';

            // Se não ocorreu erro no upload...
        } else {

            // Obtém URL da foto.
            $photo = $array_photo['url'];
        }

    // Se não enviou uma foto...
    else :

        // Carrega uma foto padrão.
        $photo = '/user/img/generic_user.png';
    endif;

    // Se não ocorreram erros ainda...
    if ($error === '') :

        // Prepara SQL
        $sql = <<<SQL

INSERT INTO users (
    user_name,
    user_email,
    user_birth,
    user_photo,
    user_profile,
    user_password,
    user_type
) VALUES 
(
    '{$name}',
    '{$email}',
    '{$birth}',
    '{$photo}',
    '{$profile}',
    SHA1('{$password}'),
    'user'
);

SQL;

        // Executa SQL
        $res = $conn->query($sql);

        // Feedback
        $feedback = true;

    // Se ocorreram erros...
    else :

        // Formada mensagem de erro.
        $error = '<h3>Oooops! Ocorreram erros:</h3><ul>' . $error . '</ul>';

    endif;

endif;

/************************************************
 * Seus códigos PHP desta página terminam aqui! *
 ************************************************/

/**
 * Variável que define o título desta página.
 */
$title = "Cadastro...";

/**
 * Inclui o cabeçalho da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_header.php');

?>

<section>

    <h2>Cadastre-se</h2>

    <p class="text-center" style="color:grey"><i class="fa-solid fa-user-plus fa-fw fa-4x"></i></p>

    <?php
    // Se o cadastyro foi finalizado com sucesso...
    if ($feedback) :
    ?>

        <div class="block-center">

            <h3>Oba!</h3>
            <p>Seu cadastro foi feito com sucesso!</p>
            <p>Logue-se para acessar o conteúdo exclusivo do site.</p>
            <hr class="divider">
            <p class="user-links">
                <a href="/user/login/"><i class="fa-solid fa-right-to-bracket fa-fw"></i> Entrar / Login</a>
                <a href="/"><i class="fa-solid fa-home fa-fw"></i> Início</a>
            </p>

        </div>

        <script>
            // JavaScript que bloqueia reenvio do form caso a página seja recarregada.
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>

    <?php else : ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" name="newuser" enctype="multipart/form-data">

            <p>Preencha todos os campos com atenção para se cadastrar e ter acesso aos recursos exclusivos.</p>

            <?php if ($error != '') echo '<div class="error">' . $error . '</div>'; ?>

            <p>
                <label for="name">Nome completo:</label>
                <input type="text" name="name" id="name" required minlength="3" class="valid" autocomplete="off" value="<?php echo $name ?>">
            </p>

            <p>
                <label for="email">E-mail:</label>
                <input type="text" name="email" id="email" required class="valid" autocomplete="off" value="<?php echo $email ?>">
            </p>

            <p>
                <label for="birth">Data de nascimento:</label>
                <input type="date" name="birth" id="birth" required class="valid" autocomplete="off" value="<?php echo $birth ?>">
            </p>

            <p>
                <label for="photo">Imagem de perfil: <small>(Opcional)</small></label>
                <input type="file" name="photo" id="photo" class="valid" accept="image/jpeg, image/jpg, image/png">

            <div class="form-help">
                <ul>
                    <li>Imagem quadrada no formato PNG ou JPG;</li>
                    <li>Tamanho mínimo de 64px x 64px x 1MB ;</li>
                    <li>Tamanho máximo de 515px x 512px x 1MB.</li>
                </ul>
            </div>
            </p>

            <p>
                <label for="profile">Prefil resumido: <small>(Opcional)</small></label>
                <textarea name="profile" id="profile" class="valid" autocomplete="off"><?php echo $profile ?></textarea>
            <div class="form-help">
                <ul>
                    <li>Escreva sobre você, de forma resumida.</li>
                </ul>
            </div>
            </p>

            <p>
                <label for="password">Senha:</label>
                <input type="password" name="password" id="password" required pattern="^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=\S+$).{7,32}$" class="valid password" autocomplete="off">
                <button type="button" id="passToggle" data-field="password"><i class="fa-solid fa-eye fa-fw"></i></button>
            <div class="form-help">
                <ul>
                    <li>Senha de teste (apague isso!) &rarr; Qw3rtyui0P</li>
                    <li>Mínimo de 7 e máximo de 32 caracteres;</li>
                    <li>Pelo menos uma letra maiúscula de A até Z;</li>
                    <li>Pelo menos um número de 0 à 9.</li>
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

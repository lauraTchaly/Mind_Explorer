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

// Se usuário não está logado, redireciona para a página inicial.
if (!isset($_COOKIE['user'])) header('Location: /');

// Vairáveis do script.
$modal_photo = false;
$error = '';

// Primeiro nome do usuário
$nome = explode(' ', $user['user_name'])[0];

// Obtém a idade do usuário
$idade = get_years_old($user['user_birth']);

// Formata perfil para exibição
$html = <<<HTML

<div class="author-meta">

    <h2>{$nome}</h2>
    <div class="user-photo">
        <img src="{$user['user_photo']}" alt="{$user['user_name']}">
        <a href="/user/profile/?photo" title="Alterar foto de perfil."><i class="fa-solid fa-pen-to-square fa-fw"></i></a>
    </div>
    &nbsp;
    <ul>
        <li><strong>{$user['user_name']}</strong></li>
        <li>E-mail: <a href="mailto:{$user['user_email']}" target="_blank">{$user['user_email']}</a></li>
        <li>Nasceu em {$user['birth_br']} ({$idade} anos)</li>
        <li>{$user['user_profile']}</li>
    </ul>
    <hr class="divider">
    <div class="user-links">

        <a href="/user/edit/">
            <i class="fa-solid fa-address-card fa-fw"></i>
            Editar Perfil
        </a>

        <a href="/user/password/">
            <i class="fa-solid fa-key fa-fw"></i>
            Trocar senha
        </a>

    </div>

    <hr class="divider">

    <div class="user-links">

        <a href="/user/logout/">
            <i class="fa-solid fa-right-from-bracket fa-fw"></i>
            Logout / Sair
        </a>

        <a href="/user/delete/">
            <i class="fa-solid fa-user-xmark fa-fw"></i>
            Cancelar
        </a>

    </div>
 
</div>

HTML;

// Se pediu para trocar a foto...
if ($_SERVER['QUERY_STRING'] === 'photo') :

    // Se formulário foi enviado...
    if ($_SERVER["REQUEST_METHOD"] == "POST") :

        // Se enviou uma foto de perfil...
        if ($_FILES['photo']['size'] > 0) :

            // Recebe a 'foto' do formulário, e faz upload.
            $array_photo = upload_photo('/user/img/');

            // Se ocorreu algum erro com a foto...
            if ($array_photo['error']) {

                // Gera mensagem de erro.
                $error .= '<li>' . $array_photo['error'] . '</li>';

                // Se não ocorreu erro no upload...
            } else {

                // Obtém URL da foto.
                $photo = $array_photo['url'];

                // Atualiza banco de dados.
                $sql = <<<SQL

UPDATE users SET user_photo = '{$photo}'
WHERE user_id = '{$user['user_id']}'
    AND user_status = 'on';

SQL;
                $conn->query($sql);

                // SQL para obter TODOS os dados do usuário e gerar o cookie novamente.
                $sql = <<<SQL

SELECT *,
    DATE_FORMAT(user_birth, '%d/%m/%Y') AS birth_br
FROM `users`
WHERE user_email = '{$user['user_email']}'
    AND user_status = 'on';
                
SQL;

                // Executa a query.
                $res = $conn->query($sql);

                // Obtém dados do usuário.
                $user_data = $res->fetch_assoc();

                // Apaga a senha.
                unset($user_data['user_password']);

                // Adiciona expiração do cookie.
                $user_data['expires'] = $user['expires'];

                // Grava o cookie no navegador
                setcookie(
                    'user',                 // nome do cookie criado
                    serialize($user_data),  // valor do cookie
                    $user['expires'],       // tempo de vida do cookie em segundos
                    '/'                     // Domínio do cookie "/" de localhost
                );

                // Recarrega a página.
                header('Location: ' . $_SERVER['SCRIPT_NAME']);
            }

        // Se não enviou uma foto...
        else :

            // Carrega uma foto padrão.
            $photo = '/user/img/generic_user.png';
        endif;

    else :

        $modal_photo = true;

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
$title = "Perfil.";

/**
 * Inclui o cabeçalho da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_header.php');

?>

<section>

    <?php if ($error !== '') : ?>

        <div class="error">
            <h3>Oooops!</h3>
            <p>Ocorreram erros que impedem a atualização do seu cadastro:</p>
            <ul>
                <?php echo $error ?>
            </ul>
        </div>

    <?php endif; ?>

    <?php echo $html ?>

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
// Exibe o modal com formulário para upload da foto.
if ($modal_photo) :
?>

    <!-- Cria modal -->
    <div id="myModal" class="modal">

        <!-- Conteúdo do modal -->
        <div class="modal-content">
            <span class="close" id="btnClose">&times;</span>
            <h3>Foto de Perfil</h3>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?photo" name="newphoto" enctype="multipart/form-data">

                <p>
                    <label for="photo">Selecione a nova foto.</label>
                    <input type="file" name="photo" id="photo" class="valid" accept="image/jpeg, image/jpg, image/png">

                <div class="form-help">
                    <ul>
                        <li>Imagem quadrada no formato PNG ou JPG;</li>
                        <li>Tamanho mínimo de 64px x 64px x 1MB ;</li>
                        <li>Tamanho máximo de 515px x 512px x 1MB.</li>
                    </ul>
                </div>
                </p>

                <button type="submit">Enviar</button>
            </form>

        </div>

    </div>

    <script>
        // Bloqueia reenvio do form caso a página seja recarregada.
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        // Abre o modal ao carregá-lo.
        myModal.style.display = "block";

        // Ao clicar no X, fecha o modal.
        btnClose.onclick = closeModal;

        // Ao cliar em qualquer lugar do modal, fecha o modal.
        window.onclick = function(event) {
            if (event.target == myModal) closeModal();
        }

        // Fecha o modal e recarrega a página.
        function closeModal() {
            myModal.style.display = "none";
            location.href = window.location.pathname;
        }
    </script>

<?php
endif;
?>

<?php

/**
 * Inclui o rodapé da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_footer.php');

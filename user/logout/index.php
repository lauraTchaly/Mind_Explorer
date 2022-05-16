<?php

/**
 * Arquivo que faz a configuração incial da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

/***********************************************
 * Seus códigos PHP desta página iniciam aqui! *
 ***********************************************/

// Se usuário não está logado, redireciona para a página inicial.
if(!isset($_COOKIE['user'])) header('Location: /');

// Se existe a variável 'logout' no URL da página...
if ($_SERVER['QUERY_STRING'] === 'logout') {

    // Apagar o cookie, colocando o tempo de vida negativo (-1)
    setcookie('user', '', -1, '/');

    // Redirecionar para a 'home'
    header('Location: /');
}

/************************************************
 * Seus códigos PHP desta página terminam aqui! *
 ************************************************/

/**
 * Variável que define o título desta página.
 */
$title = "Logout...";

/**
 * Inclui o cabeçalho da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_header.php');

?>

<section>

    <h2>Logout / Sair</h2>
    <p class="text-center" style="color: grey"><i class="fa-solid fa-right-from-bracket fa-fw fa-4x"></i></p>
    <p>Se você sair do aplicativo agora, terá que entrar novamente para ter acesso ao conteúdo exclusivo.</p>
    <p>Clique no botão abaixo para sair.</p>
    <p class="text-center">
        <button class="btn-logout" type="button" onclick="location.href = '?logout'">
            <i class="fa-solid fa-right-from-bracket fa-fw"></i>
            Logout / Sair
        </button>
    </p>

</section>

<aside>

    <h3>Lateral</h3>
    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Officia, aperiam corporis culpa consequatur iusto.</p>

</aside>

<?php

/**
 * Inclui o rodapé da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_footer.php');

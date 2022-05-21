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
$title = "- Sobre...";

/**
 * Inclui o cabeçalho da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_header.php');

?>

<section>
    <div class = "sobre-post">
        <div class = "sobre-post_img">
            <img src = "/usuários_img/natan2.JPG">
</div>
<div class ="sobre-post_info">
    <div class = "sobre-spot_date">
          
        </div>
        <h1 class = " sobre-post_title"> Natan dos Santos Coelho</h1>
        <p class= "sobre-post_text">
        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
        </p>
    </div>
</div>

<div class = "sobre-post">
        <div class = "sobre-post_img">
            <img src = "/usuários_img/1.jpeg">
</div>
<div class ="sobre-post_info">
    <div class = "sobre-spot_date">
          
        </div>
        <h1 class = " sobre-post_title"> Laura Tchaly Manes dos Santos</h1>
        <p class= "sobre-post_text">
        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
        </p>
    </div>
</div>

<div class = "sobre-post">
        <div class = "sobre-post_img">
            <img src = "/usuários_img/sandro2.JPG">
</div>
<div class ="sobre-post_info">
    <div class = "sobre-spot_date">
          
        </div>
        <h1 class = " sobre-post_title"> Sandro Cesar Dantas Pereira</h1>
        <p class= "sobre-post_text">
        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
        </p>
    </div>
</div>


   
</section>

<aside>

    <?php

    // Obtém os artigos mais visitados para a variável $mv.
    $mv = mostViewed();

    // Se existem artigos mais visitados, exibe eles...
    if ($mv) echo "<h3>Artigos Mais Vistos</h3>{$mv}";
    ?>

</aside>

<?php

/**
 * Inclui o rodapé da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_footer.php');

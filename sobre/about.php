<?php

/**
 * Arquivo que faz a configuração incial da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_config2.php');

/***********************************************
 * Seus códigos PHP desta página iniciam aqui! *
 ***********************************************/

// Variável que armazena todos os artigos para exibição no HTML.
$abt = '';

// SQL que obtém todos os artigos.
$sql = <<<SQL

SELECT about_id, about_name, about_email, about_birthday, about_cell, about_specialties
    FROM about
SQL;

// Executa a query --> '$res' contém os artigos encontrados.
$res = $conn->query($sql);

// Abre a lista de artigos.
$abt = '<div class="items">' . "\n";

// Loop que obtém cada registro de '$res'
while ($abt = $res->fetch_assoc()) :

    // Formata HTML de saída
    $abt .= <<<HTML

        <div class="item" onclick="location.href='/ler/?id={$abt['about_id']}'">
            <div class="thumb" style="background-image: url('{$abt['about_thumb']}')" title="Imagem de {$abt['about_name']}"></div>
            <div class="body">
                <h4>{$abt['about_name']}</h4>
                <span>{$abt['about_specialties']}</span>
            </div>
        </div>

HTML;

endwhile;

// Fecha a lista de artigos.
$abt .= '</div>';

/************************************************
 * Seus códigos PHP desta página terminam aqui! *
 ************************************************/

/**
 * Variável que define o título desta página.
 */
$title = "Explorando a mente humana...";

/**
 * Inclui o cabeçalho da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_header.php');

?>

<section>

    <?php

    // Exibe todos os artigos.
    echo $abt;

    ?>

</section>

<aside>

    <?php

    // Obtém os artigos mais visitados para a variável $mv.
    $mv = mostViewed();

    // Se existem artigos mais visitados, exibe eles...
    if ($mv) echo "<h3>Mais visitados</h3>{$mv}";
    ?>

</aside>

<?php

/**
 * Inclui o rodapé da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_footer.php');

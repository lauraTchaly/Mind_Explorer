<?php

/**
 * Arquivo que faz a configuração incial da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

/**
 * Variável que define o título desta página.
 */
$title = "Quem tem fome tem pressa...";

/***********************************************
 * Seus códigos PHP desta página iniciam aqui! *
 ***********************************************/

// Exibe feedback sobre um comentário enviado.
$comment_send = false;

// Lista de comentários
$comment_list = '';

// Obtém o ID do artigo da URL da página. 
if (isset($_GET['id'])) $id = intval($_GET['id']);
else $id = 0;

// Se está tentando acessar de forma incorreta, retorna para a index.
if ($id === 0) header('Location: /index.php');

// Monta a query que obtém o artigo
$sql = <<<SQL

SELECT *,
	    -- DATE_FORMAT(art_date, '%d/%m/%Y às %H:%i') AS date_br,
        DATE_FORMAT(art_date, '%d/%m/%Y') AS date_br,
        DATE_FORMAT(user_birth, '%d/%m/%Y') AS birth_br
    FROM `articles`
INNER JOIN `users` ON art_author = user_id
WHERE art_id = '{$id}'
    AND art_status = 'on'
    AND art_date <= NOW();

SQL;

// Executa a query
$res = $conn->query($sql);

// Se não retornou UM (1) artigo, retorna para a index.
if ($res->num_rows != 1) header('Location: /index.php');

// Tudo certo, vamos obter os dados do registro obtido
$artigo = $res->fetch_assoc();

// dump($artigo);

// Formata HTML para o navegador
$html_article = <<<HTML

<h2>{$artigo['art_title']}</h2>

<div class="author-date">
    Por {$artigo['user_name']} em {$artigo['date_br']}.
</div>

<div>{$artigo['art_content']}</div>

HTML;

// Primeiro nome do autor
$nome = explode(' ', $artigo['user_name'])[0];

// Obtém a idade do autor
$idade = get_years_old($artigo['user_birth']);

// Formata HTML para o autor
$html_author = <<<HTML

<div class="author-meta">

    <img src="{$artigo['user_photo']}" alt="{$artigo['user_name']}">
    <h3>{$nome}</h3>
    <ul>
        <li><strong>{$artigo['user_name']}</strong></li>
        <li>E-mail: <a href="mailto:{$artigo['user_email']}" target="_blank">{$artigo['user_email']}</a></li>
        <li>Nasceu em {$artigo['birth_br']} ({$idade} anos)</li>
        <li>{$artigo['user_profile']}</li>
    </ul>

</div>

HTML;

/**
 * Verifica se autor tem mais artigos.
 * Se tiver, obtém até 4 de forma aleatória.
 * E, não pega o artigo atual.
 */
$sql = <<<SQL

SELECT art_id, art_title, art_intro 
FROM `articles`
WHERE art_author = '{$artigo['user_id']}'
    AND art_id != '{$artigo['art_id']}'
	AND art_status = 'on'
    AND art_date <= NOW()
ORDER BY RAND()
LIMIT 4;

SQL;

// Executa a query
$res = $conn->query($sql);

// Se tem mais artigos dete author...
if ($res->num_rows > 0) :

    // Exibe a chamada para os artigos encontrados.
    $html_author .= <<<HTML

<div class="author-articles">

    <hr class="divider">

    <h3>+ Artigos de {$nome}</h3>

HTML;

    // Loop para pegar todos os artigos recebidos
    while ($mais_artigos = $res->fetch_assoc()) :

        // Monta lista de artigos
        $html_author .= <<<HTML

    <div class="author-article" onclick="location.href='/ler/?id={$mais_artigos['art_id']}'">
        <h4>{$mais_artigos['art_title']}</h4>
        <small>{$mais_artigos['art_intro']}</small>
    </div>

HTML;

    endwhile;

    // Fecha lsita de artigos
    $html_author .= "</div>";

endif;

/***************
 * Comentários *
 ***************/

// Obtém todos os comentários deste artigo.
$sql = <<<SQL

SELECT comments.*, users.user_name, users.user_photo,
DATE_FORMAT(cmt_date, '%d/%m/%Y às %H:%i') AS cmt_date_br
 FROM comments
 INNER JOIN users ON cmt_author = user_id
WHERE cmt_article = '{$id}'
AND cmt_status = 'on'
ORDER BY cmt_date DESC;

SQL;

// Executa a query
$res = $conn->query($sql);

// Se existem comentários...
if ($res->num_rows > 0) :

    // Loop para listar comentários.
    while ($cmt = $res->fetch_assoc()) :

        $comment_list .= <<<HTML
     
     <div class="comment">
        <div class="comment-meta">
            Por {$cmt['user_name']} em {$cmt['cmt_date_br']}.
        </div>
        {$cmt['cmt_comment']}
     </div>
        
HTML;

    endwhile;

// Se não existem comentários...
else :

    // Convida para comentar.
    $comment_list = '<p class="text-center">Nenhum comentário ainda! Seja a(o) primeira(o) a comentar.</p>';
endif;

// Se usuário está logado, exibe caixa de comentários no final do artigo.
if (isset($_COOKIE['user']))
    $comment_form = true;
else
    $comment_form = false;

// Se usuário comentou...
if ($_SERVER["REQUEST_METHOD"] == "POST") :

    // Recebe o comentário e sanitiza
    $comment = trim(htmlspecialchars($_POST['comment']));

    // Verifica se comentário está preenchido.
    if ($comment !== '') :

        // Gera query para o banco de dados
        $sql = <<<SQL

INSERT INTO comments (
    cmt_article,
    cmt_author,
    cmt_comment
) VALUES (
    '{$id}',
    '{$artigo['user_id']}',
    '{$comment}'
);

SQL;

        // Executa a query
        $conn->query($sql);

        // Feedback do comentário
        $comment_send = true;

    endif;

endif;

// Atualiza contador de visualizações do artigo
$views = intval($artigo['art_views']) + 1;
$sql = "UPDATE articles SET art_views = '{$views}' WHERE art_id = '{$artigo['art_id']}'";
$conn->query($sql);

/************************************************
 * Seus códigos PHP desta página terminam aqui! *
 ************************************************/

/**
 * Inclui o cabeçalho da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_header.php');

?>

<section>

    <?php
    // Exibe o conteúdo do artigo completo
    echo $html_article;
    ?>

    <hr class="divider">

    <?php if ($comment_form) : ?>

        <h3>Comente:</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>" method="post">

            <p>
                <textarea name="comment" id="comment" required></textarea>
                <button type="submit">Comentar</button>
            </p>

        </form>

        <hr class="divider">

    <?php endif; ?>

    <h3>Comentários:</h3>

    <?php echo $comment_list; ?>

</section>

<aside>

    <?php
    // Exibe os dados do autor do artigo
    echo $html_author;
    ?>

</aside>

<?php
// Se enviou um comentário...
if ($comment_send) :
?>

    <!-- Cria modal -->
    <div id="myModal" class="modal">

        <!-- Conteúdo do modal -->
        <div class="modal-content">
            <span class="close" id="btnClose">&times;</span>
            <h3>Oba!</h3>
            <p>Seu comentário foi enviado com sucesso!</p>
            <p>Atualize a página para vê-lo...</p>
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
            location.reload();
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

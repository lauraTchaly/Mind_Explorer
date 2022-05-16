<?php

/**
 * Arquivo que faz a configuração incial da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_config.php');

/***********************************************
 * Seus códigos PHP desta página iniciam aqui! *
 ***********************************************/

// Variáveis do script
$name = $email = $subject = $message = $error = '';
$feedback = false;

// Processa o formulário, somente se ele foi enviado...
if ($_SERVER["REQUEST_METHOD"] == "POST") :

    // Recebe nome, sanitiza e valida.
    $name = trim(htmlspecialchars($_POST['name']));
    if (strlen($name) < 3)
        $error .= '<li>Seu nome está muito curto;</li>';

    // Recebe o campo 'email' do formulário, sanitiza e valida.
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $error .= '<li>Seu e-mail está inválido;</li>';

    // Recebe o assunto, sanitiza e valida.
    $subject = trim(htmlspecialchars($_POST['subject']));
    if (strlen($subject) < 5)
        $error .= '<li>O assunto está muito curto;</li>';

    // Recebe a mensagem, sanitiza e valida.
    $message = trim(htmlspecialchars($_POST['message']));
    if (strlen($message) < 5)
        $error .= '<li>A mensagem está muito curta;</li>';

    // Se não ocorreram erros...
    if ($error === '') :

        // Query de escrita no banco.
        $sql = <<<SQL

INSERT INTO contacts (
    name,
    email,
    subject,
    message
) VALUES (
    '{$name}',
    '{$email}',
    '{$subject}',
    '{$message}'
);

SQL;

        // Escreve no banco de dados
        $conn->query($sql);

        /**
         * Obtém o primeiro nome do remetente.
         * 
         * Gera um array com as partes do nome.
         * $parts[0] contém o primeiro nome.
         */
        $first_name = explode(' ', $name)[0];

        /**
         * Envia e-mail para o administrador do site.
         * ATENÇÃO! Não funciona em redes locais.
         * Provavelmente, só em provedores pagos.
         */

        // Mensagem do e-mail
        $mail_message = <<<TXT

Novo contato enviado para Vitugo:

 - Remetente: {$name}
 - E-mail: {$email}
 - Assunto: {$subject}
 - Mensagem:
 {$message}

Obrigado...

TXT;

        /**
         * Enviando e-mail para 'admin@vitugo.com', administrador do site.
         * 
         * Lembre-se de trocar o e-mail do administrador.
         * 
         * OBS: não é possível enviar e-mails do XAMPP, do Windows ou da rede escolar usando o PHP.
         * Usamos o '@' para ocultar mensagens de erro. 
         * MUITO CUIDADO AO USAR '@' DESTE MODO!!!
         */
        @mail('admin@vitugo.com', 'Um contato foi enviado.', $mail_message);

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
$title = "Faça contato";

/**
 * Inclui o cabeçalho da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_header.php');

?>

<section>

    <h2>Faça contato</h2>

    <?php
    // Se o cadastro foi finalizado com sucesso...
    if ($feedback) :
    ?>

        <div class="block-center">

            <h3>Olá <?php echo $first_name ?>!</h3>
            <p>Seu contato foi enviado com sucesso!</p>
            <p><em>Obrigado...</em></p>
            <hr class="divider">
            <p class="user-links">
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

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" name="contatos">

            <p>Preencha todos os campos para entrar em contato com a equipe do Vitugo.</p>

            <?php if ($error != '') echo '<div class="error">' . $error . '</div>'; ?>

            <p>
                <label for="name">Nome:</label>
                <input type="text" name="name" id="name" required minlength="3" class="valid" value="<?php echo $name ?>">
                <!-- O campo é obrigatório (required) e deve ter pelo menos 3 caracteres. -->
            </p>

            <p>
                <label for="email">E-mail:</label>
                <input type="email" name="email" id="email" required class="valid" value="<?php echo $email ?>">
                <!-- O campo é obrigatório e deve ser um e-mail (type="email"). -->
            </p>

            <p>
                <label for="subject">Assunto:</label>
                <input type="text" name="subject" id="subject" required minlength="5" class="valid" autocomplete="off" value="<?php echo $subject ?>">
                <!-- O campo é obrigatório e deve ter pelo menos 5 caracteres. -->
            </p>

            <p>
                <label for="message">Mensagem:</label>
                <textarea name="message" id="message" required minlength="5" class="valid" autocomplete="off"><?php echo $message ?></textarea>
                <!-- O campo é obrigatório e deve ter pelo menos 5 caracteres. -->
            </p>

            <p>
                <button type="submit">Enviar</button>
            </p>

        </form>

    <?php endif; ?>

</section>

<aside>

    <h3>Mais contatos</h3>

    <div class="aside-social-box">

        <a href="https://facebook.com/Luferat">
            <i class="fa-brands fa-facebook fa-fw"></i>
            <span>Facebook</span>
        </a>

        <a href="https://youtube.com/Luferat">
            <i class="fa-brands fa-youtube fa-fw"></i>
            <span>Youtube</span>
        </a>

        <a href="https://github.com/Luferat">
            <i class="fa-brands fa-github fa-fw"></i>
            <span>GitHub</span>
        </a>
    </div>

</aside>

<?php

/**
 * Inclui o rodapé da página.
 */
require($_SERVER['DOCUMENT_ROOT'] . '/_footer.php');

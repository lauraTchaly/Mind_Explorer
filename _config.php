<?php

/**
 * Gera conteúdo sempre em UTF-8.
 * DEVE SER sempre a primeira linha de código.
 */
header('Content-Type: text/html; charset=utf-8');

/*
 * Faz conexão com MySQL/MariaDB.
 * Os dados da conexão estão em "/_config.ini".
 */

// Armazena o arquivo "/_config.ini" em um array "$ini"...
$ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/_config.ini', true);

// Itera cada chave do array...
foreach ($ini as $key => $val) :

    // Se a chave tem o mesmo nome do servidor...
    if ($_SERVER['SERVER_NAME'] === $key) :

        // Conexão com MySQL/MariaDB usando "mysqli" (orientada a objetos)
        $conn = new mysqli($val['hostname'], $val['username'], $val['password'], $val['database']);

        // Trata possíveis exceções
        if ($conn->connect_error) die("Falha de conexão com o banco e dados: " . $conn->connect_error);

    endif;
endforeach;

/**
 * Seta transações entre MySQL/MariaDB e PHP para UTF-8.
 */
$conn->query("SET NAMES 'utf8'");
$conn->query('SET character_set_connection=utf8');
$conn->query('SET character_set_client=utf8');
$conn->query('SET character_set_results=utf8');

/**
 * Seta dias da semana e meses do MySQL/MariaDB para "português do Brasil".
 */
$conn->query('SET GLOBAL lc_time_names = pt_BR');
$conn->query('SET lc_time_names = pt_BR');

/**
 * Define o fuso horário (opcional + recomendado).
 */
date_default_timezone_set('America/Sao_Paulo');

/**
 * Se usuário está logado, cria variável (array) '$user'
 */
if (isset($_COOKIE['user']))
    $user = unserialize($_COOKIE['user']);
else
    $user = false;

// dump($user, false);

/*************************
 * Funções de uso geral. *
 *************************/
/* Essas funções estão acessíveis em qualquer parte do aplicativo. */

/**
 * Função que calcula a idade.
 *
 * A data de nascimento, passada como parâmetro, deve estar no
 * formato 'system date', ou seja, 'aaaa-mm-dd'.
 * 
 * Exemplo de uso
 * 
 *      $nascimento = '1982-06-19';          // Data de nascimento no formato "system date"
 *      $idade = get_years_old($nascimento); // Chamando a função
 *      echo "{$idade} anos.";               // Saída
 * 
 * Lembre-se que existem outras formas de fazer esse cálculo.
 */
function get_years_old($birth)
{

    // O array '$n' contém a data atual.
    $n = array(date('Y'), date('m'), date('d'));

    // O array '$b' contém a data de nascimento.
    $b = explode('-', $birth);

    // Calculando a idade pelo ano (ano_atual - ano_nascimento).
    $yo = $n[0] - $b[0];

    // Se o mês é menor que o mês de nascimento...
    if ($n[1] < $b[1]) {

        // ... subtrai 1 ano da idade.
        $yo--;

        // Se é o mesmo mês e o dia é menor que o dia de nascimento...
    } elseif ($n[1] == $b[1] and $n[2] <= $b[2]) {

        // ... subtrai 1 ano da idade.
        $yo--;
    }

    // Retorna a idade em anos.
    return $yo;
}

/**
 * Função que imprime uma variável.
 * 
 * Esta função é usada apenas em desenvolvimento, para fins de DEBUG.
 * 
 * Sintaxe: debug(var, pre, exit)
 * 
 *      var --> Variável a ser debugada.
 *              Default: undefined
 *      pre --> Se true, exibe saída pré-formatada; se false exibe sem formatação.
 *              Default: $pre = true
 *      exit --> Se true, encerra o script com 'exit'. Se false, continua o script.
 *              Default: $exit = true
 * 
 * Exemplos de uso:
 * 
 *      1) degub($variavel);
 *         Envia o "DUMP" da "$variável" para a saída (navegador).
 *         O DUMP é exibido pré-formatado.
 *         O script é interrompido com 'exit'.
 * 
 *      2) debug($variavel, false);
 *         Envia o "DUMP" da "$variável" para a saída (navegador).
 *         O DUMP é exibido pré-formatado.
 *         O script NÃO é interrompido com 'exit'.
 * 
 *      3) debug($variavel, true, false);
 *         Envia o "DUMP" da "$variável" para a saída (navegador).
 *         O DUMP é exibido sem pré-formatação.
 *         O script é interrompido com 'exit'.
 * 
 *      3) debug($variavel, false, false);
 *         Envia o "DUMP" da "$variável" para a saída (navegador).
 *         O DUMP é exibido sem pré-formatação.
 *         O script NÃO é interrompido com 'exit'.
 */
function dump($variable, $exit = true, $pre = true)
{
    if ($pre) echo '<pre>';
    print_r($variable);
    if ($pre) echo '</pre>';
    if ($exit) exit;
}

/**
 * Função que envia uma imagem para o servidor via PHP upload.
 * 
 * O primeiro parâmetro é obrigatório e especifica o caminho absoluto do 
 * aplicativo, onde a imagem será armazenada, com base na raiz do aplicativo.
 * Esse atributo deve sempre terminar com a '/'.
 * 
 * O segundo parâmetro define o nome da imagem, sem a extensão.
 * Se este for omitido, a função gera um nome aleatório de 24 caracteres 
 * hexadecimais (12 bytes).
 * 
 * Exemplos de uso:
 * 
 *      upload_photo('/img/photos/');
 *      upload_photo('/img/users/prifile/photo/', $user_id);
 * 
 * Políticas de imagem definidas pela função:
 * 
 *      • Envia somente uma imagem por vez;
 *      • Suporta imagens nos formatos "jpeg", "jpg" e "png";
 *      • Suporta imagens de, no máximo, 1 megabyte;
 *      • Somente imagens quadradas;
 *      • Imagens com dimensões mínimas de 64 x 64 pixels;
 *      • Imagens com dimensões máximas de 512 x 512 pixels;
 *      • Se o nome da imagem (2º parâmentro) for omitido ou definido como 
 *        "" (vazio), a função gera um nome aleatório de 24 caracteres 
 *        hexadecimais. 
 * 
 * OBS: essas políticas só podem ser alteradas, refatorando-se a função.
 */
function upload_photo($photo_dir, $photo_name = '')
{

    // Se $photo_name==='' (DEFAULT), gera um nome aleatório para a imagem.
    if ($photo_name === '')
        $photo_name = substr(sha1(time() + rand()), 40 - min(24, 40));

    // Obtém os metadados da imagem, necessários para o tratamento desta.
    $return_url = false;                                                       // URL da imagem salva
    $error = false;                                                            // Mensagens de erro
    $photo_data = $_FILES['photo'];                                            // Dados do arquivo vindos do cliente
    list($photo_width, $photo_height) = getimagesize($photo_data['tmp_name']); // Dimensões da imagem
    $photo_type = strtolower($photo_data['type']);                             // Tipo MIME da imagem
    $photo_ext = trim(explode('/', $photo_type)[1]);                           // Extensão do nome da imagem
    $photo_url = $photo_dir . $photo_name . '.' . $photo_ext;                  // URL da imagem

    // Testa os tipos de imagem válidos (jpg, jpeg e png)...
    if (
        $photo_type !== 'image/jpeg' and
        $photo_type !== 'image/jpg' and
        $photo_type !== 'image/png'
    ) {

        $error .= "A foto não está em um formato válido.";

        // Testa o tamanho da imagem...
    } elseif (
        $photo_data['size'] > 1000000   // Imagem tem mais que 1 megabyte?
    ) {

        $error .= "A foto deve ter menos de 1MB.";

        // Testa as dimensões da imagem...
    } elseif (
        $photo_width < 64 or             // Largura menor que 64 pixels?
        $photo_width > 512 or            // Largura maior que 512 pixels?
        $photo_width !== $photo_height   // Largura e altura são diferentes?
    ) {

        $error .= "A foto não está em um formato válido.";

        // Salvando a imagem no destino...
    } else {

        //   comando_move            origem                        destino   
        if (move_uploaded_file($photo_data["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $photo_url)) {

            // Gera URL da imagem...
            $return_url .= $photo_url;
        } else {

            // Se der erro, avisa ao front-end.
            $error .= "Erro ao enviar foto.";
        }
    }

    // O retorno da função é um array com os íduices abaixo:
    return array(
        'url' => $return_url,   // Endereço (URL) da imagem salva no servidor ou "false" se der erro.
        'error' => $error       // Mensagem de erro em caso de falha ou "false" se deu certo.
    );
}

/**
 * Função que lista de artigos mais visualizados.
 * 
 * O parâmetro $num define quantos artigos serão obtidos.
 *      Default: 4 artigos
 * 
 * Retorna "false: boolean" se não encontrar artigos.
 * 
 * Exemplos de uso:
 * 
 *      echo mostViewed(); //Exibe 4 artigos.
 *      echo mostViewed(6); // Exibe 6 artigos.
 *      $aside = mostViewed(); // Armazena artigos em uma variável para uso posterior.
 * 
 * Lembre-se de atualizar o banco de dados, incluindo o campo 
 * "art_views INT DEFAULT '0'" na tabela "articles".
 * 
 * Atualize também, em '/style.css', as classes usadas na visualização.
 * 
 * Para ver um exemplo funcional, veja o código da <aside> em '/index.php'.
 */
function mostViewed($num = 4)
{

    global $conn;

    $sql = <<<SQL

SELECT art_id, art_title, art_intro
FROM articles 
WHERE art_status = 'on'
	AND art_date <= NOW()
ORDER BY art_views DESC
LIMIT {$num};

SQL;

    $res = $conn->query($sql);

    $out = '';

    if ($res->num_rows > 0) :

        while ($art = $res->fetch_assoc()) :

            $out .= <<<HTML

<div class="side-art-box" onclick="location.href='/ler/?id={$art['art_id']}'">
    <div class="side-art-title">{$art['art_title']}</div>
    <div class="side-art-intro">{$art['art_intro']}</div>
</div>

HTML;

        endwhile;

    else :

        $out = false;

    endif;

    return $out;
}

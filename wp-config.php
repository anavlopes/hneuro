<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa user o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/pt-br:Editando_wp-config.php
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar estas informações
// com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'hneuro');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'root');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', '');

/** Nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Charset do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8mb4');

/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');


define('FS_METHOD', 'direct');

define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '256M');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'f7v04oQ=>[7uv5VwiBmmv_3mTu);{RUuG|}Asyc.%kx;7RXo?>]jnZ8j-*_R2u[H');
define('SECURE_AUTH_KEY',  'a>tL-`gX1HH-H^Xp/gGPbMhJ.r>?_pE4%wBm[(#3JGHy!1aYS$%-qtZ*V.n.Qcq_');
define('LOGGED_IN_KEY',    '@xPEMK;{94[`=@Q9?30sQ}l`gkU}|R=n9pw/:82saKB!dQ`G{2!rUXz1v[l&d:*b');
define('NONCE_KEY',        '>8~kOP_P5t&wp%#&+kk|Ptq,h`v%bp)#tr=7bRPZ0J#q/8e.`(cQr/q11~dIvK*D');
define('AUTH_SALT',        'r(?^:&EqHL<BEXqV@8}66+8RY4n1!%yCt1b&E}).J!/n^<4eB.ZZ2M#/U>c1yzL*');
define('SECURE_AUTH_SALT', 'MBP>!v2K83G*P`DQT =%tplpLdnK!D.C,TMaQGyEK^7-{ds-Y<G}iF(z#bX]P_6@');
define('LOGGED_IN_SALT',   'q56)bA6OZ0{v V4[IsIiG~6v2^y0>^s[eQTa9AXQ8@Ymi$eI|GgWtV6=#05j tOa');
define('NONCE_SALT',       '9tI%<xT}+d5u *>KGLK0yx&Mv^Z_g,s/n/2hJC5>jPm0Mcx:R0t)YoGu];h,l{uT');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * para cada um um único prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'wp_';

/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://codex.wordpress.org/pt-br:Depura%C3%A7%C3%A3o_no_WordPress
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Configura as variáveis e arquivos do WordPress. */
require_once(ABSPATH . 'wp-settings.php');

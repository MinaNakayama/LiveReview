<?php
/**
 * XSS対策
 * @param string $str 対象の文字列
 * @return string 処理された文字列
 */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function e(string $str, string $charset = 'UTF-8'): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, $charset, false);
}

/**
 * CSRF対策:ワンタイムトークン
 * 　　　　　トークンの生成→送信→照会→削除
 * @param Void
 * @return string $csrf_token
 */
function setToken() {
    $csrf_token  = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrf_token;

    return $csrf_token;
}

/**
 * CSRF トークン作成
 */
function get_csrf_token() {
    $token_legth = 16; //16*2=32byte
    $bytes = openssl_random_pseudo_bytes($token_legth);
    return bin2hex($bytes);
}

/**
 * URLの一時パスワードを作成
 */
function get_url_password() {
    $token_legth = 16;
    $bytes = openssl_random_pseudo_bytes($token_legth);
    return hash('sha256', $bytes);
}

?>
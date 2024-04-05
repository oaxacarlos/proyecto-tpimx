<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'protocol' => '', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => '',
    'smtp_port' => 1234,
    'smtp_user' => "",
    'smtp_pass' => "",
    'smtp_crypto' => 'tls', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '60', //in seconds
    'crlf' => "\r\n",
    'newline' => "\r\n",
);

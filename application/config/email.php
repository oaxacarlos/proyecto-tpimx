<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'smtp-mail.outlook.com',
    'smtp_port' => 587,
    'smtp_user' => "notification@toyopower.com",
    'smtp_pass' => "Sing1234",
    'smtp_crypto' => 'tls', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '60', //in seconds
    'crlf' => "\r\n",
    'newline' => "\r\n",
);

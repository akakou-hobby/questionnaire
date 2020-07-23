<?php
require  __DIR__ . '/../../../vendor/autoload.php';

require __DIR__ . '/../../../src/auth.php';
require __DIR__ . '/../../../src/db.php';

$config = array(
    "digest_alg" => "sha512",
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
);

$form = ORM::for_table('forms')->create();

$json = file_get_contents("php://input");
$contents = json_decode($json, true);

$form->questions = json_encode($contents["data"]);
$form->user_id = $user->user_id;

$bytes = openssl_random_pseudo_bytes(16);
$form->user_token = bin2hex($bytes);

$bytes = openssl_random_pseudo_bytes(16);
$form->admin_token = bin2hex($bytes);

$res = openssl_pkey_new($config);
openssl_pkey_export($res, $prikey);

$pubkey = openssl_pkey_get_details($res)["key"];

$form->pubkey = base64_encode($pubkey);
$form->prikey = base64_encode($prikey);

$form->save(); 

echo $form->admin_token;
?>
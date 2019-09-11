<?php

use EasySwoole\Utility\Jwt;

require "../src/Jwt.php";

$jwt = new Jwt();
$jwt->setIss('Siam发行人');
$jwt->setExp(30);//30s有效期
$jwt->setSub('主题');
// 附加数据 比如用户信息
$jwt->setWith([
    'name' => '宣言',
    'age'  => 20,
]);
$jwt->setSecretKey('keykeykey');

$token =  $jwt->make();

echo $token.PHP_EOL;

$data = $jwt->decode($token);

if ($data === false){
    echo $jwt->errMsg.PHP_EOL;
}
var_dump($data);

echo "第二个".PHP_EOL;

$jwt->setWith([
    'name' => '宣言2',
    'age'  => 222,
]);
$token2 = $jwt->make();
echo $token2.PHP_EOL;

$data2 = $jwt->decode($token2."1");

if ($data2 === false){
    echo $jwt->errMsg.PHP_EOL;
}
var_dump($data2);
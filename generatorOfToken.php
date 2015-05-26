<?php 

$username = "demo";
$password = "demo";
$salt = '443qurd4va0wsgw8c4wc4occkgwswsk';

$salted = $password.'{'.$salt.'}';
$digest = hash('sha512', $salted, true);

for ($i=1; $i<5000; $i++) {
    $digest = hash('sha512', $digest.$salted, true);
}

$encodedPassword = base64_encode($digest);

echo($encodedPassword.PHP_EOL);


$nonce = "60fc915a72194d6f";
$created = date("Y-m-d\TH:i:s.uP");

$passwordDigest = base64_encode(sha1(base64_decode($nonce) . $created . $encodedPassword , true));

echo('UsernameToken Username="'.$username.'", PasswordDigest="'.$passwordDigest.'", Nonce="'.$nonce.'", Created="'.$created.'"'.PHP_EOL);



?>

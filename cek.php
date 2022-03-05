<?php
$_password = 'halimi';

$hashToStoreInDb = password_hash($_password, PASSWORD_BCRYPT);

$isPasswordCorrect = password_verify($_password, $hashToStoreInDb);

var_dump($hashToStoreInDb);
var_dump($isPasswordCorrect);
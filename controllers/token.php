<?php

try {
    $decoded = JWT::decode($jwt, $key, array('HS256'));
    print_r($decoded); // Shows the decoded payload
} catch (Exception $e) {
    echo 'Token verification failed: ' . $e->getMessage();
}

?>

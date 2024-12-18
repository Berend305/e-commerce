<?php
define('DB_HOST', getenv('DB_HOST') ?: 'ecommerce-db'); // K8s service naam
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_NAME', getenv('DB_NAME'));
?>

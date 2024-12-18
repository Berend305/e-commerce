<?php
define('DB_HOST', getenv('DB_HOST') ?: 'ecommerce-db:30081'); // K8s service naam
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_NAME', getenv('DB_NAME'));
?>

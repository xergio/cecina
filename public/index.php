<?php

define('DEBUG', true);

require_once("../loader.php");

$app = new CecinaController();
$app->dispatch();

echo $app->render();

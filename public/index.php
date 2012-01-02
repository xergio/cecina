<?php

define('DEBUG', true);

require_once("../sys/loader.php");

$app = new CecinaController();
$app->dispatch();

echo $app->render();

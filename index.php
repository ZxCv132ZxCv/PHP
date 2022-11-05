<?php


declare(strict_types=1);

namespace App;

// to powinno być na wersji na świat. Dla nas błędy są ważne.
// error_reporting(0);
// ini_set('display_errors', '0');

require_once('./src/Controller.php');
include_once('./src/utils/debug.php');
require_once('./config/config.php');

Controller::initConfiguration($configuration);

$controller = new Controller($_GET, $_POST);
$controller->run();

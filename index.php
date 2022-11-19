<?php


declare(strict_types=1);

namespace App;

// to powinno być na wersji na świat. Dla nas błędy są ważne.
// error_reporting(0);
// ini_set('display_errors', '0');

require_once('./Exception/AppException.php');
require_once('./Exception/StorageException.php');
require_once('./Exception/ConfigurationException.php');
require_once('./src/Controller.php');
require_once('./src/Request.php');
include_once('./src/utils/debug.php');
require_once('./config/config.php');

use App\Exception\AppException;
use App\Exception\StorageException;
use App\Exception\ConfigurationException;
use App\Request;
use Throwable;

$request = new Request($_GET, $_POST);
try {
    Controller::initConfiguration($configuration);
    $controller = new controller($_GET, $_POST);
    $controller->run();
} catch (AppException $e) {
    echo "<h1>Wystąpił błąd w aplikacji</h1>";
    echo '<h3>' . $e->getMessage() . '</h3';
} catch (Throwable $e) {
    echo "<h1>Wystąpił błąd w aplikacji</h1>";
}
dump($e);

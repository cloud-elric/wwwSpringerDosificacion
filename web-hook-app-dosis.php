<?php

require 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parse(file_get_contents('config.yml'));
$payload = json_decode($_POST['payload']);

$LOCAL_REPO = $_SERVER['DOCUMENT_ROOT'] . "/springer/app-dosis";
$REMOTE_REPO = $payload->repository->url . ".git";

$ROOT2GOM = $_SERVER['DOCUMENT_ROOT'] . "/springer/";

//$res = shell_exec("cd $ROOT2GOM; pwd");
//echo "Salidiuiia: $res\n"; 
//exit();

if (file_exists($LOCAL_REPO)) {
    $resultado = shell_exec("cd $LOCAL_REPO; git fetch --all; git reset --hard origin/master");
    echo "Resultado: Cambios correctos.\n$resultado";
} else {
    $resultado = shell_exec("cd $ROOT2GOM; git clone $REMOTE_REPO");
    echo "Resultado: Repo creado.\n$resultado";
}

//echo $LOCAL_REPO;
//$log = new Logger('UPDATES');
//$log->pushHandler(new StreamHandler('logs/' . $payload->repository->name . '.log', Logger::INFO));
//$log->addInfo(substr($payload->commits[0]->id, 0, 8) . " " . $payload->commits[0]->message);

<?php

use UptimeRobot\Core\Slack;

if (!file_exists(dirname(__FILE__) . '/vendor/autoload.php'))
{
    echo 'Не смог найти автолоадер. Запустите "php init" и повторите попытку.' . "\n";;
    die();
}

require_once (dirname(__FILE__) . '/vendor/autoload.php');
require_once('constants.php');

if (!empty(array_diff(['projectName', 'alertDetails', 'color'], array_keys($_POST))))
{
    echo 'Переданы не все обязательные параметры.';
    die();
}

$project = $_POST['projectName'];
$details = $_POST['alertDetails'];
$message = $_POST['message'];
$color = $_POST['color'];
$secretKey = $_POST['secretKey'];

if (md5($project . $color . $details . $message . SECRET_KEY) != $secretKey || empty($project) || empty($color) || empty($message))
{
    echo 'Параметры переданы неверно.';
    die();
}

$slack = new Slack(SLACK_BOT_USERNAME, SLACK_BOT_TOKEN, SLACK_BOT_ERRORS_CHANNEL);

echo json_encode([
    'response' => $slack->notify([
        [
            "fallback" => $details,
            "color" => $color,
            "title" => $project,
            "text" => $message
        ]
    ])
]);
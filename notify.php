<?php

use UptimeRobot\Core\Slack;

if (!file_exists(dirname(__FILE__) . '/vendor/autoload.php'))
{
    echo 'Не смог найти автолоадер. Запустите "php init" и повторите попытку.' . "\n";;
    die();
}

require_once (dirname(__FILE__) . '/vendor/autoload.php');
require_once('constants.php');

if (!empty(array_diff(['projectName', 'alertDetails', 'color'], array_keys($_GET))))
{
    echo 'Переданы не все обязательные параметры.';
    die();
}

$project = $_GET['projectName'];
$details = $_GET['alertDetails'];
$color = $_GET['color'];
$secretKey = $_GET['secretKey'];

if (md5($project . $color . $details . SECRET_KEY) != $secretKey || empty($project) || empty($color))
{
    echo 'Параметры переданы неверно.';
    die();
}

$slack = new Slack(SLACK_BOT_USERNAME, SLACK_BOT_TOKEN, SLACK_BOT_CHANNEL);

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
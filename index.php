<?php

use UptimeRobot\Core\Slack;

if (!file_exists(dirname(__FILE__) . '/vendor/autoload.php'))
{
    echo 'Не смог найти автолоадер. Запустите "php init" и повторите попытку.' . "\n";
    die();
}

require_once (dirname(__FILE__) . '/vendor/autoload.php');

const ALERT_TYPE_DOWN = 1;
const ALERT_TYPE_UP = 2;

$alertType = $_GET['alertType'];

if (!in_array($alertType, [ALERT_TYPE_DOWN, ALERT_TYPE_UP]))
{
    echo 'Not found';
    die();
}

require_once('constants.php');

$project = $_GET['monitorFriendlyName'];
$details = $_GET['alertDetails'];

if ($alertType == ALERT_TYPE_DOWN)
{
    $color = 'danger';
    $message = "Проект $project не отвечает.";
}
else
{
    $color = 'good';
    $message = "Проект $project поднялся с колен.";
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
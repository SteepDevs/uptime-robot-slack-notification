<?php

namespace UptimeRobot\Core;
use Curl\Curl;

/**
 * @link https://slack.com/api/channels.list?token=xoxp-20512462576-20511638866-20762918032-adfc86180c Информация о каналах
 *
 * Class Slack
 * @package Basis\Api
 */
class Slack
{
    const BASE_API_URL = 'https://slack.com/api';
    const API_PARAM_NAME = 'username';
    const API_PARAM_TOKEN = 'token';
    const API_PARAM_MARKDOWN = 'mrkdwn';

    protected $name = null;
    protected $token = null;

    protected $channel = null;

    public function __construct($name = null, $token = null, $channel = null)
    {
        $this->name = (!empty($name)) ? $name : null;
        $this->token = (!empty($token)) ? $token : null;
        $this->channel = (!empty($channel)) ? $channel : null;
    }

    protected function getApiUrl($method, array $params = [])
    {
        $params = array_merge($params, [
            self::API_PARAM_NAME => $this->name,
            self::API_PARAM_TOKEN => $this->token,
            self::API_PARAM_MARKDOWN => true
        ]);

        return self::BASE_API_URL . '/' . $method . '?' . http_build_query($params);
    }

    protected function apiGet($method, array $params = [])
    {
        $curl = new Curl();

        $result = json_decode(json_encode($curl->post($this->getApiUrl($method), $params)), true);

        $curl->close();

        return $result;
    }

    public function send($to, $message = null, array $attachments = [])
    {
        $data = [
            'channel' => $to,
        ];

        if (!empty($message))
        {
            $data['text'] = $message;
        }

        if (!empty($attachments))
        {
            $data['attachments'] = json_encode($attachments);
        }

        $result = $this->apiGet('chat.postMessage', $data);

        if (empty($result['ok']))
        {
            return false;
        }

        return true;
    }

    public function notify($attachments)
    {
        return $this->send($this->channel, null, $attachments);
    }
}
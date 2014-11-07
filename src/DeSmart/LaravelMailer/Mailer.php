<?php namespace DeSmart\LaravelMailer;

class Mailer extends \Illuminate\Mail\Mailer
{

    protected $config = [];

    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    protected function createMessage()
    {
        $message = new Message(new \Swift_Message);
        $message->setConfig($this->config);

        if (isset($this->from['address'])) {
            $message->from($this->from['address'], $this->from['name']);
        }

        return $message;
    }
}

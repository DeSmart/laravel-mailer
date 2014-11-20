<?php namespace DeSmart\LaravelMailer;

class MailServiceProvider extends \Illuminate\Mail\MailServiceProvider
{

    protected $defer = true;

    public function register()
    {
        $this->package('desmart/laravel-mailer');

        if (true === $this->app['config']->get('laravel-mailer::mailer.enabled')) {
            $this->registerMailer();
        } else {
            parent::register();
        }
    }

    protected function registerMailer()
    {
        $this->app->bindShared('mailer', function ($app) {
            $this->registerSwiftMailer();

            $mailer = new Mailer($app['view'], $app['swift.mailer']);
            $mailer->setLogger($app['log'])->setQueue($app['queue']);
            $mailer->setContainer($app);

            $from = $app['config']['mail.from'];

            if (is_array($from) and isset($from['address'])) {
                $mailer->alwaysFrom($from['address'], $from['name']);
            }

            $pretend = $app['config']->get('mail.pretend', false);
            $mailer->pretend($pretend);

            $mailer->setConfig($app['config']->get('laravel-mailer::mailer'));

            return $mailer;
        });
    }
}

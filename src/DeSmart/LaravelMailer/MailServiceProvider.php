<?php namespace DeSmart\LaravelMailer;

class MailServiceProvider extends \Illuminate\Mail\MailServiceProvider
{

    protected $defer = true;

    public function register()
    {
        $config_path = __DIR__ . '/../../config/desmart-laravel-mailer.php';
        $this->publishes([$config_path => config_path('desmart-laravel-mailer.php')], 'config');
        $this->mergeConfigFrom($config_path, 'desmart-laravel-mailer');

        if (true === $this->app['config']->get('desmart-laravel-mailer.enabled')) {
            $this->registerMailer();
        } else {
            parent::register();
        }
    }

    protected function registerMailer()
    {
        $this->app->singleton('mailer', function ($app) {
            $this->registerSwiftMailer();

            // Once we have create the mailer instance, we will set a container instance
            // on the mailer. This allows us to resolve mailer classes via containers
            // for maximum testability on said classes instead of passing Closures.
            $mailer = new Mailer($app['view'], $app['swift.mailer'], $app['events']);

            $this->setMailerDependencies($mailer, $app);

            // If a "from" address is set, we will set it on the mailer so that all mail
            // messages sent by the applications will utilize the same "from" address
            // on each one, which makes the developer's life a lot more convenient.
            $from = $app['config']['mail.from'];

            if (is_array($from) && isset($from['address'])) {
                $mailer->alwaysFrom($from['address'], $from['name']);
            }

            // Here we will determine if the mailer should be in "pretend" mode for this
            // environment, which will simply write out e-mail to the logs instead of
            // sending it over the web, which is useful for local dev environments.
            $pretend = $app['config']->get('mail.pretend', false);

            $mailer->pretend($pretend);
            $mailer->setConfig($app['config']->get('desmart-laravel-mailer'));

            return $mailer;
        });
    }
}

<?php

use DeSmart\LaravelMailer\Message;

class MailerTest extends \PHPUnit_Framework_TestCase
{

    protected $config = [
        'white_list' => [
            'desmart.com',
            'desmart.pl',
        ],
        'enabled' => true,
        'email' => 'pomek+test@desmart.com'
    ];

    /**
     * @dataProvider replacingEmailAddressInToProvider
     */
    public function testReplacingEmailAddress($to, $name, $method)
    {
        $function = 'add' . ucfirst($method);
        $mock = $this->getMock('Swift_Message', [$function]);

        if ('replyto' === strtolower($method)) {
            $mock->expects($this->once())->method($function)->with($this->equalTo($to), $this->equalTo($name));
        } else {
            $mock->expects($this->once())->method($function)->with($this->equalTo($this->config['email']), $this->equalTo($name));
        }

        $message = new Message($mock);
        $message->setConfig($this->config);

        $message->$method($to, $name);
    }

    /**
     * @dataProvider replacingArrayEmailAddress
     */
    public function testReplacingArrayEmailAddress($emails, $expected, $method)
    {
        $message = new Message(new \Swift_Message);
        $message->setConfig($this->config);
        $function = 'get' . ucfirst($method);

        $message->$method($emails);

        $this->assertEquals(
            $message->getSwiftMessage()->$function(),
            $expected
        );
    }

    public function replacingEmailAddressInToProvider()
    {
        return [
            ['foo@bar.com', 'Mr Foo', 'to'],
            ['foo@bar.com', null, 'to'],
            ['foo@bar.com', 'Mr Foo', 'bcc'],
            ['foo@bar.com', null, 'bcc'],
            ['foo@bar.com', 'Mr Foo', 'cc'],
            ['foo@bar.com', null, 'cc'],
            ['foo@bar.com', 'Mr Foo', 'replyTo'],
            ['foo@bar.com', null, 'replyTo'],
        ];
    }

    public function replacingArrayEmailAddress()
    {
        $result = [];

        $result[] = [
            [
                'foo@bar.com',
                'bar@foo.com',
            ],
            [
                'foo@bar.com' => null,
                'bar@foo.com' => null,
            ],
            'replyTo'
        ];

        foreach (['to', 'cc', 'bcc'] as $method) {
            $result[] = [
                [
                    'foo@bar.com',
                    'bar@foo.com',
                ],
                [
                    $this->config['email'] => null,
                ],
                $method,
            ];

            $result[] = [
                [
                    'foo@bar.com',
                    'bar@foo.com',
                    'apps@desmart.com',
                    'pomek@desmart.com',
                    'pomek@desmart.pl',
                ],
                [
                    $this->config['email'] => null,
                    'apps@desmart.com' => null,
                    'pomek@desmart.com' => null,
                    'pomek@desmart.pl' => null,
                ],
                $method,
            ];
        }

        return $result;
    }
} 

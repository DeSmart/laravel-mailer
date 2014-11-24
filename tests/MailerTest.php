<?php

use DeSmart\LaravelMailer\Message;

class MailerTest extends \PHPUnit_Framework_TestCase
{

    protected $config = [
        'white_list' => [
            'domain.net',
            'fr*@stylo.dev',
            'foo@bar.com',
        ],
        'enabled' => true,
        'email' => 'myAddress@domain.dev'
    ];

    /**
     * @dataProvider replacingEmailAddressInToProvider
     */
    public function testReplacingEmailAddress($to, $name, $method, $expected = null)
    {
        if (null === $expected) {
            $expected = $this->config['email'];
        }

        $function = 'add' . ucfirst($method);
        $mock = $this->getMock('Swift_Message', [$function]);

        if ('replyto' === strtolower($method)) {
            $mock->expects($this->once())->method($function)->with($this->equalTo($to), $this->equalTo($name));
        } else {
            $mock->expects($this->once())->method($function)->with($this->equalTo($expected), $this->equalTo($name));
        }

        $message = new Message($mock);
        $message->setConfig($this->config);

        $message->$method($to, $name);
    }

    /**
     * @dataProvider replacingCorrectEmailAddressesProvider
     */
    public function testReplacingCorrectEmailAddresses($to, $name, $method, $expected)
    {
        return $this->testReplacingEmailAddress($to, $name, $method, $expected);
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
            ['bar@foo.dev', 'Mr Foo', 'to'],
            ['bar@foo.dev', null, 'to'],
            ['bar@foo.dev', 'Mr Foo', 'bcc'],
            ['bar@foo.dev', null, 'bcc'],
            ['bar@foo.dev', 'Mr Foo', 'cc'],
            ['bar@foo.dev', null, 'cc'],
            ['bar@foo.dev', 'Mr Foo', 'replyTo'],
            ['bar@foo.dev', null, 'replyTo'],
        ];
    }

    public function replacingCorrectEmailAddressesProvider() 
    {
        return [
            ['foo@bar.com', 'Pomek', 'to', 'foo@bar.com'],
            ['free@stylo.dev', 'Apps', 'cc', 'free@stylo.dev'],
            ['foo+1@bar.com', 'Pomek1', 'bcc', $this->config['email']],
            ['as@desmart.com', 'As', 'to', $this->config['email']],
            ['foo+1@bar.com', 'Pomek1', 'replyTo', 'foo+1@bar.com'],
            ['domain.net@bar.com', 'Domain Net', 'bcc', $this->config['email']],
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
                    'example@domain.net',
                    'foo@bar.com',
                    'free@stylo.dev',
                ],
                [
                    $this->config['email'] => null,
                    'example@domain.net' => null,
                    'foo@bar.com' => null,
                    'free@stylo.dev' => null,
                ],
                $method,
            ];
        }

        return $result;
    }
} 

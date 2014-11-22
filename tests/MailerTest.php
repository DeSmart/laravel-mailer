<?php

use DeSmart\LaravelMailer\Message;

class MailerTest extends \PHPUnit_Framework_TestCase
{

    protected $config = [
        'white_list' => [
            'domain.net',
            'pomek@desmart.com',
            'ap*@desmart.com',
        ],
        'enabled' => true,
        'email' => 'pomek+test@desmart.com'
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

    public function replacingCorrectEmailAddressesProvider() 
    {
        return [
            ['pomek@desmart.com', 'Pomek', 'to', 'pomek@desmart.com'],
            ['apps@desmart.com', 'Apps', 'cc', 'apps@desmart.com'],
            ['pomek+1@desmart.com', 'Pomek1', 'bcc', $this->config['email']],
            ['as@desmart.com', 'As', 'to', $this->config['email']],
            ['pomek+1@desmart.com', 'Pomek1', 'replyTo', 'pomek+1@desmart.com'],
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
                    'pomek@desmart.com',
                    'apps@desmart.com',
                ],
                [
                    $this->config['email'] => null,
                    'example@domain.net' => null,
                    'pomek@desmart.com' => null,
                    'apps@desmart.com' => null,
                ],
                $method,
            ];
        }

        return $result;
    }
} 

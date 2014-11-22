<?php namespace DeSmart\LaravelMailer;

class Message extends \Illuminate\Mail\Message
{

    /**
     * Config of package.
     *
     * @var array
     */
    protected $config = [];

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * Parse e-mail or array with e-mail addresses.
     *
     * @param array|string $address
     * @param string $name
     * @param string $type
     * @return \Illuminate\Mail\Message
     */
    protected function addAddresses($address, $name, $type)
    {
        if ('replyto' === strtolower($type)) {
            return parent::addAddresses($address, $name, $type);
        }

        if (is_array($address)) {
            $address = $this->validateArrayEmails($address);
        } else {
            $address = $this->validateEmail($address);
        }

        return parent::addAddresses($address, $name, $type);
    }

    /**
     * Validate single e-mail and return fake e-mail if application isn't running on production.
     *
     * @param $address
     * @return string
     */
    protected function validateEmail($address)
    {
        foreach ($this->config['white_list'] as $row) {
            if ($row === $address) {
                return $address;
            }

            $pattern = sprintf('#%s#', str_replace('\*', '.*', preg_quote($row)));

            if (true == preg_match($pattern, $address)) {
                return $address;
            }
        }

        $email = $this->config['email'];

        if (true === is_callable($email)) {
            return $email($address);
        }

        return $email;
    }

    /**
     * Valdiate e-mails array and return unique e-mail array.
     *
     * @param array $addresses
     * @return array
     */
    protected function validateArrayEmails(array $addresses)
    {
        $results = [];

        foreach ($addresses as $item) {
            $results[] = $this->validateEmail($item);
        }

        return array_unique($results);
    }
}

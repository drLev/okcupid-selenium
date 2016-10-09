<?php

require_once __DIR__.'/DRLevScript.php';
require_once __DIR__.'/../lib/DRLevConfig.php';

class DRLevTempMail extends DRLevScript {
    protected $email;
    protected $iterationCount = 120; // count of check iterations
    protected $iterationWait = 0.5; // time in seconds to wait before next iteration
    protected $iterationNo = 0;

    public function start() {
        $this->iterationNo++;
        if (empty($this->email)) {
            throw new Exception("Email is empty");
        }

        $md5 = md5($this->email);
        $data = json_encode(file_get_contents("http://api.temp-mail.ru/request/mail/id/{$md5}/format/json"), true);
        if (isset($data['error'])) {
            if ($this->iterationNo <= $this->iterationCount) {
                usleep($this->iterationWait * 1000000);
                $this->start();
            } else {
                throw new Exception('Failure to get approve email');
            }
        } else {
            var_dump(array_keys($data[0]));
        }
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    public static function getEmailDomain() {
        $domains = explode('|', DRLevConfig::get('email-domain'));
        if (!empty($domains)) {
            return $domains[rand(0, count($domains) - 1)];
        }
    }
}
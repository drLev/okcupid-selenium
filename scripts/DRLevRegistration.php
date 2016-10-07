<?php

require_once __DIR__ . '/DRLevScript.php';
require_once __DIR__ . '/DRLevRuCaptcha.php';

class DRLevRegistration extends DRLevScript {

    protected $email;
    protected $name;

    public function start() {
        $this->clickElement('.next_page');
        stepSleep();
        $this->fillForm();
        stepSleep();
        $this->clickElement("xpath=//div[@id='form_container']//button[contains(@class, 'next_page')]");
        stepSleep();
        $this->fillLogin();
        stepSleep();
        $this->clickElement("xpath=//div[@id='credentials']//button[contains(text(), 'Done!')]");
        stepSleep();
//        $captcha = new DRLevRuCaptcha($this->driver);
//        $captcha->start();
        stepSleep();
        sleep(30);
        $this->clickElement("xpath=//div[@id='signup_captcha']/following-sibling::*[contains(text(), 'Done!')]");
        $this->fillFirstInfo();

    }

    protected function fillForm() {
        $day = str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
        $month = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
        $year = rand(1989, 1997);
        $country = $this->getCountry();
        $zipOrCity = $this->getZipOrCity($country);
        $email = $this->getEmail();
        var_dump(md5($email));
        throw new Exception('');
        $this->fillElement('#birthday', $day);
        $this->fillElement('#birthmonth', $month);
        $this->fillElement('#birthyear', $year);
        $this->selectItem('#country_selectContainer', $country);
        $this->fillElement('#zip_or_city', $zipOrCity);
        $this->fillElement('#email1', $email);
        $this->fillElement('#email2', $email);
    }

    protected function fillLogin() {
        $login = $this->getUserName();
        if (!$this->fillElement('#screenname_input', $login, true)) {
            $login = $this->driver->findElement($this->getByFromSelector('xpath=//input[@id=\'screenname_input\']/ancestor::div[1]//li[2]'))->getText();
            $this->clickElement("xpath=//input[@id='screenname_input']/ancestor::div[1]//li[2]");
            $this->name = $login;
            usleep(250000);
        }
        $this->fillElement('#password_input', $login.'1');
    }

    protected function fillFirstInfo() {
        $profileText = 'asddsa';
        $photo = 'asddsa';
        $this->setPhoto('#okphotos_file_input');
        $this->fillElement('#profile_textarea', $profileText);

        $rand = rand(0, 1) == 1 ? 'positive' : 'negative';
        $this->clickElement("xpath=//div[@id='answer_buttons']/button[contains(@class, '{$rand}')]");
    }

    protected function getCountry() {
        return 'United States';
    }

    protected function getZipOrCity($country) {
        return '10001';
    }

    protected function getEmail() {
        if (empty($this->email)) {
            $this->email = $this->getRandomEmail();
        }
        return $this->email;
    }
    protected function getRandomEmail() {
        return 'tratata3@polyfaust.com';
    }

    protected function getUserName() {
        if (empty($this->name)) {
            $this->name = $this->getRandomUserName();
        }
        return $this->name;
    }
    protected function getRandomUserName() {
        return 'tratata3';
    }
} 
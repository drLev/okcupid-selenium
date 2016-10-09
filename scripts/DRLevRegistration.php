<?php

require_once __DIR__ . '/DRLevScript.php';
require_once __DIR__ . '/DRLevRuCaptcha.php';

class DRLevRegistration extends DRLevScript {

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
        $captcha = new DRLevRuCaptcha($this->driver, $this->data);
        $captcha->start();
        $this->clickElement("xpath=//div[@id='signup_captcha']/following-sibling::*[contains(text(), 'Done!')]");
        sleep(5);
        $this->fillFirstInfo();

    }

    protected function fillForm() {
        $birthday = explode('.', $this->data['birthday']);
        $this->fillElement('#birthday', $birthday[2]);
        $this->fillElement('#birthmonth', $birthday[1]);
        $this->fillElement('#birthyear', $birthday[0]);
        $this->selectItem('#country_selectContainer', $this->data['country']);
        $this->fillElement('#zip_or_city', $this->data['zipcode']);
        $this->fillElement('#email1', $this->data['email']);
        $this->fillElement('#email2', $this->data['email']);
    }

    protected function fillLogin() {
        $login = $this->data['nick'];
        if (!$this->fillElement('#screenname_input', $login, true)) {
            try {
                $login = $this->driver->findElement($this->getByFromSelector('xpath=//input[@id=\'screenname_input\']/ancestor::div[1]//li[2]'))->getText();
            } catch (NoSuchElementException $e) {
                $this->data['nick'] = DRLevDataMgr::getInstance()->generateNick();
                $this->fillLogin();
                return;
            }
            $this->clickElement("xpath=//input[@id='screenname_input']/ancestor::div[1]//li[2]");
            $this->data['nick'] = $login;
            usleep(250000);
        }
//        throw new Exception('asd');
        $this->fillElement('#password_input', $login.'1');
    }

    protected function fillFirstInfo() {
        $this->clickElement("xpath=//div[contains(@class, 'photoupload-uploader-text')]", 10);
        $this->driver->findElement(WebDriverBy::id('okphotos_file_input'))->sendKeys($this->data['photo']);
        sleep(5);
        $this->fillElement("xpath=//textarea[contains(@class, 'oknf-textarea')]", $this->data['text']);
        $this->clickElement("xpath=//button[contains(@class, 'obprofile-submit flatbutton green')]");
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
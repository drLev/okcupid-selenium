<?php

require_once('vendor/autoload.php');

require_once __DIR__ . '/lib/DRLevTest.php';
require_once __DIR__ . '/scripts/DRLevRegistration.php';
require_once __DIR__ . '/scripts/DRLevLogin.php';
require_once __DIR__ . '/scripts/DRLevFillProfile.php';
require_once __DIR__ . '/lib/DRLevDataMgr.php';

class MainTest extends DRLevTest {

    public function setUp() {
        $this->url = DRLevConfig::get('url');
        parent::setUp();
    }

    public function testMain() {
        $count = max((int) DRLevConfig::get('repeat-count'), 1);
        for ($i = 0; $i < $count; $i++) {
            $this->doRegistration();
        }
    }

    public function doRegistration() {
//        return;
//        $this->restartDriver();
//        $data = json_decode(file_get_contents('mail.json'), true);
//        $mailData = $data[0]['mail_text'];
//        $part = substr($mailData, strpos($mailData, 'Sign In') + 7);
//        $approveUrl = (trim(substr($part, 0, strpos($part, '-------'))));
//        $approveUrl = "https://www.okcupid.com/l/.56VgWliSauNV.4Fi9ob21lP2NmPXdlbGNvbWVfZW1haWwAADKTAJMuXZrCV_c7WQAk6g.5CIOJUYoyzaXqJK13mWhBAidi0UKQ=";
//        $this->driver->get($approveUrl);
//        $this->driver->get($this->url);
//        return;
//        $fileInput = $this->driver->findElement(WebDriverBy::id('okphotos_file_input'));
//        $fileInput->sendKeys(realpath('minimize.png'));
//        sleep(20);
//        var_dump(file_get_contents($approveUrl));
//        die();

        $this->restartDriver();
        $this->driver->get($this->url);
        $data = DRLevDataMgr::getInstance()->generateProfileData();
        $script = new DRLevRegistration($this->driver, $data);
        $script->start();

        $script = new DRLevFillProfile($this->driver, $data);
        $script->start();

        $script = new DRLevLogin($this->driver, $data);
        $script->start();

        sleep(5);
    }


}
<?php

require_once('vendor/autoload.php');

require_once __DIR__ . '/lib/DRLevTest.php';
require_once __DIR__ . '/scripts/DRLevRegistration.php';
require_once __DIR__ . '/scripts/DRLevLogin.php';
require_once __DIR__ . '/scripts/DRLevFillProfile.php';

class MainTest extends DRLevTest {

    public function setUp() {
        $this->url = DRLevConfig::get('url');
        parent::setUp();
    }

    public function testRegistration() {

        $script = new DRLevRegistration($this->driver);
        $script->start();

        $script = new DRLevFillProfile($this->driver);
        $script->start();

        $script = new DRLevLogin($this->driver);
        $script->start();

        sleep(5);
    }


}
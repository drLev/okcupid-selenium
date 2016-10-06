<?php

require_once __DIR__.'/DRLevConfig.php';
require_once __DIR__.'/functions.php';

class DRLevTest extends PHPUnit_Framework_TestCase {
    protected $url = '';
    /**
     * @var RemoteWebDriver
     */
    protected $driver;
    public function setUp() {
        $host = 'http://localhost:4444/wd/hub'; // this is the default
        $capabilities = DesiredCapabilities::chrome();
        $this->driver = RemoteWebDriver::create($host, $capabilities, 5000);
        $this->driver->manage()->window()->maximize();
        $this->driver->get($this->url);
    }

    public function tearDown() {
        $this->driver->quit();
    }
} 
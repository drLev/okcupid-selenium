<?php

abstract class DRLevScript {

    /**
     * @var RemoteWebDriver
     */
    protected $driver;
    public function __construct(RemoteWebDriver $driver) {
        $this->driver = $driver;
    }

    /**
     * abstract methods
     */
    public function start() {}
    public function finish() {}

    /**
     * @param $selector
     * @return null|WebDriverBy
     */
    protected function getByFromSelector($selector) {
        $by = null;
        if ($selector[0] == '#') {
            $by = WebDriverBy::id(substr($selector, 1));
        } else if ($selector[0] == '.') {
            $by = WebDriverBy::className(substr($selector, 1));
        } else if (strpos($selector, 'xpath=') === 0) {
            $by = WebDriverBy::xpath(substr($selector, 6));
        } else {
            $by = WebDriverBy::cssSelector($selector);
        }
        return $by;
    }

    /**
     * @param $selector
     * @return bool
     */
    protected function clickElement($selector) {
        $by = $this->getByFromSelector($selector);
        $this->driver->wait()->until(WebDriverExpectedCondition::elementToBeClickable($by));
        $this->driver->findElement($by)->click();
        return true;
    }

    /**
     * @param $selector
     * @param $value
     * @param bool $checkError
     * @return bool
     */
    protected function fillElement($selector, $value, $checkError = false) {
        $this->clickElement($selector);
        $this->driver->getKeyboard()->sendKeys($value);
        if ($checkError) {
            sleep(2);
            $by = $this->getByFromSelector($selector);
            $el = $this->driver->findElement($by);
            try {
                $el->findElement(WebDriverBy::xpath("../ancestor-or-self::div[contains(@class, 'statuserror')]"));
                return false;
            } catch (NoSuchElementException $e) {
                return true;
            }
        }
        return true;
    }

    /**
     * @param $selector
     * @param $value
     * @return bool
     */
    protected function selectItem($selector, $value) {
        $this->clickElement($selector);
        $selector = substr($selector, 1);
        $by = WebDriverBy::xpath("//div[@id='$selector']//li[contains(text(), '$value')]");
        $this->driver->wait()->until(WebDriverExpectedCondition::elementToBeClickable($by));
        $this->driver->findElement($by)->click();
        return true;
    }
} 
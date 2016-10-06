<?php

require_once __DIR__.'/DRLevConfig.php';

if (!defined('STEP_TIMEOUT')) {
    define('STEP_TIMEOUT', DRLevConfig::get('step-timeout') * 1000);
}

function stepSleep() {
    usleep(STEP_TIMEOUT);
}
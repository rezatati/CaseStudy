<?php

/**
 * main entry point of application that load bootstrap and run app
 */

require_once "../bootstrap.php";

use App\ApiApp;

define('DEBUG_MODE', true);
(new ApiApp())->run();
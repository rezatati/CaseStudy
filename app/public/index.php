<?php
require_once "../bootstrap.php";

use App\ApiApp;

define('DEBUG_MODE', true);
(new ApiApp())->run();
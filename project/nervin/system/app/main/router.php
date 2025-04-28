<?php

namespace app\main;

use system\server\Route;

Route::start(
    Route::any()->controller("FirstPage::get_first_page")
)->base($path, $app, $arr)->go();

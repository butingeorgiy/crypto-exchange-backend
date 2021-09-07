<?php

Route::get('ping', ['App\Http\Controllers\Api\V1\PingController', 'checkPing']);
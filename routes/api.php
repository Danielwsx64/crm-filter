<?php

use Illuminate\Http\Request;

Route::get('background', 'Api\MiscellaneousController@backgroundUrl')->name('background_url');

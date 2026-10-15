<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/api/docs', 'swagger')->name('swagger.ui');

Route::get('/api/docs/openapi.json', function () {
    $document = json_decode(file_get_contents(resource_path('docs/openapi.json')), true, 512, JSON_THROW_ON_ERROR);
    $document['servers'] = [['url' => url('/api'), 'description' => 'Current application']];
    return response()->json($document);
})->name('swagger.spec');

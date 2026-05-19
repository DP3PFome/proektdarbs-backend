<?php
/*
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
*/

use Illuminate\Support\Facades\DB;

Route::get('/check-db', function () {
    try {
        DB::connection()->getPdo();
        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
        return [
            'connected' => true,
            'tables' => $tables
        ];
    } catch (\Exception $e) {
        return [
            'connected' => false,
            'error' => $e->getMessage()
        ];
    }
});

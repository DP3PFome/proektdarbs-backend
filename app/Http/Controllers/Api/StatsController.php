<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Item;
use App\Models\User;

class StatsController extends Controller
{
    public function index()
    {
        return response()->json([
            'collections' => Collection::count(),
            'items' => Item::count(),
            'users' => User::count()
        ]);
    }
}

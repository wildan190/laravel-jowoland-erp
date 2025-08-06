<?php

namespace App\Http\Controllers\App;

use App\Action\App\Dashboard\GetAction;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(GetAction $action)
    {
        $data = $action->execute();

        return view('app.dashboard', $data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_personnel = User::whereDate('dofa', '2019-01-01')->count();
        $synched = User::whereDate('dofa', '2019-01-01')->where('synched', 1)->count();
        $unsynched = User::whereDate('dofa', '2019-01-01')->where('synched', 0)->count();
        $admin =User::whereDate('dofa', '!=', '2019-01-01')->count();
        return view('dashboard.dashboard', compact(['total_personnel', 'synched', 'unsynched', 'admin']));
    }

}

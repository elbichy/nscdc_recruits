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
        $total_personnel = User::count();
        $total_formations = Formation::count();
        $commissioned_officers = User::where('cadre', 'superintendent')->count();
        $other_rank = User::where('cadre', 'assistant')->where('cadre', 'inspectorate')->count();
        return view('dashboard.dashboard', compact(['total_personnel', 'total_formations', 'commissioned_officers', 'other_rank']));
    }

}

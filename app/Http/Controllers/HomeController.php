<?php
namespace App\Http\Controllers;

use App\Format;
use App\Http\Requests;
use App\Podty\ApiClient;
use App\Podty\Podcasts;
use App\Podty\UserEpisodes;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    use Format;

    public function index()
    {
        if (!Auth::user() || Auth::user()->podcasts_count < 1) {
            return redirect('/discover');
        }

        $podcasts = (new UserEpisodes(new ApiClient))->latests(Auth::user()->name, 0, 100);
        $podcasts = collect($podcasts['data']);

        return view('home')->with([
            'podcasts' => $podcasts,
            'title' => 'Latests Episodes'
        ]);
    }

    public function discover()
    {
        return view('discover')->with([
            'content' => (new Podcasts)->top(),
            'title' => 'Discover'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Support\PortfolioData;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('index');
    }

    public function index_projects()
    {
        return view('projects', [
            'tags' => PortfolioData::tags(),
            'projects' => PortfolioData::projects(),
        ]);
    }
}

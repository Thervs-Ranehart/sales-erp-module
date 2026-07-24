<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        return view('about.index', [
            'title' => 'About Us',
            'subtitle' => 'Learn about the organization and the system that supports its sales operations',
        ]);
    }
}

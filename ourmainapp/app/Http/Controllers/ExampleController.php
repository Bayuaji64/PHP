<?php

// php artisan make:controller UserController

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function homePage()
    {
        $ourName = 'Bayuaji';
        $animals = ['Dog', 'Cat', 'Dragon'];
        return  view('homePage', ['name' => $ourName, 'allAnimals' => $animals]);
    }

    public function aboutPage()
    {
        return view('single-post');
    }
}

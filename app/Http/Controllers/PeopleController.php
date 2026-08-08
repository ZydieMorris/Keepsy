<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class PeopleController extends Controller
{
    /**
     * Show the people the user shares with.
     */
    public function index(): Response
    {
        return Inertia::render('people/Index');
    }
}

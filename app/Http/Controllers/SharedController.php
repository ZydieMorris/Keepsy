<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class SharedController extends Controller
{
    /**
     * Show the albums shared with the user.
     */
    public function index(): Response
    {
        return Inertia::render('shared/Index');
    }
}

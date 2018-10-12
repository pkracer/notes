<?php

namespace App\Http\Controllers;


class HomeController extends Controller
{
    public function index()
    {
        $notes = request()->user()
            ->notes()
            ->latest('updated_at')
            ->get();

        return view('welcome', compact('notes'));
    }
}

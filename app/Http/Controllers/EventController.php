<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $users = User::with([
            'org',
            'events' => function($query) {
                $query->orderBy('valid_from', 'asc');
            }
        ])->get();

        return view('events.index', compact('users'));
    }
}

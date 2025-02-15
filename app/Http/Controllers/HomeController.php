<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function showWelcome()
    {
        return view('welcome');
    }

    public function showPreview()
    {
        return view('preview');
    }
    public function showCalendar()
    {
        return view('calendar');
    }
    public function showAdminCalendar()
    {
        return view('admin.admin_calendar');
    }
}

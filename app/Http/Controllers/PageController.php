<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function login()
    {
        return view('login');
    }

    public function assets()
    {
        return view('assets');
    }

    public function deposit()
    {
        return view('deposit');
    }

    public function withdrawal()
    {
        return view('withdrawal');
    }

    public function depositRecords()
    {
        return view('deposit-records');
    }

    public function withdrawalRecords()
    {
        return view('withdrawal-records');
    }

    public function transferHistory()
    {
        return view('transfer-history');
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function settings()
    {
        return view('settings');
    }
}

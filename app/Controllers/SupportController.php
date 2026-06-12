<?php namespace App\Controllers;

class SupportController extends BaseController
{
    public function index(): string
    {
        return view('support/index');
    }
}
<?php namespace App\Controllers;

class SupportController extends BaseController
{
    public function index(): string
    {
        return $this->view('support/index');
    }
}
<?php
// app/Controllers/ReportController.php
namespace App\Controllers;

class ReportController extends BaseController
{
    public function index(): string
    {
        // Fungsi ini yang dicari CI4 untuk me-load UI Master Form
        return view('reports/index');
    }
}
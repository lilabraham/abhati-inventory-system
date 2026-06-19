<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\App;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 */
abstract class BaseController extends Controller
{
    protected $data = [];
    protected $config;
    protected $session;
    protected $response;

    public function __construct()
    {
        $this->config = new App();
        $this->request = \Config\Services::request();
        $this->data['config'] = $this->config;
    }

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);
    }

    /**
     * Custom view engine Abhati Group - Diselaraskan dengan CI4 Shield
     */
    protected function view(string $view, array $data = [], array $options = []): string
    {
        $router = service('router');
        $controller = $router->controllerName();
        $data['access'] = [
            'dashboard' => ['Home'],
            'layouts'    => ['LayoutDefault', 'LayoutTransparent', 'LayoutTopNavigation'],
            // tambahkan menu group lain di sini
        ];
        
        // Membersihkan nama controller dari namespace
        $controller = str_replace('App\\Controllers\\', '', $controller);
        $controller = str_replace('\\', '', $controller);
        $data['controller'] = $controller;

        // Nama Method yang sedang dipanggil
        $data['method'] = $router->methodName();

        // Proses render view spesifik
        $this->data['content'] = view($view, $data, $options);
        
        // Kembalikan ke master layout Abhati Group
        return view('layouts/main', $this->data, $options);
    }

    protected function responseJson($data = [], $code = 200)
    {
        return $this->response->setStatusCode($code)
            ->setContentType('application/json')
            ->setJSON($data);
    }

    // Helper untuk validasi form cepat
    protected function validateInput(array $rules)
    {
        if (!$this->validate($rules)) {
            return [
                'status'  => false,
                'message' => $this->validator->getErrors()
            ];
        }
        return ['status' => true];
    }
}
<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\App;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $data   = [];
    protected $config;
    // FIX #3: $session dihapus — tidak pernah di-assign
    // FIX #4: $response tidak perlu deklarasi ulang — sudah ada di parent Controller

    public function __construct()
    {
        $this->config              = new App();
        $this->request             = \Config\Services::request();
        $this->data['config']      = $this->config;
    }

    // FIX #5: initController dihapus — hanya wrap parent::, dead override

    protected function view(string $view, array $data = [], array $options = []): string
    {
        $router = service('router');

        // FIX #2: $data['access'] dihapus — tidak dipakai di view manapun

        $controller = str_replace(['App\\Controllers\\', '\\'], '', $router->controllerName());

        $data['controller'] = $controller;
        $data['method']     = $router->methodName();

        $this->data['content'] = view($view, $data, $options);

        return view('layouts/main', $this->data, $options);
    }

    // FIX #4: responseJson pakai $this->response dari parent (sudah di-assign oleh initController)
    protected function responseJson(array $data = [], int $code = 200): ResponseInterface
    {
        return $this->response
            ->setStatusCode($code)
            ->setContentType('application/json')
            ->setJSON($data);
    }

    // validateInput: pending grep result
}

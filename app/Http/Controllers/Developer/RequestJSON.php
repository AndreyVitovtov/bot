<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;

class RequestJSON extends Controller {
    public function index() {
        $view = view('developer.request.request-json');
        $view->json = file_get_contents(public_path()."/json/request.json");
        $view->menuItem = 'request';
        return $view;
    }
}

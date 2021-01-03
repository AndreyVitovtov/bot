<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\models\Curl;
use Illuminate\Http\Request;

class RequestJSON extends Controller {
    public function index() {
        $view = view('developer.request.request-json');
        $view->json = file_get_contents(public_path()."/json/request.json");
        $view->menuItem = 'request';
        return $view;
    }

    public function send(Request $request) {
        $request = $request->input();
        return view('developer.request.send', [
            'method' => isset($request['method']) ? $request['method'] : null,
            'messenger' => isset($request['messenger']) ? $request['messenger'] : null,
            'url' => isset($request['url']) ? $request['url'] : null,
            'data' => isset($request['data']) ? $request['data'] : null,
            'response' => isset($request['response']) ? $request['response'] : null,
            'menuItem' => 'send_request'
        ]);
    }

    public function getResponse(Request $request) {
        $request = $request->input();
        $headers = [];
        if(isset($request['messenger']) && $request['messenger'] == 'viber') {
            $headers = [
                'VIBER:true'
            ];
        }

        $curl = new Curl();

        if($request['method'] == 'post') {
            $response = $curl->POST($request['url'], $request['data'], $headers);
        }
        else {
            $response = $curl->GET($request['url'], $headers);
        }

        return view('developer.request.redirect-send', [
            'method' => $request['method'],
            'messenger' => isset($request['messenger']) ? $request['messenger'] : null,
            'url' => $request['url'],
            'data' => isset($request['data']) ? $request['data'] : null,
            'response' => $response
        ]);
    }
}

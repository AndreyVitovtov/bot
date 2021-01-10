<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\models\API\Telegram;
use App\models\API\Viber;
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
        $response = isset($request['response']) ? $request['response'] : null;
        file_put_contents(public_path('html/response.html'), $response);
        return view('developer.request.send', [
            'method' => isset($request['method']) ? $request['method'] : null,
            'messenger' => isset($request['messenger']) ? $request['messenger'] : null,
            'url' => isset($request['url']) ? $request['url'] : null,
            'data' => isset($request['data']) ? $request['data'] : null,
            'response' => $response,
            'menuItem' => 'send_request'
        ]);
    }

    public function getResponse(Request $request) {
        $request = $request->input();
        if(isset($request['type']) && isset($request['token'])) {
            if($request['type'] == 'telegram') {
                $messenger = new Telegram($request['token']);
            }
            else {
                $messenger = new Viber($request['token']);
            }
            $response = ($messenger->setWebhook(url('bot/index')));
        }
        else {
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
        }

        return view('developer.request.redirect-send', [
            'method' => isset($request['method']) ? $request['method'] : 'post',
            'messenger' => isset($request['messenger']) ? $request['messenger'] : null,
            'url' => isset($request['url']) ? $request['url'] : url('bot/index'),
            'data' => isset($request['data']) ? $request['data'] : null,
            'response' => $response
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;

class ApiControl extends Controller
{
    public function createMember($memberData)
    {
        //Create Client object to deal with
        $client = new Client();

        // Define the request parameters
        $url = 'http://127.0.0.1:8088/personnel/api/employees/';

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $data = [
            "emp_code" => 13,
            "department" => 1,
            "area" => 3,
            "company" => 1,
            "first_name" => "test",
            "last_name" => "test",
            "device_password" => 1234
        ];

        // POST request using the created object
        $postResponse = $client->post($url, [
            'auth' => ['bchrome', 'abcd1234'],
            'headers' => $headers,
            'json' => $data,
        ]);

        // Get the response code
        $responseCode = $postResponse->getStatusCode();
        return response()->json(['response_code' => $responseCode]);


    }
}

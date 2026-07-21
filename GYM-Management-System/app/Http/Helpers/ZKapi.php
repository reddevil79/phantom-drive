<?php

namespace App\Http\Helpers;

use Exception;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class ZKapi
{
//    protected $url;
//    protected $username;
//    protected $password;
//    protected $client;

    //"CIRCLE (27.687368367815456 85.31229827553034, 225.482177734373)" => value of area in geocode

//    public function __construct()
//    {
//        $this->url = config('sentinel.url');
//        $this->username = config('sentinel.username');
//        $this->password = config('sentinel.password');
//        $this->client = Http::withHeaders([
//            'Accept' => 'application/json'
//        ])->withBasicAuth($this->username, $this->password);
//    }

    public function createMember()
    {
        //Create Client object to deal with
        $client = new Client();

        // Define the request parameters
        $url = 'http://127.0.0.1:8088';
        $auth = ['bchrome', 'abcd1234'];

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
            'auth' => $auth,
            'headers' => $headers,
            'json' => $data,
        ]);

        // Get the response code
        $responseCode = $postResponse->getStatusCode();
        return response()->json(['response_code' => $responseCode]);


    }

    public function storeDriver(Customer $customer)
    {
        return $this->post("$this->url/api/drivers", [
            "name" => $customer->name,
            "uniqueId" => $customer->phone,
            "attributes" => [
                "email" => $customer->email,
                "phone" => $customer->phone,
                "address" => $customer->address,
            ]
        ]);
    }

    public function storeUser(Customer $customer)
    {
        return $this->post("$this->url/api/users", [
            "name" => $customer->name,
            "email" => $customer->email ?? $customer->phone,
            "password" => 'crooz321',
            "phone" => $customer->phone,
            "readonly" => false,
            "administrator" => false,
            "disabled" => false,
            "attributes" => [
                "address" => $customer->address,
                "notificationTokens" => ''
            ]
        ]);
    }
    public function storeEmergencyUser($data)
    {
        return $this->post("$this->url/api/users", [
            "name" => $data['e_name'],
            "email" => $data['e_name'],
            "password" => $data['p_name'],
            "phone" => '9818411728',
            "expirationTime" => $data['expiration'],
            "readonly" => false,
            "administrator" => false,
            "disabled" => false,
            "attributes" => [
                "address" => 'Nepal',
                "notificationTokens" => ''
            ]
        ]);
    }

    public function storeTripUser($email)
    {
        return $this->post("$this->url/api/users", [
            "name" => $email,
            "email" => $email . "@sentinellab.io",
            "password" => 'trip321',
            "phone" => "",
            "readonly" => false,
            "administrator" => false,
            "disabled" => false,
        ]);
    }

    public function updateDriver(Customer $customer)
    {
        if ($customer->driver_id) {
            return $this->put("$this->url/api/drivers/" . $customer->driver_id, [
                "id" => $customer->driver_id,
                "name" => $customer->name,
                "uniqueId" => $customer->phone,
                "attributes" => [
                    "email" => $customer->email,
                    "phone" => $customer->phone,
                    "address" => $customer->address,
                ]
            ]);
        }
    }

    public function updateUser(Customer $customer, $notificationToken = [])
    {
        if ($customer->driver_id) {
            return $this->put("$this->url/api/users/" . $customer->driver_id, [
                "id" => $customer->driver_id,
                "name" => $customer->name,
                "email" => $customer->email ?? $customer->phone,
                "phone" => $customer->phone,
                "readonly" => false,
                "administrator" => false,
                "disabled" => false,
                "attributes" => [
                    "address" => $customer->address,
                    "notificationTokens" => implode(",", $notificationToken)
                ]
            ]);
        }
    }
    public function updateUserRemove(Customer $customer)
    {
        if ($customer->driver_id) {
            return $this->put("$this->url/api/users/" . $customer->driver_id, [
                "id" => $customer->driver_id,
                "name" => $customer->name,
                "email" => $customer->email ?? $customer->phone,
                "phone" => $customer->phone,
                "readonly" => false,
                "administrator" => false,
                "disabled" => false,
                "attributes" => [
                    "address" => $customer->address
                ]
            ]);
        }
    }

    public function disableBluecrew($trip)
    {

            return $this->put("$this->url/api/users/" . $trip['group_id'], [
                "id" => $trip['group_id'],
                "name" => $trip['title'],
                "email" => $trip['title'] . '@sentinellab.io',
                "password" => 'trip321',
                "phone" => "",
                "readonly" => false,
                "administrator" => false,
                "disabled" => true,
            ]);

    }

    public function updateDevice(Vehicle $vehicle)
    {
        return $this->put("$this->url/api/devices/$vehicle->device_id", [
            "id" => $vehicle->device_id,
            "name" => $vehicle->slug,
            "uniqueId" => $vehicle->identifier,
            "status" => "",
            "disabled" => !$vehicle->is_active,
            "lastUpdate" => $vehicle->updated_at->toISOString(),
            "positionId" => 0,
            "groupId" => 0,
            "phone" => "",
            "model" => $vehicle->vehicleModel->name,
            "contact" => "",
            "category" => "Motorcycle",
            "geofenceIds" => [0]
        ]);
    }

    public function deleteDevice(Vehicle $vehicle)
    {
        return $this->delete("$this->url/api/devices/$vehicle->device_id", []);
    }

    public function assignDevice(Customer $customer, Vehicle $vehicle)
    {
        return $this->post("$this->url/api/permissions", [
            "userId" => $customer->driver_id,
            "deviceId" => $vehicle->device_id
        ]);
    }

    public function deassignDevice(Customer $customer, Vehicle $vehicle)
    {
        return $this->delete("$this->url/api/permissions", [
            "userId" => $customer->driver_id,
            "deviceId" => $vehicle->device_id
        ]);
    }

    public function assignNotifications(Vehicle $vehicle)
    {
        $notifications = collect(config('sentinel.notifications'))->where('enabled', true);

        Http::pool(function (Pool $pool) use ($vehicle, $notifications) {
            $notifications->map(function ($permission) use ($vehicle, $pool) {
                return $pool->withHeaders([
                    'Accept' => 'application/json'
                ])->withBasicAuth($this->username, $this->password)
                    ->post("$this->url/api/permissions", [
                        "deviceId" => $vehicle->device_id,
                        "notificationId" => $permission['id']
                    ]);
            });
        });
    }


    public function assignToPolice( Vehicle $vehicle)
    {

//        return $this->put("$this->url/api/devices/$vehicle->device_id", [
//            "id" => $vehicle->device_id,
//            "groupId" => 4,
//            "name" => $vehicle->slug,
//            "uniqueId" => $vehicle->identifier,
//            "model" => $vehicle->vehicleModel->name,
//            "phone" => "",
//            "contact" => "",
//            "category" => $vehicle->category
//        ]);

        return $this->post("$this->url/api/permissions", [
            "userId" => 34,
            "deviceId" => $vehicle->device_id
        ]);

        }
    public function acceptTripInvitation( Vehicle $vehicle, $tripUserId)
    {

        return $this->post("$this->url/api/permissions", [
            "userId" => $tripUserId,
            "deviceId" => $vehicle->device_id
        ]);

        }

//    public function createGroup($tit)
//    {
//
//        return $this->post("$this->url/api/groups", [
//            "id" => 0,
//            "name" => $tit,
//            "groupId" => 0,
//
//        ]);
//
//        }


    public function assignAllNotifications($userId)
    {
        $notifications =  Http::withBasicAuth($this->username, $this->password)
                ->get("$this->url/api/notifications");

        $allNotifications = json_decode($notifications->body());
        foreach ($allNotifications as $key=> $notification) {
           Http::withBasicAuth($this->username, $this->password)
                ->post("$this->url/api/permissions", [
                    'userId' => $userId,
                    'notificationId' => $notification->id,
                ]);
        }




    }


    public function assignUserToTrip($vehicle, $createUserid)
    {
        return $this->post("$this->url/api/permissions", [
            "userId" => $createUserid,
            "deviceId" => $vehicle->device_id
        ]);
    }

    public function deassign_UserToTrip($deviceId, $userId)
    {
        return $this->delete("$this->url/api/permissions", [
            "userId" => $userId,
            "deviceId" => $deviceId
        ]);
    }

    public function assignVehicleToEmergencyUser($data, $createUserid)
    {
        return $this->post("$this->url/api/permissions", [
            "userId" => $createUserid,
            "deviceId" => $data['vehicle_id']
        ]);
    }

    public function assignCommands(Vehicle $vehicle)
    {
        $commands = collect(config('sentinel.commands'));

        Http::pool(function (Pool $pool) use ($vehicle, $commands) {
            $commands->map(function ($command) use ($vehicle, $pool) {
                return $pool->withHeaders([
                    'Accept' => 'application/json'
                ])->withBasicAuth($this->username, $this->password)
                    ->post("$this->url/api/permissions", [
                        "deviceId" => $vehicle->device_id,
                        "commandId" => $command['id']
                    ]);
            });
        });
    }

    public function fetchSession()
    {
        return $this->get("$this->url/api/session");
    }

    public function createSession()
    {
        $data = [
            "email" => $this->username,
            "password" => $this->password
        ];

        return $this->postAsForm("$this->url/api/session", $data);
    }

    public function getPosition($device_id)
    {
        $position = $this->get("$this->url/api/positions?deviceId=$device_id");

        if (is_array($position) && array_key_exists(0, $position)) {
            return $position[0];
        }
        return [];
    }

    public function getEvents($device_id, $from, $to)
    {
        return $this->get("$this->url/api/reports/events?deviceId=$device_id&from=$from&to=$to");
    }

    public function getTrips($device_id, $from, $to)
    {
        return $this->get("$this->url/api/reports/trips?deviceId=$device_id&from=$from&to=$to");
    }

    public function getNotifications($device_id)
    {
        return $this->get("$this->url/api/notifications?deviceId=$device_id");
    }

    public function getDevice($device_id)
    {
        return $this->get("$this->url/api/devices/$device_id");
    }

    private function get($url)
    {
        $response = $this->client->get($url);

        if ($response->failed()) {
            Log::debug($response);
            throw new Exception($response->getBody()->getContents());
        }

        return $response->json();
    }

    private function post($url, $data)
    {
        $response = $this->client->post($url, $data);

        if ($response->failed()) {
            Log::debug($response);
            throw new Exception($response->getBody()->getContents());
        }

        return $response->json();
    }

    private function postAsync($url, $data)
    {
        return $this->client->async()->post($url, $data);
    }

    private function postAsForm($url, $data)
    {
        $response = $this->client->asForm()->post($url, $data);

        if ($response->failed()) {
            Log::debug($response);
            throw new Exception($response->getBody()->getContents());
        }

        return $response->json();
    }

    private function put($url, $data)
    {
        $response = $this->client->put($url, $data);

        if ($response->failed()) {
            Log::debug($response);
            throw new Exception($response->getBody()->getContents());
        }

        return $response->json();
    }

    private function delete($url, $data)
    {
        $response = $this->client->delete($url, $data);

        if ($response->failed()) {
            Log::debug($response);
            throw new Exception($response->getBody()->getContents());
        }

        return $response->json();
    }
}

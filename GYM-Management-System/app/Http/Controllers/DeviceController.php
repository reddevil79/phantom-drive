<?php

namespace App\Http\Controllers;

use App\Device;
use Illuminate\Http\Request;
use App\Http\Helpers\ZKT_KALPER;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $devices = Device::paginate(10);


        return view('device.index', compact('devices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('device.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['serial' => 'unique:mst_devices,serial']);

        $device = ['name'=>$request->name,
            'serial'=> $request->serial,
            'ip'=> $request->ip,
            'area' => $request->area, ];
        $device = new Device($device);
        $device->save();

        if ($device->id) {

            flash()->success('User was successfully registered');

            return redirect('device.index');
        } else {
            flash()->error('Error while user registration');

            return redirect('device.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $device = Device::findOrFail($id);

        return view('device.edit', compact('device'));
    }
    public function restart($id)
    {
        $device = Device::findOrFail($id);
        $zk = new ZKT_KALPER($device->ip, 4370);
        $zk->connect();
        $zk->disableDevice();
        $res = $zk->restart();
        $zk->enableDevice();

        flash()->success('Device restarted successfully');

        return view('device');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $device = Device::findOrFail($id);

        $device->name = $request->name;
        $device->serial = $request->serial;


        $device->ip = $request->ip;
        $device->area = $request->area;

        $device->update();
        $device->save();
        flash()->success('User details was successfully updated');

        return redirect('device');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

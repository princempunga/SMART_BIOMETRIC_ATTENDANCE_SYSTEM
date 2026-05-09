<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::all();
        return view('admin.devices.index', compact('devices'));
    }

    public function create()
    {
        return view('admin.devices.create');
    }

    public function store(StoreDeviceRequest $request)
    {
        Device::create($request->validated());
        return redirect()->route('admin.devices.index')->with('success', 'Device created successfully.');
    }

    public function edit(Device $device)
    {
        return view('admin.devices.edit', compact('device'));
    }

    public function update(UpdateDeviceRequest $request, Device $device)
    {
        $device->update($request->validated());
        return redirect()->route('admin.devices.index')->with('success', 'Device updated successfully.');
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('admin.devices.index')->with('success', 'Device deleted successfully.');
    }
}

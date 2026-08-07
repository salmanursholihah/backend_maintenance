<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceManagementController extends Controller
{
  public function index()
    {
        $services = Service::latest()->paginate(20);
        return view('pages.services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'base_price' => 'required|numeric'
        ]);

        Service::create($data);

        return back()->with('success', 'Service berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $service->update($request->all());

        return back()->with('success', 'Service berhasil diupdate');
    }

    public function destroy($id)
    {
        Service::findOrFail($id)->delete();

        return back()->with('success', 'Service berhasil dihapus');
    }
}



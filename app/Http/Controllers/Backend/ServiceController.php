<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::latest()->get();
        return view('backend.service.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.service.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate form
        $this->validate($request, [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required|min:5',
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('upload/service', $image->hashName());

        //create post
        Service::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
        ]);

        //redirect to index
        return redirect()->route('service')->with(['success' => 'Data Berhasil Disimpan!']);
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
        $service = Service::findOrFail($id);
        return view('backend.service.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $service_id = $request->id;
  $service = Service::findOrFail($service_id);
        $oldImage = $service->image;

        //check if image is uploaded
        if ($request->hasFile('image')) {
            //upload new image
            $image = $request->file('image');
            $image->storeAs('upload/service', $image->hashName());

            //delete old image
            Storage::delete('upload/service/'.$service->image);

            Service::findOrFail($service_id)->update([
                'title' => $request->title,
               'image'     => $image->hashName(),

            ]);
            $notification = array(
            'message' => 'Service Updated with Image Successfully',
            'alert-type' => 'success'
        );

            return to_route('service')->with($notification);
        } else {
            Service::findOrFail($service_id)->update([
                'title' => $request->title,
            ]);
            $notification = array(
            'message' => 'Service Updated without Image Successfully',
            'alert-type' => 'success'
        );

            return to_route('service')->with($notification);
        } // end Else
    } // End Method


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
             //delete image
        Storage::delete('upload/service/'. $service->image);

        //delete about
        $service->delete();

     $notification = array(
            'message' => 'Service Deleted Image Successfully',
            'alert-type' => 'success'
        );

            return redirect()->back()->with($notification);
    }
}

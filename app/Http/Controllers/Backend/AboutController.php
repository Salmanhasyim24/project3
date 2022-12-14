<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $abouts = About::latest()->get();
        return view('backend.about.index', compact('abouts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.about.create');
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
            'name'     => 'required|min:5',
            'description'   => 'required|min:10'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('upload/about', $image->hashName());

        //create post
        About::create([
            'image'     => $image->hashName(),
            'name'     => $request->name,
            'description'   => $request->description
        ]);

        //redirect to index
        return redirect()->route('about')->with(['success' => 'Data Berhasil Disimpan!']);
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
    public function edit(About $about, $id)
    {
        $about = About::findOrFail($id);
        return view('backend.about.edit', compact('about'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, About $about, $id)
    {
        $about_id = $request->id;

        //check if image is uploaded
        if ($request->hasFile('image')) {
            //upload new image
            $image = $request->file('image');
            $image->storeAs('upload/about', $image->hashName());

            //delete old image
            Storage::delete('upload/about/'.$about->image);

            About::findOrFail($about_id)->update([
                'name' => $request->name,
                'description' => $request->description,
               'image'     => $image->hashName(),

            ]);
            $notification = array(
            'message' => 'Home Slide Updated with Image Successfully',
            'alert-type' => 'success'
        );

            return to_route('about')->with($notification);
        } else {
            About::findOrFail($about_id)->update([
                'name' => $request->name,
                'description' => $request->description,

            ]);
            $notification = array(
            'message' => 'Home Slide Updated without Image Successfully',
            'alert-type' => 'success'
        );

            return to_route('about')->with($notification);
        } // end Else
    } // End Method


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(About $about, $id)
    {
        $about = About::findOrFail($id);
             //delete image
        Storage::delete('upload/about/'. $about->image);

        //delete about
        $about->delete();

     $notification = array(
            'message' => 'About Deleted Image Successfully',
            'alert-type' => 'success'
        );

            return redirect()->back()->with($notification);


    }
}

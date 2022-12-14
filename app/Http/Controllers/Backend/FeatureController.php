<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $features = Feature::latest()->get();

        return view('backend.feature.index', compact('features'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.feature.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $this->validate($request, [
            'title'     => 'required|min:5',
            'description'   => 'required|min:10'
        ]);

        //create post
        Feature::create([
            'title'     => $request->title,
            'description'   => $request->description
        ]);

        //redirect to index
        return redirect()->route('feature')->with(['success' => 'Data Berhasil Disimpan!']);
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
        $feature = Feature::findOrFail($id);
        return view('backend.feature.edit', compact('feature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $feature_id = $request->id;

          Feature::findOrFail($feature_id)->update([
                'title' => $request->title,
                'description' => $request->description,

            ]);
            $notification = array(
            'message' => 'Feature Updated with Image Successfully',
            'alert-type' => 'success'
        );

        return to_route('feature')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Feature::findOrFail($id)->delete();
      $notification = array(
            'message' => 'Feature Deleted with Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}

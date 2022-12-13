<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
   public function edit(){

        $banner = Banner::find(1);
        return view('backend.banner.index',compact('banner'));

     } // End Method



     public function update(Request $request, Banner $banner){

        $banner_id = $request->id;

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //upload new image
            $image = $request->file('image');
            $image->storeAs('upload/banner', $image->hashName());

            //delete old image
            Storage::delete('upload/banner/'.$banner->image);

            Banner::findOrFail($banner_id)->update([
                'name' => $request->name,
                'description' => $request->description,
               'image'     => $image->hashName(),

            ]);
            $notification = array(
            'message' => 'Home Slide Updated with Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

        } else{

            Banner::findOrFail($banner_id)->update([
                'name' => $request->name,
                'description' => $request->description,

            ]);
            $notification = array(
            'message' => 'Home Slide Updated without Image Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

        } // end Else

     } // End Method
}



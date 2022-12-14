<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $portfolios = Portfolio::latest()->get();

        return view('backend.portfolio.index', compact('portfolios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.portfolio.create');
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
        $image->storeAs('upload/portfolio', $image->hashName());

        //create post
        Portfolio::create([
            'image'     => $image->hashName(),
            'name'     => $request->name,
            'description'   => $request->description
        ]);

        //redirect to index
        return redirect()->route('portfolio')->with(['success' => 'Data Berhasil Disimpan!']);
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
      $portfolio = Portfolio::findOrFail($id);
        return view('backend.portfolio.edit', compact('portfolio'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Portfolio $portfolio)
    {
    $portfolio_id = $request->id;
     $portfolio = Portfolio::findOrFail($portfolio_id);
        $oldImage = $portfolio->image;

        //check if image is uploaded
        if ($request->hasFile('image')) {
            //upload new image
            $image = $request->file('image');
            $image->storeAs('upload/portfolio', $image->hashName());

            //delete old image
            Storage::delete('upload/portfolio/'.$portfolio->image);

            Portfolio::findOrFail($portfolio_id)->update([
                'name' => $request->name,
                'description' => $request->description,
               'image'     => $image->hashName(),

            ]);
            $notification = array(
            'message' => 'Portfolio Updated with Image Successfully',
            'alert-type' => 'success'
        );

            return to_route('portfolio')->with($notification);
        } else {
            Portfolio::findOrFail($portfolio_id)->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            $notification = array(
            'message' => 'Portfolio Updated without Image Successfully',
            'alert-type' => 'success'
        );

            return to_route('portfolio')->with($notification);
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
          $portfolio = Portfolio::findOrFail($id);
             //delete image
        Storage::delete('upload/portfolio/'. $portfolio->image);

        //delete portfolio
        $portfolio->delete();

     $notification = array(
            'message' => 'Portfolio Deleted Image Successfully',
            'alert-type' => 'success'
        );

            return redirect()->back()->with($notification);

    }
}

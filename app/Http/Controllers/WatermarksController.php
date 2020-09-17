<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class WatermarksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $companyId = Auth::user()->company_id;
        // $imagePath = URL::to('/').'/storage/uploads/'.$companyId.'/'.$request['watermark'];
        $imagePath = Storage::disk('uploads')->get($request->watermark);
        dd($imagePath);
        $imagePath = storage_path('uploads'.'/'.$companyId.'/'.$request->watermark);
        // dd($imagePath);
        // ->get($request->watermark)
       
        // Create Director if does not exist
        $userStorage = '/public/uploads/' . $companyId.'/watermark';
        if (!Storage::exists($userStorage)) {
            Storage::makeDirectory($userStorage, 0755, true);
        }
        $userWatermakrStorageDir = storage_path() . '/app' . $userStorage;
        $watermark = Image::make($imagePath);     
        dd($watermark);
        $watermark->save($userWatermakrStorageDir . '/' . $request->watermark);

        // Return response
        return response()->json([
            'message' => 'Water mark has been saved',
        ], 200);

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
        //
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
        //
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

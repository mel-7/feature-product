<?php

namespace App\Http\Controllers;

use App\Watermark;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $companyId = Auth::user()->company_id; 
        $uploadDate = Carbon::now()->format('YmdHis');

        // Create Director if does not exist
        $userStorage = '/public/uploads/' . $companyId.'/watermark';
        if (!Storage::exists($userStorage)) {
            Storage::makeDirectory($userStorage, 0755, true);
        }
        $userWatermakrStorageDir = storage_path() . '/app' . $userStorage;
        $watermark = Image::make(public_path('storage/uploads/'.$companyId.'/'.$request->watermark));

        // Convert the image to png first. The opacity() is currently not working on jpg.
        // $watermark->encode('png');
        // $watermark->encode('png')->trim();

        // Get the converted png call make() again.

        // Resize image
        $watermark->resize($request->image_width, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        // Set opacity then save to company's directory
        $watermark->opacity($request->image_opacity);
        $watermark->save($userWatermakrStorageDir . '/'.$request->watermark);

        // Check if company has Watermark
        $watermarkExist = Watermark::where('company_id', $companyId)->first();

        // Set Watermark Array
        $createWaterMarkArray = [
            'path' => $request['watermark'],
            'media_file_id' => $request['media_file_id'],
            'position' => $request['position'],
            'offset_space' => $request['offset_space'],
            'image_width' => $request['image_width'],
            'image_opacity' => $request['image_opacity'],
            'company_id' => $companyId,
            'status' => $request['status'],
            'updated_at' => Carbon::now(),
        ];
        $updateWaterMarkArray = [
            'path' => $request['watermark'],
            'media_file_id' => $request['media_file_id'],
            'position' => $request['position'],
            'offset_space' => $request['offset_space'],
            'image_width' => $request['image_width'],
            'image_opacity' => $request['image_opacity'],
            'status' => $request['status'],
            'updated_at' => Carbon::now(),
        ];

        if($watermarkExist){
            $watermarkQuery = $watermarkExist->update($updateWaterMarkArray);
        }else{
            $watermarkQuery = Watermark::create($createWaterMarkArray);
        }

        // Return response
        if($watermarkQuery){
            return response()->json([
                'message' => 'Water mark has been saved',
            ], 200);
        }else{
            return response()->json([
                'message' => 'Error saving in database',
            ], 500);
        }
    }

    public function fetchWatermark()
    {
        $watermark = Watermark::where('company_id', Auth::user()->company_id)->with('media_file')->first();
        return response()->json($watermark);
    }

    public function destroy()
    {
        $watermarkDelete = Watermark::where('company_id', Auth::user()->company_id)->first();
        $watermarkDelete->delete();
        return response()->json([
            'message' => 'Watermark has been deleted',
        ], 200);
    }
}

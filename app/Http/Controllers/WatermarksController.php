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
        $uploadDate = Carbon::now()->format('YmdHis');

        // Check if company has watermark
        $watermarkCheck = Watermark::where('company_id', $companyId)->first();

        if($watermarkCheck == false){
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
            // $watermark->encode('jpg');

            // Get the converted png call make() again.

            // Resize image
            $watermark->resize($request->image_width, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            // Set opacity then save to company's directory
            $watermark->opacity($request->image_opacity);
            $watermark->save($userWatermakrStorageDir . '/' .$uploadDate.'-'.$request->watermark);
        }

        // Save to database
        $waterMarkArray = [
            'path' => $uploadDate.'-'.$request['watermark'],
            'position' => $request['position'],
            'offset_space' => $request['offset_space'],
            'image_width' => $request['image_width'],
            'image_opacity' => $request['image_opacity'],
            'company_id' => $companyId,
            'updated_at' => Carbon::now(),
        ];
        $watermarkSaved = Watermark::updateOrCreate(['company_id' => $companyId], $waterMarkArray);

        // Return response
        if($watermarkSaved){
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
        $watermark = Watermark::where('company_id', Auth::user()->company_id)->first();
        return response()->json($watermark);
    }
}

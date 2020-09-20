<?php

namespace App\Http\Controllers;

use App\Item;
use App\Watermark;
use App\Media_file;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Images
     * https://drive.google.com/drive/folders/1aaI4LEJKpWq1paURHcrIeXnJrGUb6JcG
     */

    public function upload(Request $request)
    {
        // Validate request
        // $this->validate($request, [
        //     'file' => 'required|image|mimes:jpeg,png,jpg|max:204800',
        // ]);

        // Media_files table
        $userFiles = new Media_file;
        $itemsArray = array();
        $fileArray = array();

        $uploadKey = Carbon::now()->format('YmdHis');
        // $uploadKey = "";
        // $uploadKey = Str::random();

        // $userId = auth()->id();
        $user = Auth::user();
        $companyId = $user->company_id;
        $userStorage = '/public/uploads/' . $companyId;
        if (!Storage::exists($userStorage)) {
            Storage::makeDirectory($userStorage, 0755, true);
        }

        // Wrap the files to collection
        $files = Collection::wrap(request()->file('file'));

        // Get the Company's Watermark settings
        $watermark = Watermark::where('company_id', $companyId)->first();

        // Do something on each files uploaded
        $files->each(function ($file, $key) use (&$watermark, &$companyId, &$userStorage, &$itemsArray, &$fileArray, &$request, &$uploadKey) {
            $userStorageDir = storage_path() . '/app' . $userStorage;
            // $fileName = $uploadKey."-".$file->getClientOriginalName();
            $fileName = $file->getClientOriginalName();
            $title = pathinfo($fileName, PATHINFO_FILENAME);
            $extn = $file->getClientOriginalExtension();
            $slugTitle = Str::slug($title, '-');
            $path = $slugTitle."-".$uploadKey.".".$extn;
            // $path = Str::slug($uploadKey."-".$fileName, "-"); // Added upload key to avoid replacing of duplicated filenames
            // $fname = $file->getClientOriginalName();
            // $fileName = substr($name,0,6).'-'.auth()->id().'-'.$randomName;
            // $fileExtensions = array('jpeg','jpg','png','JPEG','JPG','PNG');
            $jpgExtensions = array('jpeg', 'jpg', 'JPEG', 'JPG');
            $pngExtensions = array('png', 'PNG');
            $format = 'jpg';
            if (in_array($extn, $pngExtensions)) {
                $format = 'png';
            }

            // Check file if image or video
            if($request->item_type == 'video'){
                // dd('vidoeeasdasdas');
                $file->move('storage/uploads/'.$companyId.'/', $path); // add user id
                // dd($userStorageDir);
                // $path = $request->file('slide')->storeAs('public/videos/',$fileNameToStore);
                // $img->save($userStorageDir . '/' . $path); // FHD
            }else{
                // File Optimization
                // $img = Image::make($file)->fit(3840,2160); // UHD
                $img = Image::make($file);
                // if ($request->item_type == "360") {
                //     // Optimize 360 images only
                //     $img->fit(1920, 1080);
                //     $img->encode($format, 50);
                // }

                if($watermark && $watermark->status == true){
                    $img->insert('storage/uploads/'.$companyId.'/watermark/'.$watermark->path, $watermark->position, $watermark->offset_space, $watermark->offset_space);
                }

                $img->save($userStorageDir . '/' . $path); // Save to directory
            }

            /**
             * Set item array
             */
            if ($request->add_items == 'true') {
                array_push($itemsArray, array(
                    'item_type' => $request->item_type,
                    'product_id' => $request->product,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ));
            }

            /**
             * Set file array
             */
            $mediaFileType = "";
            if($request->item_type == 'video'){
                $mediaFileType = "video";
            }else{
                $mediaFileType = "image";
            }
            array_push($fileArray, array(
                'file_type' => $mediaFileType,
                'title' => $title,
                'original_name' => $slugTitle."-".$uploadKey,
                'disk' => 'uploads',
                'path' => $path,
                'user_id' => auth()->id(),
                'company_id' => $companyId,
                'item_id' => null,
                'created_at' => Carbon::now(),
            ));
        });
        // 'path' => $uploadKey.".".$extn, // title-date.extension,
        // 'path' => $fileName,
        // 'path' => Str::slug($title, '-')."-".$uploadKey.".".$extn, // title-date.extension,

        // Save to Items table
        // if ($request->add_items == 'true') {
        //     Item::insert($itemsArray);
        //     $recentlySavedItems = Item::where([
        //         'product_id' => $request->product,
        //         'created_at' => $uploadKey,
        //     ])->get();

        //     // Prepare Media_files object before saving Set Connection
        //     foreach ($recentlySavedItems as $key => $item) {
        //         $fileArray[$key]['item_id'] = $item->id;
        //     }
        // }

        // Save the files to Media_files table
        Media_file::insert($fileArray);


        // If addItems == true in UploadZone component
        if ($request->add_items == 'true') {

            // Get the files by original_name
            $originaNamesArray = array_column($fileArray, 'original_name');
            $recentlySavedFiles = Media_file::whereIn('original_name', $originaNamesArray )->get();

            // Prepare items
            // $toInsertItemsArray = array();
            // foreach ($recentlySavedFiles as $file) {
                // array_push($toInsertItemsArray, array(
                //     'item_type' => '360',
                //     'product_id' => $request->product,
                //     'media_file_id' => $file->id,
                //     'created_at' => Carbon::now(),
                //     'updated_at' => Carbon::now()
                // ));
                // }
            foreach ($recentlySavedFiles as $key => $file) {
                $itemsArray[$key]['media_file_id'] = $file->id;
            }
            // Save to Items table
            Item::insert($itemsArray);
        }

        // Return response
        return response()->json([
            'message' => 'Upload Success',
        ], 200);
    }

    public function getItemImages()
    {
        $files = Media_file::where(['user_id' => 1, 'file_type' => 'image'])->take(50)->get();
        return response()->json($files, 200);
    }

    public function showImage($path)
    {
        $m = Media_file::where('path', $path)->firstOrFail();
        if (isset($m)) {
            $file = storage_path() . '/uploads/user-1/1/' . $path;
            return response()->file($file, array('Content-Type' => 'image/jpeg'));
        } else {
            return abort('403');
        }
    }

    public function getUserFilesByID($id)
    {
        $companyId = Auth::user()->company_id;
        $files = Media_file::where(['company_id' => $companyId])->orderBy('created_at', 'DESC')->paginate(200);
        return response()->json($files, 200);
    }
}

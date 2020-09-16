<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\Request;

class VideosController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->product_id =  (int) $request->product_id;
        $this->validateRequest();

        $video = Video::create([
            'title' => $request->title,
            'video_path' => $request->video_path,
            'product_id' => $request->product_id
        ]);
        // response
        return response()->json([
            'video' => $video,
            'message' => 'Video has been created',
        ], 200);
    }

    /**
     * Get All Videos by Product ID
     */
    public function fetchAllVideos($id)
    {
        $videos = Video::where('product_id', $id)->get();
        return response()->json([
            'videos' => $videos,
            'message' => 'Videos have been fetched',
        ], 200);
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
        $video = Video::where('id', $id)->firstOrFail();
        $video->update($this->validateRequest());
        return response()->json([
            'message' => 'Video has been updated',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $video = Video::where('id', $id)->firstOrFail();
        $video->delete();
        return response()->json([
            'message' => 'Video has been deleted',
        ], 200);
    }

    public function validateRequest()
    {
        return request()->validate([
            'title' => ['required', 'min:1', 'max:50', 'string'],
            'video_path' => [''],
            'product_id' => ['required'],
        ]);

    }
}
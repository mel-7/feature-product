<?php

namespace App\Http\Controllers;

use App\Product;
use App\Hotspot;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['publicproductsAPI','show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function productsAPI()
    {
        $company_id = Auth::user()->company_id;
        $products = Product::where('company_id', $company_id)->orderBy('created_at', 'desc')->paginate(10);
        return response()->json($products, 200);
    }

    public function publicproductsAPI($id)
    {
        $products = Product::where(function ($query) use ($id) {
            $query->where('id', '=', $id)
                  ->orWhere('slug', '=', $id);
        })->with('user','items','items.media_file','items.hotspot_setting')->get();

        $hotspot = Hotspot::where('product_id', '=', $products[0]->id)->orderBy("hotspot_for")->get(); 

        $videos = Video::where('product_id', '=', $products[0]->id)->get(); 
       
        return response()->json(["dataItems" => $products, "hpItems" => $hotspot, "videos" => $videos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company_id = Auth::user()->company_id;
        // validate request 

        $check_product = Product::where('slug', $request->slug)->first(); 
       
        if(@$check_product->slug){ 
            $request['slug'] = $request->slug."-".$check_product->count();
        } 

        $this->validateRequest();
        // store request
        $product = Product::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'user_id' => auth()->id(),
            'company_id' => $company_id,
        ]);
        // response
        return response()->json([
            'product' => $product->id,
            'message' => 'Product has been created',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return view('product.single-product', compact('slug'));
    }

    public function uploadVideo()
    {

    }


    /**
     * Form Validation
     */
    public function validateRequest()
    {
        return request()->validate([
            'title' => ['required', 'min:1', 'max:50', 'string'],
            'slug' => ['min:1', 'max:50', 'string', 'alpha_dash', 'unique:products'],
            'company_id' => [''],
        ]);

    }
}

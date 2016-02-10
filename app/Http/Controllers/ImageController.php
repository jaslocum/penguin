<?php

namespace App\Http\Controllers;

use Request;
use App\Image;
use App\Bucket;
use App\Category;
use App\helpers;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{

    private $path = "images/";

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
        //validate form
        //return view('images.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // find the first image stored for $id
        $image_rec = Image::where(compact('id'))->first();

        // find and return the image, if possible
        if (isset($image_rec)) {

            //get category and key for image
            $bucket_id = $image_rec->bucket_id;
            $bucket_rec = Bucket::where(['id'=>$bucket_id])->first();
            $key = $bucket_rec->key;
            $category_id = $bucket_rec->category_id;
            $category = Category::where(['id'=>$category_id])->first()->category;

            // load information stored in image table
            $filename = $image_rec->filename;
            $size = $image_rec->size;
            $mime = $image_rec->mime;
            $description = $image_rec->description;
            $deleted = $image_rec->deleted;

            // get locate stored from md5 hash save in image table
            $md5 = $image_rec->md5;
            $md5path = md5path($md5);
            $md5filename = md5filename($md5);

            // find and return the image, if possible
            if (file_exists($md5path . $md5filename)) {

                $content = file_get_contents($md5path . $md5filename);

                // return file
                return Response::create($content,
                    200,
                    array(
                        'id' => $id,
                        'content-type' => $mime,
                        'description' => $description,
                        'filename' => $filename,
                        'size' => $size,
                        'md5' => $md5,
                        'deleted' => $deleted,
                        'category' => $category,
                        'key' => $key
                    )
                );

            } else {

                return Response::create("<h1>$id: image not found</h1>", 404);

            }

        }

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

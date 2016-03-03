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
     * @param int $id
     * @return Response
     */
    public function index($id)
    {
        // find image by id
        $image_rec = Image::where(compact('id'))->first();

        if (isset($image_rec)) {

            $bucket_id = $image_rec->bucket_id;

            // bucket that contains image description
            $bucket_rec = Bucket::where(['id'=>$bucket_id])->first();

            if (isset($bucket_rec)) {

                // test if image exists for bucket_id and filename in image table
                $description = $bucket_rec->description;

                // add description to end of json
                $json = $image_rec->toJson();
                $json = substr($json,0,-1);
                $json .= ',"description":"'.$description.'"}';

                // return all info in image table for category and key key pair,
                // $image_rec = json_encode($image_rec, JSON_PRETTY_PRINT);
                return Response::create($json,200);

            } else {

                return Response::create("<h1>$id: image bucket not found</h1>", 404);

            }

        } else {

            return Response::create("<h1>$id: image not found</h1>", 404);

        }

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
        // set description if passed as a header
        if(isset($request->description)) {
            $description = $request->description;
        }else{
            $description = "";
        }

        //set default category and key
        $category = 'image';

        //get file info from request
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $size = $file->getSize();
        $mime = $file->getMimeType();
        $file_path = $file->getPathName();

        //store new image for file name
        $image_rec = Image::newImage();

        if (isset($image_rec)) {

            //get new image id to use for bucket key
            $key = $image_rec->id;

            // create new bucket
            $bucket_rec = Bucket::newBucket($category, $key, $description);

            if (!isset($bucket_rec)) {

                return Response::create("<h1>$filename: image bucket could not be created</h1>", 404);

            }else{

                $bucket_id = $bucket_rec->id;

            }

            $md5 = md5($image_rec->id);
            $image_rec->bucket_id = $bucket_id;
            $image_rec->filename = $filename;
            $image_rec->size = $size;
            $image_rec->mime = $mime;
            $image_rec->md5 = $md5;
            $image_rec->deleted = false;

        } else {

                return Response::create("<h1>$file: image record could not be created</h1>", 404);

        }

        //image was created successfully

        return Response::create(null,200,[
            'image_id' => $key,
            'category' => $category,
            'key' => $key,
            'bucket_id'=> $bucket_id,
            'deleted'=> false,
            'file_name'=> $filename,
            'file_path' => $file_path,
            'size' => $size,
            'mime' => $mime,
            'description' => $description,
            'md5' => $md5,
        ]);

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
            $description = $bucket_rec->description;
            $category_id = $bucket_rec->category_id;
            $category = Category::where(['id'=>$category_id])->first()->category;

            // load information stored in image table
            $filename = $image_rec->filename;
            $size = $image_rec->size;
            $mime = $image_rec->mime;
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

                return Response::create("<h1>$id: $filename not found</h1>", 404);

            }

        } else {

            return Response::create("<h1>$id: image not found</h1>", 404);
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

        $image_rec = Image::where(compact('id'))->first();

        // find and return the image, if possible
        if (isset($image_rec)) {

            // load information stored in image table
            $image_id = $image_rec->id;
            $size = $image_rec->size;
            $mime = $image_rec->mime;
            $filename = $image_rec->filename;
            $deleted = $image_rec->deleted;

            // get locate stored from md5 hash save in image table
            $md5 = $image_rec->md5;
            $md5path = md5path($md5);
            $md5filename = md5filename($md5);

            // find and return the image, if possible
            if (file_exists($md5path . $md5filename)) {

                unlink($md5path . $md5filename);

                // mark file deleted
                $image_rec->deleted = true;
                $image_rec->save();

                // return file
                return Response::create(null,
                    200,
                    array(
                        'id' => $image_id,
                        'content-type' => $mime,
                        'filename' => $filename,
                        'md5' => $md5,
                        'deleted' => true
                    )
                );

            } else {

                return Response::create("<h1>$id: file not found</h1>",
                    404,
                    array(
                        'id' => $image_id,
                        'content-type' => $mime,
                        'filename' => $filename,
                        'size' => $size,
                        'md5' => $md5,
                        'deleted' => $deleted
                    )
                );
            }

        } else {

            return Response::create("<h1>$id: image not defined</h1>", 404);
        }

    }

}

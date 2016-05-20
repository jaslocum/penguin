<?php

namespace App\Http\Controllers;

use App\Image;
use App\Bucket;
use App\BucketController;
use App\Category;
use App\helpers;
use Illuminate\Http\Request;
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
            $bucket_rec = Bucket::where(['id' => $bucket_id])->first();

            if (isset($bucket_rec)) {

                $description = $bucket_rec->description;
                $category = Bucket::getCategoryName($bucket_id);
                $key = $bucket_rec->key;

                // add description to end of json
                $json = $image_rec->toJson();
                $json = substr($json, 0, -1);
                $json .= ',';
                $json .= '"description":"' . $description . '",';
                $json .= '"category":"' . $category . '",';
                $json .= '"key":"' . $key . '"';
                $json .= '}';

                // return all info in image table for category and key key pair,
                // $image_rec = json_encode($image_rec, JSON_PRETTY_PRINT);
                return Response::create($json, 200);

            } else {

                return Response::create("<h1>$id: image bucket not found</h1>", 404);

            }

        } else {

            return Response::create("<h1>$id: image not found</h1>", 404);

        }

    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {

        $description = Bucket::getDescription($request);

        //set default category and key
        $category = 'image';
        $key = null;

        //get file info from request
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $size = $file->getSize();
        $mime = $file->getMimeType();
        $file_path = $file->getPathName();

        // create new bucket
        $bucket_rec = Bucket::newBucket($category, $key, $description);

        if (isset($bucket_rec)) {

            $bucket_id = $bucket_rec->id;
            $key = $bucket_id->key;

            //store new image for file name
            $image_rec = Image::newImage();

            if (isset($image_rec)) {

                $md5 = $image_rec->md5;
                $id = $image_rec->id;
                $image_rec->bucket_id = $bucket_id;
                $image_rec->filename = $filename;
                $image_rec->size = $size;
                $image_rec->mime = $mime;
                $image_rec->deleted = false;
                $image_rec = Image::updateImage($image_rec);

                if (isset($image_rec)) {

                    $file->move(md5path($md5), md5filename($md5));

                } else {

                    return Response::create("<h1>$id: image record not updated</h1>", 404);
                }

            } else {

                return Response::create("<h1>$filename: image record not created</h1>", 404);

            }

        } else {

            return Response::create("<h1>$filename: bucket not created</h1>", 404);

        }

        //image was created successfully

        return Response::create(null, 200, [
            'image_id' => $id,
            'category' => $category,
            'key' => $key,
            'bucket_id' => $bucket_id,
            'deleted' => false,
            'file_name' => $filename,
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
     * @param  int $id
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
            $bucket_rec = Bucket::where(['id' => $bucket_id])->first();
            $key = $bucket_rec->key;
            $description = $bucket_rec->description;
            $category_id = $bucket_rec->category_id;
            $category = Category::where(['id' => $category_id])->first()->category;

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
     * @param Request $request
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|Response
     */
    public function edit(Request $request, $id = null)
    {

        $category = 'image';
        $description = Bucket::getDescription($request);

        if (isset($id)) {

            // find the first image stored for $id
            $image_rec = Image::where(compact('id'))->first();

            if (isset($image_rec)) {

                $bucket_id = $image_rec->bucket_id;

                // get bucket
                $bucket_rec = Bucket::getBucketById($bucket_id);

                if (isset($bucket_rec)) {

                    if (isset($description)) {
                        $bucket_rec->description = $description;
                        $bucket_rec = Bucket::updateBucket($bucket_rec);
                    } else {
                        $description = $bucket_rec->description;
                    }

                    if (isset($bucket_rec)) {

                        $key = $bucket_rec->key;

                        $category_rec = Bucket::getCategoryRec($bucket_id);

                        if(isset($category_rec)){

                            return view('image.edit', compact('category', 'key', 'id', 'bucket_id', 'category_rec', 'description'));

                        } else {

                            return Response::create("<h1>$category: category not found</h1>", 404);

                        }

                    } else {

                        return Response::create("<h1>$id: image bucket not updated</h1>", 404);

                    }

                } else {

                    return Response::create("<h1>$id: image bucket not found</h1>", 404);

                }

            } else {


                return Response::create("<h1>$id: image record not found</h1>", 404);

            }

        } else {

            //add new bucket to add images to.

            $category = 'image';
            $key = null;

            //add bucket and use bucket id
            $bucket_rec = Bucket::newBucket($category, $key, $description);

            if (isset($bucket_rec)) {

                $bucket_id = $bucket_rec->id;
                $key = $bucket_id;

                $image_rec = Image::newImage($bucket_id);

                if (isset($image_rec)) {

                    $id = $image_rec->id;
                    $image_rec->bucket_id = $bucket_id;
                    $image_rec = Image::updateImage($image_rec);

                    if (isset($image_rec)){

                        $category_rec = Bucket::getCategoryRec($bucket_id);

                        if(isset($category_rec)){

                            return view('image.edit', compact('category', 'key', 'id', 'bucket_id', 'category_rec', 'description'));

                        } else {

                            return Response::create("<h1>$category: category not found</h1>", 404);

                        }

                    } else {

                        return Response::create("<h1>$id: image record not updated</h1>", 404);

                    }

                } else {

                    return Response::create("<h1>$id: image record not created</h1>", 404);

                }

            } else {

                return Response::create("<h1>$id: image bucket not created</h1>", 404);

            }

        }

    }


    /**
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $description = Bucket::getDescription($request);

        //get file info from request
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $size = $file->getSize();
        $mime = $file->getMimeType();
        $file_path = $file->getPathName();

        // find the first image stored for $id
        $image_rec = Image::where(compact('id'))->first();

        if (isset($image_rec)) {

            //get ids
            $id = $image_rec->id;
            $bucket_id = $image_rec->bucket_id;
            $md5 = $image_rec->md5;

            // get bucket
            $bucket_rec = Bucket::where(compact('$bucket_id'))->first();

            if (isset($bucket_rec)) {

                if(isset($description)) {
                    $bucket_rec->description = $description;
                    $bucket_rec = Bucket::updateBucket($bucket_rec);
                }

                if (isset($bucket_rec)) {

                    $category = Bucket::getCategoryName($bucket_id);
                    $key = $bucket_rec->key;

                }else{

                    return Response::create("<h1>$id: image bucket could not be updated</h1>", 404);

                }

            }else{

                return Response::create("<h1>$id: image bucket could not be found</h1>", 404);

            }

            $image_rec->filename = $filename;
            $image_rec->size = $size;
            $image_rec->mime = $mime;
            $image_rec->deleted = false;

            $image_rec = Image::updateImage($image_rec);

            if (isset($image_rec)) {

                $file->move(md5path($md5), md5filename($md5));

                //image was created successfully
                return Response::create(null,200,[
                    'image_id' => $id,
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

            } else {

                return Response::create("<h1>$id: image detail not updated</h1>", 404);

            }

        } else {

            return Response::create("<h1>$id: image record could not be found</h1>", 404);

        }

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

            // mark file deleted
            $image_rec->deleted = true;
            $image_rec->save();

            // find and return the image, if possible
            if (file_exists($md5path . $md5filename)) {

                //use soft delete for now, just in case we need the image.
                //unlink($md5path . $md5filename);

                // return file
                return Response::create(null,
                    200,
                    array(
                        'id' => $image_id,
                        'content-type' => $mime,
                        'filename' => $filename,
                        'size' => $size,
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

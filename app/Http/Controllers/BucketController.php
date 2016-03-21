<?php

namespace App\Http\Controllers;

use App\Bucket;
use App\Category;
use App\Image;
use App\helpers;
use Illuminate\Http\Request;
use Mockery\Loader\RequireLoaderTest;
use Symfony\Component\HttpFoundation\Response;

class BucketController extends Controller
{

    /**
     * @param $category
     * @param $key
     * @return Response
     */
    public function index($category, $key)
    {

        // find category and key key pair
        $bucket_rec = Bucket::getBucket($category, $key);

        if (isset($bucket_rec)) {

                // test if image exists for bucket_id and filename in image table
                $bucket_id = $bucket_rec->id;
                $description = $bucket_rec->description;

                // find the first image record stored for the category and key key pair,
                // if possible
                $image_recs = Image::where(compact('bucket_id'))->orderby('id')->get();

                // find and return the image, if possible
                if (isset($image_recs)) {

                    $json = $image_recs->toJson();

                    $extra = ',';
                    $extra .= '"description":"' . $description . '",';
                    $extra .= '"category":"' . $category . '",';
                    $extra .= '"key":"' . $key . '"';
                    $extra .= '}';

                    $json = str_replace('}',$extra,$json);

                    // return all info in image table for category and key key pair,
                    // $image_rec = json_encode($image_rec, JSON_PRETTY_PRINT);

                        return Response::create($json,200);

                } else {

                    return Response::create("<h1>$category, $key: no images in bucket</h1>", 404);

                }

            } else {


            return Response::create("<h1>$category, $key: no bucket</h1>", 404);

        }

    }

    /**
     * @param Request $request
     * @param $category
     * @param $key
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function edit( Request $request, $category, $key)
    {

        $description = Bucket::getDescription($request);

        $bucket_rec = Bucket::getBucket($category, $key);

        if(!isset($bucket_rec)) {

            $bucket_rec = Bucket::newBucket($category, $key, $description);

            if (!isset($bucket_rec)){

                return Response::create("<h1>$category, $key: bucket not created</h1>", 404);

            }

        }

        //update bucket with most recent description in response header
        if(isset($description)) {

            $bucket_rec->description = $description;
            Bucket::updateBucket($bucket_rec);

            if (!isset($bucket_rec)) {

                return Response::create("<h1>$category, $key: bucket description not updated</h1>", 404);

            }

        } else {

            $description = $bucket_rec->description;
        }

        $bucket_id = $bucket_rec->id;
        $category_rec = Bucket::getCategoryRec($bucket_id);

        if(isset($category_rec)){

            return view('bucket.edit', compact('category','key','bucket_id', 'category_rec', 'description'));

        } else {

            return Response::create("<h1>$category: category record not found</h1>", 404);

        }


   }

    /**
     * @param Request $request
     * @param $category
     * @param $key
     * @return Response
     */
    public function post(Request $request, $category, $key)
    {

        $description = Bucket::getDescription($request);

        //get file info from request
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $size = $file->getSize();
        $mime = $file->getMimeType();
        $file_path = $file->getPathName();

        //find existing category and key key pair if possible
        $bucket_rec = Bucket::getBucket($category, $key);

        if (!isset($bucket_rec)) {

            $bucket_rec = Bucket::newBucket($category, $key, $description);

            if (!isset($bucket_rec)) {
                return Response::create("<h1>$category, $key: bucket could not be created</h1>", 404);
            }

        }

        //update bucket with most recent description in response header

        if(isset($description)) {
            $bucket_rec->description = $description;
            Bucket::updateBucket($bucket_rec);

            if (!isset($bucket_rec)) {
                return Response::create("<h1>$category, $key: bucket could not be updated</h1>", 404);
            }

        }

        $bucket_id = $bucket_rec->id;

        // find existing image stored under filename for bucket_id, if possible
        $image_rec = Image::where(compact('bucket_id', 'filename'))->first();

        if (isset($image_rec)) {

            //replace existing image
            $md5 = $image_rec->md5;
            $file->move(md5path($md5), md5filename($md5));

            $image_rec->deleted = false;
            Image::updateImage($image_rec);

        } else {

            //store new image for file name
            $image_rec = Image::newImage($bucket_id);

            if (isset($image_rec)) {

                $md5 = $image_rec->md5;
                $image_rec->bucket_id = $bucket_id;
                $image_rec->filename = $filename;
                $image_rec->size = $size;
                $image_rec->mime = $mime;
                $image_rec->md5 = $md5;
                $image_rec->deleted = false;
                $image_rec =Image::updateImage($image_rec);

                if (isset($image_rec)) {
                    $file->move(md5path($md5), md5filename($md5));
                } else {
                    return Response::create("<h1>$category, $key: image could not be updated</h1>", 404);
                }
            } else {
                return Response::create("<h1>$category, $key, $filename: image record could not be added</h1>", 404);
            }

        }

        //image was updated successfully
        //return id for image to be accessed directly, if needed
        $image_id = $image_rec->id;

        return Response::create(null,200,[
            'image_id' => $image_id,
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
     * @param Request $request
     * @param $category
     * @param $key
     * @param null $filename
     * @return Response
     */
    public function get(Request $request = null, $category, $key, $filename = null)
    {

        $find_alt_on_fail = false;

        // get alternate category/key/filename to find if primary image request fails
        if(isset($request)) {
            $alt_category = Bucket::getAltCategory($request);
            $alt_key = Bucket::getAltKey($request);
            $alt_filename = Bucket::getAltFilename($request);
            if(isset($alt_category) && isset($alt_key)){
                $find_alt_on_fail = true;
            }
        }

        // find category and key key pair
        $bucket_rec = Bucket::getBucket($category, $key);

        if(isset($bucket_rec)) {

            // test if image exists for bucket_id and filename in image table
            $bucket_id = $bucket_rec->id;
            $description = $bucket_rec->description;

            if (isset($filename)){

                // find the first image stored for the category and key key pair
                // for the filename given.
                $image_rec = Image::where(compact('bucket_id', 'filename'))->first();

            } else {

                // find the first image record stored for the category and key key pair
                $deleted = false;
                $image_rec = Image::where(compact('bucket_id','deleted'))->orderby('id')->first();

            }

            // find and return the image, if possible
            if (isset($image_rec)) {

                // load information stored in image table
                $image_id = $image_rec->id;
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
                            'id' => $image_id,
                            'content-type' => $mime,
                            'description' => $description,
                            'filename' => $filename,
                            'size' => $size,
                            'md5' => $md5,
                            'deleted' => $deleted
                        )
                    );

                } else {

                    if ($find_alt_on_fail){

                        return BucketController::get(null,$alt_category,$alt_key,$alt_filename);

                    } else {

                        return Response::create("<h1>$category, $key: image file not found</h1>",
                            404,
                            array(
                                'id' => $image_id,
                                'content-type' => $mime,
                                'description' => $description,
                                'filename' => $filename,
                                'size' => $size,
                                'md5' => $md5,
                                'deleted' => $deleted
                            )
                        );

                    }

                }

            } else {

                if ($find_alt_on_fail){

                    return BucketController::get(null,$alt_category,$alt_key,$alt_filename);

                }else{

                    return Response::create("<h1>$category, $key, $filename: image not in bucket</h1>", 404);

                }
            }
        }
        if ($find_alt_on_fail){

            return BucketController::get(null,$alt_category,$alt_key,$alt_filename);

        }else {

            return Response::create("<h1>$category, $key: bucket not found</h1>", 404);

        }

    }

    /**
     * remove image
     *
     * @param $category
     * @param $key
     * @param null $filename
     * @return Response
     */
    public function destroy($category, $key, $filename = null)
    {

        // find category and key key pair
        $bucket_rec = Bucket::getBucket($category, $key);

        if(isset($bucket_rec)) {

            // test if image exists for bucket_id and filename in image table
            $bucket_id = $bucket_rec->id;
            $description = $bucket_rec->description;

            if (isset($filename)){

                // find the first image stored for the category and key key pair
                // for the filename given.
                $image_rec = Image::where(compact('bucket_id', 'filename'))->first();

            } else {

                // find the first image record stored for the category and key key pair,
                // if possible
                $image_rec = Image::where(compact('bucket_id'))->first();

            }

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
                        array('content-type' => $mime,
                            'description' => $description,
                            'filename' => $filename,
                            'md5' => $md5,
                            'deleted' => true
                        )
                    );

                } else {

                    return Response::create("<h1>$category, $key, $filename: file not found</h1>",
                        404,
                        array(
                            'id' => $image_id,
                            'content-type' => $mime,
                            'description' => $description,
                            'filename' => $filename,
                            'size' => $size,
                            'md5' => $md5,
                            'deleted' => $deleted
                        )
                    );
                }

            } else {

                return Response::create("<h1>$category, $key, $filename: image not defined</h1>", 404);
            }
        }

        return Response::create("<h1>$category, $key: bucket not found</h1>", 404);

    }

}


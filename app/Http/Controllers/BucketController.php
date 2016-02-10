<?php

namespace App\Http\Controllers;

use App\Bucket;
use App\Category;
use App\Image;
use App\helpers;
use Illuminate\Http\Request;
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
        $bucket = $this->getBucket($category, $key);

        if (isset($bucket)) {

                // test if image exists for bucket_id and filename in image table
                $bucket_id = $bucket->id;

                // find the first image record stored for the category and key key pair,
                // if possible
                $image_rec = Image::where(compact('bucket_id'))->get()->toJson();

                // find and return the image, if possible
                if (isset($image_rec)) {

                    // return all info in image table for category and key key pair,
                    // $image_rec = json_encode($image_rec, JSON_PRETTY_PRINT);
                    return Response::create($image_rec,200);

                }

            }

        return Response::create("<h1>$category, $key: not found</h1>", 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $category
     * @param  string $key
     * @return \Illuminate\Http\Response
     */
    public function update($category, $key)
    {
        // find category and key key pair
        $bucket_rec = $this->getBucket($category, $key);

        if(isset($bucket_rec)) {

            $bucket_id = $bucket_rec->id;
            $category_id = $bucket_rec->category_id;
            $category_rec = Category::where(['id'=>$category_id])->first();

            return view('bucket.update', compact('category','key','bucket_id', 'category_rec'));

        } else {

            $bucket_rec = $this->newBucket($category, $key);
            $bucket_id = $bucket_rec->id;
            $category_rec = Category::where(compact('category'))->first();

            if (isset($bucket_rec)){
                return view('bucket.update', compact('category','key','bucket_id', 'category_rec'));
            } else {
                return "<h1>$category, $key: bucket could not be created</h1>";
            }

        }

   }

    /**
     * @param Request $request
     * @param $category
     * @param $key
     * @return bool/mixed
     */
    public function post(Request $request, $category, $key)
    {

        //get file info from request
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $size = $file->getSize();
        $mime = $file->getMimeType();
        $description = "";

        //find existing category and key key pair if possible
        $bucket = $this->getBucket($category, $key);

        if ($bucket === null) {
            $bucket = $this->newBucket($category, $key);
            if ($bucket === null) {
                return Response::create("<h1>$category, $key: bucket could not be found or created</h1>", 404);
            }
        }

        //get unique bucket_id for category and key key pair
        $bucket_id = $bucket->id;

        // find existing image stored under filename for bucket_id, if possible
        $image_rec = Image::where(compact('bucket_id', 'filename'))->first();

        if (isset($image_rec)) {

            //replace existing image
            $md5 = $image_rec->md5;
            $file->move(md5path($md5), md5filename($md5));

            $image_rec->deleted = false;
            $this->updateImage($image_rec);

        } else {

            //store new image for file name
            $image_rec = $this->newImage();
            if (isset($image_rec)) {
                $md5 = md5($image_rec->id);
                $image_rec->bucket_id = $bucket_id;
                $image_rec->filename = $filename;
                $image_rec->size = $size;
                $image_rec->mime = $mime;
                $image_rec->md5 = $md5;
                $image_rec->description = $description;
                $image_rec->deleted = false;
                $image_rec = $this->updateImage($image_rec);
                if (isset($image_rec)) {
                    $file->move(md5path($md5), md5filename($md5));
                } else {
                    return Response::create("<h1>$category, $key: image detail could not be updated</h1>", 404);
                }
            } else {
                return Response::create("<h1>$category, $key, $filename: image record could not be added</h1>", 404);
            }

        }

    }

    /**
     * @param string $category
     * @param string $key
     * @param string $filename
     * @return Response
     */
    public function get($category, $key, $filename = null)
    {

        // find category and key key pair
        $bucket = $this->getBucket($category, $key);

        if(isset($bucket)) {

            // test if image exists for bucket_id and filename in image table
            $bucket_id = $bucket->id;

            if (isset($filename)){

                // find the first image stored for the category and key key pair
                // for the filename given.
                $image_rec = Image::where(compact('bucket_id', 'filename'))->first();

            } else {

                // find the first image record stored for the category and key key pair,
                // if possible
                $deleted = false;
                $image_rec = Image::orderby('id')->where(compact('bucket_id','deleted'))->first();

            }

            // find and return the image, if possible
            if (isset($image_rec)) {

                // load information stored in image table
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
                        array('content-type' => $mime,
                            'description' => $description,
                            'filename' => $filename,
                            'size' => $size,
                            'md5' => $md5,
                            'deleted' => $deleted
                        )
                    );

                } else {

                    return Response::create("<h1>$category, $key: image not found</h1>", 404);

                }

            } else {

                return Response::create("<h1>$category, $key, $filename: image not found</h1>", 404);
            }
        }

        return Response::create("<h1>$category, $key: not found</h1>", 404);
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
        $bucket = $this->getBucket($category, $key);

        if(isset($bucket)) {

            // test if image exists for bucket_id and filename in image table
            $bucket_id = $bucket->id;

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
                $filename = $image_rec->filename;
                $mime = $image_rec->mime;
                $description = $image_rec->description;

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
                            'description' => $filename.' was deleted',
                            'filename' => $filename,
                            'md5' => $md5,
                            'deleted' => true
                        )
                    );

                } else {
                    return Response::create("<h1>$category, $key, $filename: file not found</h1>", 404);
                }

            } else {

                return Response::create("<h1>$category, $key, $filename: image not defined</h1>", 404);
            }
        }

        return Response::create("<h1>$category, $key: bucket not found</h1>", 404);

    }

    /**
     * @param $category
     * @param $key
     * @return bool|mixed
     */
    public function newBucket($category, $key)
    {

        //create bucket record
        $bucket = new Bucket;
        $category_id = Category::where(compact('category'))->first()->id;
        $bucket->category_id = $category_id;
        $bucket->key = $key;
        if ($bucket->save()){
            return $bucket;
        } else {
            return null;
        }

    }

    /**
     * @return Images|null
     */
    public function newImage()
    {

        $image_rec = new Image;
        if($image_rec->save()) {
            return $image_rec;
        } else {
            return null;
        }

    }

    /**
     * @param $image_rec
     * @param $bucket_id
     * @param $filename
     * @param $mime
     * @param $md5
     * @param $description
     * @param boolean $deleted
     * @return null
     */
    public function updateImage($image_rec)
    {

        //update image record
        if($image_rec->save()) {
            return $image_rec;
        } else {
            return null;
        }

    }


    /**
     * @param $category
     * @param $key
     * @return mixed
     */
    public function getBucket($category, $key)
    {
        // find category and key key pair
        $category_rec = Category::where(compact('category'))->first();
        if (isset($category_rec)) {
            $category_id = $category_rec->id;
            $bucket = Bucket::where(compact('category_id', 'key'))->first();
            return $bucket;
        } else {
            return null;
        }
    }

}


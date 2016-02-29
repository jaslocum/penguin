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
    public function update( Request $request, $category, $key)
    {

        // find category and key key pair
        $bucket_rec = $this->getBucket($category, $key);

        if(!isset($bucket_rec)) {

            $bucket_rec = $this->newBucket($category, $key);

            if (!isset($bucket_rec)){
                return "<h1>$category, $key: bucket could not be created</h1>";
            }

        }

        $bucket_id = $bucket_rec->id;
        $category_rec = Category::where(['id'=>$bucket_rec->category_id])->first();

        if(isset($request->description)){
            $bucket_rec->description = $request->description;
            $bucket_rec->save();
        }

        $description = $bucket_rec->description;

        return view('bucket.update', compact('category','key','bucket_id', 'category_rec', 'description'));

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
        $file_path = $file->getPathName();

        //find existing category and key key pair if possible
        $bucket_rec = $this->getBucket($category, $key);

        if (!isset($bucket_rec)) {

            $bucket_rec = $this->newBucket($category, $key);

            if (!isset($bucket_rec)) {
                return Response::create("<h1>$category, $key: bucket could not be found or created</h1>", 404);
            }

        }

        //update bucket with most recent description in response header
        $description = $request->get('description');
        if(isset($description)){
            $bucket_rec->description = $request->description;
            $bucket_rec->save();
        }

        $bucket_id = $bucket_rec->id;
        $description = $bucket_rec->description;

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
                $image_rec = Image::orderby('id','DESC')->where(compact('bucket_id','deleted'))->first();

            }

            // find and return the image, if possible
            if (isset($image_rec)) {

                // load information stored in image table
                $image_id = $image_rec->id;
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

                    return Response::create("<h1>$category, $key: image not found</h1>",
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
                $image_id = $image_rec->id;
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

    /**
     * @param $category
     * @param $key
     * @return bool|mixed
     */
    public function newBucket($category, $key)
    {

        //create bucket record
        $bucket = new Bucket;
        $category_rec = Category::where(compact('category'))->first();
        if (!isset($category_rec)){
            $category_rec = $this->newCategory($category);
            if(!isset($category_rec)){
                return;
            }
        }

        $bucket->category_id = $category_rec->id;
        $bucket->key = $key;

        if ($bucket->save()){
            return $bucket;
        } else {
            return;
        }

    }

    /**
     * @param $category
     * @return Category|null
     */
    public function newCategory($category)
    {

        $category_rec = new Category;
        $category_rec->category = $category;
        //default accepted mime types
        $category_rec->mime = 'image/jpg, image/jpeg, image.png';
        //default max file size that can be uploaded
        $category_rec->max_size_MB = 5;

        if($category_rec->save()) {
            return $category_rec;
        } else {
            return;
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


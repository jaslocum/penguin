<?php

namespace App\Http\Controllers;

use App\Category_definition;
use App\Image;
use Illuminate\Http\Request;
use App\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends Controller
{

    private $path = "images/";

    /**
     * @param $category
     * @param $category_rec_id
     * @param null $filename
     * @return Response
     */
    public function index($category, $category_rec_id)
    {

        // find category and category_rec_id key pair
        $category_rec = Category::where(compact('category', 'category_rec_id'))->first();
        if (isset($category_rec)) {

                // test if image exists for category_id and filename in image table
                $category_id = $category_rec->id;

                // find the first image record stored for the category and category_rec_id key pair,
                // if possible
                $image_rec = Image::where(compact('category_id'))->get()->toJson();

                // find and return the image, if possible
                if (isset($image_rec)) {

                    // return all info in image table for category and category_rec_id key pair,
                    // $image_rec = json_encode($image_rec, JSON_PRETTY_PRINT);
                    return Response::create($image_rec,200);

                }

            }

        return Response::create("<h1>$category, $category_rec_id: not found</h1>", 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $category
     * @param  string $category_rec_id
     * @return \Illuminate\Http\Response
     */
    public function update($category, $category_rec_id)
    {
        $category_def = Category_definition::where(compact('category'))->first();
        $category_id = $category_def->id;
        $category_rec = Category::where(compact('category_id', 'category_rec_id'))->first();
        if(isset($category_rec)) {
            $category_def = $category_rec->category_definition();
            return view('categories.update', compact('category_rec'), compact('category_def'));
        } else {
            $category_rec = $this->newCategory($category, $category_rec_id);
            $category_def = $category_rec->category_definition();
            if (isset($category_rec)){
                return view('categories.update', compact('category_rec', compact('category_def')));
            } else {
                return "<h1>$category, $category_rec_id: could not be created</h1>";
            }
        }

   }

    /**
     * @param Request $request
     * @param $category
     * @param $category_rec_id
     * @return bool/mixed
     */
    public function post(Request $request, $category, $category_rec_id)
    {

        //get file info from request
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $size = $file->getSize();
        $mime = $file->getMimeType();
        $description = "";
        $deleted = false;

        //find existing category and category_rec_id key pair if possible
        $category_rec = Category::where(compact('category', 'category_rec_id'))->first();
        if ($category_rec === null) {
            $category_rec = $this->newCategory($category, $category_rec_id);
            if ($category_rec === null) {
                return Response::create("<h1>$category, $category_rec_id: key pair can not be found or created</h1>", 404);
            }
        }

        //get unique category_id for category and category_rec_id key pair
        $category_id = $category_rec->id;

        // find existing image stored under filename for category_id, if possible
        $image_rec = Image::where(compact('category_id', 'filename'))->first();

        if (isset($image_rec)) {

            //replace existing image
            $md5 = md5($image_rec->id);
            $file->move($this->path . $this->md5path($md5), $this->md5filename($md5));

        } else {

            //store new image for file name
            $image_rec = $this->newImage($category_id, $filename, $mime);
            if (isset($image_rec)) {
                $md5 = md5($image_rec->id);
                $image_rec->category_id = $category_id;
                $image_rec->filename = $filename;
                $image_rec->size = $size;
                $image_rec->mime = $mime;
                $image_rec->md5 = $md5;
                $image_rec->description = $description;
                $image_rec->deleted = $deleted;
                $image_rec = $this->updateImage($image_rec);
                if (isset($image_rec)) {
                    $file->move($this->path . $this->md5path($md5), $this->md5filename($md5));
                } else {
                    return Response::create("<h1>$category, $category_rec_id: image detail could not be updated</h1>", 404);
                }
            } else {
                return Response::create("<h1>$category, $category_rec_id, $filename: image record could not be added</h1>", 404);
            }

        }

    }

    /**
     * @param string $category
     * @param string $category_rec_id
     * @param string $filename
     * @return Response
     */
    public function get($category, $category_rec_id, $filename = null)
    {

        // find category and category_rec_id key pair
        $category_rec = Category::where(compact('category', 'category_rec_id'))->first();
        if(isset($category_rec)) {

            // test if image exists for category_id and filename in image table
            $category_id = $category_rec->id;

            if (isset($filename)){

                // find the first image stored for the category and category_rec_id key pair
                // for the filename given.
                $image_rec = Image::where(compact('category_id', 'filename'))->first();

            } else {

                // find the first image record stored for the category and category_rec_id key pair,
                // if possible
                $image_rec = Image::where(compact('category_id'))->first();

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
                $md5path = $this->path.$this->md5path($md5);
                $md5filename = $this->md5filename($md5);

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

                    return Response::create("<h1>$category, $category_rec_id: image not found</h1>", 404);

                }

            } else {

                return Response::create("<h1>$category, $category_rec_id, $filename: image not found</h1>", 404);
            }
        }

        return Response::create("<h1>$category, $category_rec_id: not found</h1>", 404);
    }

    /**
     * remove image
     *
     * @param $category
     * @param $category_rec_id
     * @param null $filename
     * @return Response
     */
    public function destroy($category, $category_rec_id, $filename = null)
    {

        // find category and category_rec_id key pair
        $category_rec = Category::where(compact('category', 'category_rec_id'))->first();
        if(isset($category_rec)) {

            // test if image exists for category_id and filename in image table
            $category_id = $category_rec->id;

            if (isset($filename)){

                // find the first image stored for the category and category_rec_id key pair
                // for the filename given.
                $image_rec = Image::where(compact('category_id', 'filename'))->first();

            } else {

                // find the first image record stored for the category and category_rec_id key pair,
                // if possible
                $image_rec = Image::where(compact('category_id'))->first();

            }

            // find and return the image, if possible
            if (isset($image_rec)) {

                // load information stored in image table
                $mime = $image_rec->mime;
                $description = $image_rec->description;

                // get locate stored from md5 hash save in image table
                $md5 = $image_rec->md5;
                $md5path = $this->path.$this->md5path($md5);
                $md5filename = $this->md5filename($md5);

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

                }

            } else {

                return Response::create("<h1>$category, $category_rec_id, $filename: image not found</h1>", 404);
            }
        }

        return Response::create("<h1>$category, $category_rec_id: not found</h1>", 404);

    }

    /**
     * @param $category
     * @param $category_rec_id
     * @return bool|mixed
     */
    public function newCategory($category, $category_rec_id)
    {

        //create categories record
        $category_rec = new Category;
        $category_rec->category = $category;
        $category_rec->category_rec_id = $category_rec_id;
        if ($category_rec->save()){
            return $category_rec;
        } else {
            return null;
        }

    }

    /**
     * @return Image|null
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
     * @param $category_id
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
     * @param $md5
     * @return string
     */
    public function md5path($md5)
    {

        $md5path = substr($md5,0,3)."/".substr($md5,3,3)."/".substr($md5,6,3)."/";
        return $md5path;

    }

    /**
     * @param $md5
     * @return string
     */
    public function md5filename($md5)
    {

        $md5filename = substr($md5,9,23);
        return $md5filename;

    }

}


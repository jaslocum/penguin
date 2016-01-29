<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;
use App\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends Controller
{
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
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $category = $request->request->get('category');
        $category_rec_id = $request->request->get('category_rec_id');
        $category_rec = Category::where(compact('category', 'category_rec_id'))->first();
        if ($category_rec === null) {
           $category_rec = $this->newCategory($category, $category_rec_id);
        }
        if (isset($category_rec))
        {
            return redirect()->action("CategoriesController@show",
                [
                    'category' => $category,
                    'category_rec_id' => $category_rec_id,
                ]);
        } else {
            return "<h1>$category, $category_rec_id: could not be created</h1>";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string $category
     * @param  string $category_rec_id
     * @return \Illuminate\Http\Response
     */
    public function show($category, $category_rec_id)
    {
        $category_rec = Category::where(compact('category', 'category_rec_id'))->first();
        if(isset($category_rec)) {
            return view('categories.show', compact('category_rec'));
        } else {
            $category_rec = $this->newCategory($category, $category_rec_id);
            if (isset($category_rec)){
                return view('categories.show', compact('category_rec'));
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
    public function addImage(Request $request, $category, $category_rec_id)
    {
        $file = $request->file('file');
        $path = 'images/';
        $filename = $file->getClientOriginalName();
        $mime = $file->getMimeType();
        $category_rec = Category::where(compact('category', 'category_rec_id'))->first();
        if ($category_rec === null) {
            $category_rec = $this->newCategory($category, $category_rec_id);
            if ($category_rec === null) {
                return Response::create("<h1>$category, $category_rec_id: image could not be added</h1>", 404);
            }
        }
        $category_id = $category_rec->id;
        $image_rec = $this->newImage($category_id, $filename, $mime);
        if (isset($image_rec)) {
            $file->move($path, $filename);
        } else {
            return Response::create("<h1>$category, $category_rec_id: image could not be added</h1>", 404);
        }
    }

    /**
     * @param $category
     * @param $category_rec_id
     * @return string|Response
     */
    public function image($category, $category_rec_id)
    {
        $category_rec = Category::where(compact('category', 'category_rec_id'))->first();
        if(isset($category_rec)) {
            $category_id = $category_rec->id;
            $image_rec = Image::where(compact('category_id'))->first();
            if (isset($image_rec)) {
                $path = "images/";
                $filename = $image_rec->filename;
                $mime = $image_rec->mime;
                if (file_exists($path . $filename)) {
                    $content = file_get_contents($path . $filename);
                    return Response::create($content, 200, array('content-type' => $mime));
                }
            }
        }
        return Response::create("<h1>$category, $category_rec_id: image not found</h1>", 404);
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

    /**
     * @param $category
     * @param $category_rec_id
     * @return bool|mixed
     */
    public function newCategory($category, $category_rec_id){
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

    public function newImage($category_id, $filename, $mime)
    {
        $image_rec = new Image;
        $image_rec->category_id = $category_id;
        $image_rec->filename = $filename;
        $image_rec->mime = $mime;
        if($image_rec->save()) {
            return $image_rec;
        } else {
            return null;
        }
    }

}

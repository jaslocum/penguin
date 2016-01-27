<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\Controller;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(CategoryRequest $request)
    {
        $category = $request->request->get('category');
        $category_rec_id = $request->request->get('category_rec_id');

        $category_rec = Category::where(compact('category', 'category_rec_id'))->first();

        //persist the images
        if (empty($category_rec)) {
            Category::create($request->all());
        }

        return redirect()->action("CategoriesController@show",
            [
                'category' => $category,
                'category_rec_id' => $category_rec_id,
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($category, $category_rec_id)
    {
        //
        //return view('categories.show',['category'=>$category,'category_rec_id'=>$category_rec_id]);
        $category_rec = Category::where(compact('category', 'category_rec_id'))->first();
        if($category_rec) {
            return view('categories.show', compact('category_rec'));
        } else {
            return "Category has not been added...";
        }
    }

    public function images(Request $request)
    {
        $file = $request->file('file');
        $name = $file->getClientOriginalName();
        $file->move('images',$name);
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

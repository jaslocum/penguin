<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Foundation\Validation;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('category.index');
    }

    /**
     *
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        //validation
        $validator = validator($request->all(), [
            'category' => 'required|regex:/[A-z]/|min:3'
        ]);

        if ($validator->fails()) {

            flash('Category','Category must contain at least on letter','info');
            return redirect()->back();

        } else {

            Category::create($request->all());
            return redirect('category');

        }

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return redirect('category');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('category.update',compact('category'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request, $id)
    {
        //validation
        $validator = validator($request->all(), [
            'category' => 'required|regex:/[A-z]/|min:3'
        ]);

        if ($validator->fails()) {

            flash('Category','A category must contain at least one letter and be a minimum of three characters long.','info');
            return redirect()->back();

        } else {


            Category::findOrFail($id)->update($request->all());
            return redirect('category');
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
        Category::findOrFail($id)->delete();
        return redirect('category');
    }
}

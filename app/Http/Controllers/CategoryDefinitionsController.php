<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category_definition;

class CategoryDefinitionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //clear unused records before index view is created
        $category = "";
        Category_definition::where(compact('category'))->where(compact('category'))->delete();
        return view('category_definitions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = "";
        $category_definition = Category_definition::firstOrCreate(compact('category'));
        return View('category_definitions.form',compact('category_definition'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        redirect('category_definitions');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return redirect('category_definitions');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category_definition = Category_definition::firstOrCreate(compact('id'));
        return View('category_definitions.form',compact('category_definition'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request, $id)
    {
        $category_definition = Category_definition::where(compact('id'))->first()->update($request->all());
        return redirect('category_definitions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category_definition::where(compact('id'))->first()->delete();
        return redirect('category_definitions');
    }
}

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
        //
        return view('category_definitions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $category = "";
        $category_definition = Category_definition::firstOrCreate(compact('category'));
        return View('category_definitions.form',compact('category_definition'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        //$category_definition = Category_definition::firstOrCreate(compact('id'));
        //return View('category_definitions.form',compact('category_definition'));
        return view('category_definitions.index');
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
        $category_definition = Category_definition::where(compact('id'))->first();
        $category_definition->update($request->all());
        return View('category_definitions.index');

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
        $category_definition = Category_definition::where(compact('id'))->first();
        $category_definition->delete();
        return View('category_definitions.index');
    }
}

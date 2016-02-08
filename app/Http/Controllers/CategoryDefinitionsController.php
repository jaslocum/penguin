<?php

namespace App\Http\Controllers;

use Request;
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
        return view('category_definitions.index');
    }

    /**
     *
     */
    public function create()
    {
        return view('category_definitions.create');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        $request = Request::all();
        Category_definition::create($request);
        return redirect('category_definitions');
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
    public static function edit($id)
    {
        $category_definition = Category_definition::findOrFail($id);
        return view('category_definitions.form',compact('category_definition'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request, $id)
    {

        $request = Request::all();
        Category_definition::findOrFail($id)->update($request);
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
        Category_definition::findOrFail($id)->delete();
        return redirect('category_definitions');
    }
}

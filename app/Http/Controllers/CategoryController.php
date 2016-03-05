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
        if ($this->valid($request)){

            Category::create($request->all());
            return redirect('category');

        } else {

            return redirect()->back();

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
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {

        $category = Category::findOrFail($id);
        return view('category.update', compact('category'));

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(Request $request, $id)
    {
        if ($this->valid($request)){

            Category::findOrFail($id)->update($request->all());
            return redirect('category');

        } else {

            return redirect()->back();

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return redirect('category');
    }

    /**
     * @param $request
     * @return bool
     */
    public function valid($request)
    {

        //validation
        $validator = validator($request->all(), ['category' => 'required|regex:/[A-z]/']);

        if ($validator->fails()) {

            flash('Category', 'Category must contain at least one lette.', 'info');
            return false;

        }

        //validation image and penguin are system categories that can not be used by clients
        $validator = validator($request->all(), [
            'category' => 'not_in:image,penguin'
        ]);

        if ($validator->fails()) {

            flash('Category', 'A category can not be penguin or image.', 'info');
            return false;

        }

        return true;

    }

}

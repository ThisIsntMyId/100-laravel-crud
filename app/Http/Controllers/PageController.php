<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private function validateRequest(Request $request)
    {
        $validatedData = $request->validate([
            "slug" => 'required | regex:/(^(([a-z0-9]+)-?[a-z0-9]+)+$)/',
            "title" => 'nullable | json',
            "content" => 'nullable | json',
            "footer_content" => 'nullable | json',
            "meta_title" => 'nullable | json',
            "meta_desc" => 'nullable | json',
            "h1_tag" => 'nullable | json',
            "h2_tag" => 'nullable | json',
            "heading" => 'nullable | json',
            "filename" => 'nullable | string',
            "ctrl_function" => 'required | string',
            "status" => 'required | in:publish,draft,trash',
            "metakey" => 'nullable | string',
            "exclude_sitemap" => 'required | boolean',
            "views" => 'required | integer',
            "image" => 'nullable | string',
        ]);
        return $validatedData;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search_query = $request->query('search');
        if ($search_query) {
            // for loop can be used here with the searchable fields stored inside an array
            return Page::where('slug', 'like', '%'. $search_query . '%')->get();
        }
        return Page::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validateRequest($request);
        return Page::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        return $page;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $data = $this->validateRequest($request);
        $status = $page->update($data);
        if ($status) {
            return $page;
        } else {
            return 'Error';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        try {
            $page->delete();
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}

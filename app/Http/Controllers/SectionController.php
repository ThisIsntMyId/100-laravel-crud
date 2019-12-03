<?php

namespace App\Http\Controllers;

use App\Section;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SectionController extends Controller
{
    private function validateRequest(Request $request)
    {
        $exists = Section::where('code', $request->code)->exists();
        $isUpdateQuery = (($request->method() == 'PUT') || ($request->method() == 'PATCH'));
        $validatedData = $request->validate([
            "code" => ['required','unique:sections,code' . (($isUpdateQuery && $exists) ? ',' . $request->code . ',code': '')],
            "title" => 'required | json',
            "subtitle" => 'required | json',
            "btn_title" => 'required | json',
            "name" => 'required',
            "pageID" => 'required | exists:pages,pageID',
            "image" => 'nullable',
            "btn_link" => 'nullable',
        ]);
        return $validatedData;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Section::all();
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
        return Section::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        return $section;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        $data = $this->validateRequest($request);
        $status = $section->update($data);
        if ($status) {
            return $section;
        } else {
            return 'Error';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        try {
            $section->delete();
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}

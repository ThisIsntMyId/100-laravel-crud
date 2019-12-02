<?php

namespace App\Http\Controllers;

use App\Block;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    private function validateRequest(Request $request)
    {
        $validatedData = $request->validate([
            "section_code" => 'required | exists:sections,code',
            "language" => 'required | in:en,ar',
            "title" => 'required',
            "content" => 'nullable',
            "image" => 'nullable',
            "image_mobile" => 'nullable',
            "link" => 'nullable',
            "btn_title" => 'nullable',
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
        return Block::all();
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
        return Block::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function show(Block $block)
    {
        return $block;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Block $block)
    {
        $data = $this->validateRequest($request);
        $status = $block->update($data);
        if ($status) {
            return $block;
        } else {
            return 'Error';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function destroy(Block $block)
    {
        try {
            $block->delete();
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }
}

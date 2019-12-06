<?php

namespace App\Http\Controllers;

use App\Block;
use App\Imports\BlocksImport;
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
    public function index(Request $request)
    {
        // return Block::all();

        $blockQuery = new Block;

        // searching
        $search_query = $request->query('search');
        if ($search_query) {
            $blockQuery = $blockQuery->where('title', 'like', '%'. $search_query . '%')
                                     ->orWhere('content', 'like', '%'. $search_query . '%')
                                     ->orWhere('section_code', 'like', '%'. $search_query . '%')
                                     ->orWhere('btn_title', 'like', '%'. $search_query . '%');
        }

        // filtering
        $filters = $request->query('filters');
        if ($filters) {
            $filters = json_decode($filters, true);
            // dd($filters);
            foreach ($filters as $key => $filter) {
                if (is_array($filter)) {
                    $blockQuery = $blockQuery->whereIn($key, $filter);
                } else {
                    $blockQuery = $blockQuery->where($key, 'like' ,'%'.$filter.'%');
                }
            }
        }

        // sorting
        // ? remember to use this sort query from next time onwards ==> ?sort=field:order ?sort=+field / sort=-field ?sort[field]=field&sort[order]=order
        $sort_field = $request->query('sort');
        $sort_mode = $request->query('sort-order');
        if ($sort_field) {
            $blockQuery = $blockQuery->orderBy($sort_field, strtolower($sort_mode ?? 'asc'));
        }
        // limiting
        $limit = $request->query('limit');
        if ($limit && $limit == -1) {
            return Block::all();
        }
        // paginiting and returning final results
        return $blockQuery->paginate($limit ?? 10);
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

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');
        Block::destroy(json_decode("[$ids]"));
        return response()->json(null, 204);
    }

    public function search()
    {
    }

    public function uploadCsv(Request $request)
    {
        dd($request);
    }

    public function uploadExcel(Request $request)
    {
        $file = $request->file('file');
        $blocksFromFile = (new BlocksImport)->toCollection($file)->flatten(1);
        $blocksFromFile->each(function ($block, $key) {
            Block::updateOrCreate(['id' => $block['id']], ["section_code" => $block["section_code"],
                                                           "language" => $block["language"],
                                                           "title" => $block["title"],
                                                           "content" => $block["content"],
                                                           "image" => $block["image"],
                                                           "image_mobile" => $block["image_mobile"],
                                                           "link" => $block["link"],
                                                           "btn_title" => $block["button_link"]]); // ? fix btn_title not there instead there is button_link
        });
        return response()->json(['message' => 'data added successfully'], 200);
    }

    public function filter(Request $request)
    {
        dd($request->all());
        // dd($request->query('filter'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    private function validateRequest(Request $request)
    {
        // $exists = Brand::where('slug', $request->slug)->exists();
        // $isUpdateQuery = (($request->method() == 'PUT') || ($request->method() == 'PATCH'));
        $validatedData = $request->validate([
            "slug" => "nullable",
            "parent_id" => "nullable | integer",
            "name" => "required | json",
            "description" => "nullable | json",
            // "icon" => "nullable | mimes:jpeg,jpg,png,bmp",
            "icon" => "nullable",
            "h1_tag" => "nullable | json",
            "h2_tag" => "nullable | json",
            "meta_title" => "nullable | json",
            "meta_desc" => "nullable | json",
            // "header_image" => "nullable | mimes:jpeg,jpg,png,bmp",
            "header_image" => "nullable",
            "meta_kw" => "nullable | json",
            "is_filter*" => "required | boolean",
            "recom_store" => "nullable | json",
            "override_stores*" => "required | boolean",
            "visits*" => "required | integer",
            "exclude_sitemap*" => "required | boolean",
            "offers_count*" => "required | integer",
            "filter" => "nullable | json",
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
        $tagQuery = new Tag;

        // Search
        $search_query = $request->query('search');
        if ($search_query) {
            foreach ($tagQuery->searchable_fields as $field) {
                $tagQuery = $tagQuery->orWhere($field, 'like', '%' . $search_query . '%');
            }
        }

        // ? skipped for now
        // Filters
        $filters = $request->query('filters');
        if ($filters) {
            // dd('skipped for now');
            $tagQuery = $tagQuery->where('visits', '>', 80);
            // $filters = json_decode($filters, true);
            // foreach ($filters as $key => $filter) {
            //     if (is_array($filter)) {
            //         $tagQuery = $tagQuery->whereIn($key, $filter);
            //     } else {
            //         $tagQuery = $tagQuery->where($key, 'like', '%' . $filter . '%');
            //     }
            // }
        }

        // Sort
        $sort_field = $request->query('sort');
        $sort_mode = $request->query('sort-order');
        if ($sort_field) {
            $tagQuery = $tagQuery->orderBy($sort_field, strtolower($sort_mode ?? 'asc'));
        }

        // limiting
        $limit = $request->query('limit');
        if ($limit && $limit == -1) {
            return Tag::all();
        }

        // Patinating and returning final results
        return $tagQuery->paginate($limit ?? 10);
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
        if (isset($data['icon'])) {
            $data['icon'] = "http://127.0.0.1:8000/storage/" . $data['icon']->store('tag/icon', 'public');
        }
        if (isset($data['header_image'])) {
            $data['header_image'] = "http://127.0.0.1:8000/storage/" . $data['header_image']->store('tag/header_image', 'public');
        }
        return Tag::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return $tag;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $data = $this->validateRequest($request);
        if ($data["icon"] !== 'undefined') {
            $data['icon'] = "http://127.0.0.1:8000/storage/" . $data['icon']->store('tag/icon', 'public');
        } else {
            $data['icon'] = $tag->icon;
        }
        if ($data["header_image"] !== 'undefined' && $data["header_image"] !== "null") {
            $data['header_image'] = "http://127.0.0.1:8000/storage/" . $data['header_image']->store('tag/header_image', 'public');
        } else {
            $data['header_image'] = $tag->header_image;
        }
        $status = $tag->update($data);
        if ($status) {
            return $tag;
        } else {
            return 'Error';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        try {
            $tag->delete();
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');
        Tag::destroy(json_decode("[$ids]"));
        return response()->json(null, 204);
    }

    public function upload(Request $request)
    {
        dd('skipped for now');
        $file = $request->file('file');
        $blocksFromFile = (new BlocksImport)->toCollection($file)->flatten(1);
        $blocksFromFile->each(function ($block, $key) {
            Block::updateOrCreate(['id' => $block['id']], [
                "section_code" => $block["section_code"],
                "language" => $block["language"],
                "title" => $block["title"],
                "content" => $block["content"],
                "image" => $block["image"],
                "image_mobile" => $block["image_mobile"],
                "link" => $block["link"],
                "btn_title" => $block["button_link"]
            ]); // ? fix btn_title not there instead there is button_link
        });
        return response()->json(['message' => 'data added successfully'], 200);
    }

    public function export(Request $request)
    {
        dd('skipped for now');
        $lang = $request->query('lang');
        return (new BrandsExport($lang))->download('BrandsExport.xlsx');
    }

    public function test(Request $request)
    {
        dd($request->all());
    }
}

<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Exports\BrandsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BrandController extends Controller
{
    private function validateRequest(Request $request)
    {
        $exists = Brand::where('slug', $request->slug)->exists();
        $isUpdateQuery = (($request->method() == 'PUT') || ($request->method() == 'PATCH'));
        $validatedData = $request->validate([
            "name" => 'required | json',
            // "slug" => ['required','unique:brands,slug' . (($isUpdateQuery && $exists) ? ',' . $request->slug . ',slug': '')],
            "slug" => ['nullable', 'unique:brands,slug'],
            "description" => 'nullable | json',
            "h1_tag" => 'nullable | json',
            "h2_tag" => 'nullable | json',
            "meta_title" => 'nullable | json',
            "meta_desc" => 'nullable | json',
            "meta_kw" => 'nullable | json',
            "icon" => 'nullable | mimes:jpeg,bmp,png',
            "header_img" => 'nullable | mimes:jpeg,bmp,png',
            "recom_store" => 'nullable | json',
            "override_stores" => 'required | boolean',
            "visits" => 'required | integer',
            "exclude_sitemap" => 'required | boolean',
            "filter" => 'nullable | json',
            "offers_count" => 'required | integer',
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
        $brandQuery = new Brand;

        // Search
        $search_query = $request->query('search');
        if ($search_query) {
            foreach ($brandQuery->searchable_fields as $field) {
                $brandQuery = $brandQuery->orWhere($field, 'like', '%' . $search_query . '%');
            }
        }

        // ? skipped for now
        // Filters
        $filters = $request->query('filters');
        if ($filters) {
            // dd('skipped for now');
            $brandQuery = $brandQuery->where('visits', '>', 80);
            // $filters = json_decode($filters, true);
            // foreach ($filters as $key => $filter) {
            //     if (is_array($filter)) {
            //         $brandQuery = $brandQuery->whereIn($key, $filter);
            //     } else {
            //         $brandQuery = $brandQuery->where($key, 'like', '%' . $filter . '%');
            //     }
            // }
        }

        // Sort
        $sort_field = $request->query('sort');
        $sort_mode = $request->query('sort-order');
        if ($sort_field) {
            $brandQuery = $brandQuery->orderBy($sort_field, strtolower($sort_mode ?? 'asc'));
        }

        // limiting
        $limit = $request->query('limit');
        if ($limit && $limit == -1) {
            return Brand::all();
        }

        // Patinating and returning final results
        return $brandQuery->paginate($limit ?? 10);
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
        return Brand::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return $brand;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $data = $this->validateRequest($request);
        $status = $brand->update($data);
        if ($status) {
            return $brand;
        } else {
            return 'Error';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');
        Brand::destroy(json_decode("[$ids]"));
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
        
        $lang = $request->query('lang');
        return (new BrandsExport($lang))->download('BrandsExport.xlsx');
    }

    public function test(Request $request) {
        dd($request->all());
    }
}

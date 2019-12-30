<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private function validateRequest(Request $request)
    {
        // $exists = Brand::where('slug', $request->slug)->exists();
        // $isUpdateQuery = (($request->method() == 'PUT') || ($request->method() == 'PATCH'));
        $validatedData = $request->validate([
            "name" => "required | json",
            "slug" => "nullable",
            "parent_id" => "nullable | integer",
            "description" => "nullable | json",
            "h1_tag" => "nullable | json",
            "h2_tag" => "nullable | json",
            "meta_title" => "nullable | json",
            "meta_desc" => "nullable | json",
            "meta_kw" => "nullable | json",
            "recom_store" => "nullable | json",
            // "icon" => "nullable | mimes:jpeg,jpg,png,bmp",
            "icon" => "nullable",
            // "header_image" => "nullable | mimes:jpeg,jpg,png,bmp",
            "header_image" => "nullable",
            "override_stores" => "required | boolean",
            "visits" => "required | integer",
            "exclude_sitemap" => "required  | boolean",
            "store_count" => "required | integer",
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
        $categoryQuery = new Category;

        // Names
        if (array_key_exists("name", $request->all())) {
            return $categoryQuery->where('name', 'like', '%'.$request->query('name').'%')->take(10)->get();
        }
        // Ids
        if (array_key_exists("ids", $request->all())) {
            return $categoryQuery->whereIn('id', json_decode('['.$request->query('ids').']'))->get();
        }

        // Search
        $search_query = $request->query('search');
        if ($search_query) {
            foreach ($categoryQuery->searchable_fields as $field) {
                $categoryQuery = $categoryQuery->orWhere($field, 'like', '%' . $search_query . '%');
            }
        }

        // ? skipped for now
        // Filters
        $filters = $request->query('filters');
        if ($filters) {
            // dd('skipped for now');
            $categoryQuery = $categoryQuery->where('visits', '>', 80);
            // $filters = json_decode($filters, true);
            // foreach ($filters as $key => $filter) {
            //     if (is_array($filter)) {
            //         $categoryQuery = $categoryQuery->whereIn($key, $filter);
            //     } else {
            //         $categoryQuery = $categoryQuery->where($key, 'like', '%' . $filter . '%');
            //     }
            // }
        }

        // Sort
        $sort_field = $request->query('sort');
        $sort_mode = $request->query('sort-order');
        if ($sort_field) {
            $categoryQuery = $categoryQuery->orderBy($sort_field, strtolower($sort_mode ?? 'asc'));
        }

        // limiting
        $limit = $request->query('limit');
        if ($limit && $limit == -1) {
            return Category::all();
        }

        // Patinating and returning final results
        return $categoryQuery->paginate($limit ?? 10);
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
        if(isset($data['icon'])) {
            $data['icon'] = "http://127.0.0.1:8000/storage/" . $data['icon']->store('category/icon', 'public');
        }
        if(isset($data['header_image'])) {
            $data['header_image'] = "http://127.0.0.1:8000/storage/" . $data['header_image']->store('category/header_image', 'public');
        }
        return Category::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $category;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $data = $this->validateRequest($request);
        if ($data["icon"] !== 'undefined') {
            $data['icon'] = "http://127.0.0.1:8000/storage/" . $data['icon']->store('category/icon', 'public');
        } else {
            $data['icon'] = $category->icon;
        }
        if ($data["header_image"] !== 'undefined' && $data["header_image"] !== "null") {
            $data['header_image'] = "http://127.0.0.1:8000/storage/" . $data['header_image']->store('category/header_image', 'public');
        } else {
            $data['header_image'] = $category->header_image;
        }
        $status = $category->update($data);
        if ($status) {
            return $category;
        } else {
            return 'Error';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');
        Category::destroy(json_decode("[$ids]"));
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

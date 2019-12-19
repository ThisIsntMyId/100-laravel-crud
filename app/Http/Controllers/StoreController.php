<?php

namespace App\Http\Controllers;

use App\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    private function validateRequest(Request $request)
    {
        // $exists = Brand::where('slug', $request->slug)->exists();
        $isUpdateQuery = (($request->method() == 'PUT') || ($request->method() == 'PATCH'));
        $validatedData = $request->validate([
            "slug" => "nullable",
            "name" => "nullable | json",
            "about_store" => "nullable | json",
            "sp_terms_title" => "nullable | json",
            "sp_terms" => "nullable | json",
            "cb_terms" => "nullable | json",
            "secrets" => "nullable | json",
            "h1_tag" => "nullable | json",
            "h2_tag" => "nullable | json",
            "meta_title" => "nullable | json",
            "meta_desc" => "nullable | json",
            "meta_kw" => "nullable | json",
            // "logo" => "nullable | mimes:jpeg,jpg,png,bmp",
            "logo" => "nullable",
            "categories" => "nullable | json",
            "offers_count" => "nullable | integer",
            "current_cb" => "nullable | numeric",
            "was_cb" => "nullable | ",
            "amount_type" => "required | in:percent,fixed",
            "rate_type" => "required | in:flat,upto",
            // "header_image" => "nullable | mimes:jpeg,jpg,png,bmp",
            "header_image" => "nullable",
            "ref_id" => "nullable",
            "homepage" => "required",
            "dl_url_build" => "nullable",
            "cb_status" => "required | boolean",
            "cashback_percent" => "required | numeric",
            "rate_stars" => "nullable | numeric",
            "rate_count" => "nullable | integer",
            "tracking_days" => "nullable | integer",
            "claim_days" => "nullable | integer",
            "clicks" => "nullable | integer",
            "creation_mode" => "nullable ",
            "network_id" => "nullable",
            "network_store_id" => "required",
            "filter" => "nullable | json",
            "status" => "nullable | in:publish,draft,trash",
            "visits" => "required | integer",
            "exclude_sitemap" => "required | boolean",
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
        $storeQuery = new Store;

        // Names
        if (array_key_exists("name", $request->all())) {
            return $storeQuery->where('name', 'like', '%'.$request->query('name').'%')->paginate(10);
        }
        // Ids
        if (array_key_exists("ids", $request->all())) {
            return $storeQuery->whereIn('id', json_decode('['.$request->query('ids').']'))->get();
        }
        // Search
        $search_query = $request->query('search');
        if ($search_query) {
            foreach ($storeQuery->searchable_fields as $field) {
                $storeQuery = $storeQuery->orWhere($field, 'like', '%' . $search_query . '%');
            }
        }

        // ? skipped for now
        // Filters
        $filters = $request->query('filters');
        if ($filters) {
            // dd('skipped for now');
            $storeQuery = $storeQuery->where('visits', '>', 80);
            // $filters = json_decode($filters, true);
            // foreach ($filters as $key => $filter) {
            //     if (is_array($filter)) {
            //         $storeQuery = $storeQuery->whereIn($key, $filter);
            //     } else {
            //         $storeQuery = $storeQuery->where($key, 'like', '%' . $filter . '%');
            //     }
            // }
        }

        // Sort
        $sort_field = $request->query('sort');
        $sort_mode = $request->query('sort-order');
        if ($sort_field) {
            $storeQuery = $storeQuery->orderBy($sort_field, strtolower($sort_mode ?? 'asc'));
        }

        // limiting
        $limit = $request->query('limit');
        if ($limit && $limit == -1) {
            return Store::all();
        }

        // Patinating and returning final results
        return $storeQuery->paginate($limit ?? 10);
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
        $data['logo'] = "http://127.0.0.1:8000/storage/" . $data['logo']->store('store/logo', 'public');
        $data['header_image'] = "http://127.0.0.1:8000/storage/" . $data['header_image']->store('store/header_image', 'public');
        return Store::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        return $store;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        $data = $this->validateRequest($request);
        if ($data["logo"] !== 'undefined') {
            $data['logo'] = "http://127.0.0.1:8000/storage/" . $data['logo']->store('logo', 'public');
        } else {
            $data['logo'] = $store->logo;
        }
        if ($data["header_image"] !== 'undefined') {
            $data['header_image'] = "http://127.0.0.1:8000/storage/" . $data['header_image']->store('header_image', 'public');
        } else {
            $data['header_image'] = $store->header_image;
        }
        $status = $store->update($data);
        if ($status) {
            return $store;
        } else {
            return 'Error';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        try {
            $store->delete();
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');
        Store::destroy(json_decode("[$ids]"));
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
        dd("skipped for now");
        $lang = $request->query('lang');
        return (new BrandsExport($lang))->download('BrandsExport.xlsx');
    }

    public function test(Request $request)
    {
        dd($request->all());
    }
}

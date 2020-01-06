<?php

namespace App\Http\Controllers;

use App\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    private function validateRequest(Request $request)
    {
        // $exists = Brand::where(slug, $request->slug)->exists();
        // $isUpdateQuery = (($request->method() == PUT) || ($request->method() == PATCH));
        $validatedData = $request->validate([
            "title" => "required | json",
            "store_id" => "required | exists:stores,id",
            "description" => "nullable | json",
            "promo_text" => "nullable | json",
            "type" => "required | in:offer,coupon",
            "code" => "nullable",
            "link" => "required",
            "brands" => "nullable | json",
            "coupons" => "nullable | json",
            "cats" => "nullable | json",
            "is_featured" => "required | boolean",
            "created_mode" => "nullable",
            "network_id" => "nullable",
            "network_coupon_id" => "nullable",
            "expiry_date" => "nullable | date",
            "status" => "required | in:publish,draft,trash",
            "clicks" => "required | integer",
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
        $couponQuery = new Coupon;

        // Search
        $search_query = $request->query('search');
        if ($search_query) {
            foreach ($couponQuery->searchable_fields as $field) {
                $couponQuery = $couponQuery->orWhere($field, 'like', '%' . $search_query . '%');
            }
        }

        // ? skipped for now
        // Filters
        $filters = $request->query('filters');
        if ($filters) {
            // dd('skipped for now');
            $couponQuery = $couponQuery->where('clicks', '>', 80);
            // $filters = json_decode($filters, true);
            // foreach ($filters as $key => $filter) {
            //     if (is_array($filter)) {
            //         $couponQuery = $couponQuery->whereIn($key, $filter);
            //     } else {
            //         $couponQuery = $couponQuery->where($key, 'like', '%' . $filter . '%');
            //     }
            // }
        }

        // Sort
        $sort_field = $request->query('sort');
        $sort_mode = $request->query('sort-order');
        if ($sort_field) {
            $couponQuery = $couponQuery->orderBy($sort_field, strtolower($sort_mode ?? 'asc'));
        }

        // limiting
        $limit = $request->query('limit');
        if ($limit && $limit == -1) {
            return Coupon::all();
        }

        // Patinating and returning final results
        return $couponQuery->paginate($limit ?? 10);
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
        return Coupon::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        return $coupon;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        $data = $this->validateRequest($request);
        $status = $coupon->update($data);
        if ($status) {
            return $coupon;
        } else {
            return 'Error';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        try {
            $coupon->delete();
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');
        Coupon::destroy(json_decode("[$ids]"));
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

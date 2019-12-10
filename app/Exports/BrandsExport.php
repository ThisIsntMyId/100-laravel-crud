<?php

namespace App\Exports;

use App\Brand;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class BrandsExport implements FromCollection, WithHeadings, WithStrictNullComparison, WithMapping
{
    use Exportable;

    protected $lang = '';

    public function __construct($lang)
    {
        $this->lang = $lang;
    }

    public function map($row): array
    {
        $name = json_decode($row->name, true)[$this->lang];
        dd($name);
        $arr = [
            $row->id,
            $name,
            $row->slug,
            $row->description,
            $row->h1_tag,
            $row->h2_tag,
            $row->meta_title,
            $row->meta_desc,
            $row->meta_kw,
            $row->icon,
            $row->header_img,
            $row->recom_store,
            $row->override_store,
            $row->visits,
            $row->exclude_sitemap,
            $row->filter,
            $row->offer_count,
        ];

        return $arr;
    }

    public function headings(): array
    {
        return [
            'Id',
            'Name',
            'Slug',
            'Description',
            'H1 Tag',
            'H2 Tag',
            'Meta Tag',
            'Meta Desc',
            'Meta Kw',
            'Icon',
            'Header Image',
            'Recommended Store',
            'Override Store',
            'Visits',
            'Exclude Sitemap',
            'Filters',
            'Offer Count',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Brand::all();
    }
}

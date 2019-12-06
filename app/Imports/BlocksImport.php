<?php

namespace App\Imports;

use App\Block;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BlocksImport implements ToModel, WithHeadingRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Block([
            "section_code" => $row['Section Code'],
            "language" => $row['Language'],
            "title" => $row['Title'],
            "content" => $row['Content'],
            "image" => $row['Image'],
            "image_mobile" => $row['Image Mobile'],
            "link" => $row['Link'],
            "btn_title" => $row['Button Link'],
        ]);
    }
}
<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class EmbarquesExport implements FromArray
{

    public $linhas;

    public function __construct(array $linhas){

        $this->linhas = $linhas;

    }

    public function array(): array
    {
        return $this->linhas;
    }

}

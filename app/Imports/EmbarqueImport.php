<?php

namespace App\Imports;

use App\Embarque;
use App\PortfolioItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmbarqueImport implements ToCollection, /* WithValidation, */ WithCustomCsvSettings, WithStartRow
{

    public $importacao;

    public function __construct($importacao){
        $this->importacao = $importacao;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $k => $row){

            $portfolioItem = PortfolioItem::where('importacao', $this->importacao)
            ->where('secundario', $row[0])->first();

            Embarque::create([
                'id_portfolio' => $portfolioItem->id_portfolio,
                'id_portfolio_item' => $portfolioItem->id,
                'id_usuario' => auth()->user()->id,
                'importacao' => $this->importacao,
                'secundario' => $row[0],
                'qtde' => $row[1],
                'valor_unitario' => $row[2],
                'tipo' => $row[3],          //MONTADO ou DESMONTADO
                'qtde_embarque1' => $row[4],
                'qtde_embarque2' => $row[5],
                'qtde_embarque3' => $row[6],
                'qtde_embarque4' => $row[7],
                'qtde_embarque5' => $row[8],
                'created_at' => now(),
            ]);
            
        }

    }

    public function startRow(): int
    {
        return 2;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ";",
        ];
    }

}

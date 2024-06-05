<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
// use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Database\Query\Builder;

class ContactsExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $items;

    public function __construct(Builder $items)
    {
        $this->items = $items;
    }

    public function query()
    {
        return $this->items;
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'lastname',
            'created_at',
            'updated_at',
        ];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->name,
            $item->lastname,
            $item->created_at,
            $item->updated_at,
        ];
    }
}

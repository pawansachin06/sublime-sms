<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Database\Query\Builder;

class ContactGroupsExport implements FromQuery, WithMapping, WithHeadings
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
            'uid',
            'name',
            'username',
            'status',
            'deleted_at',
            'created_at',
            'updated_at',
        ];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->uid,
            $item->name,
            $item->author->username,
            strtolower($item->status),
            $item->deleted_at,
            $item->created_at,
            $item->updated_at,
        ];
    }
}

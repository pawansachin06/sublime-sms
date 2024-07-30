<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
// use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Database\Query\Builder;

class SmsExport implements FromQuery, WithMapping, WithHeadings
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
            'folder',
            'delivered_at',
            'to',
            'recipient',
            'name',
            'country',
            'from',
            'status',
            'message',
            'user_id',
            'user_email',
            'user_name',
            'send_at',
        ];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->folder,
            $item->delivered_at,
            $item->to,
            $item->recipient,
            $item->name,
            $item->countrycode,
            $item->from,
            strtolower($item->status),
            $item->message,
            $item->user_id,
            $item->sender?->email,
            $item->sender?->name,
            $item->send_at,
        ];
    }
}

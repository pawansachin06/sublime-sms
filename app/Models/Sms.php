<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    use HasFactory;

    protected $table = 'sms';

    protected $fillable = [
        'id', 'sms_job_id', 'sms_id', 'user_id', 'message', 'to', 'list_id',
        'countrycode', 'from', 'send_at', 'validity', 'replies_to_email',
        'tracked_link_url', 'link_hits_callback', 'dlr_callback',
        'reply_callback', 'status', 'delivered_at', 'local_status',
        'name', 'recipient', 'direction', 'folder', 'cost',
    ];

    protected function casts(): array
    {
        return [
            'send_at' => 'datetime',
        ];
    }
}

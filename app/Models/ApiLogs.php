<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLogs extends Model
{
    use HasFactory;
    protected $fillable = [
        'method',
        'url',
        'request_data',
        'response_content',
        'user_id',
        'response_status'
    ];
}

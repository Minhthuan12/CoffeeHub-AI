<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KhuVucBan extends Model
{
    use SoftDeletes;

    protected $table = 'khu_vuc_bans';

    protected $fillable = [
        'ten_khu_vuc',
        'mo_ta',
    ];
}

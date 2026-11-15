<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiaoDich extends Model
{
    protected $table = 'giao_diches';

    protected $fillable = [
        'creditAmount',
        'description',
        'refNo',
        'id_hoa_don'
    ];
}

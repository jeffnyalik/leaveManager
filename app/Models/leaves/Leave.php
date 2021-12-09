<?php

namespace App\Models\leaves;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;
    protected $fillable =
     [
        'leave_name',
        'leave_type',
        'leave_status'
     ];
}

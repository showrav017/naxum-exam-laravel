<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'user_id',
        'last_logged_at'
    ];

    protected $table = 'users';
    protected $keyType = "string";
    protected $primaryKey = 'user_id';
    public $timestamps = true;
}

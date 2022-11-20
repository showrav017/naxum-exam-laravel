<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    use HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'contact_id',
    ];

    protected $table = 'contacts';
    protected $keyType = "string";
    protected $primaryKey = 'contact_id';
    public $timestamps = true;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NamaCustomer extends Model
{
    protected $table = 'nama_customer';

    protected $fillable = ['nama', 'gender', 'customer'];
}

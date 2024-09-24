<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pesanan';
    protected $fillable = ['total', 'email_pembeli', 'status'];

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}

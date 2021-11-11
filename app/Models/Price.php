<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'price',
        'status'
    ];
    public function ticket()
    {
        return $this->belongsTo(Ticket::class,'price_id');
    }
    use HasFactory;
}

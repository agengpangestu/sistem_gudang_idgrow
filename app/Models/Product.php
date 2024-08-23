<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_code',
        'location',
        'price'
    ];

    public function mutationRelation()
    {
        return $this->hasMany(Mutation::class, 'product_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'date',
        'mutation_type',
        'amount'
    ];

    public function userRelation()
    {
        return $this->belongsTo(User::class);
    }

    public function productRelation()
    {
        return $this->belongsTo(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class CartItem extends Model
{
    use HasFactory;

    // Disable auto-incrementing as we're using UUID strings
    public $incrementing = false;

    // Set primary key type to string (UUID)
    protected $keyType = 'string';

    // Allow mass assignment for these fields
    protected $fillable = ['cart_id', 'product_id', 'quantity'];

    /**
     * Boot method to auto-generate UUID for id on creating
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    /**
     * CartItem belongs to one Cart
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * CartItem belongs to one Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

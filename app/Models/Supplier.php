<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
    ];

    /**
     * Relationship: Supplier has Many Products (Many-to-Many via product_suppliers)
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_suppliers')
                    ->withPivot('supplier_price');
    }
}

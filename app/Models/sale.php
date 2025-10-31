<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Salesummary;
class sale extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($sale) {
            $sale->subtotal = $sale->price * $sale->Quantity;
        });
    }
    public function summary()
    {
        return $this->belongsTo(Salesummary::class, 'paymentId');
    }
    


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\sale;
class Salesummary extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function sales()
{
    return $this->hasMany(sale::class);
}

}

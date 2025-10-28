<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
   public $fillable = [
    'ProductName',
    'StockQnty',
    'Unit' ,
    'CostPerUnit',
    'SellinPerUnit',
    'ReorderLevel',
    'SupplierName',
    'last_update',
    'userId',
    'description'
   ];
}

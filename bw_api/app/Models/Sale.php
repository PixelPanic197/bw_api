<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $table = 'sales';
    // Разрешенные поля для массового заполнения
    protected $fillable = [
        'g_number', 
        'date', 
        'supplier_article', 
        'tech_size', 
        'barcode', 
        'total_price', 
        'discount_percent', 
        'is_supply', 
        'is_realization', 
        'warehouse_name', 
        'country_name', 
        'region_name', 
        'income_id', 
        'sale_id', 
        'subject', 
        'brand'
    ];

    // Если хотите защитить некоторые поля от массового назначения:
    // protected $guarded = ['id'];
}

<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Inventory\ItemModel;
use App\Models\Inventory\SupplierModel;

class ItemRequestModel extends Model
{
    use HasFactory;

    protected $table = 'item_requests';
    
    protected $fillable = [
        'user_id',
        'item_id',
        'item_name_temp',
        'qty',
        'status',
        'note',
    ];

    public function item()
    {
        return $this->belongsTo(ItemModel::class, 'item_id');
    }

    /**
     * RELATION USER (yang buat request)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * RELATION SUPPLIER (opsional jika dipakai)
     */
    public function supplier()
    {
        return $this->belongsTo(SupplierModel::class, 'supplier_id');
    }
}

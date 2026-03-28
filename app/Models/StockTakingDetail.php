<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTakingDetail extends Model
{
    /** @use HasFactory<\Database\Factories\StockTakingDetailFactory> */
    use HasFactory;

    protected $fillable = [
        'stock_taking_session_id',
        'tag_number',
        'item_id',
        'actual_quantity',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'actual_quantity' => 'decimal:2',
        ];
    }

    /**
     * Get the session this detail belongs to.
     */
    public function session()
    {
        return $this->belongsTo(StockTakingSession::class, 'stock_taking_session_id');
    }

    /**
     * Get the item for this detail.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

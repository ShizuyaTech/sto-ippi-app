<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category',
        'description',
        'unit',
    ];

    protected function casts(): array
    {
        return [
            'category' => 'string',
        ];
    }

    /**
     * Get stock taking details for this item.
     */
    public function stockTakingDetails()
    {
        return $this->hasMany(StockTakingDetail::class);
    }

    /**
     * Get category label.
     */
    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'raw_material' => 'Raw Material',
            'wip' => 'WIP (Work In Progress)',
            'finish_part' => 'Finish Part',
            default => $this->category,
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTakingSession extends Model
{
    /** @use HasFactory<\Database\Factories\StockTakingSessionFactory> */
    use HasFactory;

    protected $fillable = [
        'session_code',
        'user_id',
        'category',
        'status',
        'scheduled_date',
        'started_at',
        'completed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Get the user assigned to this session.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get stock taking details for this session.
     */
    public function details()
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

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            default => $this->status,
        };
    }
}

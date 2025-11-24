<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFault extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'fault_type',
        'detected_at',
        'stock_at_detection',
        'min_stock_at_detection',
        'max_stock_at_detection',
        'reviewed',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'reviewed' => 'boolean',
        'stock_at_detection' => 'integer',
        'min_stock_at_detection' => 'integer',
        'max_stock_at_detection' => 'integer',
    ];

    /**
     * Get the product that owns the fault.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who reviewed this fault.
     */
    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope to get only pending (not reviewed) faults.
     */
    public function scopePending($query)
    {
        return $query->where('reviewed', false);
    }

    /**
     * Scope to get only reviewed faults.
     */
    public function scopeReviewed($query)
    {
        return $query->where('reviewed', true);
    }

    /**
     * Scope to get only low stock faults.
     */
    public function scopeLowStock($query)
    {
        return $query->where('fault_type', 'low_stock');
    }

    /**
     * Scope to get only over stock faults.
     */
    public function scopeOverStock($query)
    {
        return $query->where('fault_type', 'over_stock');
    }

    /**
     * Mark this fault as reviewed by a user.
     */
    public function markAsReviewed($userId)
    {
        $this->update([
            'reviewed' => true,
            'reviewed_at' => now(),
            'reviewed_by' => $userId,
        ]);
    }
}

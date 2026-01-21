<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignalAllowedUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'signal_id',
        'user_id',
    ];

    // ==================== RELATIONSHIPS ====================

    public function signal()
    {
        return $this->belongsTo(TradingSignal::class, 'signal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ==================== SCOPES ====================

    public function scopeForSignal($query, $signalId)
    {
        return $query->where('signal_id', $signalId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Bulk insert allowed users untuk signal
     */
    public static function syncAllowedUsers($signalId, array $userIds)
    {
        // Delete existing
        self::where('signal_id', $signalId)->delete();

        // Insert new
        if (!empty($userIds)) {
            $data = [];
            foreach ($userIds as $userId) {
                $data[] = [
                    'signal_id' => $signalId,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            self::insert($data);
        }
    }

    /**
     * Check if user is allowed for signal
     */
    public static function isUserAllowed($signalId, $userId)
    {
        return self::where('signal_id', $signalId)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Get all allowed user IDs for signal
     */
    public static function getAllowedUserIds($signalId)
    {
        return self::where('signal_id', $signalId)
            ->pluck('user_id')
            ->toArray();
    }
}

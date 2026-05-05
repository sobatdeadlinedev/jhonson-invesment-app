<?php

namespace App\Observers;

use App\Models\User;
use App\Models\AchievedVolumeLog;

class UserObserver
{
    /**
     * Setiap kali user di-update dan achieved_volume bertambah,
     * catat selisihnya ke achieved_volume_logs.
     */
    public function updating(User $user): void
    {
        // Cek apakah achieved_volume berubah dan nilainya bertambah
        if ($user->isDirty('achieved_volume')) {
            $before = (float) $user->getOriginal('achieved_volume');
            $after  = (float) $user->achieved_volume;
            $diff   = $after - $before;

            // Hanya catat jika bertambah (positif)
            if ($diff > 0) {
                AchievedVolumeLog::create([
                    'user_id'          => $user->id,
                    'amount'           => $diff,
                    'achieved_before'  => $before,
                    'achieved_after'   => $after,
                ]);
            }
        }
    }
}
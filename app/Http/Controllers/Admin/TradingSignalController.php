<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TradingSignal;
use App\Models\SignalParticipant;
use App\Models\SignalAllowedUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TradingSignalController extends Controller
{
    public function index()
    {
        $signals = TradingSignal::with(['creator', 'allowedUsers'])
            ->withCount('participants')
            ->latest()
            ->paginate(15);

        return view('admin.pages.signals.index', compact('signals'));
    }

    public function create()
    {
        $coins = TradingSignal::getAvailableCoins();

        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone']);

        return view('admin.pages.signals.create', compact('coins', 'users'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPER: ambil semua downline dari satu user sampai kedalaman $maxDepth
    // Mengembalikan flat array of user IDs (tidak termasuk leader itu sendiri)
    // ─────────────────────────────────────────────────────────────────────────
    private function getDownlineIds(int $leaderId, int $maxDepth = 10): array
{
    $allIds = [];
    $queue  = [$leaderId];
    $depth  = 0;

    while (!empty($queue) && $depth < $maxDepth) {
        $depth++;

        $nextLevel = DB::table('referral_usages')
            ->whereIn('referrer_id', $queue)
            ->pluck('referred_id')
            ->toArray();

        if (empty($nextLevel)) {
            break;
        }

        $allIds = array_merge($allIds, $nextLevel);
        $queue  = $nextLevel;
    }

    return array_unique($allIds);
}

    // ─────────────────────────────────────────────────────────────────────────
    // HELPER: ambil downline per-level (untuk preview UI)
    // Mengembalikan ['1' => [users...], '2' => [users...], ...]
    // ─────────────────────────────────────────────────────────────────────────
   private function getDownlineByLevel(int $leaderId, int $maxDepth = 10): array
{
    $levels = [];
    $queue  = [$leaderId];
    $depth  = 0;

    while (!empty($queue) && $depth < $maxDepth) {
        $depth++;

        $referredIds = DB::table('referral_usages')
            ->whereIn('referrer_id', $queue)
            ->pluck('referred_id')
            ->toArray();

        if (empty($referredIds)) {
            break;
        }

        $nextLevel = User::whereIn('id', $referredIds)
            ->get(['id', 'name', 'email', 'phone'])
            ->toArray();

        if (empty($nextLevel)) {
            break;
        }

        $levels[(string)$depth] = $nextLevel;
        $queue = array_column($nextLevel, 'id');
    }

    return $levels;
}

    // ─────────────────────────────────────────────────────────────────────────
    // AJAX: Preview anggota grup sebelum signal disimpan
    // POST /admin/signals/group-preview
    // Body: { leader_ids: [id], depth: number }
    // ─────────────────────────────────────────────────────────────────────────
    public function groupPreview(Request $request)
    {
        $request->validate([
            'leader_ids'   => 'required|array|min:1|max:1',
            'leader_ids.*' => 'required|integer|exists:users,id',
            'depth'        => 'required|integer|in:1,3,5,10',
        ]);

        $leaderId = (int) $request->leader_ids[0];
        $depth    = (int) $request->depth;

        try {
            $leader = User::findOrFail($leaderId);
            $levels = $this->getDownlineByLevel($leaderId, $depth);

            // Hitung total downline (semua level digabung, sudah unique per-level)
            $totalDownline = array_sum(array_map('count', $levels));

            return response()->json([
                'total'   => $totalDownline + 1, // +1 untuk leader sendiri
                'leaders' => [
                    [
                        'id'             => $leader->id,
                        'name'           => $leader->name,
                        'email'          => $leader->email,
                        'total_downline' => $totalDownline,
                        'levels'         => $levels,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Group preview failed: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat data: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $accessMode = $request->input('access_mode', 'public');

        $rules = [
            'title'       => 'required|string|max:255',
            'coin'        => 'required|string|in:' . implode(',', array_keys(TradingSignal::getAvailableCoins())),
            'description' => 'nullable|string',
            'entry_price' => 'required|numeric|min:0',
            'target_price'=> 'required|numeric|min:0',
            'bet_type'    => 'required|in:percentage,fixed',
            'bet_value'   => 'required|numeric|min:0.01',
            'is_public'   => 'nullable|boolean',
            'access_mode' => 'required|in:public,specific,group',
        ];

        if ($accessMode === 'specific') {
            $rules['allowed_user_ids']   = 'required|array|min:1';
            $rules['allowed_user_ids.*'] = 'exists:users,id';
        }

        if ($accessMode === 'group') {
            $rules['group_leader_ids']    = 'required|array|min:1|max:1';
            $rules['group_leader_ids.*']  = 'exists:users,id';
            $rules['group_depth']         = 'required|integer|in:1,3,5,10';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            $isPublic = ($accessMode === 'public');

            // ── Simpan signal ──────────────────────────────────────────────
            $signalData = [
                'title'       => $request->title,
                'coin'        => strtoupper($request->coin),
                'description' => $request->description,
                'entry_price' => $request->entry_price,
                'target_price'=> $request->target_price,
                'bet_type'    => $request->bet_type,
                'bet_value'   => $request->bet_value,
                'is_public'   => $isPublic,
                'status'      => 'open',
                'created_by'  => auth()->id(),
            ];

            // Simpan group meta jika mode group
            if ($accessMode === 'group') {
                $leaderId              = (int) $request->group_leader_ids[0];
                $depth                 = (int) $request->group_depth;
                $signalData['group_leader_id']      = $leaderId;
                $signalData['group_depth']          = $depth;
                // total members akan dihitung setelah allowed users disync
            }

            $signal = TradingSignal::create($signalData);

            // ── Sync allowed users ─────────────────────────────────────────
            if ($accessMode === 'specific' && !empty($request->allowed_user_ids)) {
                SignalAllowedUser::syncAllowedUsers($signal->id, $request->allowed_user_ids);

            } elseif ($accessMode === 'group') {
                $leaderId    = (int) $request->group_leader_ids[0];
                $depth       = (int) $request->group_depth;
                $downlineIds = $this->getDownlineIds($leaderId, $depth);

                // Gabungkan leader + semua downline, hapus duplikat
                $allMemberIds = array_unique(array_merge([$leaderId], $downlineIds));

                SignalAllowedUser::syncAllowedUsers($signal->id, $allMemberIds);

                // Update snapshot jumlah member
                $signal->update(['group_total_members' => count($allMemberIds)]);
            }

            DB::commit();

            Log::info('Signal created', [
                'signal_id'   => $signal->id,
                'title'       => $signal->title,
                'access_mode' => $accessMode,
                'entry_price' => $signal->entry_price,
                'target_price'=> $signal->target_price,
                'group_leader_id'    => $signal->group_leader_id ?? null,
                'group_total_members'=> $signal->group_total_members ?? null,
            ]);

            return redirect()
                ->route('admin.signals.index')
                ->with('success', 'Trading signal created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create signal failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create signal: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $signal = TradingSignal::with(['creator', 'participants.user', 'allowedUsers.user'])
            ->findOrFail($id);

        $totalParticipants = $signal->participants->count();
        $totalBetAmount    = $signal->participants->sum('bet_amount');

        return view('admin.pages.signals.show', compact('signal', 'totalParticipants', 'totalBetAmount'));
    }

    public function edit($id)
    {
        $signal = TradingSignal::with('allowedUsers')->findOrFail($id);

        if ($signal->status !== 'open') {
            return redirect()->route('admin.signals.index')
                ->with('error', 'Cannot edit signal that is already closed or settled.');
        }

        $coins = TradingSignal::getAvailableCoins();

        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone']);

        return view('admin.pages.signals.edit', compact('signal', 'coins', 'users'));
    }

    public function update(Request $request, $id)
    {
        $signal = TradingSignal::findOrFail($id);

        if ($signal->status !== 'open') {
            return redirect()->route('admin.signals.index')->with('error', 'Cannot update signal.');
        }

        $accessMode = $request->input('access_mode', 'public');

        $rules = [
            'title'       => 'required|string|max:255',
            'coin'        => 'required|string|in:' . implode(',', array_keys(TradingSignal::getAvailableCoins())),
            'description' => 'nullable|string',
            'entry_price' => 'required|numeric|min:0',
            'target_price'=> 'required|numeric|min:0',
            'bet_type'    => 'required|in:percentage,fixed',
            'bet_value'   => 'required|numeric|min:0.01',
            'is_public'   => 'nullable|boolean',
            'access_mode' => 'required|in:public,specific,group',
        ];

        if ($accessMode === 'specific') {
            $rules['allowed_user_ids']   = 'required|array|min:1';
            $rules['allowed_user_ids.*'] = 'exists:users,id';
        }

        if ($accessMode === 'group') {
            $rules['group_leader_ids']   = 'required|array|min:1|max:1';
            $rules['group_leader_ids.*'] = 'exists:users,id';
            $rules['group_depth']        = 'required|integer|in:1,3,5,10';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            $isPublic = ($accessMode === 'public');

            $updateData = [
                'title'               => $request->title,
                'coin'                => strtoupper($request->coin),
                'description'         => $request->description,
                'entry_price'         => $request->entry_price,
                'target_price'        => $request->target_price,
                'bet_type'            => $request->bet_type,
                'bet_value'           => $request->bet_value,
                'is_public'           => $isPublic,
                'group_leader_id'     => null,
                'group_depth'         => 10,
                'group_total_members' => 0,
            ];

            if ($accessMode === 'group') {
                $updateData['group_leader_id'] = (int) $request->group_leader_ids[0];
                $updateData['group_depth']     = (int) $request->group_depth;
            }

            $signal->update($updateData);

            // Sync allowed users
            if ($accessMode === 'public') {
                SignalAllowedUser::where('signal_id', $signal->id)->delete();

            } elseif ($accessMode === 'specific' && !empty($request->allowed_user_ids)) {
                SignalAllowedUser::syncAllowedUsers($signal->id, $request->allowed_user_ids);

            } elseif ($accessMode === 'group') {
                $leaderId    = (int) $request->group_leader_ids[0];
                $depth       = (int) $request->group_depth;
                $downlineIds = $this->getDownlineIds($leaderId, $depth);
                $allMemberIds = array_unique(array_merge([$leaderId], $downlineIds));

                SignalAllowedUser::syncAllowedUsers($signal->id, $allMemberIds);
                $signal->update(['group_total_members' => count($allMemberIds)]);
            }

            DB::commit();

            return redirect()->route('admin.signals.show', $signal->id)
                ->with('success', 'Signal updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update signal failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update signal.');
        }
    }

    /**
     * Close signal - Admin memilih CALL atau PUT
     */
    public function close(Request $request, $id)
    {
        Log::info('=== CLOSE SIGNAL START ===', [
            'signal_id'   => $id,
            'request_data'=> $request->all(),
            'user_id'     => auth()->id(),
        ]);

        try {
            $signal = TradingSignal::findOrFail($id);

            if ($signal->status !== 'open') {
                return redirect()->route('admin.signals.index')
                    ->with('error', 'Signal is not open. Current status: ' . $signal->status);
            }

            $validated = $request->validate([
                'admin_choice'   => 'required|in:call,put',
                'rate_of_return' => 'required|numeric|min:0|max:100',
            ], [
                'admin_choice.required'   => 'Admin must choose CALL or PUT',
                'admin_choice.in'         => 'Choice must be CALL or PUT',
                'rate_of_return.required' => 'Win rate must be filled',
                'rate_of_return.min'      => 'Win rate minimum 0%',
                'rate_of_return.max'      => 'Win rate maximum 100%',
            ]);

            DB::beginTransaction();

            $actualResult   = ($signal->entry_price < $signal->target_price) ? 'call' : 'put';
            $adminChoice    = $request->admin_choice;
            $isAdminCorrect = ($adminChoice === $actualResult);
            $result         = $isAdminCorrect ? 'win' : 'loss';

            $signal->closeSignal($result, $request->rate_of_return, $adminChoice);

            DB::commit();

            Log::info('=== CLOSE SIGNAL SUCCESS ===', [
                'signal_id'      => $signal->id,
                'actual_result'  => strtoupper($actualResult),
                'admin_choice'   => strtoupper($adminChoice),
                'is_admin_correct' => $isAdminCorrect,
                'user_result'    => $result === 'win' ? 'USERS WIN' : 'USERS LOSE',
                'rate_of_return' => $request->rate_of_return,
            ]);

            $priceDirection  = $actualResult === 'call' ? 'UP (CALL)' : 'DOWN (PUT)';
            $adminChoiceText = strtoupper($adminChoice);
            $userOutcome     = $isAdminCorrect ? 'USERS WIN' : 'USERS LOSE';

            return redirect()->route('admin.signals.show', $signal->id)
                ->with('success', "Signal closed! Price went {$priceDirection}. Admin chose {$adminChoiceText}. Result: {$userOutcome} with {$request->rate_of_return}% rate.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== CLOSE SIGNAL FAILED ===', [
                'signal_id'     => $id,
                'error_message' => $e->getMessage(),
            ]);
            return redirect()->back()->withInput()
                ->with('error', 'Failed to close signal: ' . $e->getMessage());
        }
    }

    /**
     * Settle signal
     */
    public function settle($id)
    {
        Log::info('=== SETTLE SIGNAL START ===', ['signal_id' => $id]);

        $signal = TradingSignal::with('participants.user')->findOrFail($id);

        if ($signal->status !== 'closed') {
            return redirect()->route('admin.signals.show', $signal->id)
                ->with('error', 'Signal must be closed before settling. Current status: ' . $signal->status);
        }

        try {
            DB::beginTransaction();

            $settledCount = 0;
            $totalRewards = 0;
            $totalLosses  = 0;
            $usersWin     = ($signal->result === 'win');

            foreach ($signal->participants as $participant) {
                if ($participant->isSettled()) {
                    continue;
                }

                $user      = $participant->user;
                $betAmount = $participant->bet_amount;

                if ($usersWin) {
                    $user->unlockBalance($betAmount);
                    $profit = $betAmount * ($signal->rate_of_return / 100);
                    $user->addTradeBalance($profit);
                    $profitLoss    = $profit;
                    $totalRewards += $profit;
                } else {
                    $user->removeLockedBalance($betAmount);
                    $profitLoss   = -$betAmount;
                    $totalLosses += $betAmount;
                }

                $user->addAchievedVolume($betAmount);

                $participant->update([
                    'profit_loss' => $profitLoss,
                    'fee_amount'  => 0,
                    'status'      => 'settled',
                    'settled_at'  => now(),
                ]);

                $settledCount++;
            }

            $signal->markAsSettled();

            DB::commit();

            if ($usersWin) {
                return redirect()->route('admin.signals.show', $signal->id)
                    ->with('success', "Signal settled! All {$settledCount} participants WON. Total rewards: " . number_format($totalRewards, 2) . " USDT.");
            } else {
                return redirect()->route('admin.signals.show', $signal->id)
                    ->with('success', "Signal settled! All {$settledCount} participants LOST. Total losses: " . number_format($totalLosses, 2) . " USDT.");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== SETTLE SIGNAL FAILED ===', [
                'signal_id'     => $signal->id,
                'error_message' => $e->getMessage(),
            ]);
            return redirect()->route('admin.signals.show', $signal->id)
                ->with('error', 'Failed to settle signal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $signal = TradingSignal::withCount('participants')->findOrFail($id);

        if ($signal->participants_count > 0) {
            return redirect()->route('admin.signals.index')
                ->with('error', 'Cannot delete signal with participants.');
        }

        $signal->delete();

        return redirect()->route('admin.signals.index')
            ->with('success', 'Signal deleted successfully.');
    }
}
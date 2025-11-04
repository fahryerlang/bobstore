<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Display all wallets
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        
        $wallets = Wallet::with('user')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20);
        
        return view('admin.wallets.index', compact('wallets'));
    }

    /**
     * Show topup form
     */
    public function create(Request $request): View
    {
        $search = $request->input('search');
        $selectedUser = null;
        
        if ($request->has('user_id')) {
            $selectedUser = User::with('wallet')->find($request->input('user_id'));
        }
        
        $users = User::where('role', 'pembeli')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(50)
            ->get();
        
        return view('admin.wallets.create', compact('users', 'selectedUser'));
    }

    /**
     * Process topup
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1000|max:100000000',
            'description' => 'nullable|string|max:500',
        ], [
            'amount.min' => 'Jumlah minimal top up adalah Rp 1.000',
            'amount.max' => 'Jumlah maksimal top up adalah Rp 100.000.000',
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $admin = auth()->user();
            
            $transaction = $this->walletService->topup(
                $user,
                $request->amount,
                $admin,
                $request->description,
                ['topup_method' => 'admin_manual']
            );

            return redirect()
                ->route('admin.wallets.show', $user->wallet->id)
                ->with('success', 'Berhasil top up saldo sebesar ' . number_format($request->amount, 0, ',', '.') . ' untuk ' . $user->name);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal melakukan top up: ' . $e->getMessage());
        }
    }

    /**
     * Show wallet detail
     */
    public function show($id): View
    {
        $wallet = Wallet::with(['user', 'transactions.admin'])->findOrFail($id);
        $transactions = $wallet->transactions()->paginate(20);
        
        return view('admin.wallets.show', compact('wallet', 'transactions'));
    }

    /**
     * Toggle wallet status
     */
    public function toggleStatus($id): RedirectResponse
    {
        $wallet = Wallet::findOrFail($id);
        
        $wallet->update([
            'is_active' => !$wallet->is_active
        ]);
        
        $status = $wallet->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()
            ->back()
            ->with('success', "Wallet berhasil {$status}");
    }
}

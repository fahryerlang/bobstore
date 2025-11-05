<?php

namespace App\Http\Controllers;

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
     * Display wallet dashboard
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $wallet = $user->getOrCreateWallet();
        
        // Get filter parameters
        $type = $request->input('type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        
        // Build transaction query
        $transactionsQuery = $wallet->transactions()
            ->with(['admin']);
        
        if ($type) {
            $transactionsQuery->where('type', $type);
        }
        
        if ($dateFrom) {
            $transactionsQuery->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $transactionsQuery->whereDate('created_at', '<=', $dateTo);
        }
        
        $transactions = $transactionsQuery->paginate(15)->appends($request->query());
        
        // Statistics
        $stats = [
            'total_topup' => $wallet->total_topup,
            'total_spent' => $wallet->total_spent,
            'transaction_count' => $wallet->transactions()->count(),
            'last_transaction' => $wallet->transactions()->first(),
        ];
        
        return view('customers.wallet.index', compact('wallet', 'transactions', 'stats'));
    }

    /**
     * Show transaction detail
     */
    public function show($id): View
    {
        $transaction = auth()->user()->walletTransactions()->findOrFail($id);
        
        return view('customers.wallet.show', compact('transaction'));
    }

    /**
     * Show topup request form
     */
    public function createTopupRequest(): View
    {
        $user = auth()->user();
        $wallet = $user->getOrCreateWallet();
        
        // Check if user has pending request
        $pendingRequest = $user->walletTopupRequests()->pending()->first();
        
        return view('customers.wallet.request-topup', compact('wallet', 'pendingRequest'));
    }

    /**
     * Store topup request
     */
    public function storeTopupRequest(Request $request): RedirectResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000|max:100000000',
            'user_notes' => 'nullable|string|max:500',
        ], [
            'amount.required' => 'Jumlah top up harus diisi',
            'amount.numeric' => 'Jumlah top up harus berupa angka',
            'amount.min' => 'Jumlah minimal top up adalah Rp 1.000',
            'amount.max' => 'Jumlah maksimal top up adalah Rp 100.000.000',
        ]);

        try {
            $user = auth()->user();
            
            $topupRequest = $this->walletService->createTopupRequest(
                $user,
                $request->amount,
                $request->user_notes
            );

            return redirect()
                ->route('wallet.topup-requests')
                ->with('success', 'Request top up berhasil dibuat. Silakan tunggu admin memproses request Anda.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show user's topup requests
     */
    public function topupRequests(Request $request): View
    {
        $user = auth()->user();
        $status = $request->input('status');
        
        $topupRequests = $this->walletService->getUserTopupRequests($user, $status);
        
        return view('customers.wallet.topup-requests', compact('topupRequests'));
    }

    /**
     * Show topup request detail
     */
    public function showTopupRequest($id): View
    {
        $topupRequest = auth()->user()->walletTopupRequests()->with(['admin', 'walletTransaction'])->findOrFail($id);
        
        return view('customers.wallet.topup-request-detail', compact('topupRequest'));
    }
}

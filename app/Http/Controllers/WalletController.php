<?php

namespace App\Http\Controllers;

use App\Services\WalletService;
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
}

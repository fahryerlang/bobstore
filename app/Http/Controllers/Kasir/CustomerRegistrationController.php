<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kasir\StoreCustomerRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CustomerRegistrationController extends Controller
{
    /**
     * Display the form for registering a new customer member.
     */
    public function create(): View
    {
        return view('kasir.customers.create');
    }

    /**
     * Store the newly registered customer.
     */
    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $customer = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'pembeli',
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        return redirect()
            ->route('kasir.customers.create')
            ->with('success', sprintf(
                'Member berhasil dibuat. Email: %s',
                $customer->email
            ));
    }
}

<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kasir\StoreCustomerRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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

        $digits = preg_replace('/\D+/', '', $validated['phone']);
        $email = $this->generateUniqueEmail($digits);
        $passwordPlain = $this->generateInitialPassword($digits);

        $customer = User::create([
            'name' => $validated['name'],
            'email' => $email,
            'password' => Hash::make($passwordPlain),
            'role' => 'pembeli',
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        return redirect()
            ->route('kasir.customers.create')
            ->with('success', sprintf(
                'Member berhasil dibuat. Email: %s · Password awal: %s.',
                $customer->email,
                $passwordPlain
            ));
    }

    /**
     * Build a unique email address for the new customer.
     */
    private function generateUniqueEmail(string $digits): string
    {
        $base = $digits !== '' ? $digits : Str::random(6);
    $host = parse_url(config('app.url'), PHP_URL_HOST);
    $domain = $host ?: 'member.local';

        $attempt = 0;
        do {
            $suffix = $attempt === 0 ? '' : '-' . $attempt;
            $email = sprintf('member-%s%s@%s', $base, $suffix, $domain);
            $attempt++;
        } while (User::where('email', $email)->exists());

        return $email;
    }

    /**
     * Determine the initial password to assign.
     */
    private function generateInitialPassword(string $digits): string
    {
        if ($digits !== '') {
            return substr($digits, -8);
        }

        return Str::upper(Str::random(8));
    }
}

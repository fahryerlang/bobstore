@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
            <!-- Page Header -->
            <div class="mb-12">
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-[#F87B1B] via-orange-500 to-amber-600 px-8 py-12 relative overflow-hidden">
                        <!-- Decorative Elements -->
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-xl">
                                    <svg class="w-12 h-12 text-[#F87B1B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h1 class="text-4xl font-bold text-white mb-2">
                                        Profil Saya
                                    </h1>
                                    <p class="text-white/90 text-lg">
                                        Kelola informasi dan keamanan akun Anda
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Sections -->
            <div class="space-y-8">
                <!-- Profile Information Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-8">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-8">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete Account Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-l-4 border-red-500">
                    <div class="p-8">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

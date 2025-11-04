{{-- Modern Center Notification with Loading → Checkmark Animation --}}
<div x-data="notificationHandler()" class="fixed inset-0 flex items-center justify-center z-[999] pointer-events-none">
    <template x-for="notification in notifications" :key="notification.id">
        <div 
            x-show="notification.visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-75"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-75"
            class="absolute pointer-events-auto"
        >
            {{-- Main Card --}}
            <div class="bg-white rounded-2xl shadow-2xl p-8 min-w-[320px] max-w-md">
                <div class="flex flex-col items-center text-center space-y-4">
                    {{-- Animated Icon Container --}}
                    <div class="relative">
                        {{-- Success: Loading → Checkmark --}}
                        <template x-if="notification.type === 'success'">
                            <div class="relative w-20 h-20">
                                {{-- Circle Background --}}
                                <div class="absolute inset-0 rounded-full bg-green-100"></div>
                                
                                {{-- Loading Ring (shows first, then fades out) --}}
                                <svg 
                                    class="absolute inset-0 w-20 h-20 transform -rotate-90"
                                    :class="notification.animationPhase === 'loading' ? 'opacity-100' : 'opacity-0'"
                                    style="transition: opacity 0.3s;"
                                >
                                    <circle 
                                        cx="40" 
                                        cy="40" 
                                        r="36" 
                                        stroke="#10b981" 
                                        stroke-width="4" 
                                        fill="none"
                                        stroke-dasharray="226"
                                        stroke-dashoffset="0"
                                        class="animate-spin-loading"
                                    />
                                </svg>
                                
                                {{-- Checkmark (appears after loading) --}}
                                <svg 
                                    class="absolute inset-0 w-20 h-20 text-green-500"
                                    :class="notification.animationPhase === 'complete' ? 'opacity-100' : 'opacity-0'"
                                    style="transition: opacity 0.3s;"
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path 
                                        class="animate-checkmark"
                                        stroke-linecap="round" 
                                        stroke-linejoin="round" 
                                        stroke-width="2" 
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </div>
                        </template>

                        {{-- Error: Loading → X Mark --}}
                        <template x-if="notification.type === 'error'">
                            <div class="relative w-20 h-20">
                                <div class="absolute inset-0 rounded-full bg-red-100"></div>
                                
                                <svg 
                                    class="absolute inset-0 w-20 h-20 transform -rotate-90"
                                    :class="notification.animationPhase === 'loading' ? 'opacity-100' : 'opacity-0'"
                                    style="transition: opacity 0.3s;"
                                >
                                    <circle 
                                        cx="40" 
                                        cy="40" 
                                        r="36" 
                                        stroke="#ef4444" 
                                        stroke-width="4" 
                                        fill="none"
                                        stroke-dasharray="226"
                                        stroke-dashoffset="0"
                                        class="animate-spin-loading"
                                    />
                                </svg>
                                
                                <svg 
                                    class="absolute inset-0 w-20 h-20 text-red-500"
                                    :class="notification.animationPhase === 'complete' ? 'opacity-100 animate-shake' : 'opacity-0'"
                                    style="transition: opacity 0.3s;"
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path 
                                        stroke-linecap="round" 
                                        stroke-linejoin="round" 
                                        stroke-width="2" 
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </div>
                        </template>

                        {{-- Warning: Loading → Exclamation --}}
                        <template x-if="notification.type === 'warning'">
                            <div class="relative w-20 h-20">
                                <div class="absolute inset-0 rounded-full bg-yellow-100"></div>
                                
                                <svg 
                                    class="absolute inset-0 w-20 h-20 transform -rotate-90"
                                    :class="notification.animationPhase === 'loading' ? 'opacity-100' : 'opacity-0'"
                                    style="transition: opacity 0.3s;"
                                >
                                    <circle 
                                        cx="40" 
                                        cy="40" 
                                        r="36" 
                                        stroke="#f59e0b" 
                                        stroke-width="4" 
                                        fill="none"
                                        stroke-dasharray="226"
                                        stroke-dashoffset="0"
                                        class="animate-spin-loading"
                                    />
                                </svg>
                                
                                <svg 
                                    class="absolute inset-0 w-20 h-20 text-yellow-500"
                                    :class="notification.animationPhase === 'complete' ? 'opacity-100 animate-bounce' : 'opacity-0'"
                                    style="transition: opacity 0.3s;"
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path 
                                        stroke-linecap="round" 
                                        stroke-linejoin="round" 
                                        stroke-width="2" 
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                    />
                                </svg>
                            </div>
                        </template>

                        {{-- Info: Loading → Info Icon --}}
                        <template x-if="notification.type === 'info'">
                            <div class="relative w-20 h-20">
                                <div class="absolute inset-0 rounded-full bg-blue-100"></div>
                                
                                <svg 
                                    class="absolute inset-0 w-20 h-20 transform -rotate-90"
                                    :class="notification.animationPhase === 'loading' ? 'opacity-100' : 'opacity-0'"
                                    style="transition: opacity 0.3s;"
                                >
                                    <circle 
                                        cx="40" 
                                        cy="40" 
                                        r="36" 
                                        stroke="#3b82f6" 
                                        stroke-width="4" 
                                        fill="none"
                                        stroke-dasharray="226"
                                        stroke-dashoffset="0"
                                        class="animate-spin-loading"
                                    />
                                </svg>
                                
                                <svg 
                                    class="absolute inset-0 w-20 h-20 text-blue-500"
                                    :class="notification.animationPhase === 'complete' ? 'opacity-100 animate-pulse' : 'opacity-0'"
                                    style="transition: opacity 0.3s;"
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path 
                                        stroke-linecap="round" 
                                        stroke-linejoin="round" 
                                        stroke-width="2" 
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                        </template>
                    </div>

                    {{-- Title & Message --}}
                    <div class="space-y-2">
                        <h3 
                            class="text-xl font-bold"
                            :class="{
                                'text-green-600': notification.type === 'success',
                                'text-red-600': notification.type === 'error',
                                'text-yellow-600': notification.type === 'warning',
                                'text-blue-600': notification.type === 'info'
                            }"
                            x-text="notification.title"
                        ></h3>
                        <p 
                            class="text-gray-600 text-sm"
                            x-text="notification.message"
                        ></p>
                    </div>

                    {{-- Close Button (appears after animation) --}}
                    <button 
                        x-show="notification.animationPhase === 'complete'"
                        x-transition:enter="transition ease-out duration-300 delay-300"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        @click="removeNotification(notification.id)"
                        class="px-6 py-2 rounded-lg font-medium transition-colors"
                        :class="{
                            'bg-green-500 hover:bg-green-600 text-white': notification.type === 'success',
                            'bg-red-500 hover:bg-red-600 text-white': notification.type === 'error',
                            'bg-yellow-500 hover:bg-yellow-600 text-white': notification.type === 'warning',
                            'bg-blue-500 hover:bg-blue-600 text-white': notification.type === 'info'
                        }"
                    >
                        OK
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

<style>
    @keyframes spin-loading {
        0% {
            stroke-dashoffset: 226;
            transform: rotate(0deg);
        }
        50% {
            stroke-dashoffset: 50;
        }
        100% {
            stroke-dashoffset: 226;
            transform: rotate(360deg);
        }
    }

    @keyframes checkmark {
        0% {
            stroke-dasharray: 50;
            stroke-dashoffset: 50;
        }
        100% {
            stroke-dasharray: 50;
            stroke-dashoffset: 0;
        }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-8px); }
        75% { transform: translateX(8px); }
    }

    .animate-spin-loading circle {
        animation: spin-loading 1.5s ease-in-out infinite;
    }

    .animate-checkmark {
        animation: checkmark 0.5s ease-out forwards;
    }

    .animate-shake {
        animation: shake 0.4s ease-in-out;
    }
</style>

<script>
    function notificationHandler() {
        return {
            notifications: [],
            nextId: 1,

            init() {
                // Check for session flash messages on load
                @if(session('success'))
                    this.show('success', 'Berhasil!', '{{ session('success') }}');
                @endif

                @if(session('error'))
                    this.show('error', 'Error!', '{{ session('error') }}');
                @endif

                @if(session('warning'))
                    this.show('warning', 'Peringatan!', '{{ session('warning') }}');
                @endif

                @if(session('info'))
                    this.show('info', 'Informasi', '{{ session('info') }}');
                @endif

                // Listen for custom notification events
                window.addEventListener('show-notification', (event) => {
                    const { type, title, message, duration } = event.detail;
                    this.show(type, title, message, duration);
                });
            },

            show(type, title, message, duration = 3000) {
                const id = this.nextId++;
                const notification = {
                    id,
                    type,
                    title,
                    message,
                    duration,
                    visible: true,
                    animationPhase: 'loading' // loading → complete
                };

                this.notifications.push(notification);

                // Loading → Complete animation transition
                setTimeout(() => {
                    const notif = this.notifications.find(n => n.id === id);
                    if (notif) {
                        notif.animationPhase = 'complete';
                    }
                }, 800); // Loading duration

                // Auto remove after duration
                setTimeout(() => {
                    this.removeNotification(id);
                }, duration + 1000); // Extra time for user to see the result
            },

            removeNotification(id) {
                const index = this.notifications.findIndex(n => n.id === id);
                if (index !== -1) {
                    this.notifications[index].visible = false;
                    setTimeout(() => {
                        this.notifications.splice(index, 1);
                    }, 300);
                }
            }
        };
    }

    // Global helper function
    window.showNotification = function(type, title, message, duration = 3000) {
        window.dispatchEvent(new CustomEvent('show-notification', {
            detail: { type, title, message, duration }
        }));
    };
</script>

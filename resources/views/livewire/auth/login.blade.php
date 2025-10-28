<x-layouts.auth>
    <div class="min-h-screen flex items-center justify-center p-6 bg-white">
        <div class="w-full max-w-md">
            <div class="relative">
                <!-- Soft decorative waves using pseudo elements / background shapes -->
                <div aria-hidden class="absolute -inset-6 rounded-3xl bg-white shadow-2xl pointer-events-none" style="filter: blur(18px); opacity: .45;"></div>

                <div class="relative p-1 rounded-2xl bg-gradient-to-r from-indigo-50 to-white">
                    <!-- stronger soft shadow behind the card to separate from background -->
                    <div aria-hidden class="absolute -inset-6 rounded-2xl bg-indigo-100 opacity-24 blur-[44px] pointer-events-none z-0"></div>
                        <div aria-hidden class="absolute -inset-2 rounded-2xl bg-indigo-900 opacity-6 blur-[28px] pointer-events-none z-0"></div>
                        <div class="relative bg-white rounded-2xl z-10 shadow-[0_40px_100px_rgba(2,6,23,0.14)] border-2 border-indigo-100 ring-2 ring-indigo-50 overflow-hidden transform transition-all hover:-translate-y-1 hover:shadow-[0_80px_160px_rgba(2,6,23,0.18)]">
                        <div class="p-8 relative z-20">
                        <div class="flex flex-col items-center mb-6">
                            <div class="flex items-center justify-center h-20 w-20 rounded-full bg-white shadow-lg">
                                <svg class="h-10 w-10 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M12 2L2 7l10 5 10-5-10-5z" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M2 17l10 5 10-5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h2 class="mt-4 text-2xl font-semibold text-indigo-700">Bienvenido</h2>
                            <p class="text-sm text-indigo-500 mt-1">Inicia sesión para continuar</p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4 text-center text-sm text-indigo-600" :status="session('status')" />

                        <form method="POST" action="{{ route('login.store') }}" id="loginForm" class="space-y-4">
                            @csrf

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <div class="mt-1 relative rounded-lg shadow-md">
                                    <input id="email" name="email" type="email" required autofocus autocomplete="email" placeholder="email@example.com"
                                        class="block w-full px-4 py-3 bg-white border-2 border-indigo-100 rounded-lg text-indigo-800 placeholder-indigo-300 shadow focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300" />
                                </div>
                                @error('email') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                                <div class="mt-1 relative rounded-lg shadow-md">
                                    <input id="password" name="password" type="password" required autocomplete="current-password" placeholder=""
                                        class="block w-full px-4 py-3 bg-white border-2 border-indigo-100 rounded-lg text-indigo-800 placeholder-indigo-300 shadow focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300" />
                                    <button type="button" id="togglePassword" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none" aria-label="Mostrar contraseña">
                                        <svg id="eyeOpen" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <svg id="eyeClosed" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8"/></svg>
                                    </button>
                                </div>
                                @error('password') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                                    <input type="checkbox" name="remember" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    Recuerdame
                                </label>

                                @if (Route::has('password.request'))
                                    <a class="text-sm text-indigo-600 hover:underline" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                                @endif
                            </div>

                            <div>
                                <button type="submit" class="w-full inline-flex items-center justify-center rounded-lg bg-indigo-600 text-white border border-indigo-700 shadow-lg hover:shadow-2xl px-4 py-3 font-semibold transition-transform duration-150 hover:bg-indigo-700">
                                    Iniciar sesión
                                </button>
                            </div>
                        </form>

                        <!-- Social login removed per design preference -->
                    </div>
                    <div class="p-4 bg-gray-50 text-center">
                        @if (Route::has('register'))
                            <p class="text-sm text-gray-600">¿Nuevo en la plataforma? <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:underline">Crear cuenta</a></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple password toggle
        document.addEventListener('DOMContentLoaded', function () {
            const pwd = document.getElementById('password');
            const toggle = document.getElementById('togglePassword');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            if (toggle && pwd) {
                toggle.addEventListener('click', function (e) {
                    const type = pwd.type === 'password' ? 'text' : 'password';
                    pwd.type = type;
                    eyeOpen.classList.toggle('hidden');
                    eyeClosed.classList.toggle('hidden');
                });
            }
        });
    </script>
</x-layouts.auth>

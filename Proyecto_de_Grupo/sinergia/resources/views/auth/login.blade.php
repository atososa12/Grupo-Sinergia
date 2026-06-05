<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center px-6"
         style="background: linear-gradient(135deg, #ef4444, #3b82f6, #22c55e, #8b5cf6);">

        <div class="w-full max-w-md">

            <div class="backdrop-blur-xl bg-black/50 border border-white/10 shadow-2xl rounded-2xl p-8">

                <h2 class="text-2xl font-semibold text-center text-white mb-6">
                    Iniciar sesión
                </h2>

                <x-auth-session-status class="mb-4 text-white" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-200" />

                        <x-text-input
                            id="email"
                            class="block mt-1 w-full bg-white/10 border-white/20 text-white
                                   focus:border-blue-400 focus:ring-blue-400"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                        />

                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-gray-200" />

                        <x-text-input
                            id="password"
                            class="block mt-1 w-full bg-white/10 border-white/20 text-white
                                   focus:border-violet-400 focus:ring-violet-400"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                        />

                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center">
                        <input id="remember_me"
                               type="checkbox"
                               class="rounded border-gray-400 text-green-500 focus:ring-green-500"
                               name="remember">

                        <label for="remember_me" class="ms-2 text-sm text-gray-300">
                            {{ __('Remember me') }}
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between mt-6">

                        @if (Route::has('password.request'))
                            <a class="text-sm text-gray-300 hover:text-white underline"
                               href="{{ route('password.request') }}">
                                Forgot password
                            </a>
                        @endif

                        <x-primary-button
                            class="bg-gradient-to-r from-red-500 via-blue-500 via-green-500 to-violet-600
                                   hover:opacity-90 transition px-6 py-2 rounded-lg">
                            Log in
                        </x-primary-button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-guest-layout>
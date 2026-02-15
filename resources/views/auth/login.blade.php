<x-guest-layout>
    <div class="tw-flex tw-flex-col tw-items-center tw-w-full">
        <!-- Logo & Title Section -->
        <div class="tw-text-center tw-mb-8">
            <div class="tw-mb-3">
                <div class="tw-w-20 tw-h-20 tw-bg-blue-900 tw-rounded-2xl tw-flex tw-items-center tw-justify-center tw-mx-auto tw-shadow-lg">
                    <svg class="tw-w-10 tw-h-10 tw-text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900 tw-mb-1">Psychorobotic</h1>
            <p class="tw-text-sm tw-text-gray-500">Sistem Manajemen Organisasi</p>
        </div>

        <!-- Welcome Text -->
        <div class="tw-text-center tw-mb-8 tw-w-full">
            <h2 class="tw-text-3xl tw-font-bold tw-text-gray-900 tw-mb-2">Selamat Datang</h2>
            <p class="tw-text-gray-600 tw-text-sm">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="tw-mb-4 tw-w-full" :status="session('status')" />

        <!-- Login Form -->
        <form method="POST" action="{{ route("login") }}" class="tw-w-full">
            @csrf

            <!-- Email Address -->
            <div class="tw-mb-4">
                <label for="email" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old("email") }}" placeholder="emailanda@stu.pnj.ac.id" required autofocus autocomplete="username" class="tw-w-full tw-px-4 tw-py-3 tw-rounded-lg tw-border tw-border-gray-300 focus:tw-border-blue-500 focus:tw-ring-2 focus:tw-ring-blue-200 tw-outline-none tw-transition tw-text-sm" />
                <x-input-error :messages="$errors->get('email')" class="tw-mt-2" />
            </div>

            <!-- Password -->
            <div class="tw-mb-4">
                <div class="tw-flex tw-justify-between tw-items-center tw-mb-2">
                    <label for="password" class="tw-text-sm tw-font-medium tw-text-gray-700">Password</label>
                    @if (Route::has("password.request"))
                        <a href="{{ route("password.request") }}" class="tw-text-sm tw-text-blue-600 hover:tw-text-blue-700 tw-font-medium">Lupa Password?</a>
                    @endif
                </div>
                <div class="tw-relative">
                    <input id="password" type="password" name="password" required autocomplete="current-password" class="tw-w-full tw-px-4 tw-py-3 tw-rounded-lg tw-border tw-border-gray-300 focus:tw-border-blue-500 focus:tw-ring-2 focus:tw-ring-blue-200 tw-outline-none tw-transition tw-text-sm tw-pr-12" />
                    <button type="button" onclick="togglePassword()" class="tw-absolute tw-right-3 tw-top-1/2 tw-transform -tw-translate-y-1/2 tw-text-gray-500 hover:tw-text-gray-700">
                        <svg id="eye-icon" class="tw-w-5 tw-h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="tw-mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="tw-mb-6">
                <label for="remember_me" class="tw-flex tw-items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="tw-w-4 tw-h-4 tw-text-blue-900 tw-border-gray-300 tw-rounded focus:tw-ring-blue-500 focus:tw-ring-2" />
                    <span class="tw-ml-2 tw-text-sm tw-text-gray-600">Ingat saya di perangkat ini</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="tw-w-full tw-bg-blue-900 tw-text-white tw-py-3 tw-px-4 tw-rounded-lg tw-font-semibold tw-text-base hover:tw-bg-blue-800 focus:tw-outline-none focus:tw-ring-4 focus:tw-ring-blue-300 tw-transition tw-duration-200">Masuk</button>

            <!-- Register Link -->
            <div class="tw-text-center tw-mt-6">
                <p class="tw-text-sm tw-text-gray-600">
                    Belum punya akun?
                    <a href="#" onclick="alert('Silakan hubungi administrator untuk membuat akun baru'); return false;" class="tw-text-blue-600 hover:tw-text-blue-700 tw-font-medium">Hubungi Administrator</a>
                </p>
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
    </script>
</x-guest-layout>

<x-guest-layout>
    <form method="POST" action="{{ route("register") }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="tw-block tw-mt-1 tw-w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="tw-mt-2" />
        </div>

        <!-- Email Address -->
        <div class="tw-mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="tw-block tw-mt-1 tw-w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="tw-mt-2" />
        </div>

        <!-- Password -->
        <div class="tw-mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="tw-block tw-mt-1 tw-w-full" type="password" name="password" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="tw-mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="tw-mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="tw-block tw-mt-1 tw-w-full" type="password" name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="tw-mt-2" />
        </div>

        <!-- Role -->
        <div class="tw-mt-4">
            <x-input-label for="role" :value="__('Role')" />
            <select id="role" name="role" required class="tw-block tw-w-full tw-mt-1 tw-px-3 tw-py-2 tw-bg-white tw-border tw-border-gray-300 tw-rounded-md focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-border-indigo-500 dark:tw-bg-gray-700 dark:tw-text-gray-300 dark:tw-border-gray-600 dark:focus:tw-ring-indigo-500">
                <option value="" disabled selected>{{ __("Select a role") }}</option>
                <option value="pengurus">{{ __("Pengurus") }}</option>
                <option value="anggota">{{ __("Anggota") }}</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="tw-mt-2" />
        </div>

        <div class="tw-flex tw-items-center tw-justify-end tw-mt-4">
            <a class="tw-underline tw-text-sm tw-text-gray-600 dark:tw-text-gray-400 hover:tw-text-gray-900 dark:hover:tw-text-gray-100 tw-rounded-md focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500 dark:focus:tw-ring-offset-gray-800" href="{{ route("login") }}">
                {{ __("Already registered?") }}
            </a>

            <x-primary-button class="tw-ms-4">
                {{ __("Register") }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

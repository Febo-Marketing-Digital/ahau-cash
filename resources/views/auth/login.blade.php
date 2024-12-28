<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <!-- <x-input-label for="email" :value="__('Email')" />

                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />

                <x-input-error :messages="$errors->get('email')" class="mt-2" /> -->
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">{{ __('Email') }}</span>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Ingrese su e-mail" class="input input-bordered w-full" />
                    <div class="label">
                        <span class="label-text-alt">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </span>
                    </div>
                </label>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <!-- <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" /> -->

                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">{{ __('Password') }}</span>
                        <!-- <span class="label-text-alt">Top Right label</span> -->
                    </div>
                    <input id="password" type="password" name="password" placeholder="Ingrese su contraseña" class="input input-bordered w-full" />
                    <div class="label">
                        <span class="label-text-alt">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </span>
                        <!-- <span class="label-text-alt">Bottom Right label</span> -->
                    </div>
                </label>
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <!-- <label for="remember_me" class="inline-flex items-center"> 
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label> -->
                <div class="form-control">
                    <label class="cursor-pointer">
                        <input id="remember_me" type="checkbox" name="remember" checked="checked" class="checkbox checkbox-primary" />
                        <span class="label-text ml-2">{{ __('Remember me') }}</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                <!-- <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a> -->
                <a href="{{ route('password.request') }}" class="btn-link hover:btn-active">{{ __('Forgot your password?') }}</a>
                @endif

                <!-- <x-primary-button class="ml-3">
                    {{ __('Log in') }}
                </x-primary-button> -->
                <button type="submit" class="btn btn-primary ml-3">{{ __('Log in') }}</button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
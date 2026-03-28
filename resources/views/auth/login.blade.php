<x-guest-layout>
    <h2>🔐 Login</h2>
    
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <span style="color: #e74c3c; font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
            @error('password')
                <span style="color: #e74c3c; font-size: 0.875rem; display: block; margin-top: 0.25rem;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me -->
        {{-- <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="remember_me" style="display: inline-flex; align-items: center; font-weight: normal; cursor: pointer;">
                <input id="remember_me" type="checkbox" name="remember" style="width: auto; margin-right: 0.5rem;">
                <span>Remember me</span>
            </label>
        </div> --}}

        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Log in
            </button>
            
            {{-- @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="text-align: center; color: #667eea; text-decoration: none; font-size: 0.875rem;">
                    Forgot your password?
                </a>
            @endif --}}
        </div>
    </form>
</x-guest-layout>

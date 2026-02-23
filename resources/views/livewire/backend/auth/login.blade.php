<div class="p-2 mt-5">
    <!-- Updated to use the 'authenticate' or 'login' method based on your component -->
    <form wire:submit.prevent="authenticate">

        <div class="mb-3">
            <!-- Updated label to show it accepts both -->
            <label for="login_identifier" class="form-label">Email or Phone Number</label>
            <div class="input-group">
                <span class="input-group-text"><i class="ri-user-3-line"></i></span>
                <!-- wire:model matches the property in our PHP class -->
                <input type="text"
                    wire:model="login_identifier"
                    class="form-control @error('login_identifier') is-invalid @enderror"
                    id="login_identifier"
                    placeholder="Enter email or phone"
                    autofocus>
            </div>
            @error('login_identifier') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <div class="float-end">
                <!-- Using dynamic route for forgot password -->
                <a href="#" class="text-muted">Forgot password?</a>
            </div>
            <label class="form-label" for="password-input">Password</label>

            <div class="position-relative">
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-lock-2-line"></i></span>
                    <input type="password"
                        wire:model="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Enter password"
                        id="password-input">
                </div>
            </div>

            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-check">
            <input class="form-check-input" wire:model="remember" type="checkbox" id="auth-remember-check">
            <label class="form-check-label" for="auth-remember-check">Remember me</label>
        </div>

        <div class="mt-4">
            <!-- Added a loading spinner for better UX -->
            <button class="btn btn-primary w-100" type="submit" wire:loading.attr="disabled">
                <span wire:loading wire:target="authenticate" class="spinner-border spinner-border-sm me-1"></span>
                Sign In
            </button>
        </div>

    </form>
</div>
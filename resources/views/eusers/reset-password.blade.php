
<div class="container">
    <h2>Reset Password</h2>
    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    <form method="POST" action="{{ route('user.password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" value="{{ request()->email }}" class="form-control" readonly required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="password" class="form-control" required>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>

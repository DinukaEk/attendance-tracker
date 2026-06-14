@extends('layouts.custom')

@section('title', 'Profile')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-stone-800">Profile Settings</h1>
    <p class="mt-1 text-sm text-stone-500">Manage your account information and password.</p>
</div>

<div class="max-w-2xl space-y-6">

    {{-- Update Profile Info --}}
    <div class="p-6 bg-white border shadow-sm border-stone-200 rounded-xl">
        <h2 class="mb-1 text-sm font-semibold text-stone-700">Account Information</h2>
        <p class="mb-5 text-xs text-stone-400">Update your name and email address.</p>

        @if (session('status') === 'profile-updated')
            <div class="px-4 py-3 mb-4 text-sm border rounded-lg bg-emerald-50 border-emerald-200 text-emerald-700">
                Profile updated successfully.
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('patch')

            <div>
                <label for="name" class="text-xs font-medium text-stone-500 uppercase tracking-wide mb-1.5 block">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus
                       class="w-full bg-stone-50 border {{ $errors->userForm->has('name') ? 'border-red-300' : 'border-stone-200' }} text-stone-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @if ($errors->userForm->has('name'))
                    <p class="mt-1.5 text-xs text-red-500">{{ $errors->userForm->first('name') }}</p>
                @endif
            </div>

            <div>
                <label for="email" class="text-xs font-medium text-stone-500 uppercase tracking-wide mb-1.5 block">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full bg-stone-50 border {{ $errors->userForm->has('email') ? 'border-red-300' : 'border-stone-200' }} text-stone-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @if ($errors->userForm->has('email'))
                    <p class="mt-1.5 text-xs text-red-500">{{ $errors->userForm->first('email') }}</p>
                @endif
            </div>

            <div class="pt-1">
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm font-medium transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="p-6 bg-white border shadow-sm border-stone-200 rounded-xl">
        <h2 class="mb-1 text-sm font-semibold text-stone-700">Change Password</h2>
        <p class="mb-5 text-xs text-stone-400">Use a strong password to keep your account secure.</p>

        @if (session('status') === 'password-updated')
            <div class="px-4 py-3 mb-4 text-sm border rounded-lg bg-emerald-50 border-emerald-200 text-emerald-700">
                Password updated successfully.
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('put')

            <div>
                <label for="current_password" class="text-xs font-medium text-stone-500 uppercase tracking-wide mb-1.5 block">Current Password</label>
                <input id="current_password" type="password" name="current_password" autocomplete="current-password"
                       class="w-full bg-stone-50 border {{ $errors->updatePassword->has('current_password') ? 'border-red-300' : 'border-stone-200' }} text-stone-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @if ($errors->updatePassword->has('current_password'))
                    <p class="mt-1.5 text-xs text-red-500">{{ $errors->updatePassword->first('current_password') }}</p>
                @endif
            </div>

            <div>
                <label for="new_password" class="text-xs font-medium text-stone-500 uppercase tracking-wide mb-1.5 block">New Password</label>
                <input id="new_password" type="password" name="password" autocomplete="new-password"
                       class="w-full bg-stone-50 border {{ $errors->updatePassword->has('password') ? 'border-red-300' : 'border-stone-200' }} text-stone-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @if ($errors->updatePassword->has('password'))
                    <p class="mt-1.5 text-xs text-red-500">{{ $errors->updatePassword->first('password') }}</p>
                @endif
            </div>

            <div>
                <label for="password_confirmation" class="text-xs font-medium text-stone-500 uppercase tracking-wide mb-1.5 block">Confirm New Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
                       class="w-full bg-stone-50 border {{ $errors->updatePassword->has('password_confirmation') ? 'border-red-300' : 'border-stone-200' }} text-stone-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @if ($errors->updatePassword->has('password_confirmation'))
                    <p class="mt-1.5 text-xs text-red-500">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                @endif
            </div>

            <div class="pt-1">
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm font-medium transition-colors">
                    Update Password
                </button>
            </div>
        </form>
    </div>

    {{-- Delete Account --}}
    <div class="p-6 bg-white border border-red-100 shadow-sm rounded-xl">
        <h2 class="mb-1 text-sm font-semibold text-red-600">Delete Account</h2>
        <p class="mb-5 text-xs text-stone-400">Permanently delete your account and all associated data. This cannot be undone.</p>

        <form method="POST" action="{{ route('profile.destroy') }}"
              onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
            @csrf
            @method('delete')

            <div class="mb-4">
                <label for="delete_password" class="text-xs font-medium text-stone-500 uppercase tracking-wide mb-1.5 block">
                    Confirm your password to delete
                </label>
                <input id="delete_password" type="password" name="password"
                       placeholder="Enter your password"
                       class="w-full bg-stone-50 border {{ $errors->userDeletion->has('password') ? 'border-red-300' : 'border-stone-200' }} text-stone-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 placeholder-stone-400">
                @if ($errors->userDeletion->has('password'))
                    <p class="mt-1.5 text-xs text-red-500">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            <button type="submit"
                    class="px-5 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-lg text-sm font-medium transition-colors">
                Delete Account
            </button>
        </form>
    </div>

</div>

@endsection
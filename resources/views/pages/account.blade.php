
@extends('layouts.store')

@section('title', 'My Account | AK2')
@section('description', 'Manage your AK2 customer account.')

@section('content')

@php
    $user = auth()->user();
@endphp

<section class="account-section">
    <div class="container">

        {{-- Success Message --}}
        @if (session('success'))
            <div class="account-success-message">
                {{ session('success') }}
            </div>
        @endif

        {{-- Account Heading --}}
        <div class="account-heading">
            <span>AK2 CUSTOMER AREA</span>
            <h1>MY <strong>ACCOUNT</strong></h1>
            <p>Manage your personal information and orders.</p>
        </div>

        {{-- Profile Card --}}
        <div class="account-card">

            <div class="account-profile">

                {{-- Avatar --}}
                <div class="account-avatar">
                    @if ($user->avatar_path)
                        <img
                            src="{{ asset('storage/' . $user->avatar_path) }}"
                            alt="{{ $user->name }}"
                        >
                    @else
                        <span>
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    @endif
                </div>

                {{-- Avatar Actions --}}
                <div class="avatar-actions">

                    <form
                        method="POST"
                        action="{{ route('account.avatar.update') }}"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        <label for="avatar" class="avatar-upload-label">
                            Choose Photo
                        </label>

                        <input
                            type="file"
                            name="avatar"
                            id="avatar"
                            accept=".jpg,.jpeg,.png,.webp"
                            required
                        >

                        @error('avatar')
                            <p class="form-error">{{ $message }}</p>
                        @enderror

                        <button type="submit" class="avatar-save-button">
                            Upload Photo
                        </button>
                    </form>

                    @if ($user->avatar_path)
                        <form
                            method="POST"
                            action="{{ route('account.avatar.delete') }}"
                            onsubmit="return confirm('Are you sure you want to delete your profile photo?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="avatar-delete-button">
                                Remove Photo
                            </button>
                        </form>
                    @endif

                </div>
            </div>

            {{-- Account Details --}}
            <div class="account-details">

                <span class="account-welcome">WELCOME BACK</span>

                <h2>{{ $user->name }}</h2>

                <p>{{ $user->email }}</p>

                @if ($user->hasVerifiedEmail())
                    <span class="account-status verified">
                        <span class="status-dot"></span>
                        Email Verified
                    </span>
                @else
                    <span class="account-status unverified">
                        <span class="status-dot"></span>
                        Email Not Verified
                    </span>
                @endif

            </div>

        </div>

        {{-- Account Navigation --}}
        <nav class="account-links" aria-label="Account sections">

            <a href="#my-orders" class="account-menu-item">
                <span class="menu-number">01</span>

                <div class="menu-content">
                    <h3>My Orders</h3>
                    <p>View your order history and status.</p>
                </div>

                <span class="account-arrow">→</span>
            </a>

            <a href="#personal-information" class="account-menu-item">
                <span class="menu-number">02</span>

                <div class="menu-content">
                    <h3>Personal Information</h3>
                    <p>Manage your account details.</p>
                </div>

                <span class="account-arrow">→</span>
            </a>

            <a href="#saved-addresses" class="account-menu-item">
                <span class="menu-number">03</span>

                <div class="menu-content">
                    <h3>Saved Addresses</h3>
                    <p>Manage your delivery addresses.</p>
                </div>

                <span class="account-arrow">→</span>
            </a>

        </nav>

        {{-- Personal Information --}}
        <section
            class="personal-information"
            id="personal-information"
        >

            <div class="personal-information-heading">
                <span>ACCOUNT SETTINGS</span>
                <h2>PERSONAL <strong>INFORMATION</strong></h2>
                <p>Update your name and email address.</p>
            </div>

            <form
                method="POST"
                action="{{ route('account.profile.update') }}"
                class="personal-information-form"
            >
                @csrf
                @method('PATCH')

                {{-- Full Name --}}
                <div class="form-group">
                    <label for="name">Full Name</label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        maxlength="255"
                        autocomplete="name"
                    >

                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label for="email">Email Address</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        maxlength="255"
                        autocomplete="email"
                    >

                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror

                    <small>
                        Changing your email address will require verification.
                    </small>
                </div>

                <button type="submit" class="profile-save-button">
                    Save Changes
                </button>

            </form>

        </section>

        {{-- Orders Placeholder --}}
        <section
            id="my-orders"
            class="account-future-section"
        >
            <span>01</span>

            <div>
                <h3>My Orders</h3>
                <p>Your order history will appear here.</p>
            </div>
        </section>

        {{-- Addresses Placeholder --}}
        <section
            id="saved-addresses"
            class="account-future-section"
        >
            <span>03</span>

            <div>
                <h3>Saved Addresses</h3>
                <p>Your delivery addresses will appear here.</p>
            </div>
        </section>

    </div>
</section>

@endsection

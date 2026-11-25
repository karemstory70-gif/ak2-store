@props([
    'sidebar' => false,
])

@php($brandName = 'AK2 Technologies')

@if($sidebar)
    <flux:sidebar.brand :name="$brandName" {{ $attributes }}>
        <x-slot name="logo" class="flex h-10 items-center justify-center">
            <x-app-logo-icon class="h-8 w-auto" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="$brandName" {{ $attributes }}>
        <x-slot name="logo" class="flex h-10 items-center justify-center">
            <x-app-logo-icon class="h-8 w-auto" />
        </x-slot>
    </flux:brand>
@endif

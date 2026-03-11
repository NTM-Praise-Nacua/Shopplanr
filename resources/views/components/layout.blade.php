@props(['class' => ''])

<x-authwrapper>
    <x-sidebar />
    <x-mainwrapper>
        <x-responsive-header />
        <div class="inner-content bg-surface {{ $class }}">{{ $slot }}</div>
    </x-mainwrapper>
</x-authwrapper>
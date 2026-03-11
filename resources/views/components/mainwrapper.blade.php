@props(['backdrop' => false])

@if ($backdrop)
    <div class="absolute top-0 left-0 z-10 bg-black/50 w-full h-full"></div>
@endif
<div class="main-content-wrapper">
    {{ $slot }}
</div>
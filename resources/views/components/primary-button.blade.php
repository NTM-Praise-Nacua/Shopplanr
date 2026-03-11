@props(['type' => 'button', 'class' => '', 'disabled' => false])

<button type="{{ $type }}" class="bg-accent primary-button {{ $class }}" {{ $disabled ? 'disabled' : '' }}>
    {{ $slot }}
</button>
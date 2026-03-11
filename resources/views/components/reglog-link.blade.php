@props(['type' => 'register'])
<div class="text-center main-text text-sm">
    @if ($type == "register")
        Already have an account? <a href="/" class="text-link text-accent">Login</a>
    @else
        Don't have an account? <a href="/register" class="text-link text-accent">Register</a>
    @endif
</div>
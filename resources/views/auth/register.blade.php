@extends('layouts.app')
@section('content')
    <div class="flex justify-center items-center h-full w-full">
        <form method="POST" class="logreg-form">
            @csrf
            <a href="/" class="absolute text-accent top-7 left-7 text-lg font-extrabold">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <input type="text" name="first_name" class="inputfield placeholder:text-gray-500 main-text mt-7" placeholder="First Name" required>
            <input type="text" name="last_name" class="inputfield placeholder:text-gray-500 main-text" placeholder="Last Name" required>
            <input type="email" name="email" class="inputfield placeholder:text-gray-500 main-text" placeholder="Email" required>
            <input type="password" name="password" class="inputfield placeholder:text-gray-500 main-text" placeholder="Password" required>
            <x-primary-button type="submit" class="mt-6">Register</x-primary-button>
            <x-reglog-link type="register" />
        </form>
    </div>
@endsection
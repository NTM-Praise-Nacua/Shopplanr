@extends('layouts.app')
@section('content')
    <div class="flex justify-center items-center h-full w-full">
        <form method="POST" class="logreg-form">
            @csrf
            <img src="{{ asset('images/ShopPlanr - nobg.png') }}" alt="ShopPlanr Logo" class="w-80 mx-auto mb-5">
            <input type="email" name="email" placeholder="Email" class="inputfield placeholder:text-gray-500 main-text" required />
            <input type="password" name="password" placeholder="Password" class="inputfield placeholder:text-gray-500 main-text" required />
            <x-primary-button type="button" class="mt-6">Login</x-primary-button>
            <x-reglog-link type="login" />
        </form>
    </div>
@endsection
@extends('layouts.app')
@section('content')
    <x-layout>
        <div class="relative h-full flex md:block flex-col gap-5">
            <div class="content-header p-3 md:p-6 2xl:px-72 absolute top-3 left-0 right-0">
                <a href="/plan-create" class="hidden md:block">
                    <button type="button" class="bg-accent primary-action-btn w-40">
                        Add Plan
                    </button>
                </a>
                <div class="title-wrapper md:hidden">
                    <p class="mb-3">Plans</p>
                    <a href="/plan-create">
                        <button type="button" class="bg-accent primary-action-btn w-full">Add Plan</button>
                    </a>
                </div>
            </div>
            <div class="content-body p-3 md:p-6 2xl:px-72 flex-1 flex flex-col gap-6 overflow-y-auto pt-44 md:pt-24">
                @if ($shopPlans)
                    @foreach ($shopPlans as $item)
                        <x-plan.plan-item :id="$item->id" :address="$item->address" :total="$item->number_of_items" :status="$item->status" />
                    @endforeach
                @else
                    <div class="text-center italic text-gray-500">No plans yet.</div>
                @endif
            </div>
        </div>
    </x-layout>
@endsection
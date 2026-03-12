@props(['id', 'address', 'total', 'status'])

@php
    $statusNames = [
        [ 'name' => "pending", 'color' => 'maintext' ],
        [ 'name' => "in progress", 'color' => 'maintext' ],
        [ 'name' => "completed", 'color' => 'text-green-400' ],
        [ 'name' => "overdue", 'color' => 'text-emphasis' ],
    ];

    $statusColor = $statusNames[$status]['color'];
    $statusName = $statusNames[$status]['name'];
@endphp

<a href="/plan-update/0">
    <div class="plan-item bg-white rounded-xl border border-gray-50 shadow-md p-5 flex flex-col gap-6 cursor-pointer hover:scale-[102%] transition-all ease-in">
        <p class="item-title text-xl main-text">{{ $address }}</p>
        <p class="flex justify-between text-xs main-text">
            <span>Total Items: {{ $total }}</span>
            <span class="{{ $statusColor }}">{{ $statusName }}</span>
        </p>
    </div>
</a>
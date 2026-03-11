@extends('layouts.app')
@php
    $showButton = (in_array($shopPlan->status, [0,1]) && \Carbon\Carbon::parse($shopPlan->date_scheduled)->format('Y-m-d') === \Carbon\Carbon::today()->format('Y-m-d'));
@endphp
@section('content')
    <x-layout>
        <div class="plan-header relative">
            <div class="title-wrapper static md:sticky top-6 main-text">
                Shop Plan
            </div>
            <div class="plan-fields-wrapper">
                <div class="flex flex-col lg:flex-row gap-6">
                    <div class="flex flex-col flex-1">
                        <label class="ps5">
                            Address
                        </label>
                        <input type="text" class="inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200 w-full" placeholder="Type Shop Address/Name here..."
                        value="{{ $shopPlan->address }}" readonly />
                    </div>
                    <div class="flex flex-col">
                        <label class="ps-5">
                            Date Scheduled
                        </label>
                        <input type="text" class="inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200" value="{{ \Carbon\Carbon::parse($shopPlan->date_scheduled)->format('F d, Y') }}" readonly />
                    </div>
                    <div class="hidden lg:flex flex-col">
                        <label class="ps-5">
                            Budget
                        </label>
                        <input type="number" name="budget" class="inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200 w-32" placeholder="0" value="{{ $shopPlan->budget }}" readonly />
                    </div>
                    <div class="hidden lg:flex flex-col">
                        <label class="ps-5">
                            Total
                        </label>
                        <input type="number" class="inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200 w-32" placeholder="0" value="{{ $shopPlan->number_of_items }}" readonly />
                    </div>

                    <div class="flex justify-between lg:hidden gap-5">
                        <div class="flex flex-col">
                            <label for="" class="ps-5">Budget</label>
                            <input type="number" class="inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200 w-full" placeholder="0" value="{{ $shopPlan->budget }}" readonly />
                        </div>
                        <div class="flex flex-col">
                            <label class="ps-5">Total</label>
                            <input type="number" class="inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200 w-full" placeholder="0" value="{{ $shopPlan->number_of_items }}" readonly />
                        </div>
                    </div>
                </div>
            </div>
            <hr class="border border-stone-300" />
        </div>
        <div class="items-plan-wrapper md:custom-scrollbar">
            @forelse ($shopPlan->items as $item)
                <x-plan.update-item :id="$item->id" :name="$item->name" :expectedquantity="$item->expected_quantity" :actualquantity="$item->actual_quantity" :price="$item->price" :isstart="$shopPlan->status == 1" />
            @empty
                <div class="text-center italic text-gray-500">No Items Found.</div>
            @endforelse


            @if ($showButton)
                <button type="button" class="main-action-btn bg-accent primary-action-btn rounded-xl fixed bottom-0 mb-5 w-5/6 md:w-40 px-6 left-0 right-0 md:left-auto md:right-auto mx-auto md:mx-0">
                    @if ($shopPlan->status == 0)
                        Start Plan
                    @else
                        Complete
                    @endif
                </button>
            @endif
        </div>
    </x-layout>
@endsection

@push('js')
    <script>
        let budget = $('input[name="budget"]').val();

        $(document).on('click', '.main-action-btn', function() {
            const btnText = $(this).text().trim().toLowerCase();
            if (btnText == "complete") {
                updatePlan();
            } else {
                startPlan();
            }
        });

        function startPlan() {
            $.ajax({
                url: '{{ route("plan.start") }}',
                method: 'POST',
                data: {
                    id: @json($shopPlan->id)
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if (response.status == "success") {
                        $('.main-action-btn').text('Complete');

                        $('.actual-quantity-input').prop('disabled', false);
                        $('.price-input').prop('disabled', false);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: function(xhr) {
                    console.log('error', xhr.responseJSON);
                    if (xhr.resposneJSON) {
                        toastr.error(xhr.responseJSON.message);
                    }
                }
            });
        }

        function updatePlan() {
            const budget = $('input[name="budget"]').val();
            // const items = $('');
            let items = [];
            $('.product-item').each(function() {
                const item = {};

                const id = $(this).find('input[name="id"]');
                const actQty = $(this).find('[name$="[actual_quantity]"]');
                const price = $(this).find('[name$="[price]"]');
                
                item['id'] = id.val();
                item['actual_quantity'] = actQty.val();
                item['price'] = price.val();

                items.push(item);
            });
            const formData = new FormData();

            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('_method', 'PUT');
            formData.append('budget', budget);
            items.forEach((item, index) => {
                formData.append(`items[${index}][id]`, item.id);
                formData.append(`items[${index}][actual_quantity]`, item.actual_quantity);
                formData.append(`items[${index}][price]`, item.price);
            });

            $.ajax({
                url: '{{ route("plan.update", $shopPlan->id) }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status == 'success') {
                        toastr.success(response.message);
                        $('.main-action-btn').remove();
                        setTimeout(() => {
                            window.location.href = "{{ route('list') }}";
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    console.log('error', xhr.responseJSON);
                    const errors = xhr.responseJSON?.errors;
                    const message = xhr.responseJSON?.message;

                    if (errors) {
                        const reversed = Object.entries(errors).reverse();

                        reversed.forEach(([field, messages]) => {
                            toastr.error(messages[0]);
                        });
                    } else if (message) {
                        toastr.error(message);
                    }
                }
            });
        }

        $(document).on('input', '.actual-quantity-input, .price-input', function() {
            const isActQty = $(this).hasClass('actual-quantity-input');
            const isPrice = $(this).hasClass('price-input');
            const parentEl = $(this).closest('.product-item');

            let val = parseFloat($(this).val()) || 0;
            
            parentEl.find(isActQty ? '.actual-quantity-input' : '.price-input')
            .not(this).val(val);
            
            let val2 = parseFloat(parentEl.find(isActQty ? '.price-input' : '.actual-quantity-input').first().val()) || 0;

            let totalVal = val * val2;

            parentEl.find('.total-input').val(parseFloat(totalVal.toFixed(2)));

            updateBudget();
        });

        function updateBudget() {
            let totalVal = 0;

            $('.product-item').each(function() {
                let val = parseFloat($(this).find('.total-input').first().val()) || 0;
                totalVal += val;
            });

            $('input[name="budget"]').val(budget - totalVal);
        }
    </script>
@endpush
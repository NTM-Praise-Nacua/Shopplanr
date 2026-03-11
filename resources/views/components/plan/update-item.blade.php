@props(['id', 'name', 'expectedquantity', 'actualquantity', 'price', 'isstart'])

@php
    $total = $actualquantity * $price;
    
    $isStart = false;
    if ($isstart == 1) {
        $isStart = true;
    }
@endphp

<div class="product-item">
    <input type="hidden" name="id" value="{{ $id }}" />
    <div class="flex flex-col lg:flex-row gap-2 md:gap-6">
        <div class="flex flex-col">
            <label for="" class="ps-5">Item Name</label>
            <input
                type="text"
                name="items[{{ $id }}][name]"
                class="inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200"
                placeholder="(e.g. A Can of Coke)"
                value="{{ $name }}"
                readonly
            />
        </div>
        <div class="flex md:hidden lg:flex flex-col flex-1">
            <label class="ps-5">
                Expected Quantity
            </label>
            <input
                type="number"
                name="items[{{ $id }}][expected_quantity]"
                class="expected-quantity-input inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200 w-[184px]"
                placeholder="0"
                value="{{ $expectedquantity }}"
                readonly
            />
        </div>
        <div class="flex md:hidden lg:flex flex-col flex-1">
            <label class="ps-5">
                Actual Quantity
            </label>
            <input
                type="number"
                name="items[{{ $id }}][actual_quantity]"
                class="actual-quantity-input inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200 w-[184px]"
                placeholder="0"
                value="{{ $actualquantity }}"
                {{ !$isStart ? "disabled" : ""  }}
            />
        </div>
        <div class="hidden lg:flex flex-col flex-1">
            <label class="ps-5">Price</label>
            <input type="number" name="items[{{ $id }}][price]" class="price-input inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200 w-36" placeholder="0" step="0.01" value="{{ $price }}"
                {{ !$isStart ? "disabled" : ""  }}
            />
        </div>
        <div class="hidden lg:flex flex-col flex-1">
            <label class="ps-5">Total</label>
            <input type="number" name="items[{{ $id }}][total]" class="total-input inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200 w-36" placeholder="0" value="{{ $total }}" step="0.01" readonly />
        </div>


        <div class="hidden md:flex gap-4 lg:hidden">
            <div class="flex flex-col flex-1">
                <label class="ps-5">
                    Expected Quantity
                </label>
                <input
                    type="number"
                    name="items[{{ $id }}][expected_quantity]"
                    class="expected-quantity-input inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200 w-[184px]"
                    placeholder="0"
                    value="{{ $expectedquantity }}"
                />
            </div>
            <div class="flex flex-col flex-1">
                <label class="ps-5">Actual Quantity</label>
                <input type="number" name="items[{{ $id }}][actual_quantity]" class="actual-quantity-input inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200 w-[184px]" placeholder="0" value="{{ $actualquantity }}" {{ !$isStart ? "disabled" : ""  }} />
            </div>
        </div>
        <div class="flex gap-4 lg:hidden">
            <div class="flex flex-col flex-1">
                <label class="ps-5">
                    Price
                </label>
                <input type="number" name="items[{{ $id }}][price]" class="price-input inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200 w-full" placeholder="0" value="{{ $price }}" step="0.01"
                {{ !$isStart ? "disabled" : ""  }}
                 />
            </div>
            <div class="flex flex-col flex-1">
                <label class="ps-5">
                    Total
                </label>
                <input type="number" name="items[{{ $id }}][total]" class="total-input inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200 w-full" value="{{ $total }}" readonly step="0.01" />
            </div>
        </div>
    </div>
</div>
<div class="product-item">
    <div class="flex flex-col md:flex-row gap-6">
        <div class="flex flex-col">
            <label for="" class="ps-5">Item Name</label>
            <input type="text" name="item_name" class="inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200" placeholder="(e.g. A Can of Coke)" />
        </div>
        <div class="hidden md:flex flex-col flex-1">
            <label class="ps-5">
                Expected Quantity
            </label>
            <input
                type="number"
                name="item_expected_quantity"
                class="expected-quantity-input inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200 w-[184px]"
                placeholder="0"
            />
        </div>
        <div class="hidden md:flex justify-center items-center">
            <button
                type="button"
                class="rounded-xl p-2 bg-emphasis text-white cursor-pointer hover:opacity-85 transition-all ease-in remove-item-btn"
            >
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>

        <div class="flex md:hidden gap-3">
            <div class="flex flex-col flex-1">
                <label class="ps-5">
                    Expected Quantity
                </label>
                <input
                    type="number"
                    name="item_expected_quantity"
                    class="expected-quantity-input inputfield py-3 px-5 placeholder:italic shadow-md shadow-gray-200 w-[184px]"
                    placeholder="0"
                />
            </div>
            <div class="flex justify-center items-end">
              <button
                type="button"
                class="rounded-xl p-2 bg-emphasis text-white cursor-pointer hover:opacity-85 transition-all ease-in"
              >
                <i class="fa-solid fa-trash-can"></i>
              </button>
            </div>
        </div>
    </div>
</div>
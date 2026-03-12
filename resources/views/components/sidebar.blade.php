@props(['openMenu' => false])

@php
    $list = request()->routeIs('list') || request()->routeIs('create') || request()->routeIs('update');
    $settings = request()->routeIs('settings');
@endphp
<div class="sidebar">
    {{-- <div class="close-menu-btn absolute -right-7">
        <div class="text-2xl font-black text-white cursor-pointer">
            <i class="fa-solid fa-x"></i>
        </div>
    </div> --}}
    <img src="{{ asset('images/ShopPlanr-sidebar.png') }}" alt="ShopPlanr - Logo">

    <div class="sidetabs">
        <a href="/list" class="hover:opacity-75 transition-all ease-in">
            <div class="flex gap-3 text-xl px-5 py-3 {{ $list ? 'active' : '' }}">
                <div><i class="fa-solid fa-list"></i></div>
                <p class="flex-1">Plans</p>
            </div>
        </a>
        {{-- <a href="/settings" class="hover:opacity-75 transition-all ease-in">
            <div class="flex gap-3 text-xl px-5 py-3 {{ $settings ? 'active' : '' }}">
                <div><i class="fa-solid fa-gear"></i></div>
                <p class="flex-1">Settings</p>
            </div>
        </a> --}}
    </div>

    <form action="{{ route('auth.logout') }}" method="post">
        @csrf
        <div class="hover:opacity-85 transition-all ease-in cursor-pointer">
            <div class="flex text-xl gap-3 px-5 py-3 text-emphasis">
                <div>
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </div>
                <button type="submit" class="text-left flex-1">Log out</button>
            </div>
        </div>
    </form>
</div>

@push('js')
    <script>
        let closeBtn = $(`<div class="close-menu-btn-wrapper absolute -right-7">
                            <div class="text-2xl font-black text-white cursor-pointer close-menu-btn">
                                <i class="fa-solid fa-x"></i>
                            </div>
                        </div>`);
        let backdrop = $(`<div class="back-drop absolute top-0 left-0 z-10 bg-black/50 w-full h-full"></div>`);
        let isOpen = false;
        $(document).on('click', '.responsive-header>.menu-btn, .close-menu-btn', function() {
            const menuBtn = $(this).hasClass('menu-btn');
            if (!menuBtn) {
                $('.back-drop').remove();
                $('.sidebar').removeClass('bg-white left-0');
                $('.close-menu-btn-wrapper').remove();
            } else {
                $('.auth-wrapper').prepend(backdrop);
                $('.sidebar').addClass('bg-white left-0');
                $('.sidebar').prepend(closeBtn);
            }
        });
        
        function toggleSidebar() {
        }

    </script>
@endpush
<div class="navbar bg-base-100">
    <div class="flex-1">
        <a class="btn btn-ghost text-xl" href="{{ route('dashboard') }}">
            <x-application-logo-small class="block h-16 fill-current text-gray-800" />
        </a>
        <div class="hidden md:flex md:ml-2">
            <a class="btn btn-ghost" href="{{ route('dashboard') }}">Dashboard</a>
            <a class="btn btn-ghost" href="{{ route('client.index') }}">Clientes</a>
            <a class="btn btn-ghost" href="{{ route('loan.index') }}">{{ __('Loans') }}</a>
            @can('view investors')
            <a class="btn btn-ghost" href="{{ route('investor.index') }}">{{ __('Investors') }}</a>
            @endcan
            <a class="btn btn-ghost" href="#">Reportes</a>
        </div>
    </div>
    <div class="flex-none">
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full">
                    <img
                        alt="Tailwind CSS Navbar component"
                        src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                </div>
            </div>
            <ul
                tabindex="0"
                class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-dropdown-link>
                <!-- <li>
                    <a class="justify-between">
                        Profile
                        <span class="badge">New</span>
                    </a>
                </li>
                <li><a>Ajustes</a></li> -->

                @can('view staff')
                <x-dropdown-link :href="route('staff.index')">
                    {{ __('Staff') }}
                </x-dropdown-link>
                @endcan
                @can('view banks')
                <x-dropdown-link :href="route('bank.index')">
                    {{ __('Banks') }}
                </x-dropdown-link>
                @endcan
                @can('view acl')
                <x-dropdown-link :href="route('acl.index')">
                    {{ __('ACL') }}
                </x-dropdown-link>
                @endcan

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault();
                                                this.closest('form').submit();" class="hover:bg-red-200 hover:text-red-500">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </ul>
        </div>
    </div>
</div>

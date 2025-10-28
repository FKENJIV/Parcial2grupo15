<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-indigo-900">
        <flux:sidebar sticky stashable class="border-e border-indigo-200 bg-indigo-50 dark:border-indigo-700 dark:bg-indigo-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ in_array(auth()->user()->role, ['teacher', 'docente']) ? route('teacher.dashboard') : route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Menu')" class="grid">
                    @if(in_array(auth()->user()->role, ['teacher', 'docente']))
                        {{-- Teacher Menu --}}
                        <flux:navlist.item icon="home" :href="route('teacher.dashboard')" :current="request()->routeIs('teacher.dashboard')" wire:navigate class="text-indigo-700">{{ __('Dashboard') }}</flux:navlist.item>
                        <flux:navlist.item icon="calendar" :href="route('teacher.schedules')" :current="request()->routeIs('teacher.schedules')" wire:navigate class="text-indigo-700">{{ __('Mis Horarios') }}</flux:navlist.item>
                        <flux:navlist.item icon="users" :href="route('teacher.groups.create')" :current="request()->routeIs('teacher.groups.create')" wire:navigate class="text-indigo-700">{{ __('Crear Grupo') }}</flux:navlist.item>
                        <flux:navlist.item icon="clock" :href="route('teacher.attendance')" :current="request()->routeIs('teacher.attendance')" wire:navigate class="text-indigo-700">{{ __('Registrar Asistencia') }}</flux:navlist.item>
                    @else
                        {{-- Admin/Superuser Menu --}}
                        <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate class="text-indigo-700">{{ __('Dashboard') }}</flux:navlist.item>
                        <flux:navlist.item icon="calendar" :href="route('horarios')" :current="request()->routeIs('horarios')" wire:navigate class="text-indigo-700">{{ __('Ver Horarios') }}</flux:navlist.item>
                        <flux:navlist.item icon="cog" :href="route('admin.schedules.index')" :current="request()->routeIs('admin.schedules.*')" wire:navigate class="text-indigo-700">{{ __('Gestionar Horarios') }}</flux:navlist.item>
                        <flux:navlist.item icon="bolt" :href="route('administrar.carga')" :current="request()->routeIs('administrar.carga')" wire:navigate class="text-indigo-700">{{ __('Administrar Carga') }}</flux:navlist.item>
                        <flux:navlist.item icon="clock" :href="route('registro.asistencia')" :current="request()->routeIs('registro.asistencia')" wire:navigate class="text-indigo-700">{{ __('Registro Asistencia') }}</flux:navlist.item>
                        <flux:navlist.item icon="users" :href="route('docentes.index')" :current="request()->routeIs('docentes.index')" wire:navigate class="text-indigo-700">{{ __('Docentes') }}</flux:navlist.item>
                    @endif
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Panel (always visible) -->
                <div class="px-4 pb-4 hidden lg:block">
                <div class="rounded-lg border border-indigo-200 bg-white p-3 dark:border-indigo-700 dark:bg-indigo-900">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center font-semibold text-indigo-700">{{ auth()->user()->initials() }}</div>
                        <div class="flex-1 text-sm">
                            <div class="font-semibold truncate">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-zinc-500 truncate">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <flux:button type="submit" variant="ghost" class="w-full text-indigo-700">{{ __('Cerrar sesión') }}</flux:button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Desktop User Menu (fallback) -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>

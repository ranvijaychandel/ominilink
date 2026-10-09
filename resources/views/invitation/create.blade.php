<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-6 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @props([
                        'action' => route('invitationStore'),
                        'method' => 'POST',
                        'title' => 'Send Invitation',
                        'subtitle' => 'Invite a new user.',
                        'roles' => [], // ['admin' => 'Admin', 'editor' => 'Editor', 'user' => 'User']
                        'companies'  => [],
                        'submitLabel' => 'Invite',
                    ])

                    @php
                        $roles = count($roles)
                            ? $roles
                            : null;
                        $companies = count($companies) ? $companies : null;
                    @endphp

                    <div class="max-w-2xl mx-auto">
                        <div class="bg-white shadow-sm ring-1 ring-slate-200 rounded-2xl overflow-hidden">

                            {{-- Header --}}
                            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/60">
                                <h2 class="text-lg font-bold text-slate-900">{{ $title }}</h2>
                                <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
                            </div>

                            {{-- Form --}}
                            <form action="{{ $action }}"
                                method="{{ strtoupper($method) === 'GET' ? 'GET' : 'POST' }}" class="p-6 space-y-6">
                                @csrf
                                @if (!in_array(strtoupper($method), ['GET', 'POST']))
                                    @method($method)
                                @endif

                                {{-- Name --}}
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-slate-700">
                                        Full Name
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                                        required autocomplete="name" placeholder="Jane Doe"
                                        class="mt-2 block w-full rounded-lg border-0 py-2.5 px-3.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-teal-600 sm:text-sm transition">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-slate-700">
                                        Email Address
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                        required autocomplete="email" placeholder="jane@example.com"
                                        class="mt-2 block w-full rounded-lg border-0 py-2.5 px-3.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-teal-600 sm:text-sm transition">
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Roles Dropdown --}}
                                <div>
                                    <label for="role" class="block text-sm font-semibold text-slate-700">
                                        Role
                                    </label>
                                    <div class="relative mt-2">
                                        <select name="role" id="role" required
                                            class="appearance-none block w-full rounded-lg border-0 py-2.5 pl-3.5 pr-10 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-teal-600 sm:text-sm transition cursor-pointer">
                                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>
                                                Select a role…
                                            </option>
                                            @foreach ($roles as $value => $role)
                                                <option value="{{ $role->slug }}" @selected(old('role') === $role->slug)>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        {{-- Custom chevron --}}
                                        <div
                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.06l3.71-3.83a.75.75 0 111.08 1.04l-4.25 4.39a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('role')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Companies Dropdown --}}
                                @if (auth()->user()->id === 1)
                                <div>
                                    <label for="company" class="block text-sm font-semibold text-slate-700">
                                        Company
                                    </label>
                                    <div class="relative mt-2">
                                        <select name="company" id="company" required
                                            class="appearance-none block w-full rounded-lg border-0 py-2.5 pl-3.5 pr-10 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-teal-600 sm:text-sm transition cursor-pointer">
                                            <option value="" disabled {{ old('company') ? '' : 'selected' }}>
                                                Select a company...
                                            </option>
                                            @foreach ($companies as $key => $company)
                                                <option value="{{ $company->id }}" @selected(old('name') === $company->name)>
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        {{-- Custom chevron --}}
                                        <div
                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.06l3.71-3.83a.75.75 0 111.08 1.04l-4.25 4.39a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('role')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                @endif

                                {{-- Actions --}}
                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                                    <button type="reset"
                                        class="text-sm font-semibold text-slate-600 hover:text-slate-900 px-4 py-2.5 rounded-lg hover:bg-slate-100 transition">
                                        Reset
                                    </button>
                                    <button type="submit"
                                        class="inline-flex items-center justify-center gap-2 text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 px-5 py-2.5 rounded-lg shadow-sm hover:shadow-md transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ $submitLabel }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

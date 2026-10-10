<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('All Short Urls') }}
                    </h3>
                                @if(session('error'))
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 4000)"
                    class="fixed bottom-5 right-5 z-50 flex max-w-sm items-center rounded-lg bg-red-600 p-4 text-white shadow-lg">
                    <div class="mr-3 text-sm font-medium">
                        {{ session('error') }}
                    </div>
                    <button @click="show = false" class="ml-auto p-1 text-white hover:text-red-200">
                        &times;
                    </button>
                </div>
            @endif

                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-50 text-indigo-700">
                             <a href="{{ route('shortUrlCreate') }}" rel="noopener noreferrer"
                                                   class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-indigo-100 text-indigo-800 hover:bg-indigo-200 transition duration-150"
                                                   title="{{ __('Create short url') }}">
                                                    {{'Add short url' }}
                                                </a>

                    </span>
                    @if(is_countable($urls) && count($urls) > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-50 text-indigo-700">
                            {{ count($urls) }} {{ __('records') }}
                        </span>
                    @else
                         {{ __('0 records') }}
                    @endif
                </div>

                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">
                                        {{ __('No.') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        {{ __('Code') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        {{ __('Short Url') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        {{ __('Original Url') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        {{ __('Created By') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        {{ __('Company') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        {{ __('Clicks') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if(!is_countable($urls) || count($urls) === 0)
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                                </svg>
                                                <span class="mt-2 text-sm text-gray-500">{{ __('No records found.') }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($urls as $url)
                                        <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $loop->iteration }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ url($url->short_url) }}" target="_blank" rel="noopener noreferrer"
                                                   class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-indigo-100 text-indigo-800 hover:bg-indigo-200 transition duration-150"
                                                   title="{{ __('Visit short url') }}">
                                                    {{ $url->short_url }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"  id="copy-text-{{ $loop->index }}">
                                                {{url('/', $url->short_url)}}
                                            </td>

                                            <td class="px-6 py-4 text-sm max-w-xs">
                                                <a href="{{ $url->original_url }}" target="_blank" rel="noopener noreferrer"
                                                   class="text-indigo-600 hover:text-indigo-900 hover:underline break-all"
                                                   title="{{ $url->original_url }}">
                                                    {{ $url->original_url }}
                                                </a>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $url->createdBy->name ?? '—' }}
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $url->tenant->name ?? '—' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $url->clicks ?? '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Links -->
                    <div class="mt-4">
                        {{ $urls->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

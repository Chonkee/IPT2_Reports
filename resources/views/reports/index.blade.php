<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Reports') }}
            </h2>
            <a href="{{ route('reports.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Generate Report
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($reports->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Generated Reports</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($reports as $report)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                                    <h4 class="text-md font-semibold">{{ $report->month_name }} {{ $report->year }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Generated on {{ $report->created_at->format('M d, Y') }}</p>
                                    <a href="{{ route('reports.show', $report) }}" class="mt-2 inline-block bg-blue-500 hover:bg-blue-700 text-white text-sm py-1 px-3 rounded">
                                        View Details
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                        <p>No reports generated yet. Click "Generate Report" to create your first report.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

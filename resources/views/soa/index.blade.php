<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Statement of Accounts (SOA)') }}
            </h2>
            <button onclick="document.getElementById('sendAllModal').showModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Send All SOA
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('soa.batch') }}" id="soaForm">
                        @csrf

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            <input type="checkbox" id="selectAll" class="w-4 h-4">
                                        </th>
                                        <th scope="col" class="px-6 py-3">Account Number</th>
                                        <th scope="col" class="px-6 py-3">Customer Name</th>
                                        <th scope="col" class="px-6 py-3">Principal Amount</th>
                                        <th scope="col" class="px-6 py-3">Balance</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                        <th scope="col" class="px-6 py-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($accounts as $account)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td class="px-6 py-4">
                                                <input type="checkbox" name="account_ids[]" value="{{ $account->id }}" class="w-4 h-4 account-checkbox">
                                            </td>
                                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                                {{ $account->account_number }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $account->customer->name }}
                                            </td>
                                            <td class="px-6 py-4">
                                                ₱ {{ number_format($account->principal_amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4">
                                                ₱ {{ number_format($account->balance, 2) }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $account->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($account->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <form method="POST" action="{{ route('soa.send', $account) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 font-medium">
                                                        Send SOA
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                                No accounts found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span id="selectedCount">0</span> account(s) selected
                                </p>
                            </div>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" id="batchSubmitBtn" disabled>
                                Send Selected SOA
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Pagination -->
            @if ($accounts->hasPages())
                <div class="mt-6">
                    {{ $accounts->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Send All Modal -->
    <dialog id="sendAllModal" class="p-6 rounded-lg bg-white dark:bg-gray-800 shadow-lg">
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Send SOA to All Accounts</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            This will send Statement of Account emails to all {{ $totalAccounts }} accounts with a 5-second delay between each.
        </p>
        <div class="flex gap-4">
            <form method="POST" action="{{ route('soa.send-all') }}">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Confirm Send
                </button>
            </form>
            <button onclick="document.getElementById('sendAllModal').close()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Cancel
            </button>
        </div>
    </dialog>

    <script>
        // Select All Checkbox
        document.getElementById('selectAll').addEventListener('change', function(e) {
            const checkboxes = document.querySelectorAll('.account-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = e.target.checked);
            updateSelectedCount();
        });

        // Individual Checkboxes
        document.querySelectorAll('.account-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        function updateSelectedCount() {
            const selected = document.querySelectorAll('.account-checkbox:checked').length;
            document.getElementById('selectedCount').textContent = selected;
            document.getElementById('batchSubmitBtn').disabled = selected === 0;
        }
    </script>
</x-app-layout>

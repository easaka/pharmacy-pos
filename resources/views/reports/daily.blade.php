<x-layout title="Daily Sales">

<div class="max-w-6xl mx-auto py-8">

    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-semibold text-gray-800">
            Daily Sales — {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
        </h2>

        <p class="text-lg text-gray-700 mt-2">
            <span class="font-semibold">Total Sales:</span>
            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-lg font-bold">
                GHS {{ number_format($total, 2) }}
            </span>
        </p>
    </div>

    <!-- Sales Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow">

        <table class="min-w-full border-collapse">
            <thead class="bg-gray-100 text-gray-700 text-sm uppercase tracking-wide">
                <tr>
                    <th class="p-3 text-left">Invoice</th>
                    <th class="p-3 text-left">Total</th>
                    <th class="p-3 text-left">Paid</th>
                    <th class="p-3 text-left">Date</th>
                </tr>
            </thead>

            <tbody class="text-gray-800 text-sm">
                @forelse($sales as $s)
                <tr class="border-t hover:bg-gray-50 transition">

                    <td class="p-3 font-medium text-gray-900">
                        {{ $s->invoice_no }}
                    </td>

                    <td class="p-3 font-semibold text-gray-800">
                        GHS {{ number_format($s->total,2) }}
                    </td>

                    <td class="p-3 text-green-700 font-medium">
                        GHS {{ number_format($s->paid,2) }}
                    </td>

                    <td class="p-3 text-gray-600">
                        {{ $s->created_at->format('d M Y, h:i A') }}
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-gray-500">
                        No sales recorded for this day.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>

</x-layout>

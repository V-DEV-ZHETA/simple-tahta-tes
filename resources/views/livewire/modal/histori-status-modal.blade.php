<div>
    <h3 class="text-lg font-semibold mb-4">Histori Status</h3>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Status Sebelum</th>
                <th class="py-2 px-4 border-b">Status Menjadi</th>
                <th class="py-2 px-4 border-b">Oleh</th>
                <th class="py-2 px-4 border-b">Catatan</th>
                <th class="py-2 px-4 border-b">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bangkom->historiStatuses ?? [] as $history)
            <tr>
                <td class="py-2 px-4 border-b">{{ $history->status_sebelum }}</td>
                <td class="py-2 px-4 border-b">{{ $history->status_menjadi }}</td>
                <td class="py-2 px-4 border-b">{{ $history->oleh }}</td>
                <td class="py-2 px-4 border-b">{{ $history->catatan }}</td>
                <td class="py-2 px-4 border-b">{{ $history->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-4 px-4 text-center text-gray-500">Belum ada histori status</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

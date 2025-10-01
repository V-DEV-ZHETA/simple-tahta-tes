@if ($kurikulum)
    <ul style="list-style-type: none; padding-left: 0;">
        @foreach ($kurikulum as $item)
            <li style="margin-bottom: 1rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem;">
                <div><strong>Narasumber:</strong> {{ $item['narasumber'] ?? '-' }}</div>
                <div><strong>Materi:</strong> {{ $item['materi'] ?? '-' }}</div>
                <div><strong>Jam Pelajaran:</strong> {{ $item['jam_pelajaran'] ?? '-' }}</div>
            </li>
        @endforeach
    </ul>
@else
    <p>Tidak ada data kurikulum.</p>
@endif

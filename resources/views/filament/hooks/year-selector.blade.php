<div class="fi-topbar-item flex items-center gap-x-4">
    <div class="flex items-center gap-x-2">
        <span class="text-sm font-medium text-gray-950 dark:text-white">Tahun</span>
        <select 
            id="year-selector"
            class="fi-select-input block w-full rounded-lg border-none bg-white py-1.5 pe-8 ps-3 text-sm shadow-sm ring-1 ring-gray-950/10 dark:bg-white/5 dark:ring-white/20 dark:text-white"
            onchange="handleYearChange(this.value)"
        >
            @php
                use App\Models\Tahun;
                $currentYear = session('selected_year', date('Y'));
                $years = Tahun::where('status', true)->orderBy('year', 'desc')->pluck('year')->toArray();
            @endphp
            
            @foreach($years as $year)
                <option value="{{ $year }}" {{ $currentYear == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>
    </div>

<script>
    function handleYearChange(year) {
        // Simpan tahun ke session via AJAX
        fetch('{{ route("set-year") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ year: year })
        })
        .then(response => response.json())
        .then(data => {
            // Reload halaman untuk menerapkan filter tahun
            window.location.reload();
        });
    }
</script>
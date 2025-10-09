# Year Selector Database Integration

## Tasks
- [x] Modify year-selector.blade.php to fetch years from Tahun model instead of static range
- [x] Ensure selected year logic still works with session
- [ ] Test year selector functionality

## Details
The year selector currently uses `range(date('Y'), date('Y') - 10)` to generate years.
Need to replace with `Tahun::where('status', true)->orderBy('year', 'desc')->pluck('year')->toArray()`

## Changes Made
- Modified resources/views/filament/hooks/year-selector.blade.php to use Tahun model query
- Added `use App\Models\Tahun;` statement
- Changed `$years = range(date('Y'), date('Y') - 10);` to `$years = Tahun::where('status', true)->orderBy('year', 'desc')->pluck('year')->toArray();`
- Session logic for selected year remains unchanged

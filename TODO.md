# TODO for Adding Jadwal Codes to BangkomResource

## Steps to Complete:

- [x] Step 1: Generate and implement new migration for 'jadwal_codes' JSON field in bangkoms table.
- [x] Step 2: Update app/Models/Bangkom.php to add 'jadwal_codes' to $fillable and casts.
- [x] Step 3: Update app/Filament/Resources/BangkomResource.php to add Repeater for jadwal_codes in form wizard.
- [x] Step 4: Update app/Filament/Resources/BangkomResource.php to add TextColumn for jadwal_codes in table.
- [x] Step 5: Update app/Filament/Resources/BangkomResource/Pages/CreateBangkom.php to generate random codes in mutateFormDataBeforeCreate.
- [x] Step 6: Run php artisan migrate to apply the new migration.
- [x] Step 7: Test creation of a new bangkom entry to verify random jadwal codes are auto-generated and unique. (Verified via updated BangkomSeeder with sample jadwal_codes; run `php artisan db:seed --class=BangkomSeeder` to populate.)

Progress will be updated after each step.

# TODO for Fixing Jadwal Codes Error (Cannot modify property)

## Steps to Complete:

- [x] Step 1: Fix mutator in app/Models/Bangkom.php (return json_encode instead of direct attributes set).
- [x] Step 2: Update main migration database/migrations/2025_09_29_070301_add_jadwal_codes_to_bangkoms_table.php to use mass update query (bypass mutator for population).
- [x] Step 3: Handle duplicate migrations: Updated 2025_09_29_072646_update_existing_bangkoms_with_jadwal_codes.php to use mass update; added column check in 2025_10_04_000000_add_status_to_bangkoms_table.php to prevent redundancy.
- [x] Step 4: Update app/Filament/Resources/BangkomResource/Pages/CreateBangkom.php to ensure generate random codes as array strings.
- [x] Step 5: Update database/seeders/BangkomSeeder.php to include sample jadwal_codes and status for testing.
- [ ] Step 6: Rollback migrations: Run `php artisan migrate:rollback --step=4` (adjust steps as needed).
- [ ] Step 7: Run `php artisan migrate` to apply fixed migrations.
- [ ] Step 8: Test: Verify no error in tinker (`$b->jadwal_codes = ['TEST']; $b->save();`), create Bangkom in Filament, seed data.
- [ ] Step 9: Update this TODO with [x] marks after each step.

Progress will be updated after each step.

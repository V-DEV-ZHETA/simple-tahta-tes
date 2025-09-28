# TODO: Standardize Filament Resources to Match Bangkom UI

## Steps to Complete:

1. **Update BangkomResource.php**: Add minor tweaks like explicit searchable on nama_kegiatan, ensure status badge colors.

2. **Update InstansiResource.php**: Add ID column as 'No', View/Delete actions, improve form with columns(2), add create/edit/view pages (create missing files).

3. **Implement JenisPelatihanResource.php**: Add form for 'name', table with ID and name (searchable/sortable), full actions, add view page.

4. **Implement BentukPelatihanResource.php**: Similar to JenisPelatihan - form for 'name', table ID/name, full actions, add view page.

5. **Implement SasaranResource.php**: Form for 'name', table ID/name searchable, full actions/pages.

6. **Implement BidangResource.php**: Form for 'name', table ID/name, full actions/pages.

7. **Implement TahunResource.php**: Form for 'year' (numeric), table ID/year sortable, full actions/pages.

8. **Implement WidyaiswaraResource.php**: Form for name/nip/email/phone, table ID/name/nip/email, full actions/pages.

9. **Update UserResource.php**: Add ID 'No', searchable name/email, full actions (careful with delete).

10. **Implement CoachingResource.php**: Wizard form similar to Bangkom (adapt fields), table with ID/name/dates/status, full actions/pages.

11. **Implement MentoringResource.php**: Similar to Coaching - wizard if complex, table ID/name/dates/status, full actions/pages.

12. **Create missing page files**: For all resources (e.g., CreateInstansi.php, ViewSasaran.php) using Filament defaults.

13. **Test and verify**: Run server, check UI similarity (tables, forms, actions), update if needed.

## Progress:
- [x] Update InstansiResource.php: Added ID column, full actions, improved form layout, added create/edit/view pages.

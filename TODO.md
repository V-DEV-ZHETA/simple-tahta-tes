# TODO: Tambahkan Button Ajukan Permohonan dan Histori Status pada BangkomResource

## Steps to Complete
- [ ] Buat migrasi untuk tabel status_histories
- [ ] Buat model StatusHistory
- [ ] Update model Bangkom untuk relasi statusHistories dan observer untuk log perubahan status
- [ ] Update BangkomResource: tambah status "Submitted", tambah action "Ajukan Permohonan", tambah action "Histori Status"
- [ ] Jalankan migrasi
- [ ] Test fungsi button

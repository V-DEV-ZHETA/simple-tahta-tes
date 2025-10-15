<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Permohonan Bangkom</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            margin-bottom: 20px;
        }
        .content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .content table td {
            padding: 8px;
            vertical-align: top;
        }
        .content table td:first-child {
            width: 200px;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>FORMULIR PERMOHONAN PENGEMBANGAN KOMPETENSI</h2>
    </div>

    <div class="content">
        <table>
            <tr>
                <td>Kode Kegiatan</td>
                <td>: {{ $bangkom->kode_kegiatan }}</td>
            </tr>
            <tr>
                <td>Unit Kerja</td>
                <td>: {{ $bangkom->unit_kerja }}</td>
            </tr>
            <tr>
                <td>Nama Kegiatan</td>
                <td>: {{ $bangkom->nama_kegiatan }}</td>
            </tr>
            <tr>
                <td>Jenis Pelatihan</td>
                <td>: {{ $bangkom->jenisPelatihan->nama }}</td>
            </tr>
            <tr>
                <td>Bentuk Pelatihan</td>
                <td>: {{ $bangkom->bentukPelatihan->nama }}</td>
            </tr>
            <tr>
                <td>Sasaran</td>
                <td>: {{ $bangkom->sasaran->nama }}</td>
            </tr>
            <tr>
                <td>Periode</td>
                <td>: {{ $bangkom->tanggal_mulai->format('d/m/Y') }} - {{ $bangkom->tanggal_selesai->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>: {{ $bangkom->tempat }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>: {{ $bangkom->alamat }}</td>
            </tr>
            <tr>
                <td>Kuota</td>
                <td>: {{ $bangkom->kuota }} orang</td>
            </tr>
            <tr>
                <td>Nama Panitia</td>
                <td>: {{ $bangkom->nama_panitia }}</td>
            </tr>
            <tr>
                <td>No. Telepon</td>
                <td>: {{ $bangkom->no_telp }}</td>
            </tr>
        </table>

        @if($bangkom->kurikulum)
        <h4>Kurikulum</h4>
        <table border="1" style="width: 100%">
            <thead>
                <tr>
                    <th>Narasumber</th>
                    <th>Materi</th>
                    <th>Jam Pelajaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bangkom->kurikulum as $item)
                <tr>
                    <td>{{ $item['Narasumber'] }}</td>
                    <td>{{ $item['materi'] }}</td>
                    <td>{{ $item['jam_pelajaran'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if($bangkom->deskripsi)
        <h4>Deskripsi</h4>
        <p>{{ $bangkom->deskripsi }}</p>
        @endif

        @if($bangkom->persyaratan)
        <h4>Persyaratan</h4>
        <p>{{ $bangkom->persyaratan }}</p>
        @endif
    </div>

    <div class="signature">
        <p>{{ $bangkom->instansi->nama }}, {{ now()->format('d F Y') }}</p>
        <br><br><br>
        <p>{{ $bangkom->user->name }}</p>
    </div>
</body>
</html>
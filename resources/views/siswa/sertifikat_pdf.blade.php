<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat - {{ $judul_pelajaran }}</title>
    <style>
        /* [NEW FEATURE] - PDF Certificate Design */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
        }

        .certificate-container {
            background: white;
            padding: 60px 80px;
            border: 20px solid #667eea;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
        }

        .certificate-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            font-size: 60px;
            margin-bottom: 10px;
        }

        .institution-name {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }

        .certificate-title {
            font-size: 48px;
            font-weight: bold;
            color: #764ba2;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin: 30px 0;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }

        .certificate-body {
            text-align: center;
            margin: 40px 0;
        }

        .intro-text {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }

        .recipient-name {
            font-size: 36px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
            padding: 15px 30px;
            border-bottom: 2px solid #667eea;
            display: inline-block;
        }

        .achievement-text {
            font-size: 18px;
            color: #555;
            margin: 20px 0;
            line-height: 1.8;
        }

        .course-name {
            font-size: 24px;
            font-weight: bold;
            color: #764ba2;
            margin: 15px 0;
        }

        .score-section {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin: 30px auto;
            width: 300px;
        }

        .score-label {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .score-value {
            font-size: 48px;
            font-weight: bold;
        }

        .certificate-footer {
            margin-top: 60px;
            display: table;
            width: 100%;
        }

        .signature-section {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 20px;
        }

        .date-section {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .signature-line {
            border-top: 2px solid #333;
            margin: 50px auto 10px;
            width: 200px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 16px;
            color: #333;
        }

        .signature-title {
            font-size: 14px;
            color: #666;
        }

        .certificate-number {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #999;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 120px;
            opacity: 0.05;
        }

        .decorative-corner {
            position: absolute;
            width: 80px;
            height: 80px;
            border: 3px solid #667eea;
        }

        .corner-tl {
            top: 10px;
            left: 10px;
            border-right: none;
            border-bottom: none;
        }

        .corner-tr {
            top: 10px;
            right: 10px;
            border-left: none;
            border-bottom: none;
        }

        .corner-bl {
            bottom: 10px;
            left: 10px;
            border-right: none;
            border-top: none;
        }

        .corner-br {
            bottom: 10px;
            right: 10px;
            border-left: none;
            border-top: none;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        {{-- Decorative Corners --}}
        <div class="decorative-corner corner-tl"></div>
        <div class="decorative-corner corner-tr"></div>
        <div class="decorative-corner corner-bl"></div>
        <div class="decorative-corner corner-br"></div>

        {{-- Watermark --}}
        <div class="watermark">📚</div>

        {{-- Certificate Header --}}
        <div class="certificate-header">
            <div class="logo">🏆</div>
            <div class="institution-name">MTsN - AdabKita</div>
            <div style="font-size: 14px; color: #666;">Platform Pembelajaran Adab Islami</div>
        </div>

        <div class="certificate-title">SERTIFIKAT PENGHARGAAN</div>

        {{-- Certificate Body --}}
        <div class="certificate-body">
            <p class="intro-text">Sertifikat ini diberikan kepada:</p>

            <div class="recipient-name">{{ $nama_siswa }}</div>

            <p class="achievement-text">
                Atas keberhasilan menyelesaikan program pembelajaran
            </p>

            <div class="course-name">"{{ $judul_pelajaran }}"</div>

            <p class="achievement-text">
                Dengan dedikasi dan kerja keras yang luar biasa,<br>
                telah menunjukkan pemahaman yang sangat baik terhadap materi pembelajaran.
            </p>

            {{-- Score Section --}}
            <div class="score-section">
                <div class="score-label">Nilai Akhir</div>
                <div class="score-value">{{ $nilai_akhir }}%</div>
            </div>

            <p style="font-size: 14px; color: #666; margin-top: 20px;">
                Durasi Belajar: {{ $durasi_belajar }}<br>
                Tanggal Selesai: {{ $tanggal_selesai }}
            </p>
        </div>

        {{-- Certificate Footer (Signatures) --}}
        <div class="date-section">
            Diterbitkan pada: {{ $tanggal_generate }}
        </div>

        <div class="certificate-footer">
            <div class="signature-section">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $nama_guru }}</div>
                <div class="signature-title">Pengajar</div>
            </div>

            <div class="signature-section">
                <div class="signature-line"></div>
                <div class="signature-name">Kepala Sekolah</div>
                <div class="signature-title">MTsN AdabKita</div>
            </div>
        </div>

        {{-- Certificate Number --}}
        <div class="certificate-number">
            Nomor Sertifikat: {{ $nomor_sertifikat }}
        </div>
    </div>
</body>
</html>

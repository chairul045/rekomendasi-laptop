# -*- coding: utf-8 -*-
"""
SRS Bagian 1: Cover, Persetujuan, Ringkasan Eksekutif, Bab 1 (Pendahuluan),
Bab 2 (Deskripsi Keseluruhan Sistem), Bab 3 (Kebutuhan Antarmuka Eksternal)
"""

import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from srs_builder_core import (
    NAVY_PRIMARY, BLUE_SECONDARY, SLATE_DARK, GRAY_MUTED,
    HEX_PRIMARY, HEX_SECONDARY, HEX_LIGHT_BG, HEX_CALLOUT_BG,
    set_run_font, add_p, add_bullet, add_heading_1, add_heading_2, add_heading_3,
    add_callout, create_styled_table
)

def build_cover_page(doc):
    # Spacer atas
    for _ in range(2):
        doc.add_paragraph()

    # Kategori Dokumen
    p_cat = doc.add_paragraph()
    p_cat.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_cat.paragraph_format.space_after = Pt(8)
    r_cat = p_cat.add_run("DOKUMEN REKAYASA PERANGKAT LUNAK (SOFTWARE ENGINEERING)")
    set_run_font(r_cat, font_name="Calibri", size_pt=11, color=BLUE_SECONDARY, bold=True)

    # Standar IEEE
    p_std = doc.add_paragraph()
    p_std.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_std.paragraph_format.space_after = Pt(20)
    r_std = p_std.add_run("STANDAR IEEE STD 830-1998")
    set_run_font(r_std, font_name="Calibri", size_pt=12, color=NAVY_PRIMARY, bold=True)

    # Judul Dokumen Utama
    p_title = doc.add_paragraph()
    p_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_title.paragraph_format.space_after = Pt(12)
    p_title.paragraph_format.line_spacing = 1.2
    r_title = p_title.add_run("SOFTWARE REQUIREMENTS SPECIFICATION (SRS)")
    set_run_font(r_title, font_name="Calibri", size_pt=24, color=NAVY_PRIMARY, bold=True)

    # Subjudul Proyek Web
    p_sub = doc.add_paragraph()
    p_sub.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_sub.paragraph_format.space_after = Pt(36)
    p_sub.paragraph_format.line_spacing = 1.25
    r_sub = p_sub.add_run(
        "SISTEM PENDUKUNG KEPUTUSAN PEMILIHAN LAPTOP GAMING\n"
        "UNTUK MAHASISWA MENGGUNAKAN METODE TOPSIS\n"
        "BERBASIS WEB"
    )
    set_run_font(r_sub, font_name="Calibri", size_pt=16, color=BLUE_SECONDARY, bold=True)

    # Garis Pembatas Aksen
    p_line = doc.add_paragraph()
    p_line.alignment = WD_ALIGN_PARAGRAPH.CENTER
    p_line.paragraph_format.space_after = Pt(36)
    r_line = p_line.add_run("━" * 45)
    set_run_font(r_line, font_name="Calibri", size_pt=14, color=BLUE_SECONDARY, bold=True)

    # Informasi Metadata Dokumen
    tbl_meta = [
        ["Identifikasi Sistem", "Sistem Pendukung Keputusan (SPK) Laptop Gaming Mahasiswa"],
        ["Pendekatan Algoritma", "Technique for Order Preference by Similarity to Ideal Solution (TOPSIS) & Rank Order Centroid (ROC)"],
        ["Integrasi Kecerdasan Buatan", "Google Gemini 1.5 Flash API & Contextual Narrative Fallback Engine"],
        ["Teknologi Platform", "PHP 8.1+, Laravel 10.x, Tailwind CSS, MySQL 8.0, DomPDF"],
        ["Nomor Versi Dokumen", "Versi 1.0 (Rilis Final Dokumen Spesifikasi)"],
        ["Status Persetujuan", "Disetujui untuk Implementasi & Pengujian Skripsi"],
        ["Target Pengguna", "Mahasiswa Perguruan Tinggi, Content Creator, Gamer, dan Administrator Sistem"],
        ["Tahun Akademik", "2026 / 2027"]
    ]
    create_styled_table(doc, ["Atribut Proyek", "Keterangan Spesifikasi"], tbl_meta, col_widths=[2.3, 4.2], header_bg=HEX_PRIMARY)

    doc.add_page_break()

def build_approval_and_meta(doc):
    add_heading_1(doc, "LEMBAR PENGESAHAN DAN PENGENDALIAN DOKUMEN")
    add_p(
        doc,
        "Dokumen Software Requirements Specification (SRS) ini telah disusun sesuai dengan kaidah rekayasa perangkat lunak standar IEEE Std 830-1998 untuk merepresentasikan seluruh kebutuhan fungsional, kebutuhan non-fungsional, arsitektur data, formulasi matematis, serta antarmuka sistem pada perangkat lunak Sistem Pendukung Keputusan Pemilihan Laptop Gaming untuk Mahasiswa."
    )

    tbl_approvers = [
        ["Penyusun (Developer)", "Tim Pengembang Sistem SPK", "Mahasiswa Pengembang", "September 2026", "DISETUJUI"],
        ["Reviewer Teknis", "Dosen Pembimbing I / Ahli SPK", "Dosen Program Studi", "September 2026", "DISETUJUI"],
        ["Reviewer Sistem", "Dosen Pembimbing II / Penguji", "Dosen Rekayasa Web", "September 2026", "DISETUJUI"],
        ["Stakeholder Pengguna", "Perwakilan Komunitas Mahasiswa", "User Acceptance Group", "September 2026", "DISETUJUI"]
    ]
    create_styled_table(doc, ["Peran", "Nama Personel", "Jabatan", "Tanggal", "Status"], tbl_approvers, col_widths=[1.5, 1.8, 1.5, 1.0, 1.0])

    add_heading_2(doc, "Riwayat Perubahan Dokumen (Document Revision History)")
    tbl_rev = [
        ["1.0-draft", "10 September 2026", "Penyusunan draf awal identifikasi 6 kriteria & formulasi ROC."],
        ["1.0-rc1", "18 September 2026", "Penambahan modul TOPSIS terpisah kondisi baru dan second."],
        ["1.0-rc2", "24 September 2026", "Integrasi narasi AI Gemini 1.5 Flash dan fitur ekspor PDF."],
        ["1.0", "29 September 2026", "Finalisasi seluruh kebutuhan fungsional & pengujian verifikasi."]
    ]
    create_styled_table(doc, ["Versi", "Tanggal Rilis", "Deskripsi Perubahan dan Pembaruan"], tbl_rev, col_widths=[1.0, 1.6, 4.0])

    add_heading_1(doc, "RINGKASAN EKSEKUTIF (EXECUTIVE SUMMARY)")
    add_p(
        doc,
        "Pesatnya perkembangan teknologi perangkat keras komputer jinjing (laptop) dan beragamnya penawaran di pasar komersial menimbulkan permasalahan dilema keputusan (decision dilemma) bagi kalangan mahasiswa. Mahasiswa membutuhkan laptop gaming yang tidak hanya mumpuni untuk menjalankan permainan video modern, melainkan juga memiliki keandalan komputasi tinggi untuk menyelesaikan tugas perkuliahan berat (seperti kompilasi pemrograman, simulasi data, render desain grafis 3D, dan pemodelan teknik), namun sering kali dibatasi oleh anggaran finansial yang ketat serta kebutuhan mobilitas tinggi antar ruang kelas perkuliahan."
    )
    add_p(
        doc,
        "Untuk menjawab tantangan tersebut, dibangun sebuah sistem berbasis web modern: SISTEM PENDUKUNG KEPUTUSAN PEMILIHAN LAPTOP GAMING UNTUK MAHASISWA MENGGUNAKAN METODE TOPSIS BERBASIS WEB. Sistem ini menggabungkan keunggulan metode Rank Order Centroid (ROC) dalam mengonversi peringkat preferensi subjektif mahasiswa menjadi pembobotan kriteria matematis yang objektif dan konsisten, dengan algoritma Technique for Order of Preference by Similarity to Ideal Solution (TOPSIS) yang mengevaluasi kedekatan geometris setiap laptop terhadap solusi ideal positif (laptop terbaik) dan solusi ideal negatif (laptop terburuk)."
    )
    add_p(
        doc,
        "Sistem ini dilengkapi dengan kemampuan analisis komparatif antara laptop kondisi baru (bergaransi distributor resmi) dan laptop kondisi second (bekas berkualitas tinggi), engine narasi cerdas berbasis Generative AI (Google Gemini 1.5 Flash API dengan fallback kontekstual), transparansi matriks kalkulasi matematis langkah-demi-langkah, pelaporan sertifikat rekomendasi berformat PDF, serta portal manajemen data laptop dan kriteria yang komprehensif untuk administrator."
    )

    add_callout(
        doc,
        "Dokumen SRS ini bertindak sebagai acuan formal tunggal (single source of truth) yang mengikat secara teknis bagi tim pengembang, penguji sistem, dosen pembimbing, serta pemangku kepentingan akademik.",
        title="STATUS KEABSAHAN DOKUMEN"
    )

    doc.add_page_break()

def build_chapter_1(doc):
    add_heading_1(doc, "BAB 1: PENDAHULUAN")
    
    add_heading_2(doc, "1.1 Tujuan Pembuatan Dokumen (Purpose)")
    add_p(
        doc,
        "Tujuan utama penyusunan dokumen Software Requirements Specification (SRS) ini adalah:"
    )
    add_bullet(doc, "Mendefinisikan secara menyeluruh dan terstruktur seluruh kebutuhan fungsional (functional requirements) serta kebutuhan non-fungsional (non-functional requirements) dari perangkat lunak Sistem Pendukung Keputusan Pemilihan Laptop Gaming untuk Mahasiswa berbasis web.", "1. ")
    add_bullet(doc, "Menjadi dokumen rujukan formal bagi perancang sistem, pengembang perangkat lunak, dan penguji perangkat lunak selama proses siklus hidup pengembangan sistem (SDLC).", "2. ")
    add_bullet(doc, "Memberikan pemahaman yang terstandarisasi dan transparan bagi dosen pembimbing, dosen penguji, serta pemangku kepentingan akademik mengenai ruang lingkup, arsitektur teknis, dan formulasi matematis algoritma ROC dan TOPSIS yang diimplementasikan pada perangkat lunak.", "3. ")
    add_bullet(doc, "Menyediakan dasar verifikasi dan validasi (acceptance criteria) pada tahap pengujian perangkat lunak melalui Matriks Ketertelusuran Kebutuhan (Requirements Traceability Matrix / RTM).", "4. ")

    add_heading_2(doc, "1.2 Ruang Lingkup Sistem (Scope)")
    add_p(
        doc,
        "Sistem yang dikembangkan merupakan aplikasi web Sistem Pendukung Keputusan (Decision Support System / DSS) multikriteria yang dirancang khusus untuk memfasilitasi mahasiswa dalam menentukan laptop gaming paling optimal sesuai kebutuhan akademik dan hiburan mereka. Ruang lingkup sistem mencakup batasan fungsionalitas sebagai berikut:"
    )
    add_bullet(doc, "Penyediaan modul autentikasi terpusat berbasis peran pengguna, yaitu Mahasiswa (User Biasa) dan Administrator Sistem.", "Autentikasi & Otorisasi: ")
    add_bullet(doc, "Pengumpulan preferensi mahasiswa melalui formulir kuisioner interaktif yang memuat batas rentang harga (budget min dan budget max), tujuan peruntukan komputasi, preferensi merek, kebutuhan portabilitas, dan mekanisme penyusunan peringkat (drag-and-rank) atas 6 kriteria utama.", "Pengambilan Preferensi Dinamis: ")
    add_bullet(doc, "Penerapan metode Rank Order Centroid (ROC) untuk menghitung bobot matematis secara dinamis berdasarkan urutan prioritas kriteria yang disusun oleh setiap mahasiswa secara personal pada setiap sesi konsultasi.", "Engine Pembobotan ROC: ")
    add_bullet(doc, "Penerapan metode TOPSIS untuk menghitung skor preferensi relatif (Vi) atas seluruh alternatif laptop yang memenuhi filter awal, mencakup normalisasi vektor, pembentukan matriks ternormalisasi terbobot, penentuan solusi ideal positif (A+) dan negatif (A-), serta perhitungan jarak pemisah Euclidean (D+ dan D-).", "Engine Rekomendasi TOPSIS: ")
    add_bullet(doc, "Sistem melakukan evaluasi dan penyajian hasil secara terpisah antara kategori kondisi Laptop Baru dan Laptop Second guna memberikan opsi alternatif yang adil dan realistis terhadap keterbatasan dana mahasiswa.", "Segmentasi Baru vs Second: ")
    add_bullet(doc, "Integrasi layanan Generative AI (Google Gemini 1.5 Flash API) yang diperkuat sistem penanganan kegagalan cerdas (contextual rule-based fallback) untuk memproduksi narasi alasan personalisasi mengapa laptop tertentu terpilih di peringkat teratas.", "Sistem Penjelasan Cerdas: ")
    add_bullet(doc, "Penyediaan transparansi tahapan hitungan matematis matriks TOPSIS, modul riwayat konsultasi lengkap dengan fitur kloning preferensi sesi sebelumnya, serta fasilitas ekspor berkas cetak rekomendasi resmi berformat PDF.", "Transparansi & Pelaporan: ")
    add_bullet(doc, "Penyediaan modul dashboard administrator terpadu untuk melakukan manajemen katalog laptop (CRUD dan Bulk Import CSV), pengelolaan bobot kriteria acuan, serta pemantauan dan audit aktivitas konsultasi pengguna.", "Portal Administrator: ")

    add_heading_2(doc, "1.3 Definisi, Akronim, dan Singkatan")
    add_p(
        doc,
        "Tabel 1.1 merangkum definisi istilah, akronim, serta singkatan teknis yang digunakan di seluruh isi dokumen ini guna mencegah ambiguitas penafsiran."
    )
    tbl_terms = [
        ["SRS", "Software Requirements Specification", "Spesifikasi formal kebutuhan perangkat lunak menurut standar IEEE Std 830-1998."],
        ["SPK / DSS", "Sistem Pendukung Keputusan / Decision Support System", "Sistem informasi berbasis komputer yang membantu pengambilan keputusan atas permasalahan semi-terstruktur atau tidak terstruktur."],
        ["TOPSIS", "Technique for Order Preference by Similarity to Ideal Solution", "Metode pengambilan keputusan multikriteria (MCDM) berdasarkan konsep bahwa alternatif terbaik harus memiliki jarak terdekat dari solusi ideal positif dan terjauh dari solusi ideal negatif."],
        ["ROC", "Rank Order Centroid", "Metode penentuan bobot kriteria berbasis pemeringkatan prioritas tanpa perlu perbandingan berpasangan yang rumit."],
        ["CPU", "Central Processing Unit", "Unit pemroses utama komputer yang menentukan kecepatan pengolahan instruksi logika."],
        ["GPU / VGA", "Graphics Processing Unit / Video Graphics Array", "Prosesor grafis khusus yang bertanggung jawab atas akselerasi pemrosesan visual dan 3D gaming."],
        ["RAM", "Random Access Memory", "Memori akses acak sementara berkecepatan tinggi penunjang multitasking komputasi."],
        ["SSD", "Solid State Drive", "Media penyimpanan data digital berbasis flash memory berkecepatan baca/tulis tinggi."],
        ["MVC", "Model - View - Controller", "Pola arsitektur perangkat lunak yang memisahkan data (Model), antarmuka (View), dan logika kontrol (Controller)."],
        ["ORM", "Object-Relational Mapping", "Teknik pemetaan basis data relasional ke objek kode (diimplementasikan via Laravel Eloquent)."],
        ["API", "Application Programming Interface", "Antarmuka penghubung antarlayanan perangkat lunak untuk pertukaran data."],
        ["LLM", "Large Language Model", "Model kecerdasan buatan pemrosesan bahasa alami skala besar (misal: Google Gemini 1.5 Flash)."],
        ["CSRF", "Cross-Site Request Forgery", "Bentuk eksploitasi keamanan web yang dicegah melalui penyisipan token kriptografi pada setiap form HTTP POST/PUT/DELETE."],
        ["RTM", "Requirements Traceability Matrix", "Tabel penelusuran hubungan antara kebutuhan sistem, use case, kode program, dan skenario pengujian."]
    ]
    create_styled_table(doc, ["Istilah / Akronim", "Kepanjangan", "Definisi Operasional"], tbl_terms, col_widths=[1.2, 2.1, 3.4])

    add_heading_2(doc, "1.4 Referensi Standar dan Pustaka Ilmiah")
    add_bullet(doc, "IEEE Std 830-1998, 'IEEE Recommended Practice for Software Requirements Specifications', IEEE Computer Society, 1998.", "1. ")
    add_bullet(doc, "Hwang, C.L. and Yoon, K., 'Multiple Attribute Decision Making: Methods and Applications', Springer-Verlag, New York, 1981.", "2. ")
    add_bullet(doc, "Edwards, W. and Barron, F.H., 'SMARTS and SMARTER: Improved Simple Methods for Multiattribute Utility Measurement', Organizational Behavior and Human Decision Processes, Vol. 60, pp. 306-325, 1994.", "3. ")
    add_bullet(doc, "Pressman, R.S. and Maxim, B.R., 'Software Engineering: A Practitioner's Approach', 9th Edition, McGraw-Hill, 2020.", "4. ")
    add_bullet(doc, "Dokumentasi Resmi Laravel Framework Versi 10.x, Laravel LLC, https://laravel.com/docs/10.x.", "5. ")
    add_bullet(doc, "Google AI for Developers, 'Gemini API Reference Documentation', Google Cloud Platform, 2024.", "6. ")

    add_heading_2(doc, "1.5 Gambaran Umum Dokumen (Document Overview)")
    add_p(
        doc,
        "Dokumen SRS ini diorganisasikan ke dalam 9 bab berurutan. Bab 1 memuat pendahuluan, tujuan, ruang lingkup, definisi istilah, dan referensi. Bab 2 memaparkan deskripsi umum sistem, arsitektur, karakteristik pengguna, dan batasan lingkungan. Bab 3 menguraikan kebutuhan antarmuka eksternal. Bab 4 mendokumentasikan secara rinci 20 kebutuhan fungsional sistem. Bab 5 menjelaskan formulasi matematis ROC dan TOPSIS serta studi kasus perhitungan numerik. Bab 6 menyajikan desain model sistem (Use Case, Activity, Sequence, ERD, dan Kamus Data). Bab 7 merinci kebutuhan non-fungsional. Bab 8 menyajikan Matriks Ketertelusuran Kebutuhan (RTM). Bab 9 memuat lampiran dan penutup."
    )

def build_chapter_2(doc):
    add_heading_1(doc, "BAB 2: DESKRIPSI KESELURUHAN SISTEM (OVERALL DESCRIPTION)")

    add_heading_2(doc, "2.1 Perspektif Produk & Konteks Sistem")
    add_p(
        doc,
        "Sistem Pendukung Keputusan Pemilihan Laptop Gaming untuk Mahasiswa merupakan produk perangkat lunak mandiri (self-contained web application) berbasis arsitektur Model-View-Controller (MVC) yang dibangun di atas ekosistem Laravel 10. Sistem ini bertindak sebagai jembatan cerdas antara kebutuhan komputasi akademik/hiburan mahasiswa dengan basis data spesifikasi teknis dan harga pasar laptop yang senantiasa diperbarui."
    )
    add_p(
        doc,
        "Sistem mengonsumsi data masukan berupa rentang finansial, preferensi merek, dan derajat kepentingan kriteria mahasiswa, memprosesnya melalui kombinasi algoritma pembobotan ROC dan pemeringkatan TOPSIS, serta berkomunikasi secara eksternal dengan Google Gemini Generative Language API untuk menghasilkan penjelasan naratif cerdas secara real-time sebelum menyajikan antarmuka visual responsif dan dokumen laporan PDF."
    )

    add_heading_2(doc, "2.2 Ringkasan Fungsi Utama Sistem (Product Functions)")
    add_p(
        doc,
        "Fungsi-fungsi utama sistem dibagi berdasarkan peran pengguna sebagai berikut:"
    )
    add_bullet(doc, "Registrasi akun baru mahasiswa, autentikasi login terenkripsi, pengelolaan profil, dan logout sesi yang aman.", "1. Manajemen Pengguna: ")
    add_bullet(doc, "Pengisian formulir multi-langkah yang mencakup penetapan rentang budget, tujuan peruntukan utama (Kuliah, Gaming, Editing/Desain, Pemrograman), pemilihan satu atau beberapa merek, pemilihan kondisi fisik, serta penyusunan urutan prioritas 6 kriteria acuan dengan fitur drag-and-rank.", "2. Pengambilan Preferensi Kuisioner: ")
    add_bullet(doc, "Penghitungan bobot kriteria secara real-time berdasarkan formula ROC sehingga bobot bersifat dinamis dan mencerminkan preferensi personal setiap mahasiswa secara presisi matematis.", "3. Engine Pembobotan ROC: ")
    add_bullet(doc, "Penghitungan komprehensif atas seluruh kandidat laptop meliputi normalisasi vektor, pembobotan matriks, perolehan nilai ideal positif/negatif, kalkulasi jarak Euclidean, dan penentuan nilai preferensi relatif (Vi).", "4. Engine Pemeringkatan TOPSIS: ")
    add_bullet(doc, "Penyajian kartu peringkat laptop terbaik (Top Recommendations), penyajian perbandingan terpisah antara kondisi baru dan second, tampilan skor TOPSIS dalam persentase, dan perincian spesifikasi teknis.", "5. Visualisasi Hasil Rekomendasi: ")
    add_bullet(doc, "Pembentukan narasi penjelasan mengapa laptop tersebut menjadi pilihan ideal sesuai dengan peruntukan dan prioritas utama mahasiswa, baik melalui pemanggilan API LLM Gemini 1.5 Flash maupun melalui generator sintesis kontekstual lokal.", "6. Generator Narasi Cerdas AI: ")
    add_bullet(doc, "Penyediaan tabel rincian matriks matematis (Matriks X, Ternormalisasi R, Ternormalisasi Terbobot Y, Solusi Ideal A+/A-, dan Jarak D+/D-) untuk menjamin asas transparansi akademik.", "7. Transparansi Algoritma: ")
    add_bullet(doc, "Pencatatan riwayat konsultasi ke basis data, penyediaan fitur kloning jawaban dari konsultasi lampau, dan pembuatan dokumen sertifikat hasil rekomendasi berformat PDF cetak resmi.", "8. Riwayat & Pelaporan PDF: ")
    add_bullet(doc, "Dashboard statistik (total pengguna, laptop baru vs second, sesi kuisioner), katalog data laptop lengkap (tambah, ubah, hapus), fitur import bulk via CSV, pengelolaan konfigurasi bobot acuan kriteria, dan pemantauan riwayat konsultasi seluruh mahasiswa.", "9. Portal Administrasi: ")

    add_heading_2(doc, "2.3 Karakteristik & Klasifikasi Pengguna")
    tbl_users = [
        ["Mahasiswa (User Biasa)", "Mahasiswa dari berbagai jurusan yang membutuhkan panduan rekomendasi objektif dalam membeli laptop gaming untuk keperluan kuliah, komputasi grafis/coding, dan gaming.", "Mengisi kuisioner preferensi, mengatur urutan kriteria, melihat hasil perangkingan TOPSIS, membaca analisis AI, melihat rincian matriks, mengunduh PDF, dan mengelola riwayat konsultasi mandiri."],
        ["Administrator Sistem", "Pengelola sistem informasi akademik atau laboratorium komputer yang bertanggung jawab atas integritas data katalog laptop dan parameter sistem.", "Mengakses dashboard metrik analitik, mengelola master data laptop (CRUD & Bulk CSV), mengonfigurasi bobot kriteria default, dan memantau riwayat konsultasi seluruh pengguna."]
    ]
    create_styled_table(doc, ["Klasifikasi Pengguna", "Karakteristik & Latar Belakang", "Hak Akses & Tanggung Jawab Operasional"], tbl_users, col_widths=[1.5, 2.2, 3.0])

    add_heading_2(doc, "2.4 Lingkungan Operasional (Operating Environment)")
    add_p(
        doc,
        "Perangkat lunak ini dirancang untuk beroperasi pada lingkungan infrastruktur sebagai berikut:"
    )
    add_bullet(doc, "Sistem Operasi: Linux Ubuntu Server 20.04/22.04 LTS / Windows Server 2019/2022; Web Server: Nginx 1.18+ atau Apache 2.4+; Runtime: PHP 8.1 / 8.2 (dengan ekstensi pdo_mysql, mbstring, openssl, gd, curl, xml, zip); Database Server: MySQL 8.0+ atau MariaDB 10.5+.", "Lingkungan Sisi Server: ")
    add_bullet(doc, "Perangkat: PC Desktop, Laptop, Tablet, maupun Smartphone; Peramban Web (Browser): Google Chrome 90+, Mozilla Firefox 88+, Microsoft Edge 90+, Safari 14+ dengan JavaScript dan CSS3 aktif.", "Lingkungan Sisi Klien (Client): ")
    add_bullet(doc, "Layanan Eksternal: Google Generative AI (Gemini REST API) melalui protokol HTTPS port 443.", "Layanan Pihak Ketiga: ")

    add_heading_2(doc, "2.5 Batasan Desain dan Implementasi (Design Constraints)")
    add_bullet(doc, "Sistem dibangun menggunakan bahasa pemrograman PHP versi 8.1+ dengan memanfaatkan kerangka kerja Laravel versi 10.x yang menerapkan pola arsitektur MVC dan Service-Repository Pattern.", "1. Kerangka Kerja Perangkat Lunak: ")
    add_bullet(doc, "Basis data relasional menggunakan MySQL dengan penegakan integritas referensial (Foreign Key Constraints dengan aksi ON DELETE CASCADE).", "2. Manajemen Basis Data: ")
    add_bullet(doc, "Antarmuka pengguna dibangun menggunakan Tailwind CSS dengan pendekatan Mobile-First Responsiveness, memastikan tampilan tetap proporsional pada resolusi layar mulai 320px hingga 4K.", "3. Antarmuka Pengguna: ")
    add_bullet(doc, "Ekspor dokumen cetak menggunakan pustaka barryvdh/laravel-dompdf yang merender dokumen HTML/CSS menjadi berkas portabel PDF secara server-side.", "4. Ekspor Berkas: ")
    add_bullet(doc, "Komputasi TOPSIS dan ROC dieksekusi secara lokal (on-premise server) pada Service Layer Laravel untuk menjamin kecepatan kalkulasi di bawah 1 detik tanpa bergantung pada API pihak ketiga.", "5. Kinerja Algoritma: ")
    add_bullet(doc, "Panggilan ke Google Gemini API memiliki batasan timeout maksimal 5 detik; apabila koneksi internet mengalami latensi tinggi atau kuota API tercapai, sistem wajib mengaktifkan fallback engine secara mulus tanpa memunculkan galat (error) kepada pengguna.", "6. Ketergantungan AI: ")

    add_heading_2(doc, "2.6 Asumsi dan Ketergantungan Sistem")
    add_bullet(doc, "Data spesifikasi laptop (CPU, GPU, RAM, SSD, baterai, berat, harga) yang tersimpan di katalog diasumsikan valid dan merepresentasikan kondisi pasar terkini.", "1. Validitas Katalog: ")
    add_bullet(doc, "Pengguna memiliki konektivitas internet stabil saat mengakses sistem dan mengunduh berkas laporan rekomendasi PDF.", "2. Konektivitas Jaringan: ")
    add_bullet(doc, "Setiap sesi kuisioner diasumsikan minimal menemukan sedikitnya satu alternatif laptop yang berada di dalam rentang anggaran; jika tidak ditemukan alternatif yang cocok, sistem memiliki mekanisme pemberitahuan yang informatif.", "3. Ketersediaan Alternatif: ")

def build_chapter_3(doc):
    add_heading_1(doc, "BAB 3: KEBUTUHAN ANTARMUKA EKSTERNAL (EXTERNAL INTERFACE REQUIREMENTS)")

    add_heading_2(doc, "3.1 Antarmuka Pengguna (User Interface Requirements)")
    add_p(
        doc,
        "Antarmuka pengguna (UI) dirancang dengan memprioritaskan User Experience (UX) modern berprinsip Clean, Intuitive, and Responsive. Rincian antarmuka utama meliputi:"
    )
    add_bullet(doc, "Menampilkan landing page profesional dengan pengantar sistem SPK, fitur unggulan TOPSIS & ROC, integrasi AI, serta tombol Call to Action (CTA) menuju halaman registrasi dan login.", "1. Halaman Beranda (Welcome Screen): ")
    add_bullet(doc, "Formulir pendaftaran mahasiswa baru (Nama, Email, Nomor Telepon, Kata Sandi, Konfirmasi Sandi) serta form login terproteksi CSRF dengan validasi error real-time.", "2. Halaman Autentikasi (Login & Register): ")
    add_bullet(doc, "Menampilkan statistik konsultasi mahasiswa, kartu pintas untuk memulai kuisioner baru, riwayat rekomendasi terakhir, serta panduan pengisian.", "3. Halaman Dashboard Mahasiswa: ")
    add_bullet(doc, "Antarmuka formulir interaktif dengan slider/input rentang harga, seleksi peruntukan berbasis radio buttons, interaksi drag-and-rank 6 kartu kriteria menggunakan JavaScript, seleksi checkbox multi-merek, serta pemilihan kondisi fisik laptop.", "4. Wizard Kuisioner Rekomendasi: ")
    add_bullet(doc, "Menampilkan kartu sorotan laptop terbaik (Peringkat 1), tab pemisah rekomendasi Laptop Baru vs Laptop Second, kartu spesifikasi detail dengan badge performa, kotak narasi penjelasan AI berkas biru elegan, tombol unduh PDF, serta akordion tabel transparansi matriks kalkulasi matematis TOPSIS.", "5. Halaman Hasil Rekomendasi: ")
    add_bullet(doc, "Menampilkan daftar seluruh sesi konsultasi yang pernah dilakukan mahasiswa dalam bentuk tabel ringkas, dilengkapi tanggal, peruntukan, budget, tombol lihat detail, tombol gunakan preferensi ini (kloning), serta tombol hapus riwayat.", "6. Halaman Riwayat Konsultasi: ")
    add_bullet(doc, "Menampilkan 3 tab kontrol utama: Tab Katalog Laptop (pencarian, filter kondisi, pagination, modal tambah manual, modal edit, aksi hapus, dan modal impor CSV), Tab Kriteria (tabel 6 kriteria, tipe benefit/cost, dan form update bobot default), serta Tab Pengguna (daftar mahasiswa terdaftar, pencarian, dan audit riwayat sesi konsultasi).", "7. Dashboard Administrator Terpadu: ")

    add_heading_2(doc, "3.2 Antarmuka Perangkat Keras (Hardware Interfaces)")
    tbl_hw = [
        ["Komponen", "Spesifikasi Minimum Klien (Client)", "Spesifikasi Minimum Peladen (Server)"],
        ["Prosesor (CPU)", "Dual-Core 1.5 GHz atau lebih tinggi (x86-64 / ARM)", "Quad-Core 2.0 GHz (x86-64 Architecture)"],
        ["Memori RAM", "Minimal 2 GB RAM (Direkomendasikan 4 GB)", "Minimal 4 GB RAM (Direkomendasikan 8 GB)"],
        ["Ruang Penyimpanan", "Tersedia 500 MB untuk penyimpanan peramban/cache", "Minimal 20 GB SSD Storage berkecepatan tinggi"],
        ["Resolusi Layar", "Minimal 360 x 640 piksel (Smartphone) s.d. 1920x1080 (PC)", "Tidak memerlukan antarmuka tampilan (Headless Server)"],
        ["Koneksi Jaringan", "Koneksi internet dengan bandwidth minimal 256 Kbps", "Koneksi jaringan dedicated dengan bandwidth minimal 10 Mbps"]
    ]
    create_styled_table(doc, ["Komponen Perangkat Keras", "Kebutuhan Minimum Klien", "Kebutuhan Minimum Peladen"], tbl_hw, col_widths=[1.5, 2.6, 2.6])

    add_heading_2(doc, "3.3 Antarmuka Perangkat Lunak (Software Interfaces)")
    tbl_sw = [
        ["Komponen Perangkat Lunak", "Spesifikasi / Pustaka yang Digunakan", "Peran & Fungsi dalam Sistem"],
        ["Sistem Operasi Server", "Linux Ubuntu 22.04 LTS / Windows Server", "Host lingkungan eksekusi aplikasi web"],
        ["Web Server Daemon", "Nginx 1.20+ / Apache HTTP Server 2.4+", "Reverse proxy, static file server, SSL termination"],
        ["Bahasa Pemrograman", "PHP versi 8.1.x atau 8.2.x", "Bahasa skrip inti logika backend dan kalkulasi matematis"],
        ["Web Framework", "Laravel Framework Versi 10.x", "Arsitektur MVC, ORM Eloquent, Routing, CSRF, Session"],
        ["Sistem Manajemen Basis Data", "MySQL Versi 8.0.x", "Penyimpanan data relasional transaksi dan master data"],
        ["Engine Generator PDF", "barryvdh/laravel-dompdf (DomPDF 3.x)", "Rendering antarmuka HTML/CSS menjadi dokumen PDF"],
        ["HTTP Client Library", "Guzzle HTTP Client versi 7.x", "Pengiriman permintaan RESTful API ke endpoint eksternal"],
        ["External AI Service", "Google Gemini 1.5 Flash API", "Layanan Generative AI pembuat teks narasi rekomendasi"]
    ]
    create_styled_table(doc, ["Komponen Perangkat Lunak", "Teknologi Terpasang", "Peran dan Fungsi"], tbl_sw, col_widths=[1.6, 2.2, 2.9])

    add_heading_2(doc, "3.4 Antarmuka Komunikasi (Communication Interfaces)")
    add_bullet(doc, "Aplikasi web berkomunikasi dengan peramban pengguna menggunakan protokol aman HTTPS (Hypertext Transfer Protocol Secure) melalui port 443 dengan enkripsi TLS 1.3.", "1. Protokol Web: ")
    add_bullet(doc, "Komunikasi backend dengan basis data MySQL menggunakan protokol TCP/IP internal port 3306 dengan autentikasi berbasis kredensial terlindungi di berkas konfigurasi .env.", "2. Protokol Basis Data: ")
    add_bullet(doc, "Komunikasi integrasi dengan Google Gemini API menggunakan protokol RESTful HTTPS POST dengan pengiriman payload berformat JSON dan otentikasi API Key terenkripsi.", "3. Protokol Integrasi AI: ")

    doc.add_page_break()

print("SRS Part 1 module compiled successfully.")

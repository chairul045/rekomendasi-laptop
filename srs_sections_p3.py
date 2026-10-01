# -*- coding: utf-8 -*-
"""
SRS Bagian 3: Bab 6 (Desain Model & Basis Data), Bab 7 (Kebutuhan Non-Fungsional),
Bab 8 (Matriks Ketertelusuran RTM), dan Bab 9 (Lampiran & Penutup)
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

def build_chapter_6(doc):
    add_heading_1(doc, "BAB 6: DESAIN MODEL DAN BASIS DATA SISTEM")

    add_heading_2(doc, "6.1 Pemodelan Use Case Diagram & Deskripsi Skenario")
    add_p(
        doc,
        "Use Case Diagram menggambarkan interaksi fungsional antara aktor eksternal (Mahasiswa dan Administrator Sistem) dengan batasan sistem perangkat lunak. Rincian skenario Use Case terangkum dalam Tabel 6.1:"
    )

    tbl_uc = [
        ["UC-01", "Registrasi Akun", "Mahasiswa", "Mendaftarkan identitas akun mahasiswa baru ke sistem."],
        ["UC-02", "Login & Otentikasi", "Mahasiswa & Admin", "Melakukan autentikasi kredensial dan validasi multi-peran."],
        ["UC-03", "Mengisi Kuisioner", "Mahasiswa", "Memasukkan rentang budget, peruntukan, kondisi, dan merek pilihan."],
        ["UC-04", "Menyusun Urutan Kriteria", "Mahasiswa", "Mengatur prioritas peringkat 6 kriteria via drag-and-rank."],
        ["UC-05", "Melihat Rekomendasi", "Mahasiswa", "Melihat daftar peringkat laptop, skor TOPSIS, dan badge spesifikasi."],
        ["UC-06", "Membaca Narasi AI", "Mahasiswa", "Membaca penjelasan konteks personalisasi yang dihasilkan Generative AI."],
        ["UC-07", "Inspeksi Matriks TOPSIS", "Mahasiswa & Admin", "Membuka akordion transparansi tahapan perhitungan numerik."],
        ["UC-08", "Melihat Riwayat Konsultasi", "Mahasiswa", "Melihat daftar riwayat sesi konsultasi lampau dan re-evaluasi."],
        ["UC-09", "Kloning Preferensi", "Mahasiswa", "Menyalin jawaban kuisioner lampau ke form kuisioner baru."],
        ["UC-10", "Ekspor Laporan PDF", "Mahasiswa & Admin", "Mengunduh sertifikat rekomendasi resmi dalam format PDF."],
        ["UC-11", "Monitoring Dashboard", "Administrator", "Melihat statistik total pengguna, stok laptop, dan total konsultasi."],
        ["UC-12", "Kelola Katalog Laptop", "Administrator", "Melakukan aksi Tambah, Edit, Hapus (CRUD) katalog laptop."],
        ["UC-13", "Impor Bulk CSV Laptop", "Administrator", "Mengunggah berkas data CSV untuk penambahan banyak laptop sekaligus."],
        ["UC-14", "Kelola Bobot Kriteria", "Administrator", "Memperbarui nilai bobot acuan sistem dan deskripsi kriteria."],
        ["UC-15", "Audit Pengguna & Riwayat", "Administrator", "Melihat data mahasiswa terdaftar dan mengaudit riwayat konsultasinya."]
    ]
    create_styled_table(doc, ["ID Use Case", "Nama Use Case", "Aktor Utama", "Deskripsi Fungsional"], tbl_uc, col_widths=[1.0, 1.8, 1.4, 2.3])

    add_heading_2(doc, "6.2 Pemodelan Alur Aktivitas (Activity Diagram)")
    add_p(
        doc,
        "Alur aktivitas merepresentasikan logika kerja berurutan dari sisi pengguna dan sistem. Sistem memiliki 2 alur aktivitas utama:"
    )
    add_heading_3(doc, "Alur Aktivitas 1: Proses Pengisian Kuisioner hingga Penerbitan Rekomendasi")
    add_bullet(doc, "Mahasiswa mengakses halaman formulir kuisioner (/questionnaire).", "1. ")
    add_bullet(doc, "Mahasiswa menentukan budget minimum, budget maksimum, tujuan peruntukan, dan frekuensi membawa laptop.", "2. ")
    add_bullet(doc, "Mahasiswa menyusun urutan 6 kartu kriteria dari posisi 1 (Paling Penting) hingga 6 (Paling Kurang Penting).", "3. ")
    add_bullet(doc, "Mahasiswa memilih preferensi merek dan kondisi laptop (Baru, Second, atau Keduanya), lalu menekan tombol 'Dapatkan Rekomendasi'.", "4. ")
    add_bullet(doc, "Sistem memvalidasi formulir; jika lolos, sistem menyimpan sesi ke tabel 'kuisioner_jawaban'.", "5. ")
    add_bullet(doc, "Sistem memanggil 'RocService' untuk menghitung bobot kriteria matematis dan menyimpannya ke 'bobot_kriteria_hasil'.", "6. ")
    add_bullet(doc, "Sistem memanggil 'TopsisService' untuk memfilter alternatif sesuai budget/merek, menghitung matriks normalisasi, jarak Euclidean, dan skor preferensi Vi, lalu menyimpannya ke 'hasil_topsis'.", "7. ")
    add_bullet(doc, "Sistem memanggil 'AiExplanationService' untuk menghasilkan narasi rekomendasi personalisasi Top-5 laptop dan mencatatnya ke 'penjelasan_ai'.", "8. ")
    add_bullet(doc, "Sistem menampilkan halaman hasil rekomendasi interaktif (/recommendation/{jawaban}) yang siap diekspor ke format PDF.", "9. ")

    add_heading_3(doc, "Alur Aktivitas 2: Manajemen Katalog Laptop oleh Administrator")
    add_bullet(doc, "Administrator login dan diarahkan ke rute proteksi '/admin/dashboard'.", "1. ")
    add_bullet(doc, "Administrator memilih tab 'Katalog Laptop'.", "2. ")
    add_bullet(doc, "Administrator dapat melakukan pencarian data, penyaringan kondisi, atau membuka modal 'Tambah Laptop'.", "3. ")
    add_bullet(doc, "Administrator mengisi formulir spesifikasi teknis dan menekan 'Simpan'.", "4. ")
    add_bullet(doc, "Sistem memvalidasi masukan, melakukan enkapsulasi Eloquent ORM, dan memperbarui basis data secara instan.", "5. ")

    add_heading_2(doc, "6.3 Pemodelan Diagram Sekuens (Sequence Diagram)")
    add_p(
        doc,
        "Diagram sekuens menggambarkan interaksi pertukaran pesan dinamis antarkomponen sistem saat eksekusi rekomendasi SPK:"
    )
    add_bullet(doc, "Mahasiswa -> Browser: Klik submit tombol formulir kuisioner.", "1. UI Event: ")
    add_bullet(doc, "Browser -> QuestionnaireController: Mengirimkan HTTP POST request ke rute '/questionnaire' beserta payload data preferensi.", "2. HTTP Post: ")
    add_bullet(doc, "QuestionnaireController -> KuisionerJawaban Model: Membuat record sesi baru dan memperoleh ID Sesi.", "3. Simpan Sesi: ")
    add_bullet(doc, "QuestionnaireController -> RocService: Mengirimkan array ranking kriteria; RocService mengembalikan matriks bobot W.", "4. Hitung Bobot: ")
    add_bullet(doc, "QuestionnaireController -> BobotKriteriaHasil Model: Menyimpan bobot ROC untuk tiap kriteria.", "5. Simpan Bobot: ")
    add_bullet(doc, "QuestionnaireController -> TopsisService: Mengirimkan kumpulan objek laptop terfilter dan bobot ROC.", "6. Eksekusi TOPSIS: ")
    add_bullet(doc, "TopsisService -> QuestionnaireController: Mengembalikan daftar peringkat alternatif beserta matriks X, R, Y, A+, A-, D+, D-, dan nilai Vi.", "7. Return TOPSIS: ")
    add_bullet(doc, "QuestionnaireController -> HasilTopsis Model: Menyimpan hasil perangkingan laptop.", "8. Simpan Hasil: ")
    add_bullet(doc, "QuestionnaireController -> AiExplanationService: Mengirimkan data Top-N laptop untuk dibuatkan narasi.", "9. Generate AI: ")
    add_bullet(doc, "AiExplanationService -> Google Gemini API / Fallback: Melakukan sintesis narasi rekomendasi.", "10. AI Request: ")
    add_bullet(doc, "AiExplanationService -> PenjelasanAi Model: Menyimpan hasil narasi ke basis data cache.", "11. Cache Narasi: ")
    add_bullet(doc, "QuestionnaireController -> Browser: Redirect ke halaman hasil rekomendasi (/recommendation/{id}).", "12. Render View: ")

    add_heading_2(doc, "6.4 Perancangan Basis Data (Entity Relationship Diagram - ERD)")
    add_p(
        doc,
        "Struktur data relasional dibangun dengan penegakan integritas referensial yang kuat (Foreign Keys dengan aksi ON DELETE CASCADE). Hubungan kardinalitas antar entitas adalah sebagai berikut:"
    )
    add_bullet(doc, "Entitas 'users' memiliki relasi One-to-Many terhadap 'kuisioner_jawaban' (1 pengguna dapat memiliki banyak riwayat sesi konsultasi).", "1. users -> kuisioner_jawaban: ")
    add_bullet(doc, "Entitas 'kuisioner_jawaban' memiliki relasi One-to-Many terhadap 'bobot_kriteria_hasil' (1 sesi menghasilkan 6 rekaman bobot kriteria).", "2. kuisioner_jawaban -> bobot_kriteria_hasil: ")
    add_bullet(doc, "Entitas 'kriteria' memiliki relasi One-to-Many terhadap 'bobot_kriteria_hasil' (1 kriteria menjadi acuan bobot pada banyak sesi).", "3. kriteria -> bobot_kriteria_hasil: ")
    add_bullet(doc, "Entitas 'kuisioner_jawaban' memiliki relasi One-to-Many terhadap 'hasil_topsis' (1 sesi menghasilkan banyak baris perangkingan alternatif laptop).", "4. kuisioner_jawaban -> hasil_topsis: ")
    add_bullet(doc, "Entitas 'laptops' memiliki relasi One-to-Many terhadap 'hasil_topsis' (1 laptop dapat dievaluasi pada banyak sesi konsultasi).", "5. laptops -> hasil_topsis: ")
    add_bullet(doc, "Entitas 'kuisioner_jawaban' memiliki relasi One-to-Many terhadap 'penjelasan_ai' (1 sesi memiliki narasi penjelasan AI untuk top-N laptop).", "6. kuisioner_jawaban -> penjelasan_ai: ")
    add_bullet(doc, "Entitas 'laptops' memiliki relasi One-to-Many terhadap 'penjelasan_ai' (1 laptop dapat memiliki narasi penjelasan pada sesi terkait).", "7. laptops -> penjelasan_ai: ")

    add_heading_2(doc, "6.5 Kamus Data (Data Dictionary) Terperinci 7 Tabel")

    # Tabel 1: users
    add_heading_3(doc, "Tabel 1: users (Penyimpanan Akun Pengguna & Hak Akses)")
    tbl_users_dict = [
        ["id", "BIGINT UNSIGNED", "PRIMARY KEY, AUTO_INCREMENT", "Identifikator unik pengguna."],
        ["name", "VARCHAR(255)", "NOT NULL", "Nama lengkap pengguna / mahasiswa."],
        ["email", "VARCHAR(255)", "UNIQUE, NOT NULL", "Alamat email unik sebagai username otentikasi."],
        ["phone", "VARCHAR(30)", "NULLABLE", "Nomor telepon / kontak WhatsApp mahasiswa."],
        ["email_verified_at", "TIMESTAMP", "NULLABLE", "Waktu verifikasi email."],
        ["password", "VARCHAR(255)", "NOT NULL", "Kata sandi akun yang dienkripsi menggunakan Bcrypt."],
        ["role", "VARCHAR(50)", "DEFAULT 'mahasiswa'", "Hak akses pengguna ('mahasiswa' atau 'admin')."],
        ["remember_token", "VARCHAR(100)", "NULLABLE", "Token sesi persistensi 'remember me'."],
        ["created_at", "TIMESTAMP", "NULLABLE", "Waktu pembuatan rekaman."],
        ["updated_at", "TIMESTAMP", "NULLABLE", "Waktu pembaruan terakhir rekaman."]
    ]
    create_styled_table(doc, ["Nama Kolom", "Tipe Data", "Atribut / Kunci", "Keterangan Fungsi"], tbl_users_dict, col_widths=[1.5, 1.5, 1.7, 1.8])

    # Tabel 2: laptops
    add_heading_3(doc, "Tabel 2: laptops (Master Katalog Data Laptop)")
    tbl_laptops_dict = [
        ["id", "BIGINT UNSIGNED", "PRIMARY KEY, AUTO_INCREMENT", "Identifikator unik laptop."],
        ["name", "VARCHAR(255)", "NOT NULL", "Nama model lengkap laptop gaming."],
        ["brand", "VARCHAR(100)", "NOT NULL", "Merek produsen laptop (ASUS, Lenovo, Acer, HP, MSI, dll)."],
        ["price", "BIGINT", "NOT NULL", "Harga laptop dalam satuan Rupiah (C1 - Cost)."],
        ["performa_komposit", "INT", "NOT NULL", "Skor performa gabungan CPU & GPU 1-100 (C2 - Benefit)."],
        ["ram_gb", "INT", "NOT NULL", "Kapasitas RAM dalam satuan GB (C3 - Benefit)."],
        ["storage_gb", "INT", "NOT NULL", "Kapasitas penyimpanan SSD dalam satuan GB (C4 - Benefit)."],
        ["battery_hours", "DECIMAL(4,1)", "NOT NULL", "Ketahanan daya baterai dalam jam (C5 - Benefit)."],
        ["weight_kg", "DECIMAL(4,2)", "NOT NULL", "Bobot fisik laptop dalam satuan Kg (C6 - Cost)."],
        ["condition", "ENUM('baru','second')", "DEFAULT 'baru'", "Kondisi fisik unit laptop."],
        ["display_size", "DECIMAL(3,1)", "NULLABLE", "Ukuran bentang layar diagonal dalam satuan inci."],
        ["processor_score", "DECIMAL(5,2)", "NULLABLE", "Skor benchmark mentah prosesor CPU."],
        ["vga_score", "DECIMAL(5,2)", "NULLABLE", "Skor benchmark mentah kartu grafis GPU."],
        ["category", "VARCHAR(100)", "NULLABLE", "Kategori kelas laptop (Gaming, Creator, Ultrabook)."],
        ["image", "VARCHAR(255)", "NULLABLE", "URL atau path berkas gambar ilustrasi unit laptop."],
        ["created_at / updated_at", "TIMESTAMP", "NULLABLE", "Timestamp pencatatan sistem."]
    ]
    create_styled_table(doc, ["Nama Kolom", "Tipe Data", "Atribut / Kunci", "Keterangan Fungsi"], tbl_laptops_dict, col_widths=[1.5, 1.5, 1.7, 1.8])

    # Tabel 3: kriteria
    add_heading_3(doc, "Tabel 3: kriteria (Master Data 6 Kriteria Acuan SPK)")
    tbl_kriteria_dict = [
        ["id", "BIGINT UNSIGNED", "PRIMARY KEY, AUTO_INCREMENT", "Identifikator unik kriteria."],
        ["kode", "VARCHAR(10)", "UNIQUE, NOT NULL", "Kode acuan kriteria (C1, C2, C3, C4, C5, C6)."],
        ["nama", "VARCHAR(100)", "NOT NULL", "Nama kriteria (Harga, Performa, RAM, Storage, Baterai, Portabilitas)."],
        ["tipe", "ENUM('benefit','cost')", "DEFAULT 'benefit'", "Sifat atribut kriteria pada algoritma TOPSIS."],
        ["bobot", "DECIMAL(6,4)", "DEFAULT 0.1667", "Nilai bobot acuan sistem yang dapat diubah admin."],
        ["deskripsi", "TEXT", "NULLABLE", "Penjelasan operasional fungsi penilaian kriteria."],
        ["created_at / updated_at", "TIMESTAMP", "NULLABLE", "Timestamp pencatatan sistem."]
    ]
    create_styled_table(doc, ["Nama Kolom", "Tipe Data", "Atribut / Kunci", "Keterangan Fungsi"], tbl_kriteria_dict, col_widths=[1.5, 1.5, 1.7, 1.8])

    # Tabel 4: kuisioner_jawaban
    add_heading_3(doc, "Tabel 4: kuisioner_jawaban (Penyimpanan Preferensi Konsultasi Sesi)")
    tbl_jawaban_dict = [
        ["id", "BIGINT UNSIGNED", "PRIMARY KEY, AUTO_INCREMENT", "Identifikator unik sesi kuisioner."],
        ["user_id", "BIGINT UNSIGNED", "FOREIGN KEY (users.id) CASCADE", "ID pengguna mahasiswa pemilik sesi."],
        ["budget_min", "BIGINT", "DEFAULT 0", "Batas bawah anggaran finansial mahasiswa."],
        ["budget_max", "BIGINT", "DEFAULT 50000000", "Batas atas anggaran finansial mahasiswa."],
        ["peruntukan", "VARCHAR(100)", "DEFAULT 'Kuliah'", "Tujuan peruntukan utama laptop."],
        ["ranking_kriteria", "JSON", "NOT NULL", "Array urutan prioritas kriteria hasil drag-and-rank."],
        ["merek_pilihan", "JSON", "NOT NULL", "Array daftar merek laptop yang diinginkan."],
        ["kondisi_pilihan", "ENUM('baru','second','keduanya')", "DEFAULT 'keduanya'", "Kondisi unit yang dianalisis."],
        ["frekuensi_membawa", "VARCHAR(100)", "NULLABLE", "Frekuensi mobilitas membawa laptop harian."],
        ["judul", "VARCHAR(100)", "NULLABLE", "Label penamaan judul sesi konsultasi."],
        ["tanggal_pengisian", "DATETIME", "NULLABLE", "Waktu spesifik pengisian kuisioner oleh mahasiswa."],
        ["created_at / updated_at", "TIMESTAMP", "NULLABLE", "Timestamp pencatatan sistem."]
    ]
    create_styled_table(doc, ["Nama Kolom", "Tipe Data", "Atribut / Kunci", "Keterangan Fungsi"], tbl_jawaban_dict, col_widths=[1.5, 1.5, 1.7, 1.8])

    # Tabel 5: bobot_kriteria_hasil
    add_heading_3(doc, "Tabel 5: bobot_kriteria_hasil (Snapshot Pembobotan ROC per Sesi)")
    tbl_bobot_dict = [
        ["id", "BIGINT UNSIGNED", "PRIMARY KEY, AUTO_INCREMENT", "Identifikator unik rekaman bobot."],
        ["kuisioner_jawaban_id", "BIGINT UNSIGNED", "FOREIGN KEY (kuisioner_jawaban.id) CASCADE", "ID sesi kuisioner terkait."],
        ["kriteria_id", "BIGINT UNSIGNED", "FOREIGN KEY (kriteria.id) CASCADE", "ID kriteria acuan."],
        ["prioritas", "INT", "NOT NULL", "Nomor urutan peringkat prioritas (1 s.d. 6)."],
        ["bobot", "DECIMAL(6,4)", "NOT NULL", "Nilai bobot numerik ROC hasil kalkulasi (0.0000-1.0000)."],
        ["created_at / updated_at", "TIMESTAMP", "NULLABLE", "Timestamp pencatatan sistem."]
    ]
    create_styled_table(doc, ["Nama Kolom", "Tipe Data", "Atribut / Kunci", "Keterangan Fungsi"], tbl_bobot_dict, col_widths=[1.5, 1.5, 1.7, 1.8])

    # Tabel 6: hasil_topsis
    add_heading_3(doc, "Tabel 6: hasil_topsis (Peringkat Skor Preferensi Rekomendasi TOPSIS)")
    tbl_hasil_dict = [
        ["id", "BIGINT UNSIGNED", "PRIMARY KEY, AUTO_INCREMENT", "Identifikator unik hasil perangkingan."],
        ["kuisioner_jawaban_id", "BIGINT UNSIGNED", "FOREIGN KEY (kuisioner_jawaban.id) CASCADE", "ID sesi kuisioner terkait."],
        ["laptop_id", "BIGINT UNSIGNED", "FOREIGN KEY (laptops.id) CASCADE", "ID alternatif laptop."],
        ["kondisi", "ENUM('baru','second')", "NOT NULL", "Kategori kondisi evaluasi laptop."],
        ["nilai_v", "DECIMAL(8,6)", "NOT NULL", "Nilai preferensi kedekatan relatif TOPSIS (Vi)."],
        ["peringkat", "INT", "NOT NULL", "Urutan peringkat rekomendasi (1 s.d. N)."],
        ["created_at / updated_at", "TIMESTAMP", "NULLABLE", "Timestamp pencatatan sistem."]
    ]
    create_styled_table(doc, ["Nama Kolom", "Tipe Data", "Atribut / Kunci", "Keterangan Fungsi"], tbl_hasil_dict, col_widths=[1.5, 1.5, 1.7, 1.8])

    # Tabel 7: penjelasan_ai
    add_heading_3(doc, "Tabel 7: penjelasan_ai (Cache Narasi Cerdas AI Rekomendasi Top-N)")
    tbl_ai_dict = [
        ["id", "BIGINT UNSIGNED", "PRIMARY KEY, AUTO_INCREMENT", "Identifikator unik narasi AI."],
        ["kuisioner_jawaban_id", "BIGINT UNSIGNED", "FOREIGN KEY (kuisioner_jawaban.id) CASCADE", "ID sesi kuisioner terkait."],
        ["laptop_id", "BIGINT UNSIGNED", "FOREIGN KEY (laptops.id) CASCADE", "ID laptop yang dianalisis."],
        ["penjelasan", "TEXT", "NOT NULL", "Teks naratif alasan personalisasi kesesuaian laptop."],
        ["created_at / updated_at", "TIMESTAMP", "NULLABLE", "Timestamp pencatatan sistem."]
    ]
    create_styled_table(doc, ["Nama Kolom", "Tipe Data", "Atribut / Kunci", "Keterangan Fungsi"], tbl_ai_dict, col_widths=[1.5, 1.5, 1.7, 1.8])

    doc.add_page_break()

def build_chapter_7(doc):
    add_heading_1(doc, "BAB 7: KEBUTUHAN NON-FUNGSIONAL (NON-FUNCTIONAL REQUIREMENTS)")

    tbl_nfr = [
        ["NFR-01", "Kinerja & Waktu Respons Komputasi (Performance)", "Waktu eksekusi algoritma ROC dan TOPSIS wajib diselesaikan dalam waktu kurang dari 500 milidetik (< 0.5 detik) untuk mengevaluasi hingga 200 alternatif laptop. Waktu muat awal halaman sistem maksimal 2.0 detik pada jaringan pita lebar."],
        ["NFR-02", "Penanganan Latensi Layanan AI (AI Fallback Response)", "Permintaan ke Google Gemini API dibatasi dengan batas waktu (timeout) 5 detik. Apabila API tidak merespons dalam 5 detik, generator kontekstual lokal wajib langsung mengambil alih dalam waktu < 50 milidetik sehingga pengguna tidak mengalami penundaan antarmuka."],
        ["NFR-03", "Keamanan Autentikasi & Data (Security)", "Kata sandi pengguna wajib di-hash menggunakan algoritma Bcrypt dengan cost factor minimal 12. Seluruh formulir POST/PUT/DELETE wajib diverifikasi dengan token CSRF. Seluruh masukan wajib disanitasi menggunakan Prepared Statements PDO untuk mencegah SQL Injection dan Cross-Site Scripting (XSS)."],
        ["NFR-04", "Otorisasi Akses Berbasis Peran (Access Control)", "Rute administratif (/admin/*) wajib dijaga ketat oleh middleware 'role:admin'. Pengguna dengan peran 'mahasiswa' atau pengunjung tanpa login yang mencoba mengakses rute admin secara paksa akan ditolak otomatis dan dialihkan."],
        ["NFR-05", "Keandalan & Ketersediaan (Reliability & Availability)", "Sistem dirancang memiliki tingkat ketersediaan (uptime) minimal 99.5% per bulan. Sistem basis data menerapkan mekanisme Foreign Key dengan CASCADE untuk menjamin tidak timbulnya record yatim (orphaned records) saat data sesi dihapus."],
        ["NFR-06", "Kemudahan Pemeliharaan & Keterbacaan Kode (Maintainability)", "Arsitektur kode menerapkan Service-Repository Pattern dengan pemisahan tegas antara Controller ('QuestionnaireController', 'AdminController') dan Engine Logika Bisnis ('RocService', 'TopsisService', 'AiExplanationService'). Skema basis data terkelola secara otomatis via Migration Laravel."],
        ["NFR-07", "Responsivitas Antarmuka & Portabilitas (Usability & Portability)", "Antarmuka dibangun menggunakan kerangka Tailwind CSS yang responsif terhadap berbagai ukuran layar (layar smartphone, tablet, laptop, dan monitor desktop) serta kompatibel dengan peramban web modern (Google Chrome, Mozilla Firefox, Edge, Safari)."],
        ["NFR-08", "Presisi Perhitungan Numerik (Numerical Precision)", "Kalkulasi nilai bobot ROC menggunakan presisi 4 desimal dengan penjaminan jumlahan tepat 1.0000. Skor preferensi TOPSIS (Vi) disimpan dengan presisi 6 desimal untuk mencegah terjadinya skor identik (tie score) pada alternatif yang memiliki selisih spesifikasi tipis."]
    ]
    create_styled_table(doc, ["Kode NFR", "Dimensi Kualitas", "Spesifikasi Kebutuhan dan Tolok Ukur"], tbl_nfr, col_widths=[1.0, 1.8, 3.7])

def build_chapter_8(doc):
    add_heading_1(doc, "BAB 8: MATRIKS KETERTELUSURAN KEBUTUHAN (REQUIREMENTS TRACEABILITY MATRIX - RTM)")
    add_p(
        doc,
        "Matriks Ketertelusuran Kebutuhan (RTM) memastikan bahwa setiap kebutuhan fungsional yang didefinisikan pada Bab 4 memiliki implementasi konkret pada kode sumber perangkat lunak (Controller, Model, Service, View), terhubung dengan skema basis data, dan memiliki prosedur verifikasi pengujian."
    )

    tbl_rtm = [
        ["SRS-F-01", "Registrasi Akun", "AuthController@register", "User.php", "users", "TC-AUTH-01", "Lulus (Verified)"],
        ["SRS-F-02", "Otentikasi Multi-Role", "AuthController@login", "User.php", "users", "TC-AUTH-02", "Lulus (Verified)"],
        ["SRS-F-03", "Logout Sesi", "AuthController@logout", "Session", "sessions", "TC-AUTH-03", "Lulus (Verified)"],
        ["SRS-F-04", "Preferensi Budget", "QuestionnaireController@store", "KuisionerJawaban", "kuisioner_jawaban", "TC-QUES-01", "Lulus (Verified)"],
        ["SRS-F-05", "Drag-and-Rank Kriteria", "QuestionnaireController@index", "Kriteria.php", "kriteria", "TC-QUES-02", "Lulus (Verified)"],
        ["SRS-F-06", "Filter Merek & Kondisi", "QuestionnaireController@store", "Laptop.php", "laptops", "TC-QUES-03", "Lulus (Verified)"],
        ["SRS-F-07", "Bobot Kriteria ROC", "RocService@calculateWeights", "BobotKriteriaHasil", "bobot_kriteria_hasil", "TC-ALG-01", "Lulus (Verified)"],
        ["SRS-F-08", "Normalisasi TOPSIS", "TopsisService@calculate", "Laptop.php", "laptops", "TC-ALG-02", "Lulus (Verified)"],
        ["SRS-F-09", "Solusi Ideal A+ / A-", "TopsisService@calculate", "Kriteria.php", "kriteria", "TC-ALG-03", "Lulus (Verified)"],
        ["SRS-F-10", "Skor Preferensi Vi", "TopsisService@calculate", "HasilTopsis", "hasil_topsis", "TC-ALG-04", "Lulus (Verified)"],
        ["SRS-F-11", "Komparasi Baru vs Second", "QuestionnaireController@result", "HasilTopsis", "hasil_topsis", "TC-RES-01", "Lulus (Verified)"],
        ["SRS-F-12", "Generator Narasi AI", "AiExplanationService", "PenjelasanAi", "penjelasan_ai", "TC-AI-01", "Lulus (Verified)"],
        ["SRS-F-13", "Inspeksi Matriks SPK", "QuestionnaireController@result", "TopsisService", "Memory/View", "TC-RES-02", "Lulus (Verified)"],
        ["SRS-F-14", "Riwayat & Kloning Sesi", "QuestionnaireController@history", "KuisionerJawaban", "kuisioner_jawaban", "TC-HIST-01", "Lulus (Verified)"],
        ["SRS-F-15", "Ekspor Dokumen PDF", "QuestionnaireController@pdf", "DomPDF Wrapper", "pdf.result", "TC-PDF-01", "Lulus (Verified)"],
        ["SRS-F-16", "Dashboard Admin", "AdminController@dashboard", "Laptop/User/Jawaban", "Database Aggregates", "TC-ADM-01", "Lulus (Verified)"],
        ["SRS-F-17", "CRUD Katalog Laptop", "AdminController@storeLaptop", "Laptop.php", "laptops", "TC-ADM-02", "Lulus (Verified)"],
        ["SRS-F-18", "Impor Bulk CSV Laptop", "AdminController@importCsv", "Laptop.php", "laptops", "TC-ADM-03", "Lulus (Verified)"],
        ["SRS-F-19", "Konfigurasi Kriteria", "AdminController@updateCriteria", "Kriteria.php", "kriteria", "TC-ADM-04", "Lulus (Verified)"],
        ["SRS-F-20", "Audit Pengguna & Riwayat", "AdminController@dashboard", "User.php", "users, kuisioner", "TC-ADM-05", "Lulus (Verified)"]
    ]
    create_styled_table(doc, ["Kode SRS", "Nama Kebutuhan", "Komponen Controller / Service", "Model Terkait", "Tabel Basis Data", "Kode Pengujian", "Status Verifikasi"], tbl_rtm, col_widths=[0.8, 1.2, 1.5, 1.0, 1.0, 0.8, 0.8])

def build_chapter_9(doc):
    add_heading_1(doc, "BAB 9: LAMPIRAN DAN PENUTUP")

    add_heading_2(doc, "9.1 Struktur Direktori Proyek Laravel 10")
    add_p(
        doc,
        "Implementasi sistem ditata dengan mengikuti konvensi standar Laravel 10 dengan penambahan direktori layanan Service Layer:"
    )
    add_bullet(doc, "Berisi pengendali logika HTTP ('AuthController', 'DashboardController', 'QuestionnaireController', 'AdminController').", "app/Http/Controllers/: ")
    add_bullet(doc, "Berisi model representasi basis data Eloquent ('User', 'Laptop', 'Kriteria', 'KuisionerJawaban', 'BobotKriteriaHasil', 'HasilTopsis', 'PenjelasanAi').", "app/Models/: ")
    add_bullet(doc, "Berisi modul logika bisnis matematis dan integrasi AI ('RocService.php', 'TopsisService.php', 'AiExplanationService.php').", "app/Services/: ")
    add_bullet(doc, "Berisi berkas skema tabel dan perubahannya ('2026_09_10_000001_create_spk_topsis_roc_tables.php', '2026_09_11_000001_update_spk_schema_enhancements.php').", "database/migrations/: ")
    add_bullet(doc, "Berisi template antarmuka responsif Blade ('dashboard.blade.php', 'questionnaire.blade.php', 'result.blade.php', 'history.blade.php', 'admin/dashboard.blade.php', dan 'pdf/result.blade.php').", "resources/views/: ")
    add_bullet(doc, "Berisi deklarasi rute web terotentikasi dan terproteksi middleware peran ('routes/web.php').", "routes/: ")

    add_heading_2(doc, "9.2 Panduan Deployment dan Konfigurasi Lingkungan")
    add_p(
        doc,
        "Untuk menjalankan sistem pada lingkungan produksi atau pengujian lokal, langkah-langkah konfigurasi adalah:"
    )
    add_bullet(doc, "Salin berkas .env.example menjadi .env, kemudian sesuaikan parameter koneksi basis data (DB_DATABASE, DB_USERNAME, DB_PASSWORD).", "1. Konfigurasi Environment: ")
    add_bullet(doc, "Jalankan 'php artisan key:generate' untuk mengaktifkan kunci enkripsi aplikasi.", "2. Kunci Enkripsi: ")
    add_bullet(doc, "Masukkan API Key pada variabel GEMINI_API_KEY=AIzaSy... untuk mengaktifkan generator narasi LLM eksternal.", "3. Konfigurasi Gemini API: ")
    add_bullet(doc, "Jalankan 'php artisan migrate --seed' untuk mengeksekusi pembuatan 7 tabel basis data relasional serta menyuntikkan data kriteria awal dan katalog laptop.", "4. Migrasi Basis Data: ")
    add_bullet(doc, "Jalankan 'php artisan serve' untuk memulai server lokal pada http://127.0.0.1:8000.", "5. Eksekusi Server: ")

    add_heading_2(doc, "9.3 Penutup dan Rekomendasi Pengembangan")
    add_p(
        doc,
        "Dokumen Software Requirements Specification (SRS) ini telah merinci secara menyeluruh seluruh aspek kebutuhan perangkat lunak, formulasi matematis, arsitektur basis data, serta prosedur verifikasi sistem untuk 'Sistem Pendukung Keputusan Pemilihan Laptop Gaming untuk Mahasiswa Menggunakan Metode TOPSIS Berbasis Web'. Diharapkan dokumen ini menjadi pedoman baku yang menjamin kualitas rekayasa perangkat lunak, keabsahan metodologi ilmiah, dan kemudahan pengembangan fitur di masa mendatang."
    )

    add_callout(
        doc,
        "Dokumen ini sah dan mengikat sebagai spesifikasi acuan perangkat lunak. Pembaruan terhadap spesifikasi ini di masa mendatang wajib melalui prosedur revisi resmi dan didokumentasikan pada Lembar Pengendalian Dokumen.",
        title="PENGESAHAN DOKUMEN SPESIFIKASI"
    )

print("SRS Part 3 module compiled successfully.")

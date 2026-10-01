@echo off
title Sistem SPK Rekomendasi Laptop Mahasiswa
color 0A
cls
echo ================================================================
echo   SISTEM PENDUKUNG KEPUTUSAN PEMILIHAN LAPTOP (TOPSIS & ROC)
echo ================================================================
echo.
echo [1/3] Menyiapkan direktori proyek...
cd /d "%~dp0"

echo [2/3] Membuka sistem di browser...
start http://127.0.0.1:8000

echo [3/3] Menjalankan server Laravel...
echo.
echo ================================================================
echo  Aplikasi SEDANG BERJALAN di: http://127.0.0.1:8000
echo  JANGAN TUTUP jendela ini selama website sedang digunakan!
echo  (Untuk menghentikan: tekan Ctrl + C)
echo ================================================================
echo.

"C:\xampp\php\php.exe" artisan serve --host=127.0.0.1 --port=8000
pause

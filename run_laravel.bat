@echo off
title NeuroVault IMS - Laravel Application (P5)
echo =========================================================
echo   NeuroVault IMS v10 - Laravel Fundamental (Pertemuan 5)
echo   Menjalankan server di http://127.0.0.1:8000
echo =========================================================
echo Pastikan MySQL di XAMPP sudah di-START!
echo Tekan Ctrl+C jika ingin mematikan server.
echo.
start http://127.0.0.1:8000
C:\xampp\php\php.exe artisan serve
pause

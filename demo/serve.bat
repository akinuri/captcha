@echo off
start "" http://localhost:8000/
php -S localhost:8000 -t %~dp0

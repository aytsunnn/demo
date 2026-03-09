cd "C:\OSPanel\domains\demo"
start "" "http://demo/login"
"C:\ospanel\modules\php\PHP_8.0\php.exe" artisan serve --port 8001
pause

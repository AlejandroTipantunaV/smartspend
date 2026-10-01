$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$mysqlBinary = 'C:\xampp\mysql\bin\mysqld.exe'
$configuration = Join-Path $projectRoot 'tmp\mysql\my.ini'
if (!(Test-Path -LiteralPath $configuration)) {
    throw 'No existe la instancia aislada. Usa MySQL de XAMPP e importa sql/script_database.sql según README.md.'
}
$connection = New-Object System.Net.Sockets.TcpClient
$portIsOpen = $false
try { $connection.Connect('127.0.0.1', 3307); $portIsOpen = $true } catch { } finally { $connection.Dispose() }
if ($portIsOpen) {
    Write-Output 'El puerto 3307 ya está en uso. No se inició otra instancia.'
    exit
}
Start-Process -FilePath $mysqlBinary -ArgumentList "--defaults-file=`"$configuration`"", '--bind-address=127.0.0.1' -WindowStyle Hidden
Write-Output 'Base local iniciada en 127.0.0.1:3307. Abre http://localhost/smartspend/ con Apache activo.'

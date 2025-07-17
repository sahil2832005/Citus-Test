# Citus PHP Benchmark Runner for Windows PowerShell
# Usage: .\run.ps1 <command>

param(
    [Parameter(Position=0)]
    [string]$Command
)

$Commands = @{
    'install' = 'composer install'
    'test' = 'php src/php/benchmark.php --test'
    'benchmark' = 'php src/php/benchmark.php'
    'batch' = 'php src/php/benchmark.php --batch'
    'cleanup' = 'php src/php/benchmark.php --cleanup'
    'docker-up' = 'docker-compose up -d'
    'docker-down' = 'docker-compose down'
}

function Show-Help {
    Write-Host "Citus PHP Benchmark Runner" -ForegroundColor Green
    Write-Host "==========================" -ForegroundColor Green
    Write-Host ""
    Write-Host "Available commands:" -ForegroundColor Yellow
    
    foreach ($cmd in $Commands.Keys | Sort-Object) {
        $actual = $Commands[$cmd]
        Write-Host "  $($cmd.PadRight(12)) - $actual" -ForegroundColor White
    }
    
    Write-Host ""
    Write-Host "Usage: .\run.ps1 <command>" -ForegroundColor Cyan
    Write-Host "Example: .\run.ps1 test" -ForegroundColor Cyan
    Write-Host ""
}

if (-not $Command -or $Command -eq "help" -or $Command -eq "-h" -or $Command -eq "--help") {
    Show-Help
    exit 1
}

if (-not $Commands.ContainsKey($Command)) {
    Write-Host "Unknown command: $Command" -ForegroundColor Red
    Write-Host ""
    Show-Help
    exit 1
}

$ActualCommand = $Commands[$Command]
Write-Host "Running: $ActualCommand" -ForegroundColor Green
Write-Host ("-" * 50) -ForegroundColor Gray

# Execute the command
try {
    Invoke-Expression $ActualCommand
    if ($LASTEXITCODE -ne 0) {
        Write-Host "Command failed with exit code: $LASTEXITCODE" -ForegroundColor Red
        exit $LASTEXITCODE
    }
} catch {
    Write-Host "Error executing command: $_" -ForegroundColor Red
    exit 1
}

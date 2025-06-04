@echo off
setlocal

set EXEC=barista_academy

if not exist %EXEC% (
    echo Error: %EXEC% not found in current directory. Maybe it's missing?
    exit /b 1
)

echo Starting docker container and mounting %EXEC%...
docker-compose up -d

REM Replace `barista` with your actual service name
echo Opening shell into container...
docker-compose exec barista sh

endlocal

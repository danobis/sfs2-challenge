#!/bin/bash

EXEC="./barista_academy"

if [[ ! -f "$EXEC" ]]; then
    echo "Error: $EXEC not found in current directory. Maybe it's missing?"
    exit 1
fi

echo "Starting Docker container and mounting $EXEC..."
docker-compose up -d

# Replace `barista` with your actual service name
echo "Opening shell into container..."
docker-compose exec barista sh

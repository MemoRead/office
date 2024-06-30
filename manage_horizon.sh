#!/bin/bash

# Path ke artisan
ARTISAN_PATH="/home/perhimpu/office/artisan"

# Jalankan Horizon
echo "Starting Horizon..."
php $ARTISAN_PATH horizon &

# Tunggu beberapa detik agar Horizon bisa mulai dengan baik (sesuaikan dengan kebutuhan)
sleep 10

# Hentikan Horizon
echo "Stopping Horizon..."
pkill -f "php $ARTISAN_PATH horizon"

# Hapus file cache
echo "Clearing cache..."
php $ARTISAN_PATH config:clear
php $ARTISAN_PATH cache:clear
php $ARTISAN_PATH route:clear
php $ARTISAN_PATH view:clear

#!/bin/bash
# Helper script to generate APP_KEY for Laravel
# Run this locally and copy the output to Render's environment variables

echo "Generating APP_KEY for Laravel..."
php artisan key:generate --show

echo ""
echo "Copy the output above and set it as APP_KEY in Render's environment variables."
echo "Or leave it empty and it will be auto-generated on first deployment."


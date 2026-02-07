#!/bin/bash

# Script untuk replace migrations dengan yang sudah dioptimasi

echo "🔄 Starting migration replacement..."

# 1. Backup migrations lama (jika belum)
if [ ! -d "database/migrations_old" ]; then
    echo "📦 Creating backup of old migrations..."
    cp -r database/migrations database/migrations_old
    echo "✅ Backup created at database/migrations_old/"
fi

# 2. Hapus migrations lama
echo "🗑️  Removing old migrations..."
rm -f database/migrations/*.php

# 3. Copy migrations baru
echo "📋 Copying new optimized migrations..."
cp database/migrations_clean/*.php database/migrations/

# 4. Verify
echo ""
echo "📊 Migration files:"
ls -1 database/migrations/*.php | wc -l
echo " files copied"

echo ""
echo "✅ Migration replacement complete!"
echo ""
echo "📝 Next steps:"
echo "1. Check database connection in .env"
echo "2. Run: php artisan migrate:fresh --seed"
echo "3. Test the application"
echo ""
echo "💡 Rollback if needed:"
echo "   rm -f database/migrations/*.php"
echo "   cp database/migrations_old/*.php database/migrations/"

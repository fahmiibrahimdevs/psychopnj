# Clean Migrations

Migrations yang sudah dioptimasi dengan:

- Indexing untuk foreign keys dan search columns
- Hapus created_at/updated_at yang tidak diperlukan
- Foreign key constraints
- Tipe data yang tepat

## Cara pakai:

1. Backup dulu: `cp -r database/migrations database/migrations_old`
2. Hapus migrations lama: `rm database/migrations/*.php`
3. Copy migrations baru: `cp database/migrations_clean/*.php database/migrations/`
4. Run: `php artisan migrate:fresh --seed`

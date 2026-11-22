<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use App\Models\Peminjaman;
use App\Policies\PeminjamanPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paksa HTTPS di production (Vercel) agar form dan session secure
        if (config('app.env') === 'production' || isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            URL::forceScheme('https');
        }

        // Daftarkan Policy Peminjaman untuk soal nomor 4
        Gate::policy(Peminjaman::class, PeminjamanPolicy::class);

        // Mendefinisikan Gate kelola-buku untuk admin dan petugas
        Gate::define('kelola-buku', function ($user) {
            return $user->hasRole(['admin', 'petugas']);
        });
    }
}

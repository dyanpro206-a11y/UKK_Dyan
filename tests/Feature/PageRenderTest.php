<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PageRenderTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Verifikasi bahwa semua halaman admin dapat dirender setelah login.
     */
    public function test_all_admin_pages_render_after_login(): void
    {
        $admin = User::where('username', 'admin')->first();
        $this->assertNotNull($admin, 'User admin dari seeder tidak ditemukan.');
        $this->actingAs($admin);

        $pages = [
            'dashboard',
            'buku',
            'buku/create',
            'buku/1',
            'kategori-buku',
            'kategori-buku/create',
            'users',
            'users/create',
            'peminjaman',
            'peminjaman/create',
            'pengembalian',
            'pengembalian/riwayat',
            'laporan',
            'laporan/cetak',
        ];

        foreach ($pages as $page) {
            $response = $this->get('/' . $page);
            $response->assertStatus(200);
        }

        $this->assertTrue(true);
    }

    /**
     * Halaman login (pemilihan role) dapat diakses publik dan memuat tombol admin/user.
     */
    public function test_login_page_accessible(): void
    {
        $this->get('/login')
            ->assertStatus(200)
            ->assertSee('Login sebagai Admin')
            ->assertSee('Login sebagai User')
            ->assertSee('Daftar di sini');
    }

    /**
     * Halaman pendaftaran dapat diakses publik dan memuat tautan ke login.
     */
    public function test_register_page_accessible(): void
    {
        $this->get('/daftar')
            ->assertStatus(200)
            ->assertSee('Pendaftaran Anggota')
            ->assertSee('Masuk di sini');
    }

    /**
     * Halaman form login admin dapat diakses publik.
     */
    public function test_admin_login_form_accessible(): void
    {
        $this->get('/login/admin')
            ->assertStatus(200)
                        ->assertSee('Login Admin')
            ->assertSee('name="username"', false)
            ->assertSee('name="password"', false);
    }

    /**
     * Halaman form login user dapat diakses publik.
     */
    public function test_user_login_form_accessible(): void
    {
        $this->get('/login/user')
            ->assertStatus(200)
                        ->assertSee('Login User')
            ->assertSee('name="username"', false)
            ->assertSee('name="password"', false);
    }

    /**
     * Login admin berhasil dengan akun admin dan mengarahkan ke dashboard.
     */
    public function test_admin_login_succeeds(): void
    {
        $response = $this->post('/login/admin', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs(User::where('username', 'admin')->first());
    }

    /**
     * Login user berhasil dengan akun user dan mengarahkan ke dashboard user.
     */
    public function test_user_login_succeeds(): void
    {
        $response = $this->post('/login/user', [
            'username' => 'budi',
            'password' => 'budi123',
        ]);

        $response->assertRedirect('/user/dashboard');
        $this->assertAuthenticatedAs(User::where('username', 'budi')->first());
    }

    /**
     * User biasa tidak boleh mengakses panel admin (dialihkan ke dashboard user).
     */
    public function test_user_cannot_access_admin_panel(): void
    {
        $user = User::where('username', 'budi')->first();
        $this->actingAs($user);

        $pages = ['dashboard', 'buku', 'buku/create', 'users', 'peminjaman/create', 'pengembalian', 'laporan'];

        foreach ($pages as $page) {
            $response = $this->get('/' . $page);
            $response->assertStatus(302);
            $response->assertRedirect('/user/dashboard');
        }
    }

    /**
     * Admin tidak boleh mengakses panel user (dialihkan ke dashboard admin).
     */
    public function test_admin_cannot_access_user_panel(): void
    {
        $admin = User::where('username', 'admin')->first();
        $this->actingAs($admin);

        $pages = ['user/dashboard', 'user/buku', 'user/peminjaman', 'user/profil'];

        foreach ($pages as $page) {
            $response = $this->get('/' . $page);
            $response->assertStatus(302);
            $response->assertRedirect('/dashboard');
        }
    }

    /**
     * Halaman panel user dapat dirender setelah login sebagai user.
     */
    public function test_user_pages_render_after_login(): void
    {
        $user = User::where('username', 'dyann')->first();
        $this->actingAs($user);

        $pages = [
            'user/dashboard',
            'user/buku',
            'user/buku/1',
            'user/peminjaman',
            'user/peminjaman/create',
            'user/profil',
        ];

        foreach ($pages as $page) {
            $response = $this->get('/' . $page);
            $response->assertStatus(200);
        }
    }

    /**
     * Tamu (belum login) tidak bisa mengakses dashboard dan dialihkan ke login.
     */
    public function test_guest_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/user/dashboard')->assertRedirect('/login');
    }

    /**
     * Halaman utama mengarahkan sesuai role setelah login.
     */
    public function test_home_redirects_based_on_role(): void
    {
        $this->actingAs(User::where('username', 'admin')->first())
            ->get('/')
            ->assertRedirect('/dashboard');

        $this->actingAs(User::where('username', 'budi')->first())
            ->get('/')
            ->assertRedirect('/user/dashboard');
    }

    /**
     * Halaman login admin dan user tidak menampilkan menu utama (sidebar).
     */
    public function test_login_pages_do_not_show_main_menu(): void
    {
        foreach (['/login', '/login/admin', '/login/user'] as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
            $response->assertDontSee('class="sidebar"', false);
            $response->assertDontSee('Menu Utama', false);
        }
    }

    /**
     * Semua form tambah memakai konfirmasi "Ya, Tambahkan" (admin & user).
     */
    public function test_create_forms_use_confirmation(): void
    {
        $this->actingAs(User::where('username', 'admin')->first());

        foreach (['/buku/create', '/kategori-buku/create', '/users/create', '/peminjaman/create'] as $page) {
            $response = $this->get($page);
            $response->assertStatus(200);
            $response->assertSee('form-tambah', false);
            $response->assertSee('Ya, Tambahkan');
        }

        $this->actingAs(User::where('username', 'budi')->first());
        $response = $this->get('/user/peminjaman/create');
        $response->assertStatus(200);
        $response->assertSee('form-tambah', false);
        $response->assertSee('Ya, Tambahkan');
    }

    /**
     * Login admin menolak akun user biasa.
     */
    public function test_admin_login_rejects_user_account(): void
    {
        $response = $this->post('/login/admin', [
            'username' => 'budi',
            'password' => 'budi123',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    /**
     * Login user menolak akun admin.
     */
    public function test_user_login_rejects_admin_account(): void
    {
        $response = $this->post('/login/user', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    /**
     * Proses pendaftaran user baru berhasil disimpan dengan role user dan password ter-hash.
     */
    public function test_register_creates_new_user(): void
    {
        $response = $this->post('/daftar', [
            'username' => 'anggota_baru',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'email' => 'anggota@example.com',
            'nama_lengkap' => 'Anggota Baru',
            'alamat' => 'Jl. Contoh No. 1',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('success');

        $user = User::where('username', 'anggota_baru')->first();
        $this->assertNotNull($user);
        $this->assertEquals('user', $user->role);
        $this->assertNotEquals('rahasia123', $user->password);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('rahasia123', $user->password));
    }
}
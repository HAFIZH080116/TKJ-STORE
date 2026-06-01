<?php

namespace Tests\Feature;

use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TkjStoreTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $developer;
    private User $user;
    private Produk $produk;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Seed Data
        $this->admin = User::create([
            'name' => 'Admin TKJ',
            'no_hp' => '081234567890',
            'email' => 'admin@tkjstore.test',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->developer = User::create([
            'name' => 'Dev TKJ',
            'no_hp' => '081234567891',
            'email' => 'developer@tkjstore.test',
            'username' => 'developer',
            'password' => bcrypt('password'),
            'role' => 'developer',
        ]);

        $this->user = User::create([
            'name' => 'User TKJ',
            'no_hp' => '081234567892',
            'email' => 'user@tkjstore.test',
            'username' => 'user',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->produk = Produk::create([
            'nama_produk' => 'Mouse Gaming',
            'harga_satuan' => 200000.00,
            'stok' => 10,
            'deskripsi' => 'Mouse gaming keren',
            'gambar' => null,
        ]);
    }

    public function test_admin_login_redirection(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@tkjstore.test',
            'password' => 'password',
        ]);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_developer_login_redirection(): void
    {
        $response = $this->post('/login', [
            'email' => 'developer@tkjstore.test',
            'password' => 'password',
        ]);
        $response->assertRedirect(route('developer.dashboard'));
    }

    public function test_user_login_redirection(): void
    {
        $response = $this->post('/login', [
            'email' => 'user@tkjstore.test',
            'password' => 'password',
        ]);
        $response->assertRedirect(route('user.home'));
    }

    public function test_role_access_authorization(): void
    {
        // Guests cannot access dashboards
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/developer')->assertRedirect('/login');
        $this->get('/user')->assertRedirect('/login');

        // User cannot access admin or developer dashboards
        $this->actingAs($this->user);
        $this->get('/admin')->assertStatus(403);
        $this->get('/developer')->assertStatus(403);
        $this->get('/user')->assertStatus(200);

        // Admin cannot access developer dashboard
        $this->actingAs($this->admin);
        $this->get('/admin')->assertStatus(200);
        $this->get('/developer')->assertStatus(403);
    }

    public function test_product_catalog_and_detail(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('user.produk.index'));
        $response->assertStatus(200);
        $response->assertSee('Mouse Gaming');

        $response = $this->get(route('user.produk.show', $this->produk));
        $response->assertStatus(200);
        $response->assertSee('Mouse gaming keren');
    }

    public function test_cart_session_management(): void
    {
        $this->actingAs($this->user);

        // Add to cart
        $response = $this->post(route('user.keranjang.tambah', $this->produk), [
            'jumlah' => 2,
        ]);
        $response->assertSessionHas('cart');

        // View Cart
        $response = $this->get(route('user.keranjang.index'));
        $response->assertStatus(200);
        $response->assertSee('Mouse Gaming');
        $response->assertSee('Rp 400.000');
    }

    public function test_checkout_transaction_process(): void
    {
        $this->actingAs($this->user);

        // Add product to cart first
        $this->post(route('user.keranjang.tambah', $this->produk), [
            'jumlah' => 3,
        ]);

        // Process Checkout
        $response = $this->post(route('user.checkout.proses'), [
            'metode_pembayaran' => 'BCA Virtual Account',
            'nama_penerima' => 'User TKJ',
            'telepon_penerima' => '081234567892',
            'kurir' => 'JNE Express',
            'layanan' => 'Reguler',
            'alamat_lengkap' => 'Jl. Sudirman No. 20',
            'catatan' => 'Taruh teras',
            'ongkir' => 15000,
        ]);

        // Check if redirected to order success/show page
        $response->assertRedirect();

        // Check database
        $this->assertDatabaseHas('transaksi', [
            'id_user' => $this->user->id_user,
            'total_pembayaran' => 615000.00,
            'metode_pembayaran' => 'BCA Virtual Account | JNE Express - Reguler (Penerima: User TKJ, Telp: 081234567892, Alamat: Jl. Sudirman No. 20, Catatan: Taruh teras)',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('detail_transaksi', [
            'id_produk' => $this->produk->id_produk,
            'jumlah' => 3,
            'harga_satuan' => 200000.00,
            'subtotal' => 600000.00,
        ]);

        // Product stock decremented
        $this->assertEquals(7, $this->produk->fresh()->stok);
    }

    public function test_user_registration_process(): void
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);

        $response = $this->post(route('register'), [
            'name' => 'Pelanggan Baru',
            'no_hp' => '08999999999',
            'email' => 'pelanggan@tkjstore.test',
            'username' => 'pelanggan',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('user.home'));

        $this->assertDatabaseHas('users', [
            'email' => 'pelanggan@tkjstore.test',
            'role' => 'user',
        ]);
    }

    public function test_developer_monitoring_and_features(): void
    {
        $this->actingAs($this->developer);

        // 1. Dashboard & Monitoring
        $response = $this->get(route('developer.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Versi Sistem');

        $response = $this->get(route('developer.monitoring'));
        $response->assertStatus(200);
        $response->assertSee('Operating System');

        // 2. Kelola Versi
        $response = $this->post(route('developer.versi.store'), [
            'versi' => 'v2.0.0',
            'deskripsi' => 'Rilis Major Versi Kedua',
            'tanggal_rilis' => '2026-06-01',
        ]);
        $response->assertRedirect(route('developer.versi.index'));

        $this->assertDatabaseHas('system_versions', [
            'versi' => 'v2.0.0',
        ]);

        // 3. Bug Report
        $response = $this->post(route('developer.bug.store'), [
            'judul' => 'Error checkout gantung',
            'deskripsi' => 'Rincian error checkout',
        ]);
        $response->assertRedirect(route('developer.bug.index'));

        $this->assertDatabaseHas('bug_reports', [
            'judul' => 'Error checkout gantung',
            'status' => 'open',
        ]);

        // 4. Log Aktivitas
        $response = $this->get(route('developer.log.index'));
        $response->assertStatus(200);
        $response->assertSee('Audit Trail');

        // 5. Kelola Pengguna (Role Switcher)
        $response = $this->get(route('developer.users.index'));
        $response->assertStatus(200);
        $response->assertSee('Kelola Pengguna');

        $response = $this->put(route('developer.users.update-role', $this->user->id_user), [
            'role' => 'admin',
        ]);
        $response->assertRedirect(route('developer.users.index'));

        $this->assertDatabaseHas('users', [
            'id_user' => $this->user->id_user,
            'role' => 'admin',
        ]);
    }

    public function test_user_order_status_notifications(): void
    {
        $this->actingAs($this->user);

        // 1. Initially, no notifications for pending status
        $transaksi = \App\Models\Transaksi::create([
            'id_user' => $this->user->id_user,
            'tanggal_transaksi' => now(),
            'total_pembayaran' => 200000.00,
            'metode_pembayaran' => 'COD',
            'status' => 'pending',
        ]);

        $response = $this->get(route('user.home'));
        $response->assertStatus(200);
        $response->assertDontSee('sedang diproses kurir ekspedisi!');

        // 2. Status updated to diproses by admin
        $transaksi->update(['status' => 'diproses']);

        $response = $this->get(route('user.home'));
        $response->assertStatus(200);
        $response->assertSee('sedang diproses kurir ekspedisi!');

        // 3. Dismiss notification
        $key = $transaksi->id_transaksi . '_diproses';
        $response = $this->post(route('user.notifikasi.baca', $key));
        $response->assertRedirect();

        // 4. Notification disappeared
        $response = $this->get(route('user.home'));
        $response->assertDontSee('sedang diproses kurir ekspedisi!');
    }
}

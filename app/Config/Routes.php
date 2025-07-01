<?php
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Routes untuk Auth
$routes->match(['get', 'post'], '/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// Routes untuk Barang
$routes->get('/barang', 'Barang::index');
$routes->post('/barang/store', 'Barang::store');
$routes->get('/barang/edit/(:num)', 'Barang::edit/$1');
$routes->post('/barang/update/(:num)', 'Barang::update/$1');
$routes->post('/barang/delete/(:num)', 'Barang::delete/$1');

// Routes untuk Kategori
$routes->get('/kategori', 'Kategori::index');
$routes->get('/kategori/create', 'Kategori::create');
$routes->post('/kategori/store', 'Kategori::store');
$routes->get('/kategori/edit/(:num)', 'Kategori::edit/$1');
$routes->post('/kategori/update/(:num)', 'Kategori::update/$1');
$routes->post('/kategori/delete/(:num)', 'Kategori::delete/$1');

// Routes untuk Supplier
$routes->get('/supplier', 'Supplier::index');
$routes->get('/supplier/create', 'Supplier::create');
$routes->post('/supplier/store', 'Supplier::store');
$routes->get('/supplier/edit/(:num)', 'Supplier::edit/$1');
$routes->post('/supplier/update/(:num)', 'Supplier::update/$1');
$routes->post('/supplier/delete/(:num)', 'Supplier::delete/$1');

// Routes untuk users
$routes->get('/users', 'Users::index');
$routes->get('/users/create', 'Users::create');
$routes->post('/users/store', 'Users::store');
$routes->get('/users/edit/(:num)', 'Users::edit/$1');
$routes->post('/users/update/(:num)', 'Users::update/$1');
$routes->post('/users/delete/(:num)', 'Users::delete/$1');

// Routes untuk Penjualan
$routes->get('/penjualan', 'Penjualan::index');
$routes->get('/penjualan/create', 'Penjualan::create');
$routes->post('/penjualan/store', 'Penjualan::store');
$routes->get('/penjualan/edit/(:num)', 'Penjualan::edit/$1');
$routes->post('/penjualan/update/(:num)', 'Penjualan::update/$1');
$routes->post('/penjualan/delete/(:num)', 'Penjualan::delete/$1');
// Routes untuk Riwayat Penjualan
$routes->get('/riwayat_penjualan', 'Penjualan::riwayat');
// Routes untuk Struk Penjualan
$routes->get('penjualan/struk/(:segment)', 'Penjualan::struk/$1');

// Routes untuk Pembelian
$routes->get('/pembelian', 'Pembelian::index');
$routes->get('/pembelian/create', 'Pembelian::create');
$routes->post('/pembelian/store', 'Pembelian::store');
$routes->get('/pembelian/edit/(:num)', 'Pembelian::edit/$1');
$routes->post('/pembelian/update/(:num)', 'Pembelian::update/$1');
$routes->post('/pembelian/delete/(:num)', 'Pembelian::delete/$1');
// Routes untuk Riwayat Pembelian
$routes->get('/riwayat_pembelian', 'Pembelian::riwayat');


// Routes untuk Laporan
$routes->get('/laporan', 'Laporan::index');
$routes->post('/laporan/filter', 'Laporan::index');
$routes->get('laporan/export_csv', 'Laporan::export_csv');

// Routes untuk Profile
$routes->get('profile', 'Profile::index');
$routes->post('profile/update', 'Profile::update');
$routes->post('profile/upload_foto', 'Profile::upload_foto');
$routes->post('profile/password', 'Profile::ganti_password');

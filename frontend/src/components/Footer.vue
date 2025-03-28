<template>
  <footer class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-4 max-w-6xl">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
          <h3 class="text-lg font-bold mb-4">Nama Toko Buku</h3>
          <p class="text-gray-400 text-sm">
            Toko buku online terlengkap dengan koleksi berkualitas untuk semua kalangan pembaca.
          </p>
          <div class="flex space-x-4 mt-4">
            <a href="#" class="text-gray-400 hover:text-white transition-colors">📱</a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors">👤</a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors">📸</a>
            <a href="#" class="text-gray-400 hover:text-white transition-colors">🐦</a>
          </div>
        </div>

        <div>
          <h3 class="text-lg font-bold mb-4">Kategori</h3>
          <ul class="space-y-2 text-gray-400">
            <li><a href="#" class="hover:text-white transition-colors">Fiksi</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Non-fiksi</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Pendidikan</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Anak-anak</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Bisnis</a></li>
          </ul>
        </div>

        <div>
          <h3 class="text-lg font-bold mb-4">Bantuan</h3>
          <ul class="space-y-2 text-gray-400">
            <li><a href="#" class="hover:text-white transition-colors">Cara Pemesanan</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Pembayaran</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Pengiriman</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Pengembalian</a></li>
            <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
          </ul>
        </div>

        <div>
          <h3 class="text-lg font-bold mb-4">Newsletter</h3>
          <p class="text-gray-400 text-sm mb-4">Dapatkan informasi promo dan buku terbaru</p>
          <form class="space-y-2">
            <input
              type="email"
              placeholder="Masukkan email Anda"
              class="w-full px-3 py-2 bg-gray-800 text-white border border-gray-700 rounded focus:outline-none focus:border-blue-500"
            />
            <button
              type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded transition-colors"
            >
              Berlangganan
            </button>
          </form>
        </div>
      </div>

      <div class="border-t border-gray-800 mt-12 pt-6 text-center text-gray-500 text-sm">
        <p>&copy; 2025 Nama Toko Buku. Hak Cipta Dilindungi.</p>
      </div>
    </div>
  </footer>
</template>

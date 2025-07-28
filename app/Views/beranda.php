<!DOCTYPE html>
<html lang="id">
<head>
<?php date_default_timezone_set('Asia/Jakarta'); ?>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tracker Keuangan Harian</title>
  <script src="https://cdn.tailwindcss.com/3.4.16"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.5.0/echarts.min.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: "#3B82F6",
            secondary: "#10B981",
          },
          borderRadius: {
            button: "8px",
          },
        },
      },
    };
  </script>
</head>
<body class="bg-gray-50 min-h-screen">
 <div class="w-full min-h-screen bg-white flex">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-50 border-r border-gray-200 p-6">
      <h1 class="font-['Pacifico'] text-2xl text-primary mb-8">tracker</h1>
      <nav class="space-y-2">
        <a href="/" class="w-full py-3 px-4 flex items-center text-primary bg-blue-50 rounded-lg">
          <div class="w-5 h-5 flex items-center justify-center mr-3">
            <i class="ri-home-line"></i>
          </div>
          <span>Beranda</span>
        </a>
        <a href="/statistik" class="w-full py-3 px-4 flex items-center text-gray-600 hover:bg-gray-100 rounded-lg">
          <div class="w-5 h-5 flex items-center justify-center mr-3">
            <i class="ri-bar-chart-line"></i>
          </div>
          <span>Statistik</span>
        </a>
        <a href="/budget" class="w-full py-3 px-4 flex items-center text-gray-600 hover:bg-gray-100 rounded-lg">
          <div class="w-5 h-5 flex items-center justify-center mr-3">
            <i class="ri-settings-line"></i>
          </div>
          <span>Budget</span>
        </a>
      </nav>
    </div>

    <!-- Content -->
    <div class="flex-1">
      <!-- Header -->
      <div class="bg-gradient-to-r from-primary to-blue-600 text-white px-8 py-8">
        <h1 class="text-2xl font-bold mb-2">Tracker Keuangan Harian</h1>
        <p class="text-blue-100 mb-4" id="currentDate"><?= date('l, d F Y'); ?></p>
        <div class="bg-white/20 rounded-lg p-4">
          <p class="text-sm text-blue-100 mb-1">Sisa Budget Hari Ini</p>
          <p class="text-3xl font-bold" id="remainingBudget">Rp <?= number_format($budget, 0, ',', '.'); ?></p>
        </div>
      </div>

      <!-- Input Form -->
      <div class="px-8 py-6 bg-white border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Tambah Pengeluaran</h2>
        <form action="/tambah" method="post" class="space-y-4">
          <?= csrf_field(); ?>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nominal</label>
            <div class="relative">
              <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
              <input
                type="text"
                name="nominal"
                pattern="\d*"
                inputmode="numeric"
                class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg text-lg font-medium focus:ring-2 focus:ring-primary focus:border-transparent"
                placeholder="0"
              />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
            <select
              name="kategori"
              class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-white focus:ring-2 focus:ring-primary focus:border-transparent"
            >
              <option value="">Pilih Kategori</option>
              <option value="Makanan & Minuman">Makanan & Minuman</option>
              <option value="Transportasi">Transportasi</option>
              <option value="Belanja">Belanja</option>
              <option value="Hiburan">Hiburan</option>
              <option value="Kesehatan">Kesehatan</option>
              <option value="Lainnya">Lainnya</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
            <input
              type="text"
              name="catatan"
              class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="Tambahkan catatan..."
            />
          </div>
          <button
            type="submit"
            class="w-full bg-primary text-white py-3 rounded-button font-medium hover:bg-blue-600 transition-colors"
          >
            Tambah Pengeluaran
          </button>
        </form>
      </div>

      <!-- Ringkasan -->
      <div class="px-8 py-6 bg-white border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Hari Ini</h2>
        <div class="bg-gray-50 rounded-lg p-4 mb-4">
          <span class="text-sm text-gray-600 mb-2 block">Total Pengeluaran</span>
          <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
            <div class="bg-primary h-2 rounded-full transition-all duration-300" id="progressBar" style="width: <?= $persen; ?>%"></div>
          </div>
          <p class="text-xs text-gray-500 mb-2"><?= $persen; ?>% dari budget harian (Rp <?= number_format($budget, 0, ',', '.'); ?>)</p>
          <span class="text-xl font-bold text-gray-800 block" id="totalExpense">Rp <?= number_format($totalPengeluaran, 0, ',', '.'); ?></span>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
          <h3 class="text-sm font-medium text-gray-700 mb-3">Pengeluaran per Kategori</h3>
          <div id="categoryChart" style="height: 200px;"></div>
        </div>
        <!-- Riwayat Transaksi -->
        <div class="bg-white rounded-lg p-6 mt-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Transaksi</h2>
          <div class="grid grid-cols-1 gap-4" id="transactionList">
            <?php if (empty($transaksi)): ?>
              <div class="col-span-2 text-center text-gray-400 py-8">Belum ada transaksi hari ini.</div>
            <?php else: ?>
              <?php foreach ($transaksi as $t): ?>
                <div class="transaction-item bg-gray-50 rounded-lg p-4 flex items-center justify-between">
                  <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3
                      <?php
                        echo ($t['kategori']=='Makanan & Minuman') ? 'bg-orange-100' :
                            (($t['kategori']=='Transportasi') ? 'bg-blue-100' :
                            (($t['kategori']=='Belanja') ? 'bg-purple-100' :
                            (($t['kategori']=='Hiburan') ? 'bg-pink-100' :
                            (($t['kategori']=='Kesehatan') ? 'bg-red-100' : 'bg-gray-100'))));
                      ?>">
                      <i class="<?php
                        echo ($t['kategori']=='Makanan & Minuman') ? 'ri-restaurant-line text-orange-500' :
                            (($t['kategori']=='Transportasi') ? 'ri-car-line text-blue-500' :
                            (($t['kategori']=='Belanja') ? 'ri-shopping-bag-line text-purple-500' :
                            (($t['kategori']=='Hiburan') ? 'ri-movie-line text-pink-500' :
                            (($t['kategori']=='Kesehatan') ? 'ri-heart-pulse-line text-red-500' : 'ri-more-line text-gray-500'))));
                      ?>"></i>
                    </div>
                    <div>
                      <p class="font-medium text-gray-800"><?= esc($t['catatan'] ?: $t['kategori']); ?></p>
                      <p class="text-sm text-gray-500"><?= date('H:i', strtotime($t['waktu'])); ?> • <?= esc($t['kategori']); ?></p>
                    </div>
                  </div>
                  <div class="text-right">
                    <p class="font-medium text-gray-800">Rp <?= number_format($t['nominal'], 0, ',', '.'); ?></p>
                    <form action="/hapus/<?= $t['id']; ?>" method="post" onsubmit="return confirm('Hapus transaksi ini?');">
                      <?= csrf_field(); ?>
                      <button type="submit" class="text-xs text-red-500 hover:underline mt-1">Hapus</button>
                    </form>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
    </div>
  </div>

  <!-- Chart Script -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const chartDom = document.getElementById('categoryChart');
      const myChart = echarts.init(chartDom);
      const data = <?= json_encode($transaksi); ?>;

      if (!data.length) {
        chartDom.innerHTML = 'Belum ada data';
        return;
      }

      const grouped = {};
      data.forEach(item => {
        if (!grouped[item.kategori]) grouped[item.kategori] = 0;
        grouped[item.kategori] += parseInt(item.nominal);
      });

      const chartOption = {
        tooltip: { trigger: 'item' },
        legend: { orient: 'vertical', left: 'left' },
        series: [{
          name: 'Pengeluaran',
          type: 'pie',
          radius: '50%',
          data: Object.entries(grouped).map(([name, value]) => ({ name, value })),
          emphasis: {
            itemStyle: {
              shadowBlur: 10,
              shadowOffsetX: 0,
              shadowColor: 'rgba(0, 0, 0, 0.5)'
            }
          }
        }]
      };

      myChart.setOption(chartOption);
    });
  </script>
</body>
</html>

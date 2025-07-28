<!DOCTYPE html>
<html lang="id">
<head>
    <?php date_default_timezone_set('Asia/Jakarta'); ?>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Budget Harian</title>
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
      <h1 class="font-['Pacifico'] text-2xl text-primary mb-8">logo</h1>
      <nav class="space-y-2">
        <a href="/" class="w-full py-3 px-4 flex items-center text-gray-600 hover:bg-gray-100 rounded-lg">
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
        <a href="/budget" class="w-full py-3 px-4 flex items-center text-primary bg-blue-50 rounded-lg">
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
        <h1 class="text-2xl font-bold mb-2">Budget Harian</h1>
        <p class="text-blue-100 mb-4" id="currentDate"><?= date('l, d F Y'); ?></p>
      </div>

      <!-- Input Form -->
      <div class="px-8 py-6">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Set Budget Hari Ini</h2>
          <form action="/budget/tambah" method="post" class="space-y-4">
            <?= csrf_field(); ?>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nominal Budget</label>
              <div class="relative">
                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                <input
                  type="text"
                  name="nominal"
                  pattern="\d*"
                  inputmode="numeric"
                  class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg text-lg font-medium focus:ring-2 focus:ring-primary focus:border-transparent"
                  placeholder="0"
                  required
                />
              </div>
            </div>
            <button
              type="submit"
              class="w-full bg-primary text-white py-3 rounded-button font-medium hover:bg-blue-600 transition-colors"
            >
              Simpan Budget
            </button>
          </form>
        </div>

        <!-- Daftar Budget -->
        <div class="bg-gray-50 rounded-lg p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Budget Harian</h2>
          <?php if (empty($budgets)): ?>
            <div class="text-center text-gray-400 py-8">Belum ada budget yang disimpan.</div>
          <?php else: ?>
            <table class="w-full text-left">
              <thead>
                <tr>
                  <th class="py-2 px-4 text-gray-600">Tanggal</th>
                  <th class="py-2 px-4 text-gray-600">Nominal</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($budgets as $b): ?>
                  <tr>
                    <?php 
                      // Pastikan tanggal di-convert ke Asia/Jakarta
                      $dt = new DateTime($b['tanggal'], new DateTimeZone('UTC'));
                      $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
                    ?>
                    <td class="py-2 px-4 text-gray-800"><?= $dt->format('d F Y'); ?></td>
                    <td class="py-2 px-4 text-gray-800">Rp <?= number_format($b['nominal'], 0, ',', '.'); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

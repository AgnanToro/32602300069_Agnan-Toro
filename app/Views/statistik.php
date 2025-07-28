<!DOCTYPE html>
<html lang="id">
<head>
  <?php date_default_timezone_set('Asia/Jakarta'); ?>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Statistik Pengeluaran</title>
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
        <a href="/statistik" class="w-full py-3 px-4 flex items-center text-primary bg-blue-50 rounded-lg">
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
      <div class="bg-gradient-to-r from-primary to-blue-600 text-white px-8 py-8">
        <h1 class="text-2xl font-bold mb-2">Statistik Pengeluaran</h1>
        <p class="text-blue-100 mb-4"><?= date('F Y'); ?></p>
      </div>
      <div class="px-8 py-6">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Pengeluaran Per Bulan (Bar Chart)</h2>
          <div id="monthlyChart" style="height: 300px;"></div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Pengeluaran Per Tahun (Line Chart)</h2>
          <div id="yearlyChart" style="height: 300px;"></div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Proporsi Pengeluaran Per Kategori (Pie Chart)</h2>
          <div id="categoryPieChart" style="height: 300px;"></div>
        </div>
      </div>
    </div>
  </div>
  <script>
    // Data dari database
    const monthlyData = <?= json_encode($monthly); ?>;
    const yearlyData = <?= json_encode($yearly); ?>;
    const categoryData = <?= json_encode($category); ?>;

    // Monthly Bar Chart
    const monthlyChart = echarts.init(document.getElementById('monthlyChart'));
    monthlyChart.setOption({
      xAxis: {
        type: 'category',
        data: monthlyData.map(d => {
          // Bulan angka ke nama
          const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
          return bulan[(d.bulan ? d.bulan-1 : 0)];
        })
      },
      yAxis: { type: 'value' },
      series: [{
        data: monthlyData.map(d => d.total),
        type: 'bar',
        itemStyle: { color: '#3B82F6' }
      }]
    });

    // Yearly Line Chart
    const yearlyChart = echarts.init(document.getElementById('yearlyChart'));
    yearlyChart.setOption({
      xAxis: {
        type: 'category',
        data: yearlyData.map(d => d.tahun)
      },
      yAxis: { type: 'value' },
      series: [{
        data: yearlyData.map(d => d.total),
        type: 'line',
        itemStyle: { color: '#10B981' }
      }]
    });

    // Category Pie Chart
    const categoryPieChart = echarts.init(document.getElementById('categoryPieChart'));
    categoryPieChart.setOption({
      tooltip: { trigger: 'item' },
      legend: { orient: 'vertical', left: 'left' },
      series: [{
        name: 'Pengeluaran',
        type: 'pie',
        radius: '50%',
        data: categoryData.map(d => ({ name: d.kategori, value: d.total })),
        emphasis: {
          itemStyle: {
            shadowBlur: 10,
            shadowOffsetX: 0,
            shadowColor: 'rgba(0, 0, 0, 0.5)'
          }
        }
      }]
    });
  </script>
</body>
</html>

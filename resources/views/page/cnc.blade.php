<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Monitoring CNC - Analisa Harian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 p-4 font-sans text-gray-800 dark:text-gray-100">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl md:text-3xl font-bold">
            📡 Monitoring CNC - Hari Ini
        </h1>

        <div class="flex gap-2">

            <button id="exportBtn" class="px-3 py-2 rounded bg-emerald-600 hover:bg-emerald-700 text-white shadow">
                ⬇ Export Excel
            </button>

            <button id="darkToggle" class="px-3 py-2 rounded bg-gray-200 dark:bg-gray-700">
                🌙 Dark
            </button>
            <a href="{{ route('page.RAB.form.index') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-500 hover:bg-gray-600 text-white shadow transition">
                ← <span>Kembali</span>
            </a>

        </div>
    </div>

    <!-- TABEL -->
    <div class="w-full mb-6">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden mb-4">

            <table class="w-full border-collapse table-auto">

                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Waktu</th>
                        <th class="px-4 py-2 text-left">Plat Ke</th>
                        <th class="px-4 py-2 text-left">Arus (A)</th>
                        <th class="px-4 py-2 text-left">Energi (Wh)</th>
                    </tr>
                </thead>

                <tbody id="cnc-body"></tbody>

                <tfoot class="bg-gray-100 dark:bg-gray-700 font-semibold">
                    <tr>
                        <td class="px-4 py-2 text-right" colspan="3">TOTAL ENERGI</td>
                        <td class="px-4 py-2" id="total-energi">0</td>
                    </tr>
                </tfoot>

            </table>
        </div>

        <div id="pagination" class="flex gap-2 mt-2 mb-4 justify-center flex-wrap"></div>

    </div>

    <!-- CHART -->
    <div class="grid md:grid-cols-2 gap-4">

        <div>

            <button id="toggleChart1" class="bg-blue-600 text-white px-3 py-1 rounded mb-2">
                Maksimalkan Chart
            </button>

            <div id="chartContainer1" class="bg-white dark:bg-gray-800 p-2 rounded shadow" style="height:220px">

                <canvas id="energiChart"></canvas>

            </div>

        </div>

        <div>

            <button id="toggleChart2" class="bg-green-600 text-white px-3 py-1 rounded mb-2">
                Maksimalkan Chart
            </button>

            <div id="chartContainer2" class="bg-white dark:bg-gray-800 p-2 rounded shadow" style="height:220px">

                <canvas id="multiChart"></canvas>

            </div>

        </div>

    </div>

    <!-- ANALISA -->
    <div class="bg-white dark:bg-gray-800 shadow rounded p-6 mt-6">

        <h2 class="text-xl font-bold mb-3">📊 Analisa Harian</h2>

        <div id="analysisContent"></div>

    </div>

    <script>
        /* DARK MODE */

        const darkToggle = document.getElementById('darkToggle');

        if (localStorage.theme === 'dark') {
            document.documentElement.classList.add('dark');
        }

        darkToggle.onclick = () => {
            document.documentElement.classList.toggle('dark');

            localStorage.theme =
                document.documentElement.classList.contains('dark') ?
                'dark' : 'light';
        }

        /* CONFIG */

        const tarifPerKWh = 1444;
        const rowsPerPage = 10;

        let currentPage = 1;
        let todayData = [];

        /* CHART */

        const energiChart = new Chart(
            document.getElementById('energiChart'), {
                type: 'line',
                data: {
                    labels: Array.from({
                        length: 24
                    }, (_, i) => i + ":00"),
                    datasets: [{
                        label: 'Energi / Jam',
                        data: Array(24).fill(0),
                        borderColor: 'blue',
                        backgroundColor: 'rgba(59,130,246,0.2)',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            }
        );

        const multiChart = new Chart(
            document.getElementById('multiChart'), {
                type: 'line',
                data: {
                    labels: Array.from({
                        length: 24
                    }, (_, i) => i + ":00"),
                    datasets: []
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            }
        );

        /* UTIL */

        function isToday(dateStr) {
            const d = new Date(dateStr)
            const t = new Date()
            return d.toDateString() === t.toDateString()
        }

        function formatDateTime(dateStr) {
            return new Date(dateStr).toLocaleString()
        }

        /* TABLE */

        function renderTablePage(page) {

            const tbody = document.getElementById('cnc-body')
            tbody.innerHTML = ""

            const start = (page - 1) * rowsPerPage
            const end = start + rowsPerPage

            todayData.slice(start, end).forEach(row => {

                const tr = document.createElement('tr')

                tr.innerHTML = `
<td class="px-4 py-2">${formatDateTime(row.created_at)}</td>
<td class="px-4 py-2">${row.plat_ke}</td>
<td class="px-4 py-2">${Number(row.arus).toFixed(2)}</td>
<td class="px-4 py-2">${Number(row.energi_cycle).toFixed(2)}</td>
`

                tbody.appendChild(tr)

            })

        }

        /* PAGINATION */

        function renderPagination() {

            const pagination = document.getElementById('pagination')
            pagination.innerHTML = ""

            const totalPages = Math.ceil(todayData.length / rowsPerPage)

            if (totalPages <= 1) return

            const info = document.createElement('span')
            info.textContent = `Page ${currentPage} dari ${totalPages}`
            pagination.appendChild(info)

            for (let i = 1; i <= totalPages; i++) {

                const btn = document.createElement('button')

                btn.textContent = i
                btn.className = "px-3 py-1 border rounded"

                btn.onclick = () => {
                    currentPage = i
                    renderTablePage(currentPage)
                    renderPagination()
                }

                pagination.appendChild(btn)

            }

        }

        /* ANALISA */

        function renderDailyAnalysis() {

            const total = todayData.reduce((s, r) =>
                s + Number(r.energi_cycle), 0)

            const biaya = (total / 1000) * tarifPerKWh

            document.getElementById('total-energi')
                .textContent = total.toFixed(2)

            document.getElementById('analysisContent')
                .innerHTML = `
<p>Total Energi : ${total.toFixed(2)} Wh</p>
<p>Total Biaya : Rp ${biaya.toFixed(0)}</p>
`

        }

        /* FETCH */

        async function fetchCNCData() {

            const res = await fetch('/api/data')
            const data = await res.json()

            todayData = data.filter(r => isToday(r.created_at))

            renderTablePage(currentPage)
            renderPagination()
            renderDailyAnalysis()

            /* chart 1 */

            const energiPerJam = Array(24).fill(0)

            todayData.forEach(r => {

                const h = new Date(r.created_at).getHours()

                energiPerJam[h] += Number(r.energi_cycle)

            })

            energiChart.data.datasets[0].data = energiPerJam
            energiChart.update()

            /* chart 2 */

            const perPlat = {}

            todayData.forEach(r => {

                const h = new Date(r.created_at).getHours()

                if (!perPlat[r.plat_ke])
                    perPlat[r.plat_ke] = Array(24).fill(0)

                perPlat[r.plat_ke][h] += Number(r.energi_cycle)

            })

            multiChart.data.datasets = Object.keys(perPlat).map((p, i) => ({

                label: 'Plat ' + p,
                data: perPlat[p],
                borderColor: `hsl(${i*60},70%,50%)`,
                tension: 0.3

            }))

            multiChart.update()

        }

        fetchCNCData()
        setInterval(fetchCNCData, 3000)

        /* TOGGLE CHART */

        const chartContainer1 = document.getElementById('chartContainer1')
        const chartContainer2 = document.getElementById('chartContainer2')

        const toggleChart1 = document.getElementById('toggleChart1')
        const toggleChart2 = document.getElementById('toggleChart2')

        function setupToggle(container, button) {

            let min = true

            button.onclick = () => {

                if (min) {

                    container.style.height = '420px'
                    button.textContent = 'Minimize Chart'

                } else {

                    container.style.height = '220px'
                    button.textContent = 'Maksimalkan Chart'

                }

                min = !min

            }

        }

        setupToggle(chartContainer1, toggleChart1)
        setupToggle(chartContainer2, toggleChart2)

        /* EXPORT CSV */

        document.getElementById('exportBtn').onclick = () => {

            if (todayData.length === 0) {
                alert("Data kosong")
                return
            }

            let csv = "Waktu,Plat,Arus,Energi\n"

            todayData.forEach(r => {

                csv +=
                    `${formatDateTime(r.created_at)},${r.plat_ke},${Number(r.arus).toFixed(2)},${Number(r.energi_cycle).toFixed(2)}\n`

            })

            const blob = new Blob([csv], {
                type: 'text/csv'
            })

            const a = document.createElement('a')

            a.href = URL.createObjectURL(blob)

            a.download = "laporan_cnc.csv"

            a.click()

        }
    </script>

</body>

</html>

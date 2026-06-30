<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Dashboard</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="app-content">
        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon text-bg-primary shadow-sm"><i class="bi bi-people"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Warga</span>
                            <span class="info-box-number">{{ $totalWarga }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon text-bg-warning shadow-sm"><i class="bi bi-person-lines-fill"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Tamu (Bulan Ini)</span>
                            <span class="info-box-number">{{ $tamuBulanIni }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon text-bg-success shadow-sm"><i class="bi bi-wallet2"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Saldo Kas RT</span>
                            <span class="info-box-number">Rp {{ number_format($saldoKas, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RT Info -->
            <div class="row mt-2">
                <div class="col-12">
                    <div class="card shadow-sm border-info mb-4">
                        <div class="card-body py-3 px-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded p-3 me-3">
                                    <i class="bi bi-geo-alt-fill text-info fs-3"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 fw-bold text-dark">Wilayah Rukun Tetangga (RT) {{ $rtInfo->rt }} / RW {{ $rtInfo->rw }}</h5>
                                    <p class="mb-0 text-muted">
                                        Kelurahan/Desa: {{ $rtInfo->village->name ?? '-' }}, 
                                        Kecamatan: {{ $rtInfo->village->district->name ?? '-' }}, 
                                        Kab/Kota: {{ $rtInfo->village->regency->name ?? '-' }}, 
                                        Provinsi: {{ $rtInfo->village->province->name ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Grafik Keuangan Kas RT</h5>
                        </div>
                        <div class="card-body">
                            <div id="kasChart" style="min-height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @script
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            var options = {
                series: [{
                    name: 'Pemasukan',
                    data: @json($chartPemasukan)
                }, {
                    name: 'Pengeluaran',
                    data: @json($chartPengeluaran)
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: @json($chartBulan),
                },
                yaxis: {
                    title: {
                        text: 'Rupiah (Rp)'
                    },
                    labels: {
                        formatter: function (val) {
                            return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                colors: ['#198754', '#dc3545'],
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#kasChart"), options);
            chart.render();
        });
    </script>
    @endscript
</div>

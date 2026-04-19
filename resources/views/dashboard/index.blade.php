@extends('layouts.main')
    @section('css')
        <link rel="stylesheet" type="text/css" href="https://printjs-4de6.kxcdn.com/print.min.css">
        <style>
            .table th, .table td {
                vertical-align: middle;
                text-align: center;
            }
        </style>
    @endsection
    @section('content')
    <div class="container mt-4" style="background-color: white; padding: 20px; border-radius: 5px;">
        <div class="row">
            <div class="col-12">
                <h1>CHARTS</h1>
                <hr>
                @include('includes.form_error')
                <div class="container-fluid">
                    <div class="row">
                        
                        <div class="col-sm-2">
                            <select onchange="changeYear()" class="form-select w-100" id="year" name="year" form="search">
                                <option value="">Select Year</option>
                                @foreach(range(date('Y'), date('Y') - 5) as $year)
                                    @if($year == request('year', date('Y')))
                                        <option value="{{ $year }}" selected>{{ $year }}</option>
                                    @else
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" onclick="printAllCharts()" class="btn btn-primary w-100">
                                Print Form
                            </button>
                        </div>
                        <div class="col-sm-2">
                            <a href="{{ route('dashboard.export-excel', ['year' => request('year', date('Y'))]) }}" class="btn btn-success w-100">
                                ALL ORDER EXCEL
                            </a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="container-fluid text-center" id="printJS-form">
                    <div class="row">
                        <div class="col-sm-6">
                            <h2>Product Sales</h2>
                            <canvas id="salesChart"></canvas>
                            <br>
                            <h2>Total Amount by Product</h2>
                            <canvas id="totalAmountChart"></canvas>
                        </div>
                        <div class="col-sm-6">
                            <h2>Category Sales</h2>
                            <canvas id="categorySoldChart"></canvas>
                        </div>
                    </div>
                    <div class="row pt-5">
                        <div class="col-sm-12">
                            <h2>Amount by Month</h2>
                            <canvas id="amountByMonthChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modals')

@endsection

@section('js')
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <script src="{{ asset('assets/vendor/chartjs/chart.umd.js-4.5.1.min.js') }}"></script>
    <script>
        let yearSelect = document.getElementById('year').value;
        let baseUrl = '{{ url('/') }}';
        setTimeout(() => {
            getQtySold(yearSelect);
            categorySold(yearSelect);
            getProductsAmountByYear(yearSelect);
            getAmountByMonth(yearSelect);
        }, 3000);
        
    </script>
    <script>
        function changeYear(){
            console.log('Year changed');
            let yearSelect = document.getElementById('year').value;
            // DESTROY EXISTING CHARTS
            Chart.getChart("salesChart")?.destroy();
            Chart.getChart("categorySoldChart")?.destroy();
            Chart.getChart("totalAmountChart")?.destroy();
            Chart.getChart("amountByMonthChart")?.destroy();
            getQtySold(yearSelect);
            categorySold(yearSelect);
            getProductsAmountByYear(yearSelect);
            getAmountByMonth(yearSelect);
        }
    </script>
    <script>
        function getQtySold(year){
             fetch('' + baseUrl + '/dashboard/products-amount/' + year)
            .then(response => response.json())
            .then(data => {
                const productNames = data.data.map(item => item.name);
                const totalQuantities = data.data.map(item => item.total_quantity);
                const backgroundColors = data.data.map(item => item.color);

                const ctx = document.getElementById('salesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: productNames,
                        datasets: [{
                            label: 'Total Quantity Sold',
                            data: totalQuantities,
                            backgroundColor: backgroundColors,
                            borderColor: backgroundColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));

        }

        function categorySold(year){
             fetch('' + baseUrl + '/dashboard/category-sold/' + year)
            .then(response => response.json())
            .then(data => {
                const categoryNames = data.data.map(item => item.name);
                const totalQuantities = data.data.map(item => item.total_quantity);
                const backgroundColors = data.data.map(item => item.color);

                const ctx = document.getElementById('categorySoldChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: categoryNames,
                        datasets: [{
                            label: 'Total Quantity Sold',
                            data: totalQuantities,
                            backgroundColor: backgroundColors,
                            borderColor: backgroundColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Category Sold'
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));

        }

        function getProductsAmountByYear(year){
             fetch('' + baseUrl + '/dashboard/products-amount-by-year/' + year)
            .then(response => response.json())
            .then(data => {
                const productNames = data.data.map(item => item.name);
                const totalAmounts = data.data.map(item => item.total_amount);
                const backgroundColors = data.data.map(item => item.color);

                const ctx = document.getElementById('totalAmountChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: productNames,
                        datasets: [{
                            label: 'Total Amount',
                            data: totalAmounts,
                            backgroundColor: backgroundColors,
                            borderColor: backgroundColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));

        }
        function getAmountByMonth(year){
             fetch('' + baseUrl + '/dashboard/amount-by-month/' + year)
            .then(response => response.json())
            .then(data => {
                const monthNames = data.data.map(item => item.month);
                const totalAmounts = data.data.map(item => item.total_amount);
                const backgroundColors = data.data.map(item => item.color);

                const ctx = document.getElementById('amountByMonthChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: monthNames,
                        datasets: [{
                            label: 'Total Amount',
                            data: totalAmounts,
                            backgroundColor: backgroundColors,
                            borderColor: backgroundColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));

        }

        function printAllCharts() {
   
            const canvases = document.querySelectorAll('canvas');
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Print Charts</title></head><body>');
            printWindow.document.write('<div style="text-align:center;">');
            
            printWindow.document.write('<div style="display:flex; flex-wrap:wrap; justify-content:center;">');
            canvases.forEach((canvas, index) => {
                const dataUrl = canvas.toDataURL('image/png');
                printWindow.document.write(`<div style="flex: 0 0 50%; text-align:center;">`);
                printWindow.document.write(`<h3>${canvas.previousElementSibling.innerText}</h3>`);
                printWindow.document.write(`<img src="${dataUrl}" style="width:100%; margin-bottom:20px; text-align:center;"/>`);
                printWindow.document.write(`</div>`);
            });
            printWindow.document.write('</div>');
            printWindow.document.write('</div>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // 4. Wait for images to load before printing
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
            }
    </script>
@endsection
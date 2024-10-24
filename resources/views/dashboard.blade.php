@extends('layouts.app')
@section('main-content')
    <style>
        .card-custom {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            width: 22%;
            height: 120px;
            border-radius: 20px;
            padding: 10px;
            margin: 20px;
        }

        .card-custom:hover {
            box-shadow: 0px 5px 10px lightblue;
        }
        .card-edit {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            width: 55%;
            height: 380px;
            border-radius: 20px;
            padding: 10px;
            margin: 20px;
        }

        .card-edit:hover {
            box-shadow: 0px 5px 10px lightblue;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h2 class="m-0">Dashboard</h2>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <!-- <li class="breadcrumb-item"><a href="#">Home</a></li> -->
                            <!-- <li class="breadcrumb-item active">Dashboard v2</li> -->
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Info boxes -->
                <div class="row">
                    <div class="card-custom d-flex align-items-center justify-content-between">
                        <div class="container">
                            <h4>Total Customer</h4>
                            <p class="font-weight-bold" style="font-size: 25px;">10</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <span
                                class="info-box-icon bg-secondary elevation-1 d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 55px; border-radius: 10px;">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.col -->
                    <div class="card-custom d-flex align-items-center justify-content-between">
                        <div class="container">
                            <h4>Total Cash</h4>
                            <p class="font-weight-bold" style="font-size: 25px;"><big>₹</big>9000</p>
                        </div>
                        <!-- /.info-box-content -->
                        <div class="d-flex align-items-center">
                            <span
                                class="info-box-icon bg-success elevation-1 d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 55px; border-radius: 10px;"><i class="fas fa-wallet"></i></span>
                        </div>
                    </div>
                    <!-- /.col -->
                    {{--  <div class="card-custom d-flex align-items-center justify-content-between">
                        <div class="container">
                            <h4>Total Debits</h4>
                            <p class="font-weight-bold" style="font-size: 25px;"><big>₹</big>4900</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <span
                                class="info-box-icon bg-danger elevation-1 d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 55px; border-radius: 10px;">
                                <i class="fas fa-thumbs-up"></i>
                            </span>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="card-custom d-flex align-items-center justify-content-between">
                        <div class="container">
                            <h4>Total Balance</h4>
                            <p class="font-weight-bold" style="font-size: 25px;"><big>₹</big>10977</p>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="info-box-icon bg-info elevation-1 d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 55px; border-radius: 10px;">
                                <i class="fas fa-rupee-sign"></i>
                            </span>
                        </div>
                    </div>  --}}
                    <!-- /.col -->
                </div>
                <!-- /.row -->
                <!-- Main row -->
                <div class="row">
                    <!-- Left col -->

                    <!-- MAP & BOX PANE -->
                    {{--  <div class="card-edit">
                        <!-- /.card-header -->
                        <div class="card-header">
                            <h6 class="mt-1">Today s Dues - <span class="today-date text-primary">:</span></h6>

                        </div>
                        <div class="card-body p-0">

                            <div class="table-responsive">
                                <table class="table align-items-center mb-0 table-bordered table-hover">
                                    <thead class="table-active">
                                        <tr>

                                            <th class="text-uppercase text-secondary text-x opacity-7 ps-2">ID</th>
                                            <th class="text-uppercase text-secondary text-x opacity-7 ps-2">Customer</th>
                                            <th class="text-uppercase text-secondary text-x opacity-7 ps-2">Amount</th>
                                            <th class="text-uppercase text-secondary text-x opacity-7 ps-2">Particular</th>
                                            <th class="text-uppercase text-secondary text-x opacity-7 ps-2">TXT Date</th>
                                            <th class="text-uppercase text-secondary text-x opacity-7 ps-2">DUE Date</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="pages/examples/invoice.html">1</a></td>
                                            <td>abc</td>
                                            <td>5000</td>
                                            <td>
                                                <div class="sparkbar" data-color="#00a65a" data-height="20">213</div>
                                            </td>
                                            <td>25-06-2024</td>
                                            <td>25-06-2024</td>
                                        </tr>

                                        <tr>
                                            <td><a href="pages/examples/invoice.html">2</a></td>
                                            <td>def</td>
                                            <td>1100</td>
                                            <td>
                                                <div class="sparkbar" data-color="#f39c12" data-height="20">123</div>
                                            </td>
                                            <td>25-06-2024</td>
                                            <td>25-06-2024</td>
                                        </tr>

                                        <tr>
                                            <td><a href="pages/examples/invoice.html">3</a></td>
                                            <td>abc</td>
                                            <td>123</td>
                                            <td>
                                                <div class="sparkbar" data-color="#f56954" data-height="20">342</div>
                                            </td>
                                            <td>25-06-2024</td>
                                            <td>25-06-2024</td>
                                        </tr>

                                        <tr>
                                            <td><a href="pages/examples/invoice.html">4</a></td>
                                            <td>abc</td>
                                            <td>120000</td>
                                            <td>
                                                <div class="sparkbar" data-color="#00c0ef" data-height="20">13</div>
                                            </td>
                                            <td>25-06-2024</td>
                                            <td>25-06-2024</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                    </div>
                    <!-- /.card -->

                    <!-- PRODUCT LIST -->
                    <div class="col-lg-5">
                        <div class="card-edit" style="border-radius: 20px; width: 95%; height: 400px;">
                            <div class="card-header">
                                <h3 class="card-title" style="font-weight:bold;">Overview</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="chart-responsive">

                                        </div>
                                        <!-- ./chart-responsive -->
                                    </div>

                                </div>
                                <!-- /.row -->
                                <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
                            </div>
                        </div>
                    </div>  --}}
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!--/. container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('script')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Your custom JavaScript -->
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get today's date
            var today = new Date();

            // Format the date (example: Monday, June 25, 2024)
            var options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            var formattedDate = today.toLocaleDateString('en-US', options);

            // Display the formatted date in the designated element
            var dateElement = document.querySelector('.today-date');
            dateElement.textContent = formattedDate;
        });
    </script> --}}
    {{-- <script type="text/javascript">
        < script src = "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js" >
    </script>
    <script>
        const xValues = ["Customer", "Credits", "Debits", "Payments", "Balance"];
        const yValues = [0, 713, 0, 340, 115.327, 0];
        const barColors = [
            "#b91d47",
            "#f7b531",
            "#29b350",
            "#1662fa",
            "#1e7145"
        ];

        new Chart("myChart", {
            type: "doughnut",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                title: {
                    display: true,
                    text: ""
                }
            }
        });
    </script> --}}
@endpush

@extends('admin_layout')
@section('admin_content')


<style>
    input::placeholder,
    select::placeholder {
        color: #6c757d !important; /* Added !important to override any other styles */
        opacity: 1 !important;
    }

    /* Add this to ensure placeholder text in select elements is visible */
    select option:first-child {
        color: #6c757d;
    }

    /* Reset autofill styles */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus {
        -webkit-text-fill-color: inherit !important;
        -webkit-box-shadow: 0 0 0px 1000px white inset;
        transition: background-color 5000s ease-in-out 0s;
    }

    /* Make empty password input show placeholder */
    input[type="password"]:placeholder-shown {
        font-family: inherit !important;
    }

    /* Keep the dots when user starts typing */
    input[type="password"]:not(:placeholder-shown) {
        font-family: password !important;
    }
</style>


<section class="container mt-4">
    <!-- Title -->
    <h3 class="mb-3">Pharmacy Management</h3>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <h4>Total Customer</h4>
                <h2>25</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <h4>Total Medicine</h4>
                <h2>25</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <h4>Total Manufactures</h4>
                <h2>25</h2>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between mb-4">
        <button class="btn btn-primary">Create Invoice</button>
        <button class="btn btn-secondary">Supplier</button>
        <button class="btn btn-secondary">Medicine</button>
    </div>

    <!-- Reports Section -->
    <div class="row">
        <!-- Purchases Reports -->
        <div class="col-md-6 mb-4">
            <div class="card p-4">
                <h5>Purchases Reports</h5>
                <canvas id="purchasesChart" style="height: 200px;"></canvas>
                <div class="d-flex justify-content-around mt-3">
                    <span>All time: <strong>41,234</strong></span>
                    <span>30 days: <strong>41,234</strong></span>
                    <span>7 days: <strong>41,234</strong></span>
                </div>
            </div>
        </div>

        <!-- Sales Reports -->
        <div class="col-md-3 mb-4">
            <div class="card p-4">
                <h5>Sales Reports</h5>
                <canvas id="salesPieChart" style="height: 200px;"></canvas>
            </div>
        </div>

        <!-- Stock Reports -->
        <div class="col-md-3 mb-4">
            <div class="card p-4">
                <h5>Stock Reports</h5>
                <canvas id="stockBarChart" style="height: 200px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Out of Stock Table -->
    <div class="card mt-4 p-4">
        <h5>Out of Stock</h5>
        <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</small>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Drug Name</th>
                    <th>Expire Date</th>
                    <th>Manufacture Date</th>
                    <th>Price</th>
                    <th>QTY</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Vitamin C</td>
                    <td>2025-04-13</td>
                    <td>2021-12-13</td>
                    <td>1500.00</td>
                    <td>150</td>
                    <td><span class="text-success">Available</span></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Paracetamol</td>
                    <td>2025-03-13</td>
                    <td>2022-04-04</td>
                    <td>4500.00</td>
                    <td>0</td>
                    <td><span class="text-danger">Out of Stock</span></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Actos</td>
                    <td>2026-01-16</td>
                    <td>2020-06-08</td>
                    <td>5000.00</td>
                    <td>65</td>
                    <td><span class="text-success">Available</span></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Amoxicillin</td>
                    <td>2024-12-13</td>
                    <td>2021-01-13</td>
                    <td>1200.00</td>
                    <td>275</td>
                    <td><span class="text-danger">Out of Stock</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Purchases Chart
    const purchasesCtx = document.getElementById('purchasesChart').getContext('2d');
    new Chart(purchasesCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Purchases',
                data: [500, 1500, 2212, 2000, 1800, 1200, 1600],
                borderColor: 'blue',
                tension: 0.3
            }]
        }
    });

    // Sales Pie Chart
    const salesCtx = document.getElementById('salesPieChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'pie',
        data: {
            labels: ['Chrome', 'IE', 'FireFox', 'Safari', 'Opera'],
            datasets: [{
                data: [30, 20, 25, 15, 10],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#8B78E6', '#4CAF50']
            }]
        }
    });

    // Stock Bar Chart
    const stockCtx = document.getElementById('stockBarChart').getContext('2d');
    new Chart(stockCtx, {
        type: 'bar',
        data: {
            labels: ['FireFox', 'Chrome', 'Opera', 'Safari', 'IE'],
            datasets: [{
                label: 'Stock',
                data: [13212, 15000, 12000, 10000, 8000],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#8B78E6', '#4CAF50']
            }]
        }
    });
</script>

@endsection

<?php include 'layouts/top.php'; ?>

<?php if (!isset($_SESSION['admin'])) {
    header('location: ' . ADMIN_URL . 'login.php');
} ?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1 class="display-4 mb-4">Dashboard</h1>
        </div>
        
        <div class="row g-4 mb-4">
            <!-- Summary Cards -->
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-primary mb-4 shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Services</h5>
                        <h2 class="card-text">50</h2>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-warning mb-4 shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Projects</h5>
                        <h2 class="card-text">20</h2>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <i class="fas fa-folder-open"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-success mb-4 shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Posts</h5>
                        <h2 class="card-text">100</h2>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <i class="fas fa-pencil-alt"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-danger mb-4 shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Payments</h5>
                        <h2 class="card-text">250</h2>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>

            <!-- Revenue Cards -->
            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-info mb-4 shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Revenue from Payments</h5>
                        <h2 class="card-text">₹15,000.00</h2>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-success mb-4 shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Revenue from Customers</h5>
                        <h2 class="card-text">₹12,000.00</h2>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card text-white bg-warning mb-4 shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Revenue from Invoices</h5>
                        <h2 class="card-text">₹10,000.00</h2>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">View Details</a>
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional dashboard content -->
        <div class="row g-4">
            <div class="col-xl-6">
                <div class="card mb-4 shadow">
                    <div class="card-header">
                        <i class="fas fa-chart-area me-1"></i>
                        Project Completion Rate
                    </div>
                    <div class="card-body">
                        <canvas id="projectCompletionChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card mb-4 shadow">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-1"></i>
                        Revenue by Product
                    </div>
                    <div class="card-body">
                        <canvas id="revenueByProductChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card mb-4 shadow">
                    <div class="card-header">
                        <i class="fas fa-database me-1"></i>
                        Database Table Counts
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">About: <span class="badge bg-secondary">5</span></li>
                            <li class="list-group-item">Carousel Images: <span class="badge bg-secondary">8</span></li>
                            <li class="list-group-item">Cashfree Config: <span class="badge bg-secondary">1</span></li>
                            <li class="list-group-item">Cashfree Payments: <span class="badge bg-secondary">250</span></li>
                            <li class="list-group-item">Comments: <span class="badge bg-secondary">100</span></li>
                            <li class="list-group-item">Users: <span class="badge bg-secondary">150</span></li>
                            <li class="list-group-item">Customers: <span class="badge bg-secondary">50</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Project Completion Rate Chart
    const ctxCompletion = document.getElementById('projectCompletionChart').getContext('2d');
    const projectCompletionChart = new Chart(ctxCompletion, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Projects Completed',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
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

    // Revenue by Product Chart
    const ctxRevenue = document.getElementById('revenueByProductChart').getContext('2d');
    const revenueByProductChart = new Chart(ctxRevenue, {
        type: 'bar',
        data: {
            labels: ['Product 1', 'Product 2', 'Product 3', 'Product 4', 'Product 5'],
            datasets: [{
                label: 'Revenue',
                data: [3000, 2000, 1500, 2500, 3500],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
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
});
</script>

<?php include 'layouts/footer.php'; ?>

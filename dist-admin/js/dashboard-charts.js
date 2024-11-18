// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Project Completion Rate Chart
    var ctxArea = document.getElementById("projectCompletionChart");
    if (ctxArea) {
        var myLineChart = new Chart(ctxArea, {
            type: 'line',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Completion Rate",
                    lineTension: 0.3,
                    backgroundColor: "rgba(2,117,216,0.2)",
                    borderColor: "rgba(2,117,216,1)",
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(2,117,216,1)",
                    pointBorderColor: "rgba(255,255,255,0.8)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(2,117,216,1)",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: [65, 70, 80, 81, 76, 85, 90, 95, 91, 95, 98, 100],
                }],
            },
            options: {
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        ticks: {
                            min: 0,
                            max: 100,
                            maxTicksLimit: 5
                        },
                        grid: {
                            color: "rgba(0, 0, 0, .125)",
                        }
                    },
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Revenue by Service Chart
    var ctxBar = document.getElementById("revenueByServiceChart");
    if (ctxBar) {
        var myBarChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ["Web Dev", "Mobile App", "SEO", "Consulting", "Branding"],
                datasets: [{
                    label: "Revenue",
                    backgroundColor: "rgba(2,117,216,1)",
                    borderColor: "rgba(2,117,216,1)",
                    data: [4215, 5312, 6251, 7841, 9821],
                }],
            },
            options: {
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        ticks: {
                            min: 0,
                            max: 10000,
                            maxTicksLimit: 5
                        },
                        grid: {
                            color: "rgba(0, 0, 0, .125)",
                        }
                    },
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
});
// import './bootstrap';

// import Alpine from 'alpinejs';

// window.Alpine = Alpine;

// Alpine.start();


// document.addEventListener('DOMContentLoaded', function () {
//     var button = document.querySelector('[data-bs-target="#customerTable"]');
//     var icon = button.querySelector('.chevron-icon');
//     var customerTable = document.getElementById('customerTable');

//     customerTable.addEventListener('hidden.bs.collapse', function () {
//         icon.classList.remove('fa-chevron-up');
//         icon.classList.add('fa-chevron-down');
//     });

//     customerTable.addEventListener('shown.bs.collapse', function () {
//         icon.classList.remove('fa-chevron-down');
//         icon.classList.add('fa-chevron-up');
//     });
// });

// Finding active sessions
function loadActiveSessions() {
    const formatBytes = (bytes) => {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    };

    const secondsToHHMMSS = (seconds) => {
        return new Date(seconds * 1000).toISOString().substr(11, 8);
    };

    const fetchActiveSessions = (username) => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/admin/active-session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                pppoe: username
            })
        })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('activeSessionContainer');
                if (data.success) {
                    const sessions = data.active_sessions;
                    const tableRows = sessions.map(session => `
                        <tr>
                            <td>${session.username}</td>
                            <td>${formatBytes(session.acctinputoctets || 0)}</td>
                            <td>${formatBytes(session.acctoutputoctets || 0)}</td>
                            <td>${session.acctstarttime}</td>
                            <td>${secondsToHHMMSS(session.acctsessiontime || 0)}</td>
                            <td>${session.framedipaddress}</td>
                            <td>${session.nasipaddress}</td>
                        </tr>
                    `).join('');

                    container.innerHTML = `
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Login</th>
                                            <th>In</th>
                                            <th>Out</th>
                                            <th>Start at</th>
                                            <th>Duration</th>
                                            <th>IP</th>
                                            <th>NAS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${tableRows}
                                    </tbody>
                                </table>
                            </div>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('activeSessionContainer').innerHTML = '<p>Error loading active sessions data.</p>';
            });
    };

    // Initial load
    let username = $('#serviceSelect').val();
    fetchActiveSessions(username);

    // Detect change in #serviceSelect
    $('#serviceSelect').on('change', function () {
        username = $(this).val();
        fetchActiveSessions(username);
    });
}


function initializeDateRangePicker() {
    var start = moment().startOf('month');
    var end = moment().endOf('month');

    function cb(start, end) {
        $('#dateRangeSelect').val(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#dateRangeSelect').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    // Add event listener for date range change
    $('#dateRangeSelect').on('apply.daterangepicker', function (ev, picker) {
        // Call your function to update data based on the selected date range
        updateDataBasedOnDateRange(picker.startDate, picker.endDate);
        updateDailyBandwidthChart(picker.startDate, picker.endDate);
        loadEndedSessions(picker.startDate, picker.endDate)
    });

    // Add event listener for refresh button
    $('#refreshButton').on('click', function () {
        var picker = $('#dateRangeSelect').data('daterangepicker');
        updateDataBasedOnDateRange(picker.startDate, picker.endDate);
        updateDailyBandwidthChart(picker.startDate, picker.endDate);
        loadEndedSessions(picker.startDate, picker.endDate)
    });
    // Call the main function on page load
    updateDataBasedOnDateRange(start, end);
    updateDailyBandwidthChart(start, end);
    loadEndedSessions(start, end)
}





let eventSource = null;
let rxData = [];
let txData = [];
let streamingDuration = 60000; // Default streaming duration is 1 minute
const subscriptionSelect = document.getElementById('serviceSelect');
let subscriptionId = subscriptionSelect.value;
subscriptionSelect.addEventListener('change', function () {
    subscriptionId = this.value;
    // Clear existing data arrays
    rxData = [];
    txData = [];

    // Clear existing chart data
    bandwidthChart.data.datasets[0].data = rxData;
    bandwidthChart.data.datasets[1].data = txData;

    // Update the chart
    bandwidthChart.update('none'); // Use 'none' to skip animation

    // Re-setup SSE connection
    setupRealtimeChart();
});
const config = {
    type: 'line',
    data: {
        datasets: [{
            label: 'Upload',
            backgroundColor: 'rgba(255, 121, 149, 0.5)',
            borderColor: 'rgb(255, 121, 149)',
            data: rxData,
            fill: true,
            tension: 0.4
        }, {
            label: 'Download',
            backgroundColor: 'rgba(82, 175, 238, 0.5)',
            borderColor: 'rgb(82, 175, 238)',
            data: txData,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
            streaming: {
                duration: streamingDuration,
                refresh: 1000,
                delay: 2000,
                frameRate: 30,
                pause: false,
                ttl: undefined,
            }
        },
        scales: {
            x: {
                type: 'realtime',
                time: {
                    unit: 'second',
                    displayFormats: {
                        second: 'HH:mm:ss'
                    },
                    tooltipFormat: 'HH:mm:ss'
                },
                ticks: {
                    major: {
                        enabled: true,
                        fontStyle: 'bold'
                    },
                    autoSkip: true,
                    maxTicksLimit: 10,
                    maxRotation: 45,
                    minRotation: 45
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                },
                ticks: {
                    callback: function (value) {
                        if (value >= 1e9) {
                            return (value / 1e9) + ' Gbps';
                        } else if (value >= 1e6) {
                            return (value / 1e6) + ' Mbps';
                        } else if (value >= 1e3) {
                            return (value / 1e3) + ' Kbps';
                        } else {
                            return value + ' bps';
                        }
                    }
                }
            }
        },
        animation: {
            duration: 0
        }
    }
};

// Create a new Chart instance
const ctx = document.getElementById('bandwidthChart').getContext('2d');
const bandwidthChart = new Chart(ctx, config);

// Function to setup SSE connection and handle reconnections
function setupRealtimeChart() {
    // Close existing SSE connection if open
    if (eventSource) {
        eventSource.close();
    }

    // Initialize SSE connection
    // eventSource = new EventSource('/sse');
    eventSource = new EventSource(`/sse?subscription_id=${subscriptionId}`);

    // SSE error handling and reconnect logic
    eventSource.onerror = function (error) {
        // console.error('SSE Error:', error);
        // // Attempt to reconnect after 3 seconds
        // setTimeout(() => {
        //     setupRealtimeChart(); // Re-establish SSE connection
        // }, 3000);

        // Destroy the chart instance if it exists
        if (bandwidthChart) {
            bandwidthChart.destroy(); // Properly destroy the Chart.js instance
            bandwidthChart = null; // Clear the reference to the destroyed chart
        }

        // Remove the canvas element
        const canvas = document.getElementById('bandwidthChart');
        if (canvas) {
            canvas.remove();
        }

        // Create and insert the new div element
        noDataDiv.id = 'noDataMessage'; // Optional: give it an ID for styling
        noDataDiv.style.display = 'flex';
        noDataDiv.style.justifyContent = 'center';
        noDataDiv.style.alignItems = 'center';
        noDataDiv.style.height = '200px'; // Set this to match the height of the old canvas
        noDataDiv.style.width = '100%'; // Full width
        noDataDiv.style.textAlign = 'center';
        noDataDiv.style.fontSize = '20px';
        noDataDiv.style.color = '#666'; // Optional: adjust color
        noDataDiv.innerHTML = '<p>No data to display</p>';

        // Insert the new div into the container
        const container = document.getElementById('bandwidthChartContainer'); // Ensure you have a container element with this ID
        if (container) {
            container.appendChild(noDataDiv);
        }

        // // Replace canvas with a div containing the text "No data to display"
        // const chartContainer = document.getElementById('bandwidthChartContainer'); // Assume you have a chart container element
        // const noDataDiv = document.createElement('div');
        // noDataDiv.textContent = 'No data to display';
        // chartContainer.appendChild(noDataDiv);
        if (eventSource) {
            eventSource.close();
        }
    };

    // EventSource event handler for receiving data
    eventSource.onmessage = function (event) {
        const data = JSON.parse(event.data);

        // Add new data point
        const newTime = new Date().getTime(); // Use milliseconds for timestamp

        // Push new data to the datasets
        rxData.push({
            x: newTime,
            y: data.rxRate
        });
        txData.push({
            x: newTime,
            y: data.txRate
        });

        // Limit the data arrays to show only data within the selected time frame
        const cutoff = newTime - streamingDuration; // Calculate cutoff based on selected duration
        removeOldData(rxData, cutoff);
        removeOldData(txData, cutoff);

        // Update the chart
        bandwidthChart.update('none'); // Use 'none' to skip animation
    };

    // Function to remove old data from arrays
    function removeOldData(dataArray, cutoff) {
        while (dataArray.length > 0 && dataArray[0].x < cutoff) {
            dataArray.shift();
        }
    }
}

// Refresh button click event listener
function refreshRealtimeChart() {
    // Clear existing data arrays
    rxData = [];
    txData = [];

    // Clear existing chart data
    bandwidthChart.data.datasets[0].data = rxData;
    bandwidthChart.data.datasets[1].data = txData;

    // Update the chart
    bandwidthChart.update('none'); // Use 'none' to skip animation

    // Re-setup SSE connection
    setupRealtimeChart();
}

// Stop SSE and clear data on page refresh or unload
window.addEventListener('beforeunload', function () {
    if (eventSource) {
        eventSource.close();
    }
    rxData = [];
    txData = [];
    bandwidthChart.data.datasets[0].data = rxData;
    bandwidthChart.data.datasets[1].data = txData;
    bandwidthChart.update('none');
});


// Event listener for time frame selection change
document.getElementById('timeFrameSelect').addEventListener('change', function () {
    // Update streaming duration based on selected option
    streamingDuration = parseInt(this.value);

    // Update streaming duration in chart options
    bandwidthChart.options.plugins.streaming.duration = streamingDuration;

    // Re-setup SSE connection with updated streaming duration
    setupRealtimeChart();
});

// Function to update data based on selected date range
async function updateDataBasedOnDateRange(startDate, endDate) {
    var selectedService = $('#serviceSelect').val();
    try {
        const response = await fetch('/admin/data-totals/' + selectedService + '/' + startDate.format('YYYY-MM-DD HH:mm:ss') + '/' + endDate.format('YYYY-MM-DD HH:mm:ss'));
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();

        // Update UI with the new data
        $('#total-download').text(formatBytes(data.total_download));
        $('#total-upload').text(formatBytes(data.total_upload));
        $('#total-uptime').text(formatDuration(data.total_uptime));
        $('#total-sessions').text(data.total_sessions);

        // // Update daily usage graph
        // updateDailyUsageGraph(data.daily_usage);
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}


let bandwidthAverageChart;

function updateBandwidthChart(period) {
    const username = $('#serviceSelect').val();

    const currentYear = new Date().getFullYear();
    const startYear = period === 'yearly' ? currentYear - 5 : currentYear;

    fetch(`/bandwidth/average?username=${username}&start_year=${startYear}&end_year=${currentYear}`)
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('bandwidthAverageChart').getContext('2d');

            if (bandwidthAverageChart) {
                bandwidthAverageChart.destroy();
            }

            const chartData = data[period] || [];
            const labels = [];
            const downloadValues = [];
            const uploadValues = [];

            let startDate, endDate, formatDate, increment;

            // Determine the start and end dates and format
            if (period === 'hourly') {
                startDate = moment().startOf('day');
                endDate = moment().endOf('day');
                formatDate = 'HH:mm';
                increment = 'hour';
            } else if (period === 'daily') {
                startDate = moment().startOf('week');
                endDate = moment().endOf('week');
                formatDate = 'YYYY-MM-DD';
                increment = 'day';
            } else if (period === 'weekly') {
                startDate = moment().startOf('month').startOf('week');
                endDate = moment().endOf('month').endOf('week');
                formatDate = 'YYYY-WW';
                increment = 'week';
            } else if (period === 'monthly') {
                startDate = moment().startOf('year').startOf('month');
                endDate = moment().endOf('year').endOf('month');
                formatDate = 'YYYY-MM';
                increment = 'month';
            } else if (period === 'yearly') {
                startDate = moment().startOf('year').subtract(5, 'years');
                endDate = moment().endOf('year');
                formatDate = 'YYYY';
                increment = 'year';
            }

            // Generate all possible labels and data points
            let tempDate = startDate.clone();
            while (tempDate <= endDate) {
                labels.push(tempDate.format(formatDate));
                downloadValues.push(0); // Default to zero if no data
                uploadValues.push(0);   // Default to zero if no data
                tempDate.add(1, increment);
            }

            // Map data to the corresponding labels
            chartData.forEach(item => {
                let formattedDate;
                if (period === 'hourly') {
                    formattedDate = moment(item.hour).format('HH:mm');
                } else if (period === 'daily') {
                    formattedDate = moment(item.day).format('YYYY-MM-DD');
                } else if (period === 'weekly') {
                    formattedDate = `Week ${moment(item.week).format('YYYY-WW')}`;
                } else if (period === 'monthly') {
                    formattedDate = moment(item.month).format('YYYY-MM');
                } else if (period === 'yearly') {
                    formattedDate = moment(item.year).format('YYYY');
                }

                const index = labels.indexOf(formattedDate);
                if (index !== -1) {
                    downloadValues[index] = Math.max(0, item.average_download);
                    uploadValues[index] = Math.max(0, item.average_upload);
                }
            });

            // Reverse arrays to ensure the latest record is on the right
            labels.reverse();
            downloadValues.reverse();
            uploadValues.reverse();

            // Create the chart
            bandwidthAverageChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Average Download',
                        data: downloadValues,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                        tension: 0.4,
                        cubicInterpolationMode: 'monotone'
                    },
                    {
                        label: 'Average Upload',
                        data: uploadValues,
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        fill: true,
                        tension: 0.4,
                        cubicInterpolationMode: 'monotone'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 0
                    },
                    scales: {
                        x: {
                            ticks: {
                                maxTicksLimit: 10,
                                autoSkip: false // Show all labels
                            },
                            title: {
                                display: true,
                                text: period.charAt(0).toUpperCase() + period.slice(1)
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Bandwidth'
                            },
                            ticks: {
                                callback: function (value) {
                                    return formatBandwidth(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += formatBandwidth(context.parsed.y);
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}

document.getElementById('bandwidthPeriod').addEventListener('change', function () {
    updateBandwidthChart(this.value);
});


function updateDailyBandwidthChart(start, end) {
    const username = $('#serviceSelect').val();
    fetch(
        `/bandwidth/daily?username=${username}&start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`
    )
        .then(response => response.json())
        .then(data => {
            // Ensure we have data for all dates in the range
            const allDates = [];
            let currentDate = start.clone();
            while (currentDate <= end) {
                allDates.push(currentDate.format('YYYY-MM-DD'));
                currentDate.add(1, 'day');
            }

            const filledData = allDates.map(date => {
                const existingData = data.find(item => item.date === date);
                return existingData || {
                    date: date,
                    total_download: "0",
                    total_upload: "0"
                };
            });

            const hasPositiveData = filledData.some(item =>
                parseFloat(item.total_download) >= 0 || parseFloat(item.total_upload) >= 0
            );

            if (!hasPositiveData) {
                document.getElementById('dailyBandwidthChart').style.display = 'none';
                document.getElementById('noDataMessage').style.display = 'block';
                return;
            }

            document.getElementById('dailyBandwidthChart').style.display = 'block';
            document.getElementById('noDataMessage').style.display = 'none';

            const ctx = document.getElementById('dailyBandwidthChart').getContext('2d');

            // Check if the chart instance already exists and destroy it
            if (window.dailyBandwidthChartInstance) {
                window.dailyBandwidthChartInstance.destroy();
            }

            window.dailyBandwidthChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: filledData.map(item => item.date),
                    datasets: [{
                        label: 'Download',
                        data: filledData.map(item => Math.max(0, parseFloat(item
                            .total_download) || 0)),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Upload',
                        data: filledData.map(item => Math.max(0, parseFloat(item
                            .total_upload) || 0)),
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: true,
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Bandwidth'
                            },
                            ticks: {
                                callback: function (value) {
                                    return formatBytes(Math.max(0, value));
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += formatBytes(Math.max(0, context.parsed.y));
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching bandwidth data:', error);
            document.getElementById('dailyBandwidthChart').style.display = 'none';
            document.getElementById('noDataMessage').style.display = 'block';
        });
}

function loadEndedSessions(startDate, endDate) {
    const username = $('#serviceSelect').val();
    const tableBody = document.querySelector('#endedSessionsTable tbody');

    // Clear the table body content before starting the fetch
    tableBody.innerHTML = '';

    // Show a loading message or spinner (optional)
    const loadingMessage = document.createElement('tr');
    loadingMessage.innerHTML = `<td colspan="6">Loading...</td>`;
    tableBody.appendChild(loadingMessage);

    fetch(
        `/admin/ended-sessions/${username}?start_date=${startDate.format('YYYY-MM-DD')}&end_date=${endDate.format('YYYY-MM-DD')}`
    )
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Remove the loading message
            tableBody.innerHTML = '';

            if (data.success) {
                if (data.data.length === 0) {
                    // Show a message when no data is found
                    const noDataMessage = document.createElement('tr');
                    noDataMessage.innerHTML = `<td colspan="6">No ended sessions found for the selected period.</td>`;
                    tableBody.appendChild(noDataMessage);
                } else {
                    // Populate the table with data
                    const fragment = document.createDocumentFragment();
                    data.data.forEach(session => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${session.radacctid}</td>
                            <td>${new Date(session.acctstarttime).toLocaleString()}</td>
                            <td>${new Date(session.acctstoptime).toLocaleString()}</td>
                            <td>${formatDuration(session.acctsessiontime)}</td>
                            <td>${formatBytes(session.acctoutputoctets)}</td>
                            <td>${formatBytes(session.acctinputoctets)}</td>
                        `;
                        fragment.appendChild(row);
                    });
                    tableBody.appendChild(fragment);
                }
            } else {
                // Handle any issues with the API response
                const errorMessage = document.createElement('tr');
                errorMessage.innerHTML = `<td colspan="6">Error: ${data.message || 'An error occurred while fetching data.'}</td>`;
                tableBody.appendChild(errorMessage);
            }
        })
        .catch(error => {
            // Remove the loading message and display error
            tableBody.innerHTML = '';
            const errorRow = document.createElement('tr');
            errorRow.innerHTML = `<td colspan="6">Error: ${error.message}</td>`;
            tableBody.appendChild(errorRow);
        });
}



function formatBandwidth(bytes) {
    const units = ['bps', 'Kbps', 'Mbps', 'Gbps', 'Tbps'];
    let value = bytes * 8; // Convert bytes/s to bits/s
    let unitIndex = 0;
    while (value >= 1024 && unitIndex < units.length - 1) {
        value /= 1024;
        unitIndex++;
    }
    return value.toFixed(2) + ' ' + units[unitIndex];
}
function formatDuration(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const remainingSeconds = seconds % 60;
    return `${hours}h ${minutes}m ${remainingSeconds}s`;
}

function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}



document.addEventListener('DOMContentLoaded', (event) => {
    // Retrieve the active tab ID from local storage
    var activeTab = localStorage.getItem('activeTab');

    if (activeTab) {
        // Check if active tab is statistics
        if (activeTab === 'statistics') {
            initializeDateRangePicker();
            updateBandwidthChart("hourly");
            loadActiveSessions();
            // setupRealtimeChart();
        } else {
            // Close existing SSE connection if open
            if (eventSource) {
                eventSource.close();
            }
        }
    }
});
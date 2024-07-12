import Chart from 'chart.js/auto';
import moment from 'moment';
import 'chartjs-adapter-moment';


function formatBandwidth(value) {
    if (value >= 1000000) {
        return (value / 1000000).toFixed(2) + ' Gbps';
    } else if (value >= 1000) {
        return (value / 1000).toFixed(2) + ' Mbps';
    } else if (value > 0) {
        return value.toFixed(2) + ' kbps';
    } else {
        return '0 kbps';
    }
}

// function fetchBandwidth() {
//     const customerId = window.location.pathname.split('/')[3];
//     fetch(`/admin/customer/${customerId}/bandwidth`)
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 const tx_kbps = Math.max(data.tx_bits_per_second / 1000, 0);
//                 const rx_kbps = Math.max(data.rx_bits_per_second / 1000, 0);

//                 document.getElementById('bandwidthValues').innerHTML = `Upload: ${formatBandwidth(tx_kbps)}, Download: ${formatBandwidth(rx_kbps)}`;

//                 const now = moment().toDate();
//                 chart.data.datasets[0].data.push({ x: now, y: tx_kbps });
//                 chart.data.datasets[1].data.push({ x: now, y: rx_kbps });

//                 // Remove old data points to keep the chart within the desired range
//                 if (chart.data.datasets[0].data.length > 30) {
//                     chart.data.datasets[0].data.shift();
//                     chart.data.datasets[1].data.shift();
//                 }

//                 updateXAxis();
//                 chart.update('none'); // Use 'none' to skip animations
//             }
//         });
// }

// function updateXAxis() {
//     const now = moment().toDate();
//     const minTime = moment().subtract(30, 'seconds').toDate();

//     chart.options.scales.x.min = minTime;
//     chart.options.scales.x.max = now;

//     chart.update('none'); // Use 'none' to skip animations
// }

// fetchBandwidth();
// setInterval(fetchBandwidth, 100);
// requestAnimationFrame(function animate() {
//     updateXAxis();
//     requestAnimationFrame(animate);
// });

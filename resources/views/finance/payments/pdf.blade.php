<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<script>
    window.onload = function() {
        const {
            jsPDF
        } = window.jspdf;
        const doc = new jsPDF();

        // Set custom fonts
        doc.setFont("helvetica", "normal");

        // Add title
        doc.setFontSize(24);
        doc.setTextColor(0, 0, 0);
        doc.text('Receipt #{{ $payment->recordable->transaction_id}}', 105, 20, null, null, 'center');

        // Add company info
        doc.setFontSize(12);
        doc.setTextColor(100, 100, 100);
        doc.text('Your Company Name', 20, 40);
        doc.setFontSize(10);
        doc.text('123 Business Street, City, Country', 20, 45);
        doc.text('Phone: +1 234 567 890', 20, 50);
        doc.text('Email: info@yourcompany.com', 20, 55);

        // Add receipt info
        doc.setFontSize(10);
        doc.text('Reciept to:  {{ $payment->recordable->customer->name }}', 130, 40);
        doc.text('Document number: # {{ $payment->recordable->id }}', 130, 45);
        doc.text('Transaction time: {{ $payment->created_at }}', 130, 50);

        // Set up the table
        doc.autoTable({
            head: [
                ['Payment type', 'Transaction ID', 'Sum']
            ],
            body: [
                ['{{ $payment->payment_method }}',
                    '{{ $payment->transaction_id }}', '{{ $payment->amount }}'
                ]
            ],
            startY: 70,
            styles: {
                font: 'helvetica',
                fillColor: [255, 255, 255],
                textColor: [50, 50, 50],
                lineColor: [200, 200, 200],
                lineWidth: 0.1
            },
            headStyles: {
                fillColor: [240, 240, 240],
                textColor: [0, 0, 0],
                fontStyle: 'bold'
            },
            theme: 'plain',
            tableLineColor: [200, 200, 200],
            tableLineWidth: 0.1,
            margin: {
                top: 70,
                bottom: 30,
                left: 20,
                right: 20
            }
        });

        // Add a thank you message
        doc.setFontSize(12);
        doc.setTextColor(100, 100, 100);
        doc.text('Thank you for your business!', 105, doc.autoTable.previous.finalY + 20, null, null, 'center');

        // Add footer
        doc.setFontSize(10);
        doc.setTextColor(150, 150, 150);
        doc.text('Page 1 of 1', 105, 285, null, null, 'center');

        // Add document generation date and time
        var now = new Date();
        var dateTime = now.toLocaleString();
        doc.text('Generated on: ' + dateTime, 105, 290, null, null, 'center');

        doc.save('Reciept-{{ $payment->recordable->transaction_id}}.pdf');

        // Close the page after downloading
        setTimeout(function() {
            window.close();
        }, 1000);
    }
</script>

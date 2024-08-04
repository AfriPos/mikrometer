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
        doc.text('Invoice #{{ $invoice->recordable->invoice_number }}', 105, 20, null, null, 'center');
        // Add company info
        doc.setFontSize(12);
        doc.setTextColor(100, 100, 100);
        doc.text('Your Company Name', 20, 40);
        doc.setFontSize(10);
        doc.text('123 Business Street, City, Country', 20, 45);
        doc.text('Phone: +1 234 567 890', 20, 50);
        doc.text('Email: info@yourcompany.com', 20, 55);

        // Add invoice info
        doc.setFontSize(10);
        doc.text('Invoice to: {{ $invoice->recordable->customer->name }}', 130, 40);
        doc.text('Document number: # {{ $invoice->recordable->id }}', 130, 45);
        doc.text('Document Date: {{ $invoice->recordable->created_at }}', 130, 50);
        doc.text('Due Date: {{ $invoice->recordable->due_date }}', 130, 55);

        // Set up the table
        let finalY = 70;
        console.log('payments');
        doc.autoTable({
            head: [
                ['#', 'Description', 'Price']
            ],
            body: [
                @if (count($payments) > 0)
                    @foreach ($payments as $index => $item)
                        ['{{ $index + 1 }}', '{{ $item->description }}', '{{ $item->amount }}'],
                    @endforeach
                @endif
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
            },
            didDrawPage: function(data) {
                finalY = data.cursor.y;
            }
        });
        // Add totals
        doc.setFontSize(10);
        doc.text('Total without VAT:', 140, finalY + 10);
        doc.text('Ksh {{ $invoice->recordable->amount }}', 190, finalY + 10, null, null, 'right');
        doc.text('VAT:', 140, finalY + 15);
        doc.text('Ksh ' + ({{ $invoice->recordable->amount }} * 0.16).toFixed(2), 190, finalY + 15, null, null,
            'right');
        doc.setFontSize(12);
        doc.setFont("helvetica", "bold");
        doc.text('Total:', 140, finalY + 25);
        doc.text('Ksh ' + ({{ $invoice->recordable->amount }} * 1.16).toFixed(2), 190, finalY + 25, null, null,
            'right');
        doc.setFont("helvetica", "normal");
        doc.setFontSize(10);
        doc.text('Due:', 140, finalY + 30);
        doc.text('Ksh ' + ({{ $invoice->recordable->due_amount }}).toFixed(2), 190, finalY + 30, null, null,
            'right');

        // Add footer
        doc.setFontSize(10);
        doc.setTextColor(150, 150, 150);
        doc.text('Page 1 of 1', 105, 285, null, null, 'center');

        // Add document generation date and time
        var now = new Date();
        var dateTime = now.toLocaleString();
        doc.text('Generated on: ' + dateTime, 105, 290, null, null, 'center');

        doc.save('invoice-{{ $invoice->recordable->invoice_number }}.pdf');

        // Close the page after downloading
        setTimeout(function() {
            window.close();
        }, 1000);
    }
</script>

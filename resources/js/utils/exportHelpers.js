import * as XLSX from 'xlsx';
import jsPDF from 'jspdf';
import 'jspdf-autotable';

export function exportToExcel(data, filename = 'archive') {
    const ws = XLSX.utils.json_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Archive Data');
    XLSX.writeFile(wb, `${filename}.xlsx`);
}

export function exportToPDF(data, columns, filename = 'archive') {
    const doc = new jsPDF();

    doc.text('Archived Posts', 20, 10);

    doc.autoTable({
        head: [columns],
        body: data.map(item => [item.path, item.caption, item.tanggal]),
        startY: 20,
    });

    doc.save(`${filename}.pdf`);
}

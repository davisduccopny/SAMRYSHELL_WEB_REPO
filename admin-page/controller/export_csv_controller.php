<?php
require '../../phpspreadsheet/vendor/autoload.php';
require '../config/database.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Truy vấn dữ liệu từ CSDL
    $table = $_POST['table_export_csv'];
    $query = "SELECT * FROM $table";
    $result = $conn->query($query);

    // Kiểm tra và lấy dữ liệu
    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Tạo một đối tượng Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Đặt tiêu đề cho các cột
        $columns = array_keys($data[0]);
        $sheet->fromArray(array($columns), NULL, 'A1');
        
        // Thêm dữ liệu từ CSDL vào tệp CSV
        $sheet->fromArray($data, NULL, 'A2');

        // Thiết lập header để trình duyệt biết rằng đây là một file CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="export.csv"');

        // Xuất dữ liệu ra file CSV
        $writer = new Csv($spreadsheet);
        $writer->setDelimiter(',');
        $writer->setEnclosure('');
        $writer->setLineEnding("\r\n");
        $writer->setSheetIndex(0);
        $writer->save('php://output');

        // Đóng kết nối
        $conn->close();
        exit; // Kết thúc script sau khi xuất file
    } else {
        echo "Không có dữ liệu để xuất!";
    }
} else {
    echo "Phương thức không được hỗ trợ!";
}
?>

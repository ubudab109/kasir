<?php
require('assets/lib/fpdf.php');
class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 30);
        $this->Cell(30, 10, 'Aquaqita');

        $this->Ln(10);
        $this->SetFont('Arial', 'i', 10);
        $this->cell(30, 10, 'Universitas Trilogi');

        $this->cell(80);
        $this->SetFont('Arial', '', 10);
        $this->cell(30, 10, 'Jakarta, ' . base64_decode($_GET['uuid']) . '');
        $this->Line(10, 40, 200, 40);

        $this->Ln(5);
        $this->SetFont('Arial', 'i', 10);
        $this->cell(30, 10, 'Telp/Fax : 0812-345-432-123');
        $this->Line(10, 40, 200, 40);

        $this->Cell(80);
        $this->SetFont('Arial', 'u', 15);
        $this->Cell(30, 10, 'Kepada : ' . base64_decode($_GET['id-uid']) . '', 0, 'C');

        $this->Cell(80);
        $this->SetFont('Arial', 'u', 15);
        $this->Cell(30, 10, 'Email : ' . base64_decode($_GET['id-uid']) . '', 0, 'C');

        $this->Ln(5);
        $this->SetFont('Arial', 'i', 10);
        $this->cell(30, 10, 'No Invoice : ' . base64_decode($_GET['inf']) . '');
        $this->Line(10, 40, 200, 40);
    }
    function LoadData()
    {
        $username = "root";
        $password = "";
        $hostname = "localhost";
        //koneksi string dengan database
        $dbkonek = mysqli_connect($hostname, $username, $password);

        echo "";
        //konek ke database
        $selek = mysqli_select_db($dbkonek, "tekno");

        $id = base64_decode($_GET['oid']);
        $data = mysqli_query($dbkonek, "SELECT sub_transaksi.jumlah_beli,sub_transaksi.potongan,sub_transaksi.jenis_potongan, barang.nama_barang,barang.harga_jual,sub_transaksi.total_harga from sub_transaksi inner join barang on barang.id_barang=sub_transaksi.id_barang where sub_transaksi.id_transaksi='$id'");

        while ($r =  mysqli_fetch_array($data, MYSQLI_ASSOC)) {
            $hasil[] = $r;
        }
        return $hasil;
    }
    function BasicTable($header, $data)
    {

        $this->SetFont('Arial', 'B', 7);
        $this->Cell(10, 7, $header[0], 1);
        $this->Cell(80, 7, $header[1], 1);
        $this->Cell(30, 7, $header[2], 1);
        $this->Cell(30, 7, $header[3], 1);
        $this->Cell(30, 7, $header[4], 1);
        $this->Ln();

        $this->SetFont('Arial', '', 7);
        foreach ($data as $row) {
            $this->Cell(10, 7, $row['jumlah_beli'], 1);
            $this->Cell(80, 7, $row['nama_barang'], 1);
            if ($row['jenis_potongan'] == 'diskon') {
                $this->Cell(30, 7, $row['potongan'] . "%", 1);
            } else if ($row['jenis_potongan'] == 'potongan') {
                $this->Cell(30, 7, "RP " . number_format($row['potongan']), 1);
            } else {
                $this->Cell(30, 7, "", 1);
            }
            $this->Cell(30, 7, "Rp " . number_format($row['harga_jual']), 1);
            $this->Cell(30, 7, "Rp " . number_format($row['total_harga']), 1);
            $this->Ln();
        }

        $username = "root";
        $password = "";
        $hostname = "localhost";
        //koneksi string dengan database
        $dbkonek = mysqli_connect($hostname, $username, $password);

        echo "";
        //konek ke database
        $selek = mysqli_select_db($dbkonek, "tekno");
        $id = base64_decode($_GET['oid']);

        $getsum = mysqli_query($dbkonek, "select sum(total_harga) as grand_total,sum(jumlah_beli) as jumlah_beli from sub_transaksi where id_transaksi='$id'");
        $getsum1 = mysqli_fetch_array($getsum, MYSQLI_ASSOC);
        $this->cell(10);
        $this->cell(90);
        $this->cell(50, 7, 'Sub total : ');
        $this->cell(30, 7, 'Rp. ' . number_format($getsum1['grand_total']) . '', 1);
        $this->Ln(25);
        $this->SetFont('Arial', '', 10);
        session_start();
        $this->cell(30, -10, 'Diterima Oleh : ' . $_SESSION['username'] . '');
        $this->Ln(10);
        $this->SetFont('Arial', '', 7);
        // $this->cell(30, -10, '* Barang Yang Sudah Dibeli Tidak Bisa Dikembalikan.');
    }
}

$pdf = new PDF();
$pdf->SetTitle('Invoice : ' . base64_decode($_GET['inf']) . '');
$pdf->AliasNbPages();
$header = array('Qty', 'Nama Barang', 'Diskon/Potongan', 'Harga',  'Total Harga');
$data = $pdf->LoadData();
$pdf->AddPage();
$pdf->Ln(20);
$pdf->BasicTable($header, $data);
$filename = base64_decode($_GET['inf']);
$pdf->Output('', 'Traditional Food/' . $filename . '.pdf');

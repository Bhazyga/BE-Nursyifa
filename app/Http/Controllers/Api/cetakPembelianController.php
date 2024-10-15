<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Helpers\converterTanggal;
use Illuminate\Http\Request;
use FPDF;

class cetakPembelianController  extends Controller
{
    public function cetakLaporanPembelian()
    {

        $pdf = new TCPDF();

        $pdf->AddPage();
        $pdf->setTitle('Table Data Hasil Akhir');
        $pdf->setFooterData();
        $pdf->finalFooter();

        return response($pdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET')
        ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Authorization, Origin');
    }
}


// Ensure you define the fetch_data function to generate table rows
function fetch_data_pembelian($pembelians)
{
  $rows = '';
  foreach ($pembelians as $pembelian) {
      $rows .= '<tr>
          <td align="center">' . $pembelian->material_id . '</td>
          <td>' . $pembelian->quantity . '</td>
          <td>' . $pembelian->nohp . '</td>
          <td align="center">' . $pembelian->status . '</td>
          <td align="center">' . $pembelian->namamaterial . '</td>
          <td align="center">' . $pembelian->namapembeli . '</td>
          <td align="center">' . $pembelian->alamat . '</td>
      </tr>';
  }
  return $rows;
}

class PembelianTCPDF extends \TCPDF
{
    function Header()
    {
        $pembelians = pembelian::all(); // Adjust this as needed for your data source

        $this->Image(public_path('LastSYJYNOBG.png'), 10, 8, 38,20);

        // $this->setFont('arial', 'B', 24); Arial Gabisa karna gak ada di tcpdf kudu download dulu
        $this->SetTextColor(46, 49, 142);
        $this->setFont('helvetica', 'B',25);
        $this->Cell(0, 10, 'Surya Jaya', 0, 1, 'C');

        $this->SetFont('helvetica', 'B', 9);
        $this->SetTextColor(0, 0, 1);
        $address_lines = [
            ' Jl. Tegal Parang Sel. No.6 5, RT.5/RW.1, ',
            'Kec. Mampang Prpt., Kota Jakarta Selatan,',
            'Daerah Khusus Ibukota Jakarta 12790'

        ];
        foreach ($address_lines as $line) {
            $this->Cell(0, 5, $line, 0, 1, 'C');
        }

        $this->Ln(10);
        $this->Line(10, 27, 200, 27);
        $this->setAlpha(0.3);
        $this->Image(public_path('LastSYJYNOBG.png'), 5, 50, 200);
        $this->setAlpha(1);

        $this->setFont('helvetica', 'B',25);
        $this->Cell(0, 2, 'Table Data Pengguna', 0, 1, 'C');

        $this->Ln(5);
  // Add table with data
  $this->SetFont('helvetica', '', 10);
  $this->writeHTML('
      <table class="table table-bordered" width="100%" cellspacing="0" border="1" style="padding-left: 5px;">
          <tr>
              <th align="center" style="height: 20px; width: 60px;"><b>ID Barang</b></th>
              <th align="center" style="height: 20px; width: 60px;"><b>Kuantitas</b></th>
              <th align="center" style="height: 20px;"><b>No Hp</b></th>
              <th align="center" style="height: 20px;"><b>Status</b></th>
              <th align="center" style="height: 20px;"><b>Nama Material</b></th>
              <th align="center" style="height: 20px;"><b>Nama Pembeli</b></th>
              <th align="center" style="height: 20px;"><b>Alamat Pembeli</b></th>
          </tr>
          ' . fetch_data_pembelian($pembelians) . '
      </table>
  ', true, true, true, true, '');
}



    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(169, 169, 169);  // Set text color to grey
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function finalFooter()
    {
        $this->SetY(-75);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(0, 0, 0);  // Set text color to black
        $current_date = strftime('%A, %d %B %Y', time());
        $current_date = converterTanggal::convertToIndonesianMonth(date('F')) . date('-d-Y');

        $this->Cell(0, 10, 'Jakarta, ' . $current_date, 0, 0, 'R');

        $this->SetY(-65);
        $this->Cell(0, 10, 'Yang Bertanda Tangan Dibawah ini', 0, 0, 'R');

        $this->SetY(-55);
        $this->Cell(0, 25, '______________________________', 0, 0, 'R');

        $this->SetY(-45);
        $this->SetX(-52);  // Adjust the x position to add right margin
        $this->Cell(0, 20, 'Kepala Toko bangunan', 0, 0, 'R');
    }

    function ChapterTitle($title)
    {
        $this->SetFont('helvetica', 'B', 16);
        $this->SetTextColor(0, 51, 102);  // Biru terang
        $this->Cell(0, 10, $title, 0, 1, 'C');
        $this->Ln(10);
    }


    function ChapterBody($columns, $data)
    {
        $this->SetTextColor(0, 51, 102);
        $this->SetFont('helvetica', 'B', 7.5);

        $col_width = $this->GetPageWidth() / (count($columns) + 1);
        foreach ($columns as $col) {
            $this->Cell($col_width, 10, $col, 1, 0, 'C');
        }
        $this->Ln();
        $this->SetFont('helvetica', '', 7.5);
        foreach ($data as $row) {
            foreach ($row as $item) {
                $this->Cell($col_width, 10, $item, 1, 0, 'C');
            }
            $this->Ln();
        }
    }
}

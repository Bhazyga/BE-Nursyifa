<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\converterTanggal; // Import the helper class
use Illuminate\Http\Request;
use TCPDF; // Make sure to include TCPDF if you're using it

class cetakUserController extends Controller
{
    public function cetakLaporanUser(Request $request)
    {
        $perPage = 15; // Define how many users to show per page
        $page = $request->input('page', 1);

        // Create an instance of UserTCPDF
        $pdf = new UserTCPDF();
        $pdf->AddPage();
        $pdf->setTitle('Table Data Pengguna');
        $pdf->setFooterData();

        // Fetch paginated users
        $users = User::paginate($perPage, ['*'], 'page', $page);

        // For pagination, create multiple pages if necessary
        $totalPages = $users->lastPage();

        // Loop through the pages
        for ($currentPage = 1; $currentPage <= $totalPages; $currentPage++) {
            if ($currentPage > 1) {
                $pdf->AddPage(); // Add a new page only when it's not the first one
            }
            // Get the users for the current page
            $users = User::paginate($perPage, ['*'], 'page', $currentPage);

            // Write HTML content for the current page
            $pdf->writeHTML($this->generatePDFContent($users, $pdf), true, false, true, false, '');
        }

        $pdf->finalFooter();

        // Generate the PDF content and return the response
        return response($pdf->output('Laporan_User', 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Authorization, Origin');
    }

    private function generatePDFContent($users, $pdf)
    {
        $pdf->SetY(40);  // Adjust this value to add space at the top (in mm)

        return '
            <h2 align="center">Table Data Pengguna</h2>
            <table class="table table-bordered" width="100%" cellspacing="0" border="1" style="padding-left: 5px;">
                <tr>
                    <th align="center" style="height: 20px; width: 40px;"><b>ID</b></th>
                    <th align="center" style="height: 20px; width: 150px;"><b>Nama</b></th>
                    <th align="center" style="height: 20px;"><b>Email</b></th>
                    <th align="center" style="height: 20px;"><b>Role</b></th>
                    <th align="center" style="height: 20px;"><b>Tanggal Dibuat</b></th>
                </tr>
                ' . $this->fetch_data_user($users) . '
            </table>
        ';
    }

    private function fetch_data_user($users)
    {
        $rows = '';
        foreach ($users as $user) {
            $rows .= '<tr>
                <td align="center">' . $user->id . '</td>
                <td>' . $user->name . '</td>
                <td>' . $user->email . '</td>
                <td>' . $user->role . '</td>
                <td align="center">' . $user->created_at . '</td>
            </tr>';
        }
        return $rows;
    }
}


class UserTCPDF extends TCPDF
{
    public function Header()
    {
        $this->Image(public_path('logo.jpg'), 10, 8, 38, 20);
        $this->SetTextColor(27, 193, 163);
        $this->setFont('helvetica', 'B', 25);
        $this->Cell(0, 10, 'PO. Nursyifa', 0, 1, 'C');
        $this->SetFont('helvetica', 'B', 9);
        $this->SetTextColor(0, 0, 1);
        $address_lines = [
            'Jl. Dr. Husein Kartasasmita link.Cisauheun',
            'RT.21/RW.07, Situbatu, Kec. Banjar, Kota Banjar,',
            'Jawa Barat 46311'
        ];
        foreach ($address_lines as $line) {
            $this->Cell(0, 5, $line, 0, 1, 'C');
        }
        $this->Ln(10);
        $this->Line(10, 27, 200, 27);
        $this->setAlpha(0.3);
        $this->Image(public_path('logo.jpg'), 5, 50, 200);
        $this->setAlpha(1);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(169, 169, 169); // Set text color to grey
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    public function finalFooter()
    {
        $this->SetY(-75);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(0, 0, 0); // Set text color to black
        $current_date = strftime('%A, %d %B %Y', time());
        $current_date = converterTanggal::convertToIndonesianMonth(date('F')) . date('-d-Y');

        $this->Cell(0, 10, 'Banjar, ' . $current_date, 0, 0, 'R');
        $this->SetY(-65);
        $this->Cell(0, 10, 'Yang Bertanda Tangan Dibawah ini', 0, 0, 'R');
        $this->SetY(-55);
        $this->Cell(0, 25, '______________________________', 0, 0, 'R');
        $this->SetY(-45);
        $this->SetX(-52); // Adjust the x position to add right margin
        $this->Cell(0, 20, 'Kepala Bus PO Nursyifa', 0, 0, 'R');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CodeItNow\BarcodeBundle\Utils\QrCode;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;


class CodigosQRController extends Controller
{
  //
  public function generar($id)
  {
    // code...
    // $empleados = DB::table('empleados AS e')->select('e.id',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado"))
    // ->whereIn('e.id',['148','35','36','335','165','4','149','328','103','6','329','14','2','85','147','12','95','178','336','238','8','123','150',
    // '133','143','5','3','204','327','331','337','24','179','23','28','223','27','236','182','207','183','40','206','48','184','209','214','83',
    // '86','186','332','101','90','221','227','93','74','189','84','73','218','97','211','191','192','225','200','126','128','326','134','139','330',
    // '1','230','321','17','157','30','50','71','114','7','78','75','77','98','125','11','119','118','13','21','34','37','61','64','59',
    // '94','116','79','82','117','141','129'])->get();
    $empleados = DB::table('empleados AS e')->select('e.id',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado"))
    ->whereIn('e.id',[$id])->get();
    $arreglo = [];
    foreach ($empleados as $key => $value) {
      $arreglo [] = $this->qrcode($value);
    }
    $pdf = PDF::loadView('pdf.codigoqr', compact('arreglo','empleados'));
    $pdf->setPaper("letter", "portrait");
    return $pdf->stream();
  }

  public function qrcode($data)
  {
    $text = $data->id.'|'.$data->empleado;
    // code...
    $qrCode = new QrCode();
    $qrCode
    ->setText($text)
    ->setSize(300)
    ->setPadding(10)
    ->setErrorCorrection('high')
    ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
    ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
    ->setImageType(QrCode::IMAGE_TYPE_PNG);

    return $qrCode;
  }
}

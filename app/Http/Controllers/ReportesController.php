<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pago;
use DB;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Database\Query\JoinClause;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ReportesController extends Controller
{    
    public function getReportesClasificador($fecha_ini, $fecha_fin){
        $reportes_clasificador=DB::table('pago_detalle')
                 ->leftjoin('pago', 'pago.id','=','pago_detalle.pago_id')
                 ->leftjoin('cuenta_clasificador','pago.cuenta_clasificador_id','=','cuenta_clasificador.id')
                 ->leftjoin('rubro','rubro.id','=','cuenta_clasificador.rubro_id')
                 ->select(
                    'rubro.numero_identificador',
                    'rubro.descripcion as rubro_descripcion',
                    'cuenta_clasificador.descripcion as descripcion_clasificador',
                    'cuenta_clasificador.id',
                    DB::raw("sum(pago_detalle.precio_unitario) as monto_consolidado")                    

                 )->where('fecha_pago','>=',ReportesController::mysqlDatetoStr($fecha_ini))
                 ->where('fecha_pago','<=',ReportesController::mysqlDatetoStr($fecha_fin))
                 ->groupby('cuenta_clasificador.id')
                 ->get();
        return $reportes_clasificador;
    }
    public function getReporteFromIniFinPorClasificador(Request $request){
        $fecha_ini=$request->get('fecha_ini');
        $fecha_fin=$request->get('fecha_fin');
        $lpagos=$this->getReportesClasificador($fecha_ini,$fecha_fin);//Pago::where('fecha_pago','>=',$fecha_ini)->where('fecha_pago','<=',$fecha_fin);        
        return view('reportes.reportes_clasificador_resultado',['lconsolidado_clasificador'=>$lpagos,"fecha_ini"=>$fecha_ini,"fecha_fin"=>$fecha_fin]);
    }
    public function reportes_clasificador(){
        return view('reportes.reportes_clasificador');
    }
    public static function mysqlDatetoStr($date){        
        if($date!=null)
            // return \DateTime::createFromFormat('Y-m-d',$date)->format('d/m/Y');//sale error de format bool
            return \DateTime::createFromFormat('d/m/Y',$date)->format('Y-m-d');
        else 
            return "";
    }

    ///POR RUBRO
    public function getReportesRubro($fecha_ini, $fecha_fin){
        $reportes_clasificador=DB::table('pago_detalle')
                 ->leftjoin('pago', 'pago.id','=','pago_detalle.pago_id')
                 ->leftjoin('cuenta_clasificador','pago.cuenta_clasificador_id','=','cuenta_clasificador.id')
                 ->leftjoin('rubro','rubro.id','=','cuenta_clasificador.rubro_id')
                 ->select(
                    'rubro.numero_identificador',
                    'rubro.descripcion as rubro_descripcion',
                    DB::raw("sum(pago_detalle.precio_unitario) as monto_consolidado")                    
                 )->where('fecha_pago','>=',ReportesController::mysqlDatetoStr($fecha_ini))
                 ->where('fecha_pago','<=',ReportesController::mysqlDatetoStr($fecha_fin))
                 ->groupby('rubro.id')
                 ->get();
        return $reportes_clasificador;
    }
    public function reportes_rubro(){
        return view('reportes.reportes_rubro');
    }
    public function getReporteFromIniFinPorRubro(Request $request){
        $fecha_ini=$request->get('fecha_ini');
        $fecha_fin=$request->get('fecha_fin');
        $lrubros=$this->getReportesRubro($fecha_ini,$fecha_fin);//Pago::where('fecha_pago','>=',$fecha_ini)->where('fecha_pago','<=',$fecha_fin);        
        return view('reportes.reportes_rubro_resultado',['lconsolidado_rubro'=>$lrubros,"fecha_ini"=>$fecha_ini,"fecha_fin"=>$fecha_fin]);
    }
    /// return Reportes rubro
    public function reportes_extracto(){
        return view('reportes.reportes_pago_detalle');
    }
    public function getExtracto($fecha_ini, $fecha_fin){
        return 
        Pago::where('fecha_pago','>=',ReportesController::mysqlDatetoStr($fecha_ini))
            ->where('fecha_pago','<=',ReportesController::mysqlDatetoStr($fecha_fin))->get();
    }
    public function getReporteFromIniFinExtracto(Request $request){
      $fecha_ini=$request->get('fecha_ini');
      $fecha_fin=$request->get('fecha_fin');
        
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();      
      $extracto_pagos=$this->getExtracto($fecha_ini, $fecha_fin);
      $row=1;
      $COL=1;
      $FECHA=$COL;$COL++;
      $RUBRO=$COL;$COL++;
      $UNIDAD=$COL;$COL++;
      $NUMERO_RECIBO=$COL;$COL++;
      $NOMBRE_Y_APELLIDO=$COL;$COL++;
      $DESCRIPCION=$COL;$COL++;
      $MONTO=$COL;$COL++;
      $CUENTA=$COL;$COL++;
      $sheet->setCellValueByColumnAndRow($FECHA,$row,"FECHA");
      $sheet->setCellValueByColumnAndRow($RUBRO,$row,"RUBRO");    
      $sheet->setCellValueByColumnAndRow($UNIDAD,$row,"UNIDAD");    
      $sheet->setCellValueByColumnAndRow($NUMERO_RECIBO,$row,"Nº RECIBO");
      $sheet->setCellValueByColumnAndRow($NOMBRE_Y_APELLIDO,$row,"NOMBRE Y APELLIDOS");
      $sheet->setCellValueByColumnAndRow($DESCRIPCION,$row,"DETALLE DE PAGO");
      $sheet->setCellValueByColumnAndRow($MONTO,$row,"MONTO");
      $sheet->setCellValueByColumnAndRow($CUENTA,$row,"USUARIO SISTEMA");
     
      foreach($extracto_pagos as $pago){
        foreach($pago->detalle_pago as $detalle){
            $row++;        
            $sheet->setCellValueByColumnAndRow($FECHA,$row,$pago->getFechaPagoStr());
            $sheet->setCellValueByColumnAndRow($RUBRO,$row,$detalle->cuenta->cuenta_clasificador->unidad->rubro->numero_identificador);       
            $sheet->setCellValueByColumnAndRow($UNIDAD,$row,$detalle->cuenta->cuenta_clasificador->unidad->descripcion);       
            $sheet->setCellValueByColumnAndRow($NUMERO_RECIBO,$row,$pago->serie);
            $sheet->setCellValueByColumnAndRow($NOMBRE_Y_APELLIDO,$row,$pago->cliente->apellidos." ".$pago->cliente->nombres);        
            $sheet->setCellValueByColumnAndRow($DESCRIPCION,$row,$detalle->descripcion);        
            $sheet->setCellValueByColumnAndRow($MONTO,$row,$detalle->monto); 
            $sheet->setCellValueByColumnAndRow($CUENTA,$row,$pago->user->email); 
        }          
      }
       //autosize
       $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
       $cellIterator->setIterateOnlyExistingCells( true );
       foreach( $cellIterator as $cell ) {
               $sheet->getColumnDimension( $cell->getColumn() )->setAutoSize( true );
       }
   //FORMATEANDO     
        $styleArray = [
            'font' => [
                'bold' => true,                
                'color' => [
                    'argb' => 'ff000000',
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'ffFF8008',
                ],
                'endColor' => [
                    'argb' => 'ffFFC837',
                ],
            ],
        ];  
       //FORMATEANDO LOS ROTULOS
        $spreadsheet->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($FECHA)."1:".Coordinate::stringFromColumnIndex($CUENTA)."1")->applyFromArray($styleArray);          
        //FORMATEANDO LAS LINEAS
        $highestRow = $sheet->getHighestDataRow(); // e.g. 10
        $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FFFF0000'],
                ],
            ],
        ];
       $sheet->getStyle('A1:'.$highestColumn."".$highestRow)->applyFromArray($styleArray);         
       $writer = new Xlsx($spreadsheet);      
       try {
           $writer->save(storage_path('ExtractoPago.xlsx'));
       } catch (Exception $e) {  }   
       return response()->download(storage_path('ExtractoPago.xlsx'));     
    }    
}

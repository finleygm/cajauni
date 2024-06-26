<?php

namespace App\Http\Controllers;

use App\Cuenta;
use App\CuentaClasificador;
use Illuminate\Http\Request;
use App\Pago;
use App\Rubro;
use App\Unidad;
use App\User;
use DB;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Database\Query\JoinClause;
// use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ReportesController extends Controller
{    

    public function index(){
        $unidad=Unidad::all();
        $rubro= Rubro::all();
        $cuentaClasificador=CuentaClasificador::all();
        $cuenta=Cuenta::all();
        $pago=Pago::all();
       return view('reportes.reportes_index',compact('unidad','rubro','cuentaClasificador','cuenta','pago'));          
    }

    public function recoger(Request $request){
        $reporte=$request->reportes;
        $fecha_ini=$request->get('fecha_ini');
        $fecha_fin=$request->get('fecha_fin');
        switch ($reporte) {
            case 'Rubro_Unidad':
                
                return $this->getReporteFromIniFinPorRubro($request);

            break;
            case 'Extracto_General':
                $resul=$this->getExtractoGrande($fecha_ini, $fecha_fin);
                return view('reportes.reportes_extracto_grande',['lconsolidado'=>$resul,"fecha_ini"=>$fecha_ini,"fecha_fin"=>$fecha_fin]);
            
            break;    
            case 'Extracto_Boleta':
                $resul=$this->getExtractoPorBoleta($fecha_ini, $fecha_fin);
                return view('reportes.reportes_extracto_boleta',['lconsolidado'=>$resul,"fecha_ini"=>$fecha_ini,"fecha_fin"=>$fecha_fin]);
            
            break; 
            // case 'Clasificador_Consolidado':
            //     $resul=$this->getReportesConsolidadoClasificador($fecha_ini, $fecha_fin);
            //     return view('reportes.reportes_unidad_rubro_clasificador',['lconsolidado'=>$resul,"fecha_ini"=>$fecha_ini,"fecha_fin"=>$fecha_fin]);
            
            // break;   

            // case 'Unidad_Clasificador_Pago':
            //     $resul=$this->getReportesConsolidadoClasificador($fecha_ini, $fecha_fin);
            //     return view('reportes.reportes_unidad_rubro_clasificador',['lconsolidado'=>$resul,"fecha_ini"=>$fecha_ini,"fecha_fin"=>$fecha_fin]);
            // break;

            // case 'Unidad_Rubro_clasificador_Pago':
            //     $resul=$this->getReportesCuentaClasificadorPagoCliente($fecha_ini, $fecha_fin);
            //     return view('reportes.reportes_rubro_cclasificador_cliente_pago',['lconsolidado'=>$resul,"fecha_ini"=>$fecha_ini,"fecha_fin"=>$fecha_fin]);
            // break;
            case 'Rubro_Clasificador':
                return $this->getReporteFromIniFinPorClasificador($request);
                break;
            
        }
    
     }

     public function getUnidadRubro($fecha_ini, $fecha_fin){
        $reportes_clasificador=DB::table('pago_detalle')
                 ->leftjoin('pago', 'pago.id','=','pago_detalle.pago_id')
                 ->leftjoin('cuenta','cuenta.id','=','pago_detalle.cuenta_id')
                 ->leftjoin('unidad','unidad.id','=','cuenta.unidad_id')
                 ->leftjoin('rubro','rubro.id','=','cuenta.rubro_id')
                 ->select(
                    'unidad.descripcion as unidad_descripcion',
                    'rubro.descripcion as rubro_descripcion',

                    DB::raw("sum(pago_detalle.precio_unitario*detalle_pago.cantidad) as monto_consolidado")                    

                 )->where('fecha_pago','>=',ReportesController::mysqlDatetoStr($fecha_ini))
                 ->where('fecha_pago','<=',ReportesController::mysqlDatetoStr($fecha_fin))
                 ->groupby('unidad.id', 'rubro_descripcion')
                 ->get();
        return $reportes_clasificador;
    }


    ////Extracto total

    public function getExtractoGrande($fecha_ini, $fecha_fin){
        $reportes_clasificador=DB::table('pago_detalle')
                 ->leftjoin('pago', 'pago.id','=','pago_detalle.pago_id')
                 ->leftjoin('cuenta','cuenta.id','=','pago_detalle.cuenta_id')
                 ->leftjoin('unidad','unidad.id','=','cuenta.unidad_id')
                 ->leftjoin('rubro','rubro.id','=','cuenta.rubro_id')
                 ->leftjoin('cuenta_producto_clasificador','cuenta_producto_clasificador.cuenta_id','=','cuenta.id')
                 ->leftjoin('cuenta_clasificador','cuenta_clasificador.id','=','cuenta_producto_clasificador.cuenta_clasificador_id')
                 ->select(
                    

                    DB::raw("GROUP_CONCAT(DISTINCT rubro.descripcion) as rubro_descripcion"),
                    DB::raw("GROUP_CONCAT(DISTINCT unidad.descripcion) as unidad_descripcion"),
                    DB::raw("GROUP_CONCAT(DISTINCT cuenta_clasificador.descripcion SEPARATOR '\\n') as cuenta_clasificador_descripcion"),
                    DB::raw("GROUP_CONCAT(DISTINCT cuenta.nombre_cuenta) AS producto"),
                    DB::raw("GROUP_CONCAT(DISTINCT pago.serie) AS serie"),
                    DB::raw("GROUP_CONCAT(DISTINCT pago.fecha_pago) AS fecha_pago"), 
                    DB::raw("GROUP_CONCAT(DISTINCT pago.nro_recibo) as numero_recibo"), 
                    'pago.sector','pago.categoria',
                    DB::raw("GROUP_CONCAT(DISTINCT pago_detalle.descripcion) as pago_detalle_descripcion"),          
                    'pago_detalle.cantidad',
                    'pago_detalle.precio_unitario',
                    'pago_detalle.monto'
                    
                 )->where('fecha_pago','>=',ReportesController::mysqlDatetoStr($fecha_ini))
                 ->where('fecha_pago','<=',ReportesController::mysqlDatetoStr($fecha_fin))
                 ->groupby('pago_detalle.id') 
                 ->orderby('unidad_descripcion', 'asc')
              //   ->orderby( DB::raw('CAST(serie AS UNSIGNED)'), 'desc')
                 ->get();
        return $reportes_clasificador;
    }
    
    
    public function getExtractoPorBoleta($fecha_ini, $fecha_fin){
        $reportes_clasificador=DB::table('pago_detalle')
                 ->leftjoin('pago', 'pago.id','=','pago_detalle.pago_id')
                 ->leftjoin('cuenta','cuenta.id','=','pago_detalle.cuenta_id')
                 ->leftjoin('unidad','unidad.id','=','cuenta.unidad_id')
                 ->leftjoin('rubro','rubro.id','=','cuenta.rubro_id')
                 ->join('users','users.id','=','pago.user_id')
                 ->select(
                    DB::raw("IF(pago.categoria=1,'TESORERIA','COMERCIALIZACION') AS categoria"),
                    DB::raw("GROUP_CONCAT(DISTINCT pago.serie) AS serie"),
                    DB::raw("GROUP_CONCAT(DISTINCT rubro.numero_identificador) as nidentificador"),
                    DB::raw("GROUP_CONCAT(DISTINCT rubro.descripcion) as rubro_descripcion"),
                    'cuenta.nombre_cuenta',
                    DB::raw("GROUP_CONCAT(DISTINCT unidad.descripcion) as unidad_descripcion"),
                    'users.name',
                    DB::raw("GROUP_CONCAT(DISTINCT pago.fecha_pago) AS fecha_pago"),
                    DB::raw("sum(pago_detalle.precio_unitario*pago_detalle.cantidad) as monto"),
                    'pago.total'
                    
                 )->where('fecha_pago','>=',ReportesController::mysqlDatetoStr($fecha_ini))
                 ->where('fecha_pago','<=',ReportesController::mysqlDatetoStr($fecha_fin))
                 ->groupby('pago_detalle.id')
                 ->orderby('rubro.descripcion', 'asc')
                 ->orderby( DB::raw('CAST(serie AS UNSIGNED)'), 'desc')
                 ->get();
        return $reportes_clasificador;
    }
        /*    

    IF(pago.categoria=1,'TESORERIA','COMERCIALIZACION') AS categoria,
    GROUP_CONCAT(DISTINCT pago.serie) AS serie,
    GROUP_CONCAT(DISTINCT rubro.descripcion) as rubro_descripcion, 
    GROUP_CONCAT(DISTINCT unidad.descripcion SEPARATOR '\n') as unidad_descripcion,
    users.name,
    GROUP_CONCAT(DISTINCT pago.fecha_pago) AS fecha_pago ,
    sum(pago_detalle.precio_unitario*pago_detalle.cantidad) as monto, pago.total
    from `pago_detalle` 
    left join `pago` on `pago`.`id` = `pago_detalle`.`pago_id`
    left join `cuenta` on `cuenta`.`id` = `pago_detalle`.`cuenta_id`
    left join `unidad` on `unidad`.`id` = `cuenta`.`unidad_id`
    left join `rubro` on `rubro`.`id` = `cuenta`.`rubro_id`
    INNER join users on users.id= pago.user_id
    where `fecha_pago` >= '2021-04-01' and `fecha_pago` <= '2021-05-31'
    
    GROUP by pago.id ORDER BY pago.categoria asc, serie ASC;
    */

//consolidado de pago por rubro, cuenta_clasificador, unidad --- quiere decir que sumara la columna precio unitario 

     public function getReportesConsolidadoClasificador($fecha_ini, $fecha_fin){
        $reportes_clasificador=DB::table('pago_detalle')
                 ->leftjoin('pago', 'pago.id','=','pago_detalle.pago_id')
                 ->leftjoin('cliente','cliente.id','=','pago.cliente_id')
                 ->leftjoin('cuenta','cuenta.id','=','pago_detalle.cuenta_id')
                 ->leftjoin('unidad','unidad.id','=','cuenta.unidad_id')
                 ->leftjoin('rubro','rubro.id','=','cuenta.rubro_id')
                 ->leftjoin('cuenta_producto_clasificador','cuenta_producto_clasificador.cuenta_id','=','cuenta.id')
                 ->leftjoin('cuenta_clasificador','cuenta_clasificador.id','=','cuenta_producto_clasificador.cuenta_clasificador_id')
                 ->select(
                    'rubro.numero_identificador',
                    'rubro.descripcion as rubro_descripcion',
                    'cuenta_clasificador.numero_clasificador', 
                    'cuenta_clasificador.descripcion as  cuenta_clasificador_descripcion', 
                    'unidad.descripcion as unidad_descripcion',
                    'pago_detalle.monto',
                  

                 )->where('fecha_pago','>=',ReportesController::mysqlDatetoStr($fecha_ini))
                 ->where('fecha_pago','<=',ReportesController::mysqlDatetoStr($fecha_fin))
                 ->groupby('rubro.numero_identificador', 'rubro_descripcion', 
                 'cuenta_clasificador.numero_clasificador', 'cuenta_clasificador_descripcion', 
                 'unidad_descripcion','pago_detalle.monto')
                 ->get();
        return $reportes_clasificador;
    }

    //reporte clasificador pago de cliente  
 
    public function getReportesCuentaClasificadorPagoCliente($fecha_ini, $fecha_fin){
        $reportes_clasificador=DB::table('pago_detalle')
                 ->join('pago', 'pago.id','=','pago_detalle.pago_id')
                 ->join('cliente','cliente.id','=','pago.cliente_id')
                 ->join('cuenta','cuenta.id','=','pago_detalle.cuenta_id')
                 ->join('rubro','rubro.id','=','cuenta.rubro_id')
                 ->join('cuenta_producto_clasificador','cuenta_producto_clasificador.cuenta_id','=','cuenta.id')
                 ->join('cuenta_clasificador','cuenta_clasificador.id','=','cuenta_producto_clasificador.cuenta_clasificador_id')
                 ->select(
                    'rubro.numero_identificador',
                    'rubro.descripcion as rubro_descripcion',

                    DB::raw("GROUP_CONCAT(cuenta_clasificador.descripcion) as descripcionClasificador"),  
                
                    'pago_detalle.id as id_pago_detalle', 
                    DB::raw( 'pago_detalle.precio_unitario*pago_detalle.cantidad as subtotal'),
                    'pago.categoria',
                    'pago.serie',
                    'cliente.ci',
                    'cliente.nombres'
                    ,'cliente.apellidos',
                   
                 )->where('fecha_pago','>=',ReportesController::mysqlDatetoStr($fecha_ini))
                 ->where('fecha_pago','<=',ReportesController::mysqlDatetoStr($fecha_fin))
                 ->groupby('rubro.numero_identificador', 'rubro_descripcion', 
                  'id_pago_detalle','subtotal', 'pago.categoria',
                 'pago.serie', 'cliente.ci', 'cliente.nombres', 'cliente.apellidos') 
             
                 ->Orderby('pago.categoria','asc')
                 ->Orderby('pago.serie', 'asc')
                 ->get();
        return $reportes_clasificador;
    }

    
    public function getReportesClasificador($fecha_ini, $fecha_fin){
        $reportes_clasificador=DB::table('pago_detalle')
                 ->join('pago', 'pago.id','=','pago_detalle.pago_id')
                 ->join('cuenta','cuenta.id','=','pago_detalle.cuenta_id')
                 ->join('unidad','unidad.id','=','cuenta.unidad_id')
                 ->join('rubro','rubro.id','=','cuenta.rubro_id')
                 ->join('cuenta_producto_clasificador','cuenta.id','=','cuenta_producto_clasificador.cuenta_id')
                 ->join('cuenta_clasificador','cuenta_clasificador.id','=','cuenta_producto_clasificador.cuenta_clasificador_id')
                 ->select(
                    'rubro.numero_identificador',
                    'rubro.descripcion as rubro_descripcion',
                    'cuenta_clasificador.numero_clasificador',
                    'cuenta_clasificador.descripcion as cuenta_clasificador_descripcion',
                    
                    DB::raw("sum(pago_detalle.precio_unitario) as monto_consolidado")                    

                 )->where('fecha_pago','>=',ReportesController::mysqlDatetoStr($fecha_ini))
                 ->where('fecha_pago','<=',ReportesController::mysqlDatetoStr($fecha_fin))
                 ->groupby('cuenta_clasificador.id','rubro.numero_identificador','rubro_descripcion','cuenta_clasificador.numero_clasificador',
                 'cuenta_clasificador_descripcion')
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
        
        $reportes_clasificador=DB::table('pago_detalle')->get();
        
        // DB::enableQueryLog();
        //DB::enableQueryLog();
        $reportes_clasificador=DB::table('pago_detalle')
        ->leftjoin('pago', 'pago.id','=','pago_detalle.pago_id')
        ->leftjoin('cuenta','cuenta.id','=','pago_detalle.cuenta_id')
        ->leftjoin('unidad','unidad.id','=','cuenta.unidad_id')
        ->leftjoin('rubro','rubro.id','=','cuenta.rubro_id')
        ->select(
           'rubro.numero_identificador',
           'rubro.descripcion as rubro_descripcion',
           DB::raw("sum(pago_detalle.precio_unitario*pago_detalle.cantidad) as monto_consolidado")                    
        )->where('fecha_pago','>=',ReportesController::mysqlDatetoStr($fecha_ini))
        ->where('fecha_pago','<=',ReportesController::mysqlDatetoStr($fecha_fin))
        ->groupby('rubro.id')
        ->get();
        //          //dd($reportes_clasificador);
       
          //       dd(DB::enableQueryLog());
                // dd($reportes_clasificador);
        return $reportes_clasificador;
        //return response()->json($reportes_clasificador);
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

     public function getConsulta($fecha_ini, $fecha_fin){
        $reportes_clasificador=DB::table('pago_detalle')
        ->join('pago', 'pago.id','=','pago_detalle.pago_id')
        ->join('cuenta','cuenta.id','=','pago_detalle.cuenta_id')
        ->join('unidad','unidad.id','=','cuenta.unidad_id')
        ->join('rubro','rubro.id','=','cuenta.rubro_id')
        ->join('users','users.id','=','pago.user_id')
        ->join('cuenta_producto_clasificador','cuenta.id','=','cuenta_producto_clasificador.cuenta_id')
        ->join('cuenta_clasificador','cuenta_clasificador.id','=','cuenta_producto_clasificador.cuenta_clasificador_id')
        ->select(
           'pago.fecha_pago',
           'rubro.numero_identificador',
           'rubro.descripcion as rubro_descripcion',
           'cuenta_clasificador.numero_clasificador',
           'cuenta_clasificador.descripcion as cuenta_clasificador_descripcion',
           DB::raw("sum(pago_detalle.precio_unitario) as monto_consolidado"),
           'users.name'

        )->where('fecha_pago','>=',ReportesController::mysqlDatetoStr($fecha_ini))
        ->where('fecha_pago','<=',ReportesController::mysqlDatetoStr($fecha_fin))
        ->groupby('cuenta_clasificador.id','rubro.numero_identificador','rubro_descripcion','cuenta_clasificador.numero_clasificador',
        'cuenta_clasificador_descripcion','users.name','pago.fecha_pago')
        ->get();
        return $reportes_clasificador;
   
    }

    public function getReporteFromIniFinExtracto(Request $request){
      $usuario=auth()->user();
      $fecha_ini=$request->get('fecha_ini');
      $fecha_fin=$request->get('fecha_fin');
      $prueba=$this->getConsulta($fecha_ini,$fecha_fin);
      $extracto=$this->getExtractoGrande($fecha_ini,$fecha_fin);
       $rubro=$this->getReportesRubro($fecha_ini,$fecha_fin);
     
      $spreadsheet = new Spreadsheet();
      $image= new Drawing();
      $sheet = $spreadsheet->getActiveSheet();    
      $boleta=$this->getExtractoPorBoleta($fecha_ini, $fecha_fin);
      $extracto_pagos=$this->getExtracto($fecha_ini, $fecha_fin);
      //dd($extracto_pagos);
      $row=1;
      $COL=1;
      $sheet->insertNewRowBefore(1, 1);
      $row++; 
      $gu=0;
    $re=0;
    $act=0;
      //FORMATEANDO     
      $styleArray1 = [
        'font' => [
            'bold' => true,                
            'color' => [
                'argb' => '000000',
            ],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'wrapText'=>true,
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
                'argb' => 'D9D9D9',
            ],
            'endColor' => [
                'argb' => 'D9D9D9',
            ],
        ],
    ];  
    //para las lineas
     $styleArray = [
        'alignment' => [
         //   'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            'wrapText'=>true,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],
    ];  
     $styleRubro = [
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'wrapText'=>true,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
                'color' => ['argb' => '808080'],
            ],
        ],
    ];
    $stylenuevo =array(
        'font' => array(
            'bold' => true,
            'size' => 11,
            'wrapText'=>true,
        ),
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment:: HORIZONTAL_CENTER,
             'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_CENTER,
            'wrapText'=>true,
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
            'rotation' => 90,
            'startColor' => [
                'argb' => 'D9D9D9',
            ],
            'endColor' => [
                'argb' => 'D9D9D9',
            ],
        ],  
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],

    );
    $stylo =array(
        'font' => array(
            'bold' => true,
            'size' => 11,
            'wrapText'=>true,
        ),
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment:: HORIZONTAL_CENTER,
             'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment:: VERTICAL_CENTER,
            'wrapText'=>true,
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
            'rotation' => 90,
            'startColor' => [
                'argb' => 'D9D9D9',
            ],
            'endColor' => [
                'argb' => 'D9D9D9',
            ],
        ],  
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
                'color' => ['argb' => '808080'],
            ],
        ],

    );
$estilototal = array(
    'font' => array(
        'bold' => true,
        'size' => 11,
        'wrapText'=>true,
    ),
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment:: HORIZONTAL_LEFT,
        'wrapText'=>true,
    ],
);
$total = array(
    'font' => array(
        'bold' => true,
        'size' => 11,
        'wrapText'=>true,
    ),
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment:: HORIZONTAL_RIGHT,
        'wrapText'=>true,
    ],
);
     // $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
     $titleStyle = array(
        'font' => array(
            'bold' => true,
            'size' => 20,
            'wrapText'=>true,
        ),
     
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'wrapText'=>true,
        ],
        'borders' => array(
            'allborders' => array(
                'styleBorder' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
            ),
        ),
    );
    if(($request->get('rubro'))=="rubro"){
        $sheet->getPageSetup()->setScale(105);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
   
        $prueba=storage_path("unibol_logo.png");
    //dd($prueba);
    $sheet->mergeCells('C3:D3');
    $image->setPath($prueba); 
    $image->setCoordinates('B2');
    $image->setWidth(80);
    $image->setHeight(80);
    $image->setWorksheet($sheet);
    $sheet->getStyle('C3')->applyFromArray($titleStyle);
    
    $sheet->setCellValue('C3', 'REPORTE DEL TOTAL POR RUBRO');
    $sheet->getRowDimension(3)->setRowHeight(25);
    $row++; 
    $row++; 
    $sheet->setCellValue('B6', 'Fecha inicio:'.$fecha_ini);
    $sheet->setCellValue('D6', 'Fecha fin:'.$fecha_fin);
    $row++; 
    $row++; $row++; $row++; $row++; 
$COL++;
        $RUBRO=$COL;$COL++;
        $RUBRO_DESCRIPCION=$COL;$COL++;
        $MONTO=$COL;$COL++;
        $sheet->setCellValueByColumnAndRow($RUBRO,$row,"RUBRO");    
        $sheet->setCellValueByColumnAndRow($RUBRO_DESCRIPCION,$row,"NOMBRE DEL RUBRO");
        $sheet->setCellValueByColumnAndRow($MONTO,$row,"TOTAL BS.-");   
   // dd($extracto_pagos);
     $l=0;
     foreach($rubro as $run){
        $row++; 
//dd($run);
           $sheet->setCellValueByColumnAndRow($RUBRO,$row,$run->numero_identificador);  
           $sheet->setCellValueByColumnAndRow($RUBRO_DESCRIPCION,$row,$run->rubro_descripcion);
           $sheet->setCellValueByColumnAndRow($MONTO,$row,$run->monto_consolidado); 
       //      $sheet->getColumnDimension('C')->setAutoSize(true);
       //    $sheet->getColumnDimension('D')->setAutoSize(true);
       $sheet->getStyleByColumnAndRow($MONTO, $row)
       ->getNumberFormat()
       ->setFormatCode('#,##0.00');
           $l=$l+$run->monto_consolidado;
        $highestRow = $sheet->getHighestDataRow(); // e.g. 10
         $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
          $sheet->getStyle('B'.$row.':'.$highestColumn."".$highestRow)->applyFromArray($styleRubro);      
      $sheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
       
       }
       $m=$row+1;
       $sheet->setCellValueByColumnAndRow($RUBRO,$row+1,'TOTAL'); 
       $sheet->mergeCells('B'.$m.':C'.$m);
       $sheet->setCellValueByColumnAndRow($MONTO,$row+1,$l); 
       $sheet->getStyleByColumnAndRow($MONTO, $row+1)
       ->getNumberFormat()
       ->setFormatCode('#,##0.00');
    //     foreach($pago->detalle_pago as $detalle){
    //         if($detalle->cuenta->rubro->descripcion!=$ex){
    //              $row++; 
    //              $sheet->setCellValueByColumnAndRow($RUBRO,$row,$detalle->cuenta->rubro->numero_identificador);  
    //              $sheet->setCellValueByColumnAndRow($RUBRO_DESCRIPCION,$row,$detalle->cuenta->rubro->descripcion);     
    //              $ex=$detalle->cuenta->rubro->descripcion;
    //           }
    //            $l=$detalle->monto+$l;  
    //           }      
    //         $sheet->setCellValueByColumnAndRow($USUARIO,$row,$pago->user->email);
    //   
    // //    $prf2=$prf;  
    $sheet->getStyle('C3')->applyFromArray($titleStyle);
      $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
         $cellIterator->setIterateOnlyExistingCells( true );
         foreach( $cellIterator as $cell ) {
                 $sheet->getColumnDimension( $cell->getColumn() )->setAutoSize( true );
         }
         $highestRow = $sheet->getHighestDataRow(); // e.g. 10
         $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
        $f=$row+1;
         $sheet->getStyle('B'.$f.':'.$highestColumn."".$highestRow)->applyFromArray($styleRubro);      
         $sheet->getColumnDimension('B')->setAutoSize(FALSE);
         $sheet->getColumnDimension('B')->setWidth(17);
         $sheet->getColumnDimension('C')->setWidth(54);
         $sheet->getColumnDimension('D')->setWidth(20);
         $s=$row+1;
        
         $sheet->getStyle('A'.$s . ':' . $sheet->getHighestColumn() . $s)->applyFromArray($estilototal);
          $sheet->getStyle('D'.$s)->applyFromArray($total);
        $sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        // $gu=1;
        $sheet->getProtection()->setSheet(true);
        $sheet->getProtection()->setPassword('1234');
    }

      if(($request->get('rubros'))=="rubros"){

        $FECHA=$COL;$COL++;
        $RUBRO=$COL;$COL++;
        $RUBRO_DESCRIPCION=$COL;$COL++;
        $UNIDAD=$COL;$COL++;
        $PRODUCTO=$COL;$COL++;
        $MONTO=$COL;$COL++;
        $USUARIO=$COL;$COL++;
        $sheet->setCellValueByColumnAndRow($FECHA,$row,"FECHA");
        $sheet->setCellValueByColumnAndRow($RUBRO,$row,"RUBRO");    
        $sheet->setCellValueByColumnAndRow($RUBRO_DESCRIPCION,$row,"NOMBRE DEL RUBRO");   
        $sheet->setCellValueByColumnAndRow($UNIDAD,$row,"UNIDAD");   
        $sheet->setCellValueByColumnAndRow($PRODUCTO,$row,"PRODUCTO");
        $sheet->setCellValueByColumnAndRow($MONTO,$row,"MONTO");
        $sheet->setCellValueByColumnAndRow($USUARIO,$row,"USUARIO SISTEMA");
       
        foreach($extracto_pagos as $pago){

          foreach($pago->detalle_pago as $detalle){
              $row++;        
              $sheet->setCellValueByColumnAndRow($FECHA,$row,$pago->getFechaPagoStr());
              $sheet->setCellValueByColumnAndRow($RUBRO,$row,$detalle->cuenta->rubro->numero_identificador);  
              $sheet->setCellValueByColumnAndRow($RUBRO_DESCRIPCION,$row,$detalle->cuenta->rubro->descripcion);       
              $sheet->setCellValueByColumnAndRow($UNIDAD,$row,$detalle->cuenta->unidad_ent->descripcion);
              $sheet->setCellValueByColumnAndRow($PRODUCTO,$row,$detalle->cuenta->nombre_cuenta);        
              //$sheet->setCellValueByColumnAndRow($,$row,$detalle->descripcion);        
              $sheet->setCellValueByColumnAndRow($MONTO,$row,$detalle->monto); 
              $sheet->setCellValueByColumnAndRow($USUARIO,$row,$pago->user->email);
          }          
        }
         //autosize
         $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
         $cellIterator->setIterateOnlyExistingCells( true );
         foreach( $cellIterator as $cell ) {
                 $sheet->getColumnDimension( $cell->getColumn() )->setAutoSize( true );
         }
         
     
     }

     /////Clasificador
      if(($request->get('clasificador'))=='clasificador'){

      $FECHA=$COL;$COL++;
      $RUBRO=$COL;$COL++;
      $RUBRO_NOMBRE=$COL;$COL++;
      $NUMERO_CLASICADOR=$COL;$COL++;
      $NOMBRE_CLASIFICADOR=$COL;$COL++;
      $MONTO=$COL;$COL++;
      $USUARIO=$COL;$COL++;  
      $sheet->setCellValueByColumnAndRow($FECHA,$row,"FECHA");    
      $sheet->setCellValueByColumnAndRow($RUBRO,$row,"RUBRO");    
      $sheet->setCellValueByColumnAndRow($RUBRO_NOMBRE,$row,"NOMBRE DE RUBRO");    
      $sheet->setCellValueByColumnAndRow($NUMERO_CLASICADOR,$row,"NUMERO CLASIFADOR");    
      $sheet->setCellValueByColumnAndRow($NOMBRE_CLASIFICADOR,$row,"NOMBRE DEL CLASIFICADOR");
      $sheet->setCellValueByColumnAndRow($MONTO,$row,"MONTO");
      $sheet->setCellValueByColumnAndRow($USUARIO,$row,"USARIO");
     
        foreach($prueba as $detalle){
            $row++;      
            $sheet->setCellValueByColumnAndRow($FECHA,$row,$detalle->fecha_pago);   
            $sheet->setCellValueByColumnAndRow($RUBRO,$row,$detalle->numero_identificador);   
            $sheet->setCellValueByColumnAndRow($RUBRO_NOMBRE,$row,$detalle->rubro_descripcion);  
            $sheet->setCellValueByColumnAndRow($NUMERO_CLASICADOR,$row,$detalle->numero_clasificador);     
            $sheet->setCellValueByColumnAndRow($NOMBRE_CLASIFICADOR,$row,$detalle->cuenta_clasificador_descripcion);  
            $sheet->setCellValueByColumnAndRow($MONTO,$row,$detalle->monto_consolidado); 
            $sheet->setCellValueByColumnAndRow($USUARIO,$row,$detalle->name); 
        }   
       //autosize
       $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
       $cellIterator->setIterateOnlyExistingCells( true );
       foreach( $cellIterator as $cell ) {
               $sheet->getColumnDimension( $cell->getColumn() )->setAutoSize( true );
       }
   } 
   //////àara 
   $bo=0;
   if(($request->get('extracto_general'))=="extracto_general"){
    
    $prueba=storage_path("unibol_logo.png");
    //dd($prueba);
   // $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
      $sheet->mergeCells('B3:I3');
    $image->setPath($prueba); 
    $image->setCoordinates('A1');
    $image->setWidth(200);
    $image->setHeight(100);
    $image->setWorksheet($sheet);
    $sheet->getStyle('B3')->applyFromArray($titleStyle);
    
    $sheet->setCellValue('B3', 'REPORTE GENERAL');
    $sheet->getRowDimension(3)->setRowHeight(25);
    $row++; 
    $row++;
    $sheet->setCellValue('B6', 'Fecha inicio:'.$fecha_ini);
    if($usuario->categoria=='2'){
        $sheet->setCellValue('H6', 'Fecha fin:'.$fecha_fin);
    }else{
        $sheet->setCellValue('G6', 'Fecha fin:'.$fecha_fin);
    }
    
    $row++; 
    $row++; $row++; $row++; $row++; 

 
    $SERIE=$COL;$COL++;
    $RUBRO=$COL;$COL++;
    $UNIDAD=$COL;$COL++;
    if($usuario->categoria=='2'){
      $CUENTA_CLASIFICADOR=$COL;$COL++;
      $NRO_BOLETA=$COL;$COL++;
      $sheet->getPageSetup()->setScale(60);
      $bo=1;
    }else{
        $sheet->getPageSetup()->setScale(75);
    }
    $PRODUCTO=$COL;$COL++;
    $FECHA=$COL;$COL++;
    $SECTOR=$COL;$COL++;
    $CANTIDAD=$COL;$COL++;
    $PRECIO_UNITARIO=$COL;$COL++;
    $MONTO=$COL;$COL++;

    $sheet->setCellValueByColumnAndRow($SERIE,$row,"Nº DE SERIE"); 
    $sheet->setCellValueByColumnAndRow($RUBRO,$row,"NOMBRE DE RUBRO");
    $sheet->setCellValueByColumnAndRow($UNIDAD,$row,"NOMBRE DE UNIDAD");  
    if($usuario->categoria=='2'){
       $sheet->setCellValueByColumnAndRow($CUENTA_CLASIFICADOR,$row,"NOMBRE DEL CLASIFICADOR"); 
       $sheet->setCellValueByColumnAndRow($NRO_BOLETA,$row,"NRO DE BOLETO"); 
   
    }
    $sheet->setCellValueByColumnAndRow($PRODUCTO,$row,"CUENTA");
    $sheet->setCellValueByColumnAndRow($FECHA,$row,"FECHA"); 
    $sheet->setCellValueByColumnAndRow($SECTOR,$row,"SECTOR DE PAGO"); 
    $sheet->setCellValueByColumnAndRow($CANTIDAD,$row,"CANTIDAD");   
    $sheet->setCellValueByColumnAndRow($PRECIO_UNITARIO,$row,"PRECIO POR UNIDAD");
    $sheet->setCellValueByColumnAndRow($MONTO,$row,"MONTO");
    $salta=0;
    $praaaa=0;
    $cont=0;
    $auxa=0;
    $fina=0;
    $suma=0;
      foreach($extracto as $detalle){
       
          $row++;
          if($detalle->serie!=$praaaa){ 
          $sheet->setCellValueByColumnAndRow($SERIE,$row,$detalle->serie);   
          $sheet->setCellValueByColumnAndRow($RUBRO,$row,$detalle->rubro_descripcion);  
          $praaaa=$detalle->serie;  
          $salta=$row;
          if($cont!=0){
             //dd($cont);
            $fil=$salta-$cont;
            $auxa=$fil+$cont-1;

          //  dd($fil,$auxa);
            $sheet->mergeCells('A'.$fil.':A'.$auxa);
            $sheet->mergeCells('B'.$fil.':B'.$auxa);
            $cont=0;
            $sheet->getStyle('A'.$fil.':A'.$auxa)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A'.$fil.':A'.$auxa)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle('B'.$fil.':B'.$auxa)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
           
        }
        } 
        $cont++;
          $sheet->setCellValueByColumnAndRow($UNIDAD,$row,$detalle->unidad_descripcion); 
          if($usuario->categoria=='2'){
          $sheet->setCellValueByColumnAndRow($CUENTA_CLASIFICADOR,$row,$detalle->cuenta_clasificador_descripcion);       
          $sheet->setCellValueByColumnAndRow($NRO_BOLETA,$row,$detalle->numero_recibo);   
        //   $sheet->getColumnDimension('J')->setAutoSize(true);
        //   $sheet->getColumnDimension('K')->setAutoSize(true);
        }
          $sheet->setCellValueByColumnAndRow($PRODUCTO,$row,$detalle->producto);
          $sheet->setCellValueByColumnAndRow($FECHA,$row,$detalle->fecha_pago);   
          $sheet->setCellValueByColumnAndRow($SECTOR,$row,$detalle->sector);        
          $sheet->setCellValueByColumnAndRow($CANTIDAD,$row,$detalle->cantidad); 
          $sheet->setCellValueByColumnAndRow($PRECIO_UNITARIO,$row,$detalle->precio_unitario); 
          $sheet->setCellValueByColumnAndRow($MONTO,$row,$detalle->monto); 
          $sheet->getRowDimension($row)->setRowHeight(30);  
             
           if($usuario->categoria=='1'){
      
        $sheet->getColumnDimension('B')->setWidth(22);
          $sheet->getColumnDimension('C')->setWidth(22);
         $sheet->getColumnDimension('D')->setWidth(40);
         $sheet->getColumnDimension('E')->setWidth(18);
          $sheet->getColumnDimension('F')->setWidth(10);
          $sheet->getColumnDimension('G')->setWidth(11);
          $sheet->getColumnDimension('I')->setWidth(10);
          $sheet->getColumnDimension('H')->setWidth(12);
          }else{    
          
            $sheet->getColumnDimension('B')->setWidth(22);
            $sheet->getColumnDimension('C')->setWidth(22);
           $sheet->getColumnDimension('D')->setWidth(40);
           $sheet->getColumnDimension('E')->setWidth(18);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(11);
            $sheet->getColumnDimension('J')->setWidth(13);
            $sheet->getColumnDimension('H')->setWidth(12);
          }
         
          $sheet->getStyleByColumnAndRow($MONTO, $row, $PRECIO_UNITARIO, $row)
          ->getNumberFormat()
          ->setFormatCode('#,##0.00');
        //   $adu= $sheet->getStyleByColumnAndRow($MONTO, $row);
        //   $a= $sheet->getStyleByColumnAndRow($PRECIO_UNITARIO, $row);
        //   $a->getNumberFormat()->setFormatCode('#,##0.00');
        //   $adu->getNumberFormat()->setFormatCode('#,##0.00');

          //   $sheet->getColumnDimension('A')->setAutoSize(true);
        //   $sheet->getColumnDimension('B')->setAutoSize(true);
        //   $sheet->getColumnDimension('C')->setAutoSize(true);
        //   $sheet->getColumnDimension('D')->setAutoSize(true);
        //   $sheet->getColumnDimension('G')->setAutoSize(true);
        //   $sheet->getColumnDimension('E')->setAutoSize(true);
        //   $sheet->getColumnDimension('F')->setAutoSize(true);
        //   $sheet->getColumnDimension('G')->setAutoSize(true);
        //   $sheet->getColumnDimension('H')->setAutoSize(true);
        //   $sheet->getColumnDimension('I')->setAutoSize(true);
       $suma=$suma+$detalle->monto;
       $prs=$row-1; 
       $highestRow = $sheet->getHighestDataRow(); // e.g. 10
     $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
   
     $sheet->getStyle('A'.$prs.':'.$highestColumn."".$highestRow)->applyFromArray($styleArray);      
  //   $sheet->getStyle('A'.$row . ':' . $sheet->getHighestColumn() . $row)->applyFromArray($estilototal);
   
    }  
        
$fina=$salta+$cont-1;
$ñ=$row+1;

$sheet->mergeCells('A'.$salta.':A'.$fina);
$sheet->mergeCells('B'.$salta.':B'.$fina);  

$sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A'.$salta.':A'.$fina)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->setCellValue('A'.$ñ, 'TOTAL'); 

if($usuario->categoria=='1'){
$sheet->setCellValue('I'.$ñ, $suma); 
$sheet->mergeCells('A'.$ñ.':H'.$ñ);
$sheet->getStyle('A'.$ñ)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
$a= $sheet->getStyleByColumnAndRow($MONTO, $ñ);
$a->getNumberFormat()->setFormatCode('#,##0.00');

$sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('C')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getStyle('D')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getStyle('I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$sheet->getStyle('B'.$salta.':B'.$fina)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getColumnDimension('C')->setVisible(false);
}else{
    $sheet->setCellValue('K'.$ñ, $suma); 
$sheet->mergeCells('A'.$ñ.':J'.$ñ);
$sheet->getStyle('A'.$ñ)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
$a= $sheet->getStyleByColumnAndRow($MONTO, $ñ);
$a->getNumberFormat()->setFormatCode('#,##0.00');
$sheet->getStyle('B'.$salta.':B'.$fina)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
$sheet->getStyle('K')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
}

//   $adu->getNumberFormat()->setFormatCode('#,##0.00');

//$re=$fina+4;

     //autosize
     $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
     $cellIterator->setIterateOnlyExistingCells( true );
     foreach( $cellIterator as $cell ) {
             $sheet->getColumnDimension( $cell->getColumn() )->setAutoSize( true );
     }
       
     $sheet->getPageMargins()->setLeft(0);
     $sheet->getPageMargins()->setRight(0);
     $prs=$row-1;
     $highestRow = $sheet->getHighestDataRow(); // e.g. 10
     $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
     $s=$row+1;
     $sheet->getStyle('A'.$prs.':'.$highestColumn."".$highestRow)->applyFromArray($styleArray);      
     $sheet->getStyle('A'.$s . ':' . $sheet->getHighestColumn() . $s)->applyFromArray($estilototal);
    $sheet->getProtection()->setSheet(true);
     $sheet->getProtection()->setPassword('1234');
 //$act=1;
 //$sheet->getPageSetup()->setVerticalCentered(true);
 
$sheet->getStyle('I'.$ñ)->applyFromArray($total);
 $sheet->getColumnDimension('A')->setAutoSize(false);
 $sheet->getColumnDimension('A')->setWidth(7);
      }


    if(($request->get('extracto_boleta'))=="extracto_boleta"){
      
        $prueba=storage_path("unibol_logo.png");
        //dd($prueba);
       // $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
       
       $sheet->mergeCells('B3:H3');
        $image->setPath($prueba); 
        $image->setCoordinates('A1');
        $image->setWidth(200);
        $image->setHeight(100);
        $image->setWorksheet($sheet);
        $sheet->getStyle('B3')->applyFromArray($titleStyle);
        
        $sheet->setCellValue('B3', 'REPORTE DE SERIE POR BOLETA');
        $sheet->getRowDimension(3)->setRowHeight(25);
        $row++; 
        $row++; 
        $sheet->setCellValue('B6', 'Fecha inicio:'.$fecha_ini);
        $sheet->setCellValue('H6', 'Fecha fin:'.$fecha_fin);
        $row++; 
        $row++; $row++; $row++; $row++; 
        $CATEGORIA=$COL;$COL++;
        $IDRUBRO=$COL;$COL++;
        $RUBRO=$COL;$COL++;
        $SERIE=$COL;$COL++;
        $CUENTA=$COL;$COL++;
        $UNIDAD=$COL;$COL++;
        $USUARIO=$COL;$COL++;
        $FECHA=$COL;$COL++;
        $TOTAL=$COL;$COL++;
    
        $sheet->setCellValueByColumnAndRow($CATEGORIA,$row,"CATEGORIA");
        $sheet->setCellValueByColumnAndRow($IDRUBRO,$row,"ID DE RUBRO");  
        $sheet->setCellValueByColumnAndRow($RUBRO,$row,"NOMBRE DEL RUBRO"); 
        $sheet->setCellValueByColumnAndRow($SERIE,$row,"SERIE");  
        $sheet->setCellValueByColumnAndRow($CUENTA,$row,"CUENTA");
     //   $sheet->setCellValueByColumnAndRow($UNIDAD,$row,"NOMBRE DE LA UNIDAD");
        $sheet->setCellValueByColumnAndRow($USUARIO,$row,"NOMBRE DE USUARIO"); 
        $sheet->setCellValueByColumnAndRow($FECHA,$row,"FECHA"); 
        $sheet->setCellValueByColumnAndRow($TOTAL,$row,"TOTAL"); 
        $salta1=0;
        $cont=0;
        $auxa=0;
        $fina=0;
        $salta="";
        $contador=0;
        $prua=count($boleta);
        //dd($prua);
        $iterador=0;
          $praaaa=0;
          $asig=0;
          $ah=0;
          $m=0;
            foreach($boleta as $detalle){
  //   dd($detalle);   
                    $iterador++;
             
                     $row++; 
                      
                     if($salta!=$detalle->rubro_descripcion and $praaaa!=0){
                            $row++;
                            //  dd($row);
                              $asig=$row-1;
                                $ah=$asig;
                              $sheet->setCellValue('A'.$asig, 'TOTAL'); 
                               $sheet->setCellValue('I'.$asig, $contador); 
                              $sheet->mergeCells('A'.$asig.':G'.$asig);
                              $sheet->getStyle('A'.$asig)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                            $sheet->getStyle('A'.$asig)->getProtection()->setLocked(true);
                              $highestRow = $sheet->getHighestDataRow(); // e.g. 10
                              $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
                              //$sheet->getStyle('A'.$asig)->applyFromArray($estilototal);
                              $sheet->getStyle('A'.$asig . ':' . $sheet->getHighestColumn() . $asig)->applyFromArray($estilototal);
                              $sheet->getStyle('I'.$asig)->applyFromArray($total);
                              $sheet->getStyle('A9:'.$highestColumn."".$highestRow)->applyFromArray($styleArray);      
                             $spreadsheet->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($CATEGORIA)."9:".Coordinate::stringFromColumnIndex($TOTAL)."9")->applyFromArray($styleArray1); 
                             $tot= $sheet->getStyleByColumnAndRow($TOTAL, $asig);
                              $tot->getNumberFormat()->setFormatCode('#,##0.00');
                              
                              $row++;
                              $sheet->setCellValueByColumnAndRow($CATEGORIA,$row,"CATEGORIA");
                              $sheet->setCellValueByColumnAndRow($IDRUBRO,$row,"ID DE RUBRO");  
                              $sheet->setCellValueByColumnAndRow($RUBRO,$row,"NOMBRE DEL RUBRO"); 
                              $sheet->setCellValueByColumnAndRow($SERIE,$row,"SERIE");  
                              $sheet->setCellValueByColumnAndRow($CUENTA,$row,"CUENTA"); 
                            ///  $sheet->setCellValueByColumnAndRow($UNIDAD,$row,"NOMBRE DE LA UNIDAD");
                              $sheet->setCellValueByColumnAndRow($USUARIO,$row,"NOMBRE DE USUARIO"); 
                              $sheet->setCellValueByColumnAndRow($FECHA,$row,"FECHA"); 
                              $sheet->setCellValueByColumnAndRow($TOTAL,$row,"TOTAL");  
                              $spreadsheet->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($CATEGORIA).$row.":".Coordinate::stringFromColumnIndex($TOTAL).$row)->applyFromArray($styleArray1); 
                              $highestRow = $sheet->getHighestDataRow(); // e.g. 10
                              $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
                            
                              $sheet->getStyle('A'.$row.':'.$highestColumn."".$highestRow)->applyFromArray($styleArray);      
                              
                          
                               $row++;
                              $contador=0;
                             
                    }
                    //    $sheet->setCellValueByColumnAndRow($CATEGORIA,$row,($detalle->categoria==1)?'TESORERIA':'COMERCIALIZACION'); 
                        if($detalle->serie!=$praaaa){ 

                         $sheet->setCellValueByColumnAndRow($CATEGORIA,$row,$detalle->categoria);
                         $sheet->setCellValueByColumnAndRow($IDRUBRO,$row,$detalle->nidentificador);
                         $sheet->setCellValueByColumnAndRow($RUBRO,$row,$detalle->rubro_descripcion);    
                         $sheet->setCellValueByColumnAndRow($SERIE,$row,$detalle->serie); 
                         $praaaa=$detalle->serie;  
                         $m=1;
                         $salta1=$row;
                         if($cont!=0){
                            //dd($cont);
                            if($ah==0){
                                $fil=$salta1-$cont;
                                $auxa=$fil+$cont-1;
                         $sheet->mergeCells('A'.$fil.':A'.$auxa);
                         $sheet->mergeCells('B'.$fil.':B'.$auxa);
                         $sheet->mergeCells('C'.$fil.':C'.$auxa);
                         $sheet->mergeCells('D'.$fil.':D'.$auxa);
                       //  $sheet->mergeCells('F'.$fil.':F'.$auxa);
                         $sheet->mergeCells('I'.$fil.':I'.$auxa);
                         $cont=0;
                            }
                            
                        //  dd($fil,$auxa);
                            //     $sheet->getStyle('A'.$fil.':A'.$auxa)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                      //     $sheet->getStyle('A'.$fil.':A'.$auxa)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                     //      $sheet->getStyle('B'.$fil.':B'.$auxa)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                          
                       }

                     }
                    
                     if($asig!=0){
                   //    dd($fil,$auxa);
                  // dd($asig);
                        $c=$asig-$cont;
                        $c1=$c+$cont-1;
                        $sheet->mergeCells('A'.$c.':A'.$c1);
                        $sheet->mergeCells('B'.$c.':B'.$c1);
                        $sheet->mergeCells('C'.$c.':C'.$c1);
                        $sheet->mergeCells('D'.$c.':D'.$c1);
                       // $sheet->mergeCells('F'.$c.':F'.$c1);
                        $sheet->mergeCells('I'.$c.':I'.$c1);
                    $ah=0;
                     }
                     //     dd($SERIE,$row);
                         $cont++;
                         
                        $sheet->setCellValueByColumnAndRow($CUENTA,$row,$detalle->nombre_cuenta);   
                        $sheet->setCellValueByColumnAndRow($FECHA,$row,$detalle->fecha_pago);      
                       //if($m==1){ 
                        // $sheet->setCellValueByColumnAndRow($UNIDAD,$row,$detalle->unidad_descripcion);  
                        
                       // }
                         $sheet->setCellValueByColumnAndRow($USUARIO,$row,$detalle->name);
                         if($m==1){
                        $sheet->setCellValueByColumnAndRow($TOTAL,$row,$detalle->total);
                         $m++;
                        }
                        $adu= $sheet->getStyleByColumnAndRow($TOTAL, $row);
                        $adu->getNumberFormat()->setFormatCode('#,##0.00');

                        $sheet->getRowDimension($row)->setRowHeight(30); 
                       
                        $sheet->getColumnDimension('C')->setWidth(22);
                        $sheet->getColumnDimension('D')->setWidth(9);
                        $sheet->getColumnDimension('E')->setWidth(40);
                       // $sheet->getColumnDimension('F')->setWidth(22);
                        $sheet->getColumnDimension('G')->setWidth(11);
                        $sheet->getColumnDimension('H')->setWidth(20);
                        // $sheet->getColumnDimension('A')->setAutoSize(true);
                        // $sheet->getColumnDimension('B')->setAutoSize(true);
                        // $sheet->getColumnDimension('C')->setAutoSize(true);
                        // $sheet->getColumnDimension('D')->setAutoSize(true);
                        // $sheet->getColumnDimension('G')->setAutoSize(true);
                        // $sheet->getColumnDimension('E')->setAutoSize(true);
                       
                   
                        $contador+=$detalle->total;
                        $salta=$detalle->rubro_descripcion;
                  
                    
                    if($prua == $iterador){
                        $row++;
                        $asig=$row;
                      //  dd($contador);
                    $sheet->setCellValue('A'.$asig, 'TOTAL'); 
                     $sheet->setCellValue('I'.$asig, $contador); 
                    $sheet->mergeCells('A'.$asig.':G'.$asig);
                    $contador=0;
                     }
                    //
                 
                    // $salta= $row;
                     $f=$row;
                // else{
                //     $salta++;
                // }            
                //  
             
              }

         
             //  dd($contador);
         //autosize
         $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
         $cellIterator->setIterateOnlyExistingCells( true );
         foreach( $cellIterator as $cell ) {
                 $sheet->getColumnDimension( $cell->getColumn() )->setAutoSize( true );
         }
         //$sheet->getPageMargins()->setScale(1.30, \PhpOffice\PhpSpreadsheet\Worksheet\PageMargins::FORMAT_RIGHT);
        //  $sheet->getPageSetup()->setHorizontalCentered(false); 
        //  $sheet->getPageSetup()->setVerticalCentered(false);
        
$sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
//$sheet->getStyle('B')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
         $sheet->getPageMargins()->setLeft('0.6');
         $sheet->getPageMargins()->setRight(0);
        //$sheet->getPageMargins()->setLeft(\PhpOffice\PhpSpreadsheet\Shared\Drawing::centimetersToPoints(1.30));
         $sheet->getColumnDimension('F')->setVisible(false);
         $sheet->getPageSetup()->setScale(70);   
$prs=$row-1;

       //  $spreadsheet->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($CATEGORIA).$row.":".Coordinate::stringFromColumnIndex($TOTAL).$row)->applyFromArray($styleArray1); 
         $highestRow = $sheet->getHighestDataRow(); // e.g. 10
         $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
       
         $sheet->getStyle('A'.$prs.':'.$highestColumn."".$highestRow)->applyFromArray($styleArray);      
         $sheet->getStyle('A'.$asig . ':' . $sheet->getHighestColumn() . $asig)->applyFromArray($estilototal);
         $sheet->getStyle('I'.$asig)->applyFromArray($total);
         $tot= $sheet->getStyleByColumnAndRow($TOTAL, $asig);
         $tot->getNumberFormat()->setFormatCode('#,##0.00');
      //   $sheet->getProtection()->setSelectLockedCells('J1', '123');           
         $style = $sheet->getStyle('A1:'.'I'.$row);
        
        $style->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $sheet->getProtection()->setSheet(true);
    $sheet->getProtection()->setPassword('1234');
        
// $sheet->getStyle('A')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
 $sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
// $sheet->getStyle('B')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
 //$sheet->getStyle('I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); 
// $sheet->getStyle('C')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
// $sheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
//           $sheet->getStyle('D')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
//         $sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
//         $sheet->getStyle('E')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
//         $sheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
//         $sheet->getStyle('F')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
//         $sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
//         $sheet->getStyle('G')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
//         $sheet->getStyle('G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
//                   $sheet->getStyle('H')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
//                 $sheet->getStyle('H')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
         //$re=$f+4;
           }
    


 if(($request->get('extracto_bosleta'))=="extracto_bsoleta"){

    $prueba=storage_path("unibol_logo.png");
    //dd($prueba);
   // $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
   
   $sheet->mergeCells('B3:G3');
    $image->setPath($prueba); 
    $image->setCoordinates('A1');
    $image->setWidth(200);
    $image->setHeight(100);
    $image->setWorksheet($sheet);
    $sheet->getStyle('B3')->applyFromArray($titleStyle);
    
    $sheet->setCellValue('B3', 'REPORTE DE SERIE POR BOLETA');
    $sheet->getRowDimension(3)->setRowHeight(25);
    $row++; 
    $row++; 
    $sheet->setCellValue('B6', 'Fecha inicio:'.$fecha_ini);
    $sheet->setCellValue('F6', 'Fecha fin:'.$fecha_fin);
    $row++; 
    $row++; $row++; $row++; $row++; 
    $CATEGORIA=$COL;$COL++;
    $SERIE=$COL;$COL++; 
    $RUBROid=$COL;$COL++;
    $RUBRO=$COL;$COL++;
    $NCUENTA=$COL;$COL++;
    $UNIDAD=$COL;$COL++;
    $USUARIO=$COL;$COL++;
    $FECHA=$COL;$COL++;
    $TOTAL=$COL;$COL++;

    $sheet->setCellValueByColumnAndRow($CATEGORIA,$row,"CATEGORIA");
    $sheet->setCellValueByColumnAndRow($SERIE,$row,"SERIE");  
    $sheet->setCellValueByColumnAndRow($RUBRO,$row,"NOMBRE DEL RUBRO"); 
    $sheet->setCellValueByColumnAndRow($UNIDAD,$row,"NOMBRE DE LA UNIDAD");
    $sheet->setCellValueByColumnAndRow($USUARIO,$row,"NOMBRE DE USUARIO"); 
    $sheet->setCellValueByColumnAndRow($FECHA,$row,"FECHA"); 
    $sheet->setCellValueByColumnAndRow($TOTAL,$row,"TOTAL"); 
    $salta=0;
    $f=0;
    
    foreach($extracto_pagos as $pago){
         $praaaa=0;
        foreach($pago->detalle_pago as $detalle){
      //      if($pago->serie!=$praaaa){ 
               $row++; 
                  
                    $sheet->setCellValueByColumnAndRow($CATEGORIA,$row,($pago->categoria==1)?'TESORERIA':'COMERCIALIZACION'); 
                    $sheet->setCellValueByColumnAndRow($SERIE,$row,$pago->serie); 
                    //     dd($SERIE,$row);
                    $sheet->setCellValueByColumnAndRow($RUBROid,$row,$detalle->cuenta->rubro->numero_identificador); 
                    $sheet->setCellValueByColumnAndRow($RUBRO,$row,$detalle->cuenta->rubro->descripcion); 
                    $sheet->setCellValueByColumnAndRow($NCUENTA,$row,$detalle->cuenta->nombre_cuenta); 
                    $sheet->setCellValueByColumnAndRow($FECHA,$row,$pago->getFechaPagoStr());      
                    $sheet->setCellValueByColumnAndRow($TOTAL,$row,$pago->total); 
                    $sheet->setCellValueByColumnAndRow($UNIDAD,$row,$detalle->cuenta->unidad_ent->descripcion);  
                    $sheet->setCellValueByColumnAndRow($USUARIO,$row,$pago->user->email);
                    $sheet->getRowDimension($row)->setRowHeight(30); 
                    $sheet->getColumnDimension('C')->setWidth(22);
                    $sheet->getColumnDimension('D')->setWidth(22);
                    $sheet->getColumnDimension('E')->setWidth(22);
                    $sheet->getColumnDimension('F')->setWidth(22);
                    // $sheet->getColumnDimension('A')->setAutoSize(true);
                    // $sheet->getColumnDimension('B')->setAutoSize(true);
                    // $sheet->getColumnDimension('C')->setAutoSize(true);
                    // $sheet->getColumnDimension('D')->setAutoSize(true);
                    // $sheet->getColumnDimension('G')->setAutoSize(true);
                    // $sheet->getColumnDimension('E')->setAutoSize(true);
                 // $praaaa=$pago->serie;
               // }
                //
                // $salta= $row;
                 $f=$row;
            // else{
            //     $salta++;
            // }            
            //    
          }     
      }  
     //autosize
     $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
     $cellIterator->setIterateOnlyExistingCells( true );
     foreach( $cellIterator as $cell ) {
             $sheet->getColumnDimension( $cell->getColumn() )->setAutoSize( true );
     }
     
     //$re=$f+4;

 }



   
       //FORMATEANDO LOS ROTULOS
       if(($request->get('rubro'))=="rubro"){
        $spreadsheet->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($RUBRO)."9:".Coordinate::stringFromColumnIndex($MONTO)."9")->applyFromArray($stylo);          
        }
       if(($request->get('clasificador'))=='clasificador'){
        $spreadsheet->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($FECHA)."5:".Coordinate::stringFromColumnIndex($USUARIO)."5")->applyFromArray($styleArray);          
        }  
       if(($request->get('extracto_general'))=="extracto_general"){
        $spreadsheet->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($SERIE)."9:".Coordinate::stringFromColumnIndex($MONTO)."9")->applyFromArray($stylenuevo);          
        }
        if(($request->get('extractos_boleta'))=="extractos_boleta"){
            $spreadsheet->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($CATEGORIA)."9:".Coordinate::stringFromColumnIndex($TOTAL)."9")->applyFromArray($styleArray);          
        }

        //FORMATEANDO LAS LINEAS
     
     //   $sheet->getStyle('A9:'.$highestColumn."".$highestRow)->applyFromArray($styleArray);      
       
    //         if($gu!=0){
    //    $sheet->setCellValue('A'.$d, 'RECIBE CONFORME');  
    //    $sheet->setCellValue('C'.$d, 'ENTREGA CONFORME');
    // } else{
    //     if($act!=0){
    //         if($bo!=0){
    //     $sheet->setCellValue('B'.$re, 'RECIBE CONFORME');  
    //     $sheet->setCellValue('H'.$re, 'ENTREGA CONFORME');
    //     }else{
    //         $sheet->setCellValue('B'.$re, 'RECIBE CONFORME');  
    //         $sheet->setCellValue('G'.$re, 'ENTREGA CONFORME');
    //     }
    // }else{
    //     $sheet->setCellValue('B'.$re, 'RECIBE CONFORME'); 
    //     $sheet->setCellValue('F'.$re, 'ENTREGA CONFORME');
    //     }
    // }
       $writer = new Xlsx($spreadsheet);      
       try {
           $writer->save(storage_path('ExtractoPago.xlsx'));
       } catch (Exception $e) {  }   
       return response()->download(storage_path('ExtractoPago.xlsx'));     
    }    
}

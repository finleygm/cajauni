<?php

namespace App\Http\Controllers;

use App\Cuenta;
use App\CuentaClasificador;
use Illuminate\Http\Request;
use App\Pago;
use App\Rubro;
use App\Unidad;
use DB;

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
                    'pago.sector',
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
                    DB::raw("GROUP_CONCAT(DISTINCT rubro.descripcion) as rubro_descripcion"),
                    DB::raw("GROUP_CONCAT(DISTINCT unidad.descripcion) as unidad_descripcion"),
                    'users.name',
                    DB::raw("GROUP_CONCAT(DISTINCT pago.fecha_pago) AS fecha_pago"),
                    DB::raw("sum(pago_detalle.precio_unitario*pago_detalle.cantidad) as monto"),
                    'pago.total'
                    
                 )->where('fecha_pago','>=',ReportesController::mysqlDatetoStr($fecha_ini))
                 ->where('fecha_pago','<=',ReportesController::mysqlDatetoStr($fecha_fin))
                 ->groupby('pago_detalle.id')
                 ->orderby('pago.categoria', 'asc')
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

      $fecha_ini=$request->get('fecha_ini');
      $fecha_fin=$request->get('fecha_fin');
      $prueba=$this->getConsulta($fecha_ini,$fecha_fin);
      $extracto=$this->getExtractoGrande($fecha_ini,$fecha_fin);
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();      
      $extracto_pagos=$this->getExtracto($fecha_ini, $fecha_fin);
      $row=1;
      $COL=1;

      if(($request->get('rubro'))=="rubro"){

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
   
   if(($request->get('extracto_general'))=="extracto_general"){

    $RUBRO=$COL;$COL++;
    $UNIDAD=$COL;$COL++;
    $CUENTA_CLASIFICADOR=$COL;$COL++;
    $PRODUCTO=$COL;$COL++;
    $SERIE=$COL;$COL++;
    $FECHA=$COL;$COL++;
    $NRO_BOLETA=$COL;$COL++;
    $SECTOR=$COL;$COL++;
    $CANTIDAD=$COL;$COL++;
    $PRECIO_UNITARIO=$COL;$COL++;
    $MONTO=$COL;$COL++;

    $sheet->setCellValueByColumnAndRow($RUBRO,$row,"NOMBRE DE RUBRO");
    $sheet->setCellValueByColumnAndRow($UNIDAD,$row,"NOMBRE DE UNIDAD");  
    $sheet->setCellValueByColumnAndRow($CUENTA_CLASIFICADOR,$row,"NOMBRE DEL CLASIFICADOR"); 
    $sheet->setCellValueByColumnAndRow($PRODUCTO,$row,"NOMBRE DEL PRODUCTO");
    $sheet->setCellValueByColumnAndRow($SERIE,$row,"Nº DE SERIE"); 
    $sheet->setCellValueByColumnAndRow($FECHA,$row,"FECHA"); 
    $sheet->setCellValueByColumnAndRow($NRO_BOLETA,$row,"NRO DE BOLETO"); 
    $sheet->setCellValueByColumnAndRow($SECTOR,$row,"SECTOR"); 
    $sheet->setCellValueByColumnAndRow($CANTIDAD,$row,"CANTIDAD");   
    $sheet->setCellValueByColumnAndRow($PRECIO_UNITARIO,$row,"PRECIO POR UNIDAD");
    $sheet->setCellValueByColumnAndRow($MONTO,$row,"MONTO");
   
   
      foreach($extracto as $detalle){
          $row++;        
          $sheet->setCellValueByColumnAndRow($RUBRO,$row,$detalle->rubro_descripcion);  
          $sheet->setCellValueByColumnAndRow($UNIDAD,$row,$detalle->unidad_descripcion); 
          $sheet->setCellValueByColumnAndRow($CUENTA_CLASIFICADOR,$row,$detalle->cuenta_clasificador_descripcion);       
          $sheet->setCellValueByColumnAndRow($PRODUCTO,$row,$detalle->producto);
          $sheet->setCellValueByColumnAndRow($SERIE,$row,$detalle->serie);        
          $sheet->setCellValueByColumnAndRow($FECHA,$row,$detalle->fecha_pago);   
          $sheet->setCellValueByColumnAndRow($NRO_BOLETA,$row,$detalle->numero_recibo);   
          $sheet->setCellValueByColumnAndRow($SECTOR,$row,$detalle->sector);        
          $sheet->setCellValueByColumnAndRow($CANTIDAD,$row,$detalle->cantidad); 
          $sheet->setCellValueByColumnAndRow($PRECIO_UNITARIO,$row,$detalle->precio_unitario); 
          $sheet->setCellValueByColumnAndRow($MONTO,$row,$detalle->monto);
      }  

     //autosize
     $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
     $cellIterator->setIterateOnlyExistingCells( true );
     foreach( $cellIterator as $cell ) {
             $sheet->getColumnDimension( $cell->getColumn() )->setAutoSize( true );
     }
 }



 if(($request->get('extracto_boleta'))=="extracto_boleta"){
    
    $CATEGORIA=$COL;$COL++;
    $SERIE=$COL;$COL++;
    $RUBRO=$COL;$COL++;
    $UNIDAD=$COL;$COL++;
    $USUARIO=$COL;$COL++;
    $FECHA=$COL;$COL++;
    $MONTO=$COL;$COL++;
    $TOTAL=$COL;$COL++;

    $sheet->setCellValueByColumnAndRow($CATEGORIA,$row,"CATEGORIA");
    $sheet->setCellValueByColumnAndRow($SERIE,$row,"SERIE");  
    $sheet->setCellValueByColumnAndRow($RUBRO,$row,"NOMBRE DEL RUBRO"); 
    $sheet->setCellValueByColumnAndRow($UNIDAD,$row,"NOMBRE DE LA UNIDAD");
    $sheet->setCellValueByColumnAndRow($USUARIO,$row,"NOMBRE DE USUARIO"); 
    $sheet->setCellValueByColumnAndRow($FECHA,$row,"FECHA"); 
    $sheet->setCellValueByColumnAndRow($MONTO,$row,"MONTO"); 
    $sheet->setCellValueByColumnAndRow($TOTAL,$row,"TOTAL"); 
   
    foreach($extracto_pagos as $pago){
        foreach($pago->detalle_pago as $detalle){
            $row++;        
            
            $sheet->setCellValueByColumnAndRow($CATEGORIA,$row,($pago->categoria==1)?'TESORERIA':'COMERCIALIZACION');
            $sheet->setCellValueByColumnAndRow($SERIE,$row,$pago->serie); 
            $sheet->setCellValueByColumnAndRow($RUBRO,$row,$detalle->cuenta->rubro->descripcion); 
            $sheet->setCellValueByColumnAndRow($UNIDAD,$row,$detalle->cuenta->unidad_ent->descripcion);  
            $sheet->setCellValueByColumnAndRow($USUARIO,$row,$pago->user->email);
            $sheet->setCellValueByColumnAndRow($FECHA,$row,$pago->getFechaPagoStr());      
            $sheet->setCellValueByColumnAndRow($MONTO,$row,$detalle->monto);       
            $sheet->setCellValueByColumnAndRow($TOTAL,$row,$pago->total); 
        }          
      }  

     //autosize
     $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
     $cellIterator->setIterateOnlyExistingCells( true );
     foreach( $cellIterator as $cell ) {
             $sheet->getColumnDimension( $cell->getColumn() )->setAutoSize( true );
     }
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
                    'argb' => 'ffFF8008',
                ],
                'endColor' => [
                    'argb' => 'ffFFC837',
                ],
            ],
        ];  
       //FORMATEANDO LOS ROTULOS
       if(($request->get('rubro'))=="rubro"){
        $spreadsheet->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($FECHA)."1:".Coordinate::stringFromColumnIndex($USUARIO)."1")->applyFromArray($styleArray);          
        }
       if(($request->get('clasificador'))=='clasificador'){
        $spreadsheet->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($FECHA)."1:".Coordinate::stringFromColumnIndex($USUARIO)."1")->applyFromArray($styleArray);          
        }  
       if(($request->get('extracto_general'))=="extracto_general"){
        $spreadsheet->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($RUBRO)."1:".Coordinate::stringFromColumnIndex($MONTO)."1")->applyFromArray($styleArray);          
        }
        if(($request->get('extracto_boleta'))=="extracto_boleta"){
            $spreadsheet->getActiveSheet()->getStyle(Coordinate::stringFromColumnIndex($CATEGORIA)."1:".Coordinate::stringFromColumnIndex($TOTAL)."1")->applyFromArray($styleArray);          
        }

        //FORMATEANDO LAS LINEAS
        $highestRow = $sheet->getHighestDataRow(); // e.g. 10
        $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
        $styleArray = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'wrapText'=>true,
            ],
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

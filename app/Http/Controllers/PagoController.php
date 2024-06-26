<?php

namespace App\Http\Controllers;
//require_once "vendor/autoload.php";
use PhpOffice\PhpWord\Shared\Waterfall;
use Illuminate\Http\Request;
use App\Pago;
use App\ClasificadoPago;
use App\Cliente;
use App\Cuenta;
use App\PagoDetalle;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use DB;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
//instalacion
//composer require phpoffice/phpword
//si hay problema con memoria 
/*
Allowed memory size of 1610612736 bytes exhausted
php -i
en php.ini
memory_limit = 2G 
memory_limit = -1 //otra opción

*/
//para php
use \PhpOffice\PhpWord\Shared\Converter;
use \PhpOffice\PhpWord\Style\Font;
use \PhpOffice\PhpWord\Style\Language;
use Illuminate\Support\Facades\Storage;
use NunoMaduro\Collision\Contracts\Writer;
use PhpOffice\PhpWord\Style\Frame;
use PhpOffice\PhpWord\Writer\PDF;

;

class PagoController extends Controller
{
    public function index(Request $recibir){
        // if($request)
        // {
                       
        // }
        $criterio=$recibir->get('busq');  
        
       // 
        if($criterio==""){
            $lpagos=Pago::orderBy('id','desc')->paginate(15);        
            return view('pago.index',["lpagos"=>$lpagos]);            
        }else{
           
            $lpagos=Pago::where('serie',$criterio)->paginate(15);
           // dd($lpagos);
            if($lpagos->isEmpty()){ 
                //dd($lpagos);
                $cliente=Cliente::where('ci',$criterio)->first();
               // dd($cliente->id);
               if($cliente==null){
                $mensaje="No existe el elemento buscado, busca bien...!";
                $lpagos=Pago::orderBy('id','desc')->paginate(15);  
                return redirect()->route('pago.index')->with('mensaje', $mensaje);
                //return view('pago.index',["lpagos"=>$lpagos]);
               }
                $lpagos=Pago::where('cliente_id',$cliente->id)->paginate(15);
                return view('pago.index',["lpagos"=>$lpagos]);
            }else{
              //  dd($lpagos);
                return view('pago.index',["lpagos"=>$lpagos]);
            }

        //    dd($lpagos);
         //   $lpagos=Pago::findOrFail($criterio);  
            //return view('pago.index',["lpagos"=>$lpagos]);
        }
    }
    public function create(){
        return  view('pago.create');
    }
    public function create2(){
        return  view('pago.create2');
    }
    public function pagar2(Request $request){
        $pago_r=$request->pago;
        $pago_detalle_r=$request->pago_detalle;    
        $pago_aux=Pago::findOrFail($pago_r->id);
        return "Error";
    }
    // public function pagar3(Request $request){
    //     $pago_r=$request->pago;
    //     $pago_detalle_r=$request->pago_detalle;        
    //     $pago_aux=Pago::find($pago_r["id"]);
    //     $pago=new Pago();        
    //     $pago->cliente_id=$pago_r["cliente_id"];       
    //     //$pago->fecha_pago= date('Y-m-d',$pago_r["fecha_pago"]);    
    //     $pago->total=$pago_r["total"];
    //     $pago->id=$pago_r["id"];
    //     if($pago->id==-1)
    //         $pago->id=null;
    //     $pago->serie=1;
    //     if($pago_aux==null){                        
    //         $pago->save();                        
    //     }
    //     $pago_detalle=new PagoDetalle();
    //     $pago_detalle->pago_id=$pago["id"];
    //     $pago_detalle->cantidad=$pago_detalle_r["cantidad"];
    //     $pago_detalle->monto=$pago_detalle_r["monto"];
    //     $pago_detalle->precio_unitario=$pago_detalle_r["precio_unitario"];
    //     $pago_detalle->cuenta_id=$pago_detalle_r["cuenta_id"];  
    //     $pago_detalle->save();                  
    //     //$cuenta=Cuenta::find($pago_detalle->cuenta_id);
    //     $pago_detalle->descripcion=$pago_detalle->cuenta->nombre_cuenta;
    //     $resultado=["pago"=>$pago,"pago_detalle"=>$pago_detalle];        
    //     return response()->json(['error'=>0,'resultado'=>$resultado]);
    // }


    public function buscarSerieT($valor){
        $pagocat=Pago::where('categoria',$valor)->orderBy('id','desc')->first();
        if($pagocat!=null){
            return $pagocat->serie;
        }else{
        return $pagocat=0;
        }
        dd($pagocat);
    }
    

    public function buscarCuenta($id){
        $idcuenta=Cuenta::where('id',$id)->first();
       //
        return $idcuenta->stock;
    }

    public function pagar(Request $request){
        

        //dd($request);
        $pago_r=$request->pago;
        $pago=new Pago(); 
       // $numero=$pago_r["total"];
        $pago->cliente_id=$pago_r["cliente_id"];               
        $pago->total=$pago_r["total"]; 
        $pago->lugar=$pago_r["lugar"];
        if($pago_r["sector"]=='Externa'){
           $serie=$this->buscarSerieT(Auth::user()->categoria);
           $pago->serie=$serie+1;
           $pago->nro_recibo=$pago_r['serie'];
           $pago->sector=$pago_r['sector'];
        }else{
            if($pago_r["sector"]=='Interna'){
                   // $pago->serie=Pago::max('serie')+1;
             $serie=$this->buscarSerieT(Auth::user()->categoria);
             $pago->serie=$serie+1;
             $pago->sector=$pago_r['sector'];
             $pago->nro_recibo=0;
         }else{
            if($pago_r["sector"]==null){
                $serie=$this->buscarSerieT(Auth::user()->categoria);
                dd($serie);
                $pago->serie=$serie+1;
                $pago->sector='Interna';
                $pago->nro_recibo=0;
            }
         }
        }
        $pago->fecha_pago= \DateTime::createFromFormat('d/m/Y', $pago_r["fecha_pago"]);
        $pago->categoria=$request->propsTipocuenta;
        $pago->id=null;
        $pago->user_id=Auth::user()->id;
        $clasi_pago=ClasificadoPago::where("concepto",'=',$request->pago_clasi['concepto'])->first();
        if( $clasi_pago!=null){
             $pago->clasificador_pago_id=$clasi_pago->id;
        }else{
            $pago->clasificador_pago_id=0;
        }
        $pago->estado_pago='Activo';
        $pago->justificacion='';
        DB::beginTransaction();
        $error=0;
        try {
         
            $listado_pago=$request->lista_pago_detalle;
            $sto=$this->buscarCuenta($listado_pago[0]['cuenta_id']);
             if($sto!=0)  {
                $pago->save();   
           }else{
            $success = true;
            DB::rollback();     
            $error=2;
           }
            $lSin_cupo=[];
            foreach($listado_pago as $pago_detalle_r){
                $pago_detalle=new PagoDetalle();
                $pago_detalle->pago_id=$pago->id;
                $pago_detalle->cantidad=$pago_detalle_r["cantidad"];
                $pago_detalle->monto=$pago_detalle_r["monto"];
                $pago_detalle->precio_unitario=$pago_detalle_r["precio_unitario"];
                $pago_detalle->cuenta_id=$pago_detalle_r["cuenta_id"];  
                $pago_detalle->descripcion=$pago_detalle_r["descripcion"];  
                //$pago_detalle->descripcion=$pago_detalle->cuenta->nombre_cuenta;

                 $cal=$this->buscarCuenta($pago_detalle_r["cuenta_id"]);

                 if($pago->categoria!=1){
                    if ($cal > 0) {
                        $resultado = $cal - $pago_detalle_r["cantidad"];
                        $pago_detalle->cuenta->stock = $resultado;
                        $pago_detalle->cuenta->update();
                        $pago_detalle->save();
                    } else {
                        array_push($lSin_cupo, $pago_detalle->cuenta->nombre_cuenta);
                    }
                }else{

                $pago_detalle->save();
                }

            }  
            DB::commit();
            $success = true;
            $error=0;
            if(count($lSin_cupo)>0){
                return response()->json(['error'=>2,'lSincupo'=>$lSin_cupo]);
            }
        }catch (\Exception $e) {
            $success = false;
            DB::rollback();     
            $error=1;
            throw $e;
        }
        return response()->json(['error'=>$error,'pago'=>$pago]);
      
    }
    public function show($id){
        $pago=Pago::findOrFail($id); 
        //   dd($pago);
        return view("pago.show",[      
          "pago"=>$pago      
      ]);
    }
    public function anular(Request $request){
    //  dd($request); 
    date_default_timezone_set('America/La_Paz');
       $hor=Carbon::now(); //Para la fecha y hora
       $horaf=$hor->toDateTimeString();
            //dd($horaf);
        $id=$request->id;
        $pago=Pago::findOrFail($id); 
    //    dd('ingreso');
        if($pago==null){
           return response()->json(['error'=>1,'error_msg'=>'No existe el id']);
        }
        if($pago->estado_pago!='Anulado'){
            $pagocuenta=DB::table('pago_detalle')
            ->where('pago_id','=',$id)
            ->get();

            foreach($pagocuenta as $cuent){
                $cuenta1=Cuenta::findOrFail($cuent->cuenta_id);
                $cuenta1->stock=$cuenta1->stock+$cuent->cantidad;
                $cuenta1->update();
            }
            $pago->estado_pago='Anulado';
            $pago->justificacion=$request->justifica;
            $pago->fecha_anulacion=$horaf;
            $pago->update();
            return   response()->json(['error'=>0,'error_msg'=>'Anulacion exitosa']);
        }
        return  response()->json(['error'=>2,'error_msg'=>'No se puede anular 2 veces']);
    
    }





    public function getBoleta($id){
        $pago=Pago::findOrFail($id);        
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
 
        $section = $phpWord->addSection(
         array('paperSize' => 'Letter',
          'marginTop' => Converter::cmToTwip(2), 
          'marginLeft' => Converter::cmToTwip(4), 
          'marginRight' => Converter::cmToTwip(2), 
          'marginBottom' => Converter::cmToTwip(1),           
             )
         );
         $phpWord->getSettings()->setThemeFontLang(new Language(Language::ES_ES));
         //PARA FORMATEAR LA SERIE
         $formato_serie=array('bold'=> true,'size'=>9, 'name'=>'Cambria','color'=>'Red');         
         $parrafo_serie=array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT);         
         $parrafo_centrado=array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT);
         $parrafo_recibo_ingreso=array('bold'=> true,'size'=>15, 'name'=>'Cambria','color'=>'blue');
         $parrafo_unidad_presupuesto_tesoreria=array('bold'=> false,'size'=>12, 'name'=>'Cambria','color'=>'blue');

        $section->addImage(storage_path('unibol_logo.png'),[
            'width'=>153,
            'height'=>74,
            'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-0.97)),
            'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.26)),
            'wrappingStyle' => 'infront'
        ]);
        $section->addText("UNIDAD DE PRESUPUESTO Y TESORERIA",$parrafo_unidad_presupuesto_tesoreria,$parrafo_serie);
        $section->addText("RECIBO DE INGRESO",$parrafo_recibo_ingreso,$parrafo_serie);       
        
        $section->addText("Nº ".$pago->getNumeroSerieStr(),$formato_serie,$parrafo_serie);
        $tabla_centrada=array('align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
        $table = $section->addTable($tabla_centrada);    
        $styleCell = array('valign' => 'center');
            
        $fontStyle = new Font();
        $fontStyle->setBold(true);
        $fontStyle->setName('Cambria');
        $fontStyle->setSize(14);
 
        $cellColSpan = array('gridSpan' => 4, 'valign' => 'center');
        $cellHCentered = array('align' => 'center');
        $cellVCentered = array('valign' => 'center');
        
        $cellStyleTitulo=['borderStyle' => 'double','borderSize' => '6','gridSpan' => 4,'valign'=>'center'];
        $cellStyleVacia=['gridSpan' => 3];
        
        
        // $table->addRow(Converter::cmToTwip(0.69));
        // $rotulo_certificacion=array('align' => 'center','spaceAfter'=>'0');
        // $table->addCell(Converter::cmToTwip(0.74), $cellStyleTitulo)->addText('CERTIFICADO DE SOLVENCIA INSTITUCIONAL', $fontStyle, $rotulo_certificacion);       
  
        //$table->addRow(Converter::cmToTwip(0.53));       
        //$table->addCell(Converter::cmToTwip(14.5),$cellStyleVacia)->addText("");
 
        $table->addRow(Converter::cmToTwip(0.74));
        $cellStyle1=['gridSpan' => 1,
            'borderLeftStyle' => 'double',
            'borderTopStyle' => 'double',
            'borderRightStyle' => 'double',
            'borderLeftSize' => '6',
            'borderTopSize' => '6',
            'borderRightSize' => '6',
            'borderBottomStyle' => 'double',
            'borderBottomSize' => '6'
        ];
        $cellStyleRotuloCol1=['gridSpan' => 1,
            'borderLeftStyle' => 'double',
            'borderTopStyle' => 'double',
            'borderRightStyle' => 'double',
            'borderBottomStyle' => 'double',
            'borderLeftSize' => '6',
            'borderTopSize' => '6',
            'borderRightSize' => '6',
            'borderBottomSize' => '6',
        ];         
        $cellStyleInvisible=[
            'gridSpan' => 1           
        ];   
        $cellStyleRotulo_left=['gridSpan' => 1,
            'borderLeftStyle' => 'double',
            'borderTopStyle' => 'double',            
            'borderBottomStyle' => 'double',
            'borderLeftSize' => '6',
            'borderTopSize' => '6',
            'borderBottomSize' => '6'
        ];    
        $cellStyleRotulo_right=['gridSpan' => 1,
            'borderRightStyle' => 'double',
            'borderTopStyle' => 'double',            
            'borderBottomStyle' => 'double',
            'borderRightSize' => '6',
            'borderTopSize' => '6',
            'borderBottomSize' => '6'
        ];    
        $cellStyleRotulo_centro=['gridSpan' => 1,            
            'borderTopStyle' => 'double',            
            'borderBottomStyle' => 'double',            
            'borderTopSize' => '6',
            'borderBottomSize' => '6',
            'borderLeftSize' => '6',
            'borderRightSize' => '6'
        ];
        $cellStyleContenido_left=['gridSpan' => 1,            
            'borderLeftStyle' => 'double',            
            'borderTopSize' => '6',
            'borderBottomSize' => '6',
            'borderLeftSize' => '6',
            'borderRightSize' => '6',
            'valign'=>'center'
        ];
        $cellStyleContenido_right=['gridSpan' => 1,  
            'valign' => 'center',          
            'borderRightStyle' => 'double',            
            'borderTopSize' => '6',
            'borderBottomSize' => '6',
            'borderLeftSize' => '6',
            'borderRightSize' => '6'
        ];

       $glosa_font = array('bold'=> false, 'size'=>11, 'name'=>'Cambria');
       $glosa_font_bold = array('bold'=> true,'size'=>9, 'name'=>'Cambria');
        
       $lugar_y_fecha_rotulo=$table->addCell(Converter::cmToTwip(4),$cellStyleInvisible)->addTextRun(['align'=>'both']);       
       $lugar_y_fecha_rotulo->addText("LUGAR Y FECHA :",$glosa_font_bold,null);   
       $lugar_y_fecha_rotulo=$table->addCell(Converter::cmToTwip(0.5),$cellStyleInvisible)->addTextRun(['align'=>'center']);
       $lugar_y_fecha_rotulo->addText(":",$glosa_font_bold,null);   
       $lugar_y_fecha=$table->addCell(Converter::cmToTwip(10.5),$cellStyleInvisible)->addTextRun(['align'=>'both']);
       $lugar_y_fecha->addText($pago->lugar." ". $pago->getFechaPagoStr(),$glosa_font,null);   


       $table->addRow(Converter::cmToTwip(0.74));

       $recibe_rotulo=$table->addCell(Converter::cmToTwip(4),$cellStyleInvisible)->addTextRun(['align'=>'both']);
       $recibe_rotulo->addText("RECIBE  DE",$glosa_font_bold,null); 

       $recibe_rotulo=$table->addCell(Converter::cmToTwip(0.5),$cellStyleInvisible)->addTextRun(['align'=>'center']);
       $recibe_rotulo->addText(":",$glosa_font_bold,null);   

       $recibe=$table->addCell(Converter::cmToTwip(10.5),$cellStyleInvisible)->addTextRun(['align'=>'both']);
       $recibe->addText($pago->cliente->nombres." ". $pago->cliente->apellidos,$glosa_font,null);   

       $table->addRow(Converter::cmToTwip(0.74));      
       $ci_rotulo=$table->addCell(Converter::cmToTwip(4),$cellStyleInvisible)->addTextRun(['align'=>'both']);
       $ci_rotulo->addText("CARNET DE IDENTIDAD",$glosa_font_bold,null);   

       $ci_rotulo=$table->addCell(Converter::cmToTwip(0.5),$cellStyleInvisible)->addTextRun(['align'=>'center']);
       $ci_rotulo->addText(":",$glosa_font_bold,null);   

       $ci=$table->addCell(Converter::cmToTwip(10.5),$cellStyleInvisible)->addTextRun(['align'=>'both']);
       $ci->addText($pago->cliente->ci." ". $pago->cliente->ci_expedido,$glosa_font,null);   


       //concepto
       $table->addRow(Converter::cmToTwip(0.74));      
       $ci_rotulo=$table->addCell(Converter::cmToTwip(4),$cellStyleInvisible)->addTextRun(['align'=>'both']);
       $ci_rotulo->addText("POR CONCEPTO",$glosa_font_bold,null);   

       $ci_rotulo=$table->addCell(Converter::cmToTwip(0.5),$cellStyleInvisible)->addTextRun(['align'=>'center']);
       $ci_rotulo->addText(":",$glosa_font_bold,null);   

       $ci=$table->addCell(Converter::cmToTwip(10.5),$cellStyleInvisible)->addTextRun(['align'=>'both']);
       $ci->addText($pago->cuenta_clasificador->descripcion,$glosa_font,null);   

        $cellStyle1=['gridSpan' => 4,'borderLeftStyle' => 'double','borderRightStyle' => 'double','borderBottomStyle' => 'double','borderLeftSize' => '6','borderBottomSize' => '6','borderRightSize' => '6'];
      
 
        $table->addRow(Converter::cmToTwip(0.53));    
        $table->addCell(Converter::cmToTwip(15),$cellStyleVacia)->addText("");
        
        $cellStyle2=['borderLeftStyle' => 'solid','borderLeftSize' => '10',];
        //$styleCell 
        $cellStyle2= array('valign' => 'center','borderTopSize'=>1 ,'borderTopColor' =>'black','borderLeftSize'=>1,'borderLeftColor' =>'black','borderRightSize'=>1,
        'borderRightColor'=>'black','borderBottomSize' =>1,'borderBottomColor'=>'black' );
        // $cellStyleNumeroCentro=[
        //     'valign' => 'center',
        //     'borderTopSize'=>1 ,
        //     'borderTopColor' =>'black',
        //     'borderLeftSize'=>1,
        //     'borderLeftColor' =>'black',
        //     'borderRightSize'=>1,
        //     'borderRightColor'=>'black',
        //     'borderBottomSize' =>1,
        //     'borderBottomColor'=>'black'
        // ];
        
        $cellTituloUnidad= array('valign' => 'center','borderTopSize'=>1 ,'borderTopColor' =>'black','borderLeftSize'=>1,'borderLeftColor' =>'black','borderRightSize'=>1,
        'borderRightColor'=>'black','borderBottomSize' =>1,'borderBottomColor'=>'black' );
      
        $fontStyleTituloUnidades = array('bold'=> true, 'size'=>11, 'name'=>'Cambria');
        $fontStyleTitulopPequeno = array('bold'=> true, 'size'=>8, 'name'=>'Cambria');
        $table=$section->addTable($tabla_centrada);
        $table->addRow(Converter::cmToTwip(0.53));       
        $rotulo_unidad=array('align' => 'center','spaceAfter'=>'0');

        $total_literal= array('gridspan'=>3,'valign' => 'center','borderTopSize'=>1 ,'borderTopColor' =>'black','borderLeftSize'=>1,'borderLeftColor' =>'black','borderRightSize'=>1,
        'borderRightColor'=>'black','borderBottomSize' =>1,'borderBottomColor'=>'black' );

        $total_literal_left= [
            'gridspan'=>3,
            'valign' => 'center',
            'borderTopColor' =>'black',
            'borderLeftColor' =>'black',
            'borderRightColor'=>'black',
            'borderBottomColor'=>'black',
            'borderTopSize'=>6,
            'borderLeftSize'=>6,
            'borderRightSize'=>6,
            'borderBottomSize' =>6,
            'borderTopStyle' => 'double',
            'borderRightStyle' => 'double',
            'borderLeftStyle' => 'double',
            'borderBottomStyle' => 'double',            
        ];
        $total_literal_right= [
            'gridspan'=>1,
            'valign' => 'center',
            'borderTopSize'=>6,
            'borderLeftSize'=>6,
            'borderRightSize'=>6,
            'borderBottomSize' =>6,
            'borderTopColor' =>'black',
            'borderLeftColor' =>'black',
            'borderRightColor'=>'black',
            'borderBottomColor'=>'black',
            'borderTopStyle' => 'double',
            'borderLeftStyle' => 'double',
            'borderRightStyle' => 'double',
            'borderBottomStyle' => 'double',
        ];

        $table->addCell(Converter::cmToTwip(2),$cellStyleRotulo_left)->addText("CANTIDAD",$fontStyleTitulopPequeno,$rotulo_unidad);
        $table->addCell(Converter::cmToTwip(9),$cellStyleRotulo_centro)->addText("DESCRIPCIÓN",$fontStyleTituloUnidades,$rotulo_unidad);
        $table->addCell(Converter::cmToTwip(2),$cellStyleRotulo_centro)->addText("VALOR UNITARIO(BS)",$fontStyleTitulopPequeno,$rotulo_unidad);
        $table->addCell(Converter::cmToTwip(2),$cellStyleRotulo_right)->addText("VALOR TOTAL(BS)",$fontStyleTitulopPequeno,$rotulo_unidad);
        
        foreach($pago->detalle_pago as $pago_detalle){
            $table->addRow(Converter::cmToTwip(1),$cellStyle2);       
            $table->addCell(Converter::cmToTwip(2),$cellStyleContenido_left)->addText($pago_detalle->cantidad,null,$cellHCentered);
            $table->addCell(Converter::cmToTwip(9),$cellStyle2)->addText($pago_detalle->descripcion);
            $table->addCell(Converter::cmToTwip(2),$cellStyle2)->addText($pago_detalle->precio_unitario,null,$cellHCentered);
            $table->addCell(Converter::cmToTwip(2),$cellStyleContenido_right)->addText($pago_detalle->monto,null,$cellHCentered);   
        }
 
        $table->addRow(Converter::cmToTwip(1.35),$cellStyle2);       
        //$table->addCell(Converter::cmToTwip(0.74),$cellStyle2)->addText("TOTALS",null,$cellHCentered);
        $table->addCell(Converter::cmToTwip(5.24),$total_literal_left)->addText("TOTAL BS.- ".$pago->getTotalALiteral());
        //$table->addCell(Converter::cmToTwip(4.02),$cellStyle2)->addText("NO DEBE",null,$cellHCentered);
        $table->addCell(Converter::cmToTwip(2),$total_literal_right)->addText($pago->total,null,$cellHCentered); 
        
        $fontFecha = array('bold'=> false,'italic'=>true, 'size'=>14, 'name'=>'Cambria');
        $section->addText("\n");
        $nombre_completo=$pago->getNumeroSerieStr()." ".$pago->cliente->apellidos." ".$pago->cliente->nombres;

        
        //  $fecha_hoy=date("Y-m-d");
        //  $fecha_hoy=strtotime($fecha_hoy);
        //  $dia=date('d',$fecha_hoy);
        //  $mes=date('m',$fecha_hoy);
        //  $mes=$this->getMes($mes);
        //  $annhio=date('Y',$fecha_hoy);
        //  // $fecha_hoy1=strftime("%A, %d de %B de %Y");
        //  $fecha_hoy1=$dia." de ".$mes." de ".$annhio; 
        //  $fecha_hoy1="18 de marzo de 2021";
        //  //$fecha_hoy1=$fecha_hoy;
        //  $section->addText("Ivo, ".$fecha_hoy1,$fontFecha,$cellHCentered);

        $cellStylePieFirmaTop= array('valign' => 'center','borderTopSize'=>1 ,'borderTopColor' =>'black' );

        $cellStylePieFirma= array('valign' => 'center','borderTop'=>0);

        $section->addText("");
        $section->addText("");
        //$tabla_centrada=array('align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
        $table = $section->addTable($tabla_centrada);
        $glosa_font_recibi_entregue = array('bold'=> true,'size'=>10, 'name'=>'Cambria');
        $glosa_font_depositante = array('bold'=> false,'size'=>8, 'name'=>'Cambria');
        $table->addRow(Converter::cmToTwip(0.6),$cellStyle2);       
        $table->addCell(Converter::cmToTwip(4.67),$cellStylePieFirmaTop)->addText("RECIBI CONFORME",$glosa_font_recibi_entregue,$cellHCentered);
        $table->addCell(Converter::cmToTwip(4.67),$cellStylePieFirma)->addText("");
        $table->addCell(Converter::cmToTwip(4.67),$cellStylePieFirmaTop)->addText("ENTREGUE CONFORME",$glosa_font_recibi_entregue,$cellHCentered);
        $table->addRow(Converter::cmToTwip(0.6),$cellStyle2);       
        $table->addCell(Converter::cmToTwip(4.67),$cellStylePieFirma)->addText("");
        $table->addCell(Converter::cmToTwip(4.67),$cellStylePieFirma)->addText("");
        $table->addCell(Converter::cmToTwip(2),$cellStylePieFirma)->addText($pago->cliente->nombres." ".$pago->cliente->apellidos,$glosa_font_depositante,$cellHCentered);
        
        $section->addText("");
        $parrafo_pie_pagina=['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT,'spaceAfter'=>0];
        $font_parrafo_usuario=array('bold'=> true,'size'=>8, 'name'=>'Cambria','color'=>'blue');
        $font_parrafo_usuario_contenido=array('bold'=> false,'size'=>8, 'name'=>'Cambria','color'=>'blue');
        $texto_parrafo=$section->addTextRun($parrafo_pie_pagina);
        $texto_parrafo->addText("USUARIO : ",$font_parrafo_usuario);
        $texto_parrafo->addText($pago->user->email,$font_parrafo_usuario_contenido);
        $texto_parrafo=$section->addTextRun($parrafo_pie_pagina);
        $texto_parrafo->addText("IMPRESO : ",$font_parrafo_usuario);        
        // $texto_parrafo->addText( date("Y-m-d H:i:s"),$font_parrafo_usuario_contenido);
        $fecha_hoy = new \DateTime(date("Y-m-d H:i:s"), new \DateTimeZone('America/La_Paz'));        
        $texto_parrafo->addText( $fecha_hoy->format("Y-m-d H:i:s") ,$font_parrafo_usuario_contenido);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try { 
            $objWriter->save(storage_path($nombre_completo.".docx"));
 
        } catch (Exception $e) { 
        }
        return response()->download(storage_path($nombre_completo.".docx"));
    }
    private function getMes($dia){
        $mes="";
        switch($dia){
            case 1:
                $mes="Enero";
            break;
            case 2:
                $mes="Febrero";
            break;
            case 3:
                $mes="Marzo";
            break;
            case 4:
                $mes="Abril";
            break;
            case 5:
                $mes="Mayo";
            break;
            case 6:
                $mes="Junio";
            break;
            case 7:
                $mes="Julio";
            break;
            case 8:
                $mes="Agosto";
            break;
            case 9:
                $mes="Septiembre";
            break;
            case 10:
                $mes="Octubre";
            break;
            case 11:
                $mes="Noviembre";
            break;
            case 12:
                $mes="Diciembre";
            break;
        }
        return $mes;
    }

    public function getDocument($id){
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $pago=Pago::findOrFail($id); 
        $usuario=User::findOrFail($pago->user_id);
        $cliente=Cliente::findOrFail($pago->cliente_id);  
        $clasi_pago=ClasificadoPago::find($pago->clasificador_pago_id); 
        $pagodet=PagoDetalle::where('pago_id','=',$pago->id)->get();
   
      // dd($pagodet);

        $section = $phpWord->addSection(
         array('paperSize' => 'Letter',
          'marginTop' => Converter::cmToTwip(2), 
          'marginLeft' => Converter::cmToTwip(2), 
          'marginRight' => Converter::cmToTwip(2), 
          'marginBottom' => Converter::cmToTwip(1),           
             )
         );

         $phpWord->getSettings()->setThemeFontLang(new Language(Language::ES_ES));
           
        $imagenStyle = array(
            'width'=>153,
            'height'=>74,
            'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'marginLeft' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(-0.50)),
            'marginTop' => round(\PhpOffice\PhpWord\Shared\Converter::cmToPixel(0.26)),
            'wrappingStyle' => 'infront',
        );

         $paraTituloAl = 
         array(
               'alineacion1'=> array(
               'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT, //ALINEACION    
               'spaceAfter'=>'0', 
                ),
                'alineacion2' =>array(
                    'indent' =>(7.49),
                ), 
                'alineacion3' =>array(
                    'indent' =>(10.5),
                ), 
                'alineacion4' =>array(
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, //ALINEACION 
                    'spaceAfter'=>'0', 
                ),  
                
                'alineacion5' =>array(
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT, //ALINEACION 
                   
                ),   
                'alineacion6' =>array(
                    'indent' =>(9.3),   
                ),  
                'alineacion7' =>array(
                    'spaceAfter'=>'0', 
                ),  
             


         );
         $paraTituloLetra=
         array(
            
            'estilo1' => array(
            'underline' => 'single', //subrayado 
            'bold'=> false,   //Texto en negrita
            'size'=>12,          //Tamaño de letra
            'name'=>'Cambria',   //stilo de letra
            'color'=>'#00b0f0'      //
            ),
            'estilo2' => array(
           // 'underline' => 'single', //subrayado 
            'bold'=> true,   //Texto en negrita
            'size'=>14,          //Tamaño de letra
            'name'=>'Cambria',   //stilo de letra
            'color'=>'#00b0f0'    
             ),
             'estilo3' => array(
                // 'underline' => 'single', //subrayado 
                 'bold'=> true,   //Texto en negrita
                 'size'=>12,          //Tamaño de letra
                 'name'=>'Agency FB',   //stilo de letra
                 'color'=>'	#FF0000'    
                  ), 
                  
            'estilo4' => array(
                    // 'underline' => 'single', //subrayado 
              //'bold'=> true,   //Texto en negrita
              'size'=>10, 
             // 'allCaps' =>true,         //Mayuscula letra
              'name'=>'Bahnschrift SemiBold',   //stilo de letra
              'color'=>'black'    //color letra
             ),        
             'estilo5' => array(
                 
               'allCaps' =>false,         //Mayuscula letra
               'name'=>'Times New Roman',   //stilo de letra
               'color'=>'black'    //color letra
              ),
              'estilo6' => array(
              //   'alignment'=>'center',
        
              'size'=>8, 
              'allCaps' =>false,         //Mayuscula letra
                'name'=>'Cambria',   //stilo de letra
                'color'=>'black'    //color letra
               ),
               'estilo7' => array(
                //   'alignment'=>'center',
                'bold' => true,
                'size'=>8, 
                'allCaps' =>false,         //Mayuscula letra
                  'name'=>'Cambria',   //stilo de letra
                  'color'=>'black'    //color letra
                 ),
                 
                 'estilo8' => array(
                    //   'alignment'=>'center',
                    'bold' => true,
                    'size'=>8, 
                    'allCaps' =>true,         //Mayuscula letra
                      'name'=>'Cambria',   //stilo de letra
                      'color'=>'black'    //color letra
                     ),
                     
                     'estilo8' => array(
                        //   'alignment'=>'center',
                        'bold' => true,
                        'size'=>8, 
                        'allCaps' =>true,         //Mayuscula letra
                          'name'=>'Cambria',   //stilo de letra
                          'color'=>'black'    //color letra
                         ),
       
   

         );
  
         
        if($pago->estado_pago=='Anulado'){
       
          $header = $section->addHeader();
            // Configura un estilo de fuente para el texto de la marca de agua
            $fontStyle = array(
                'name' => 'Chiller',
                'size' => 75,        // Tamaño de fuente
                'color' => 'FF0000',   // Color del texto (formato hexadecimal)
                );
                // Agrega el texto como marca de agua
                $header->addText('ANULADO', $fontStyle, array(
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, //ALINEACION  
                    'angle' => 45    // Ángulo de inclinación del texto
                ));
        }
        $frame=new Frame(array(          
            'width'            => round(Converter::cmToPoint(19.78)),            
            'height'           => round(Converter::cmToPoint(15)),     
        ));
        $frame->setWrap('behind');
        $frame->setPos('absolute');
        $frame->setHPosRelTo(Frame::POS_RELTO_PAGE);
        $frame->setVPosRelTo(Frame::POS_RELTO_PAGE);
        $frame->setTop(round(Converter::cmToPoint(2)));
        $frame->setLeft(round(Converter::cmToPoint(1)));
        $shape02=$section->addShape('rect',
            //storage_path('./logo_planilla/ministerio.png'),
            array(
                'roundness' => 0.05,
                'frame'     => $frame,
                'outline'   => array('color' => '#990000', 'weight' => 1),
                'fill'      =>  array('color' => null),                 
        )
        );

        $section->addImage(storage_path('unibol_logo.png'),$imagenStyle);

         if($pago->categoria==1){
         $section->addText("UNIDAD DE PRESUPUESTO Y TESORERÍA",$paraTituloLetra['estilo1'],$paraTituloAl['alineacion1']);
        }else{
            $section->addText("UNIDAD DE COMERCIALIZACION",$paraTituloLetra['estilo1'],$paraTituloAl['alineacion6']);
        }

                
         $section->addText("RECIBO DE INGRESO\n",$paraTituloLetra['estilo2'],$paraTituloAl['alineacion6']);
         $section->addText("Nº ".$pago->getNumeroSerieStr(),$paraTituloLetra['estilo3'],$paraTituloAl['alineacion3']);

        $tabla = $section->addTable();
        // Establece el estilo de borde de la tabla en "none" (sin bordes)
       // $tabla->getStyle()->setBorderSize();

        // fila 1 
        $row = $tabla->addRow();
        $cell = $row->addCell(2000); // Ancho de celda en twips
        $cell->addText('Lugar',$paraTituloLetra['estilo8'], $paraTituloAl['alineacion7'] );  
        $cell1 = $row->addCell(2000); // Ancho de celda en twips
        $cell1->addText(': '.$pago->lugar,$paraTituloLetra['estilo4'],$paraTituloAl['alineacion7']);
        //fila 2
        $row = $tabla->addRow();
        $cell = $row->addCell(2000); // Ancho de celda en twips
        $cell->addText('FECHA',$paraTituloLetra['estilo7'],$paraTituloAl['alineacion7']);
        $cell1 = $row->addCell(2000);
        $cell1->addText(': '.$pago->fecha_pago,$paraTituloLetra['estilo4'],$paraTituloAl['alineacion7']);
        //fila 3
        $row = $tabla->addRow();
        $cell = $row->addCell(2000); // Ancho de celda en twips
        $cell->addText('RECIBE DE',$paraTituloLetra['estilo7'],$paraTituloAl['alineacion7']);  
        $cell1 = $row->addCell(7000); // Ancho de celda en twips
        $prueba = $cliente->nombres.' '.$cliente->apellidos;
        $textoModificado = ucfirst($prueba);        
        $cell1->addText(': '.$textoModificado,null,null);
       // $cell1->addText(': '.$cliente->nombres.' '.$cliente->apellidos,$paraTituloLetra['estilo4'],null);
       
        if($pago->nro_recibo!=0){
        $row = $tabla->addRow();
        $cell = $row->addCell(2000); // Ancho de celda en twips
        $cell->addText('Nro. de recibe ',$paraTituloLetra['estilo5'],$paraTituloAl['alineacion7']);
        $cell1 = $row->addCell(2000);
        $cell1->addText(': '.$pago->nro_recibo,$paraTituloLetra['estilo4'],null);
         }
        

        //fila 4
        // $row = $tabla->addRow();
        // $cell = $row->addCell(2000); // Ancho de celda en twips
        // $cell->addText('Por Concepto ',$paraTituloLetra['estilo5'],null);
        // $cell1 = $row->addCell(2000);
        // if($clasi_pago!=null){
        // $cell1->addText(': ',$paraTituloLetra['estilo4'],null);
        //  }


      //   $section->addText($pago->lugar,$paraTituloLetra['estilo1'],$paraTituloAl['alineacion1']);
       
      //   $section->addText("RECIBO DE INGRESO\n",$paraTituloLetra['estilo2'],$paraTituloAl['alineacion2']);
     //    $section->addText("00018",$paraTituloLetra['estilo3'],$paraTituloAl['alineacion3']);


         $tabla_centrada=array('align' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
         $cellStyleInvisible=[
            'gridSpan' => 1,
            'valign'=>'center',
            'borderLeftStyle' => 'double',
            'borderTopStyle' => 'double',            
            'borderBottomStyle' => 'double',
            'borderRightStyle' => 'double',

            'borderLeftSize' => '3',
            'borderTopSize' => '3',
            'borderBottomSize' => '3',
            'borderRightSize' => '3'
        ];
      
        $cellStyleInvisible1=[
            'gridSpan' => 1,
            'valign'=>'center',
            'borderLeftStyle' => 'double',//borde simple solo izquierda
            'borderLeftSize' => '4',
            'borderTopSize' => '4',
            'borderBottomSize' => '4',
            'borderRightSize' => '4'
        ];
        $cellStyleInvisible3=[
            'gridSpan' => 1,
            'valign'=>'center',
            'borderRightStyle' => 'double',// borde doble solo derecha
            'borderLeftSize' => '4',
            'borderTopSize' => '4',
            'borderBottomSize' => '4',
            'borderRightSize' => '4'
        ];

        $cellStyleInvisible2=[
           'gridSpan' => 1,
           'align' => 'left', 
           'valign'=>'center',
            'borderLeftSize' => '4',
            'borderTopSize' => '4',
            'borderBottomSize' => '4',
            'borderRightSize' => '4'
        ];

        $cellStyleInvisible_ancho=[
            'gridSpan' => 3,
            'align' => 'left', 
            'valign'=>'center',
            'borderLeftStyle' => 'double',
            'borderTopStyle' => 'double',            
            'borderBottomStyle' => 'double',
            'borderRightStyle' => 'double',

            'borderLeftSize' => '2',
            'borderTopSize' => '2',
            'borderBottomSize' => '2',
            'borderRightSize' => '2'
        ];
         $section->addText("");
       
        $table = $section->addTable($tabla_centrada);   
         $table->addRow(Converter::cmToTwip(0.74));
         $table->addCell(2000,$cellStyleInvisible)->addText("CANTIDAD",$paraTituloLetra['estilo6'],$paraTituloAl['alineacion4']);
         $table->addCell(9000,$cellStyleInvisible)->addText("DESCRIPCIÓN",$paraTituloLetra['estilo6'],$paraTituloAl['alineacion4']);
         $table->addCell(2000,$cellStyleInvisible)->addText("VALOR UNITARIO(BS)",$paraTituloLetra['estilo6'],$paraTituloAl['alineacion4']);
         $table->addCell(2000,$cellStyleInvisible)->addText("VALOR \n TOTAL(BS)",$paraTituloLetra['estilo6'],$paraTituloAl['alineacion4']);
  
         foreach($pagodet as $detalle_pago){

         $table->addRow(Converter::cmToTwip(0.74));
         $table->addCell(2000,$cellStyleInvisible1)->addText($detalle_pago->cantidad,$paraTituloLetra['estilo6'],$paraTituloAl['alineacion4']);
         $table->addCell(9000,$cellStyleInvisible2)->addText($detalle_pago->descripcion,$paraTituloLetra['estilo6'],array(  'spaceAfter'=>'0', ));
         $table->addCell(2000,$cellStyleInvisible2)->addText($detalle_pago->precio_unitario,$paraTituloLetra['estilo6'],$paraTituloAl['alineacion4']);
         $table->addCell(2000,$cellStyleInvisible3)->addText($detalle_pago->monto,$paraTituloLetra['estilo6'],$paraTituloAl['alineacion4']);
         }
         $table->addRow(Converter::cmToTwip(0.74));
         $table->addCell(13000,$cellStyleInvisible_ancho)->addText("TOTAL EN BOLIVIANOS",$paraTituloLetra['estilo7'],array(  'spaceAfter'=>'0', ));
         $table->addCell(2000,$cellStyleInvisible)->addText($pago->total,$paraTituloLetra['estilo7'],$paraTituloAl['alineacion4']);


         $section->addText("\n");
         $section->addText("\n");
         $section->addText("\n");
         $tabla2 = $section->addTable();
  
         // fila 1 
         $row = $tabla2->addRow();
         $cell = $row->addCell(6000); // Ancho de celda en twips
         $cell->addText('RECIBE CONFORME');  
         $cell1 = $row->addCell(2000); // Ancho de celda en twips
         $cell1->addText('',$paraTituloLetra['estilo4'],null);
         $cell2 = $row->addCell(6000); // Ancho de celda en twips
         $cell2->addText('ENTREGA CONFORME',null,$paraTituloAl['alineacion1']);
      
         $section->addText("\n");

         $section->addText('usuario '.$usuario->email,[ //Texto en negrita
         'size'=>8,          //Tamaño de letra
         'name'=>'Agency FB',   //stilo de letra
         'color'=>'	#0000FF'],$paraTituloAl['alineacion1']);

        //  $phpWord->getProtection()->setEditing('forms');
        //  $phpWord->getProtection()->setPassword('123456');

         $nombre_completo='T-'.$pago->getNumeroSerieStr().' '.$cliente->apellidos.' '.$cliente->nombres; 
         
         
         try { 
             $objWriter1 = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
             $objWriter1->save(storage_path($nombre_completo.".docx"));             
             $objWriter2 = \PhpOffice\PhpWord\IOFactory::load(storage_path($nombre_completo.".docx"));
             
             $dompdf = new Dompdf();
             $objWriter2->save(storage_path($nombre_completo."2.docx"));             
        

        //   \PhpOffice\PhpWord\Settings::setPdfRendererName(\PhpOffice\PhpWord\Settings::PDF_RENDERER_DOMPDF);
        //   \PhpOffice\PhpWord\Settings::setPdfRendererPath('../vendor/dompdf/dompdf');
  
        // $writer = \PhpOffice\PhpWord\IOFactory::createWriter($objWriter2, 'HTML')->save(storage_path($nombre_completo.".html"));
      //  $reader = \PhpOffice\PhpWord\IOFactory::load($objWriter1);
      $dompdf->loadHtml(storage_path($nombre_completo.".html"));
      $dompdf->render();
      // $dompdf->stream('documento.pdf', array('Attachment' => 0));
     //return $pdf = PDF::loadFile(public_path().storage_path($nombre_completo.".html"))->stream('archivo.pdf');
       // $writer->save(storage_path($nombre_completo.".pdf"));
        } catch (Exception $e) { 
        }
     
       // return response()->download(storage_path($nombre_completo.".pdf"));
   
    }


}

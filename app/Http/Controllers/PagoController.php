<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pago;
use App\Cuenta;
use App\PagoDetalle;
use Illuminate\Support\Facades\Redirect;
use DB;

use Illuminate\Support\Facades\Auth;
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
use Illuminate\Support\Facades\Storage;;

class PagoController extends Controller
{
    public function index(Request $request){
        if($request)
        {
                       
        }  
        $lpagos=Pago::orderBy('id','desc')->paginate(15);        
        return view('pago.index',["lpagos"=>$lpagos]);            
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

    public function pagar(Request $request){
        $pago_r=$request->pago;
        $pago=new Pago();        
        $pago->cliente_id=$pago_r["cliente_id"];               
        $pago->total=$pago_r["total"];
        $pago->lugar=$pago_r["lugar"];
        $pago->serie=Pago::max('serie')+1;
        $pago->cuenta_clasificador_id=$pago_r["cuenta_clasificador_id"];
        $pago->fecha_pago= \DateTime::createFromFormat('d/m/Y', $pago_r["fecha_pago"]);
        $pago->id=null;
        $pago->user_id=Auth::user()->id;
        DB::beginTransaction();
        $error=0;
        try {
            $pago->save();                        
            $listado_pago=$request->lista_pago_detalle;
            foreach($listado_pago as $pago_detalle_r){
                $pago_detalle=new PagoDetalle();
                $pago_detalle->pago_id=$pago->id;
                $pago_detalle->cantidad=$pago_detalle_r["cantidad"];
                $pago_detalle->monto=$pago_detalle_r["monto"];
                $pago_detalle->precio_unitario=$pago_detalle_r["precio_unitario"];
                $pago_detalle->cuenta_id=$pago_detalle_r["cuenta_id"];  
                $pago_detalle->descripcion=$pago_detalle_r["descripcion"];  
                //$pago_detalle->descripcion=$pago_detalle->cuenta->nombre_cuenta;
                $pago_detalle->save();                  
            }  
            DB::commit();
            $success = true;
            $error=0;
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
        return view("pago.show",[      
          "pago"=>$pago      
      ]);
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
}

<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CuentaProdClasificadorController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/vista', function () {
    return view('vistaword');
});

Auth::routes();

Route::get('/detalle', function () {
    return view('auth.detalleuser');
});

Route::get('/authreg', function () {
    return view('auth.register');
})->name('authreg')->middleware('auth');

Route::get('/registro',[ RegisterController::class,'index']) ->name('registro.index'); 
Route::get('/usuario',[ UsuarioController::class,'index']) ->name('usuario.index'); 

//Route::get('/cuentaPC',[ CuentaProdClasificadorController::class,'create']) ->name('cuenta_prod_clasificador.create'); 


Route::delete('/cuenta_prod_clasificador/eliminar/{id}','CuentaProdClasificadorController@delete')->name('cuenta_clasificador.delete');



Route::get('/ajax/clasiPago', 'ClasificadorPagoController@ajaxclasiPago')->name('clasificadorPago.clasiPago');

Route::post('clasipago/registro', 'ClasificadorPagoController@register')->name('clasificadorPago.register');
Route::get('/cuentaByd/{id_user}', 'CuentaController@cargarBydUser')->name('cuenta.cargarBydUser');


Route::get('/permisos', 'ProductoUsuarioController@create')->name('prod_cuenta.create');

Route::post('/permisos/register', 'ProductoUsuarioController@ajaxproductoUsuario')->name('prodUser.ajaxproductoUsuario');

//Route::post('/permisos/register', 'ProductoUsuarioController@ajaxproductoUsuario')->name('prodUser.ajaxproductoUsuario');
Route::get('/permisos/user/{id_user}', 'ProductoUsuarioController@cargaProdUser')->name('prodUser.cargaProdUser');


Route::get('/cuentaPCI', 'CuentaProdClasificadorController@index')->name('cuenta_prod_clasificador.index');
Route::get('/cuentaPC', 'CuentaProdClasificadorController@create')->name('cuenta_prod_clasificador.create');

Route::get('/cuentaPCe/{idr}',[CuentaProdClasificadorController::class,'edit'])->where('id','[0-9]+')->name('cuenta_prod_clasificador.edit');
Route::get('/detalle/{id}',[UsuarioController::class,'show'])->where('id','[0-9]+')->name('usuario.detallado');

Route::get('/detalle',[ UsuarioController::class,'mostrarUltimo']) ->name('usuario.detalle'); 

Route::get('/usuario/edit/{id}', 'UsuarioController@edit')->name('usuario.edit');
Route::get('/usuario/contrasenha/{id}', 'UsuarioController@edit_contrasenha')->name('usuario.cambiar_contrasenha');
Route::put('/usuario/contrasenha/{id}', 'UsuarioController@update_contrasenha')->name('usuario.actualizar_contrasenha');
Route::get('/home', 'HomeController@index')->name('home');
// para la cuenta
Route::get('/cuenta/index', 'CuentaController@index')->name('cuenta.index');
Route::get('/cuenta', 'CuentaController@create')->name('cuenta.create');
Route::post('/cuenta', 'CuentaController@store')->name('cuenta.store');
Route::get('/cuenta/{id}/edit', 'CuentaController@edit')->name('cuenta.edit');
Route::put('/cuenta/{id}', 'CuentaController@update')->name('cuenta.update');
Route::get('/cuenta/{id}', 'CuentaController@show')->name('cuenta.show');

//para el rubro


Route::get('/rubro/index', 'RubroController@index')->name('rubro.index');
Route::get('/rubro', 'RubroController@create')->name('rubro.create');
Route::post('/rubro', 'RubroController@store')->name('rubro.store');
Route::get('/rubro/{id}/edit', 'RubroController@edit')->name('rubro.edit');
Route::put('/rubro/{id}', 'RubroController@update')->name('rubro.update');
Route::get('/rubro/{id}', 'RubroController@show')->name('rubro.show');

//para el registro de la  unidad 
Route::get('/unidad/index', 'UnidadController@index')->name('unidad.index');
Route::get('/unidad', 'UnidadController@create')->name('unidad.create');
Route::post('/unidad', 'UnidadController@store')->name('unidad.store');
Route::get('/unidad/{id}/edit','UnidadController@edit')->name('unidad.edit');
Route::put('/unidad/{id}', 'UnidadController@update')->name('unidad.update');
Route::get('/unidad/{id}','UnidadController@show')->name('unidad.show');


//para el clasificador de cuenta


Route::get('/cuenta_clasificador/index', 'CuentaClasificadorController@index')->name('cuenta_clasificador.index');
Route::get('/cuenta_clasificador', 'CuentaClasificadorController@create')->name('cuenta_clasificador.create');
Route::post('/cuenta_clasificador', 'CuentaClasificadorController@store')->name('cuenta_clasificador.store');
Route::get('/cuenta_clasificador/{id}/edit', 'CuentaClasificadorController@edit')->name('cuenta_clasificador.edit');
Route::put('/cuenta_clasificador/{id}', 'CuentaClasificadorController@update')->name('cuenta_clasificador.update');
Route::get('/cuenta_clasificador/{id}', 'CuentaClasificadorController@show')->name('cuenta_clasificador.show');

//para el cliente
Route::get('/cliente/index', 'ClienteController@index')->name('cliente.index');
Route::get('/cliente', 'ClienteController@create')->name('cliente.create');
Route::post('/cliente', 'ClienteController@store')->name('cliente.store');
Route::get('/cliente/{id}/edit', 'ClienteController@edit')->name('cliente.edit');
Route::put('/cliente/{id}', 'ClienteController@update')->name('cliente.update');
Route::get('/cliente/{id}', 'ClienteController@show')->name('cliente.show');
//pago
Route::get('/Pago/index', 'PagoController@index')->name('pago.index');
Route::get('/Pago/{id}', 'PagoController@show')->name('pago.show');
Route::get('/Pago', 'PagoController@create')->name('pago.create');
Route::get('/Pago/boleta/{id}', 'PagoController@getBoleta')->name('pago.getBoleta');
Route::get('/Pago2', 'PagoController@create2')->name('pago.create2');
Route::get('/Pago/Recibo/{id}', 'PagoController@getDocument')->name('pago.getDocument');
///Reportes
Route::get('/reportes/clasificador', 'ReportesController@reportes_clasificador')->name('reportes.reportes_clasificador');
Route::get('/reportes/rubro', 'ReportesController@reportes_rubro')->name('reportes.reportes_rubro');
Route::get('/reportes/extracto_pago', 'ReportesController@reportes_extracto')->name('reportes.reportes_extracto');
//servicios ajax
Route::get('/ajax/Cuente/{numero_cuenta}', 'CuentaController@ajaxCuente')->name('cuenta.ajaxCuente');
Route::get('/ajax/Cuenta', 'CuentaController@ajaxCuenta')->name('cuenta.ajaxCuenta');
Route::get('/ajax/cuentaPorClasificador/{id}', 'CuentaController@ajaxCuentaPorClasificador')->name('cuenta.ajaxCuentaPorClasificador');
Route::get('/ajax/clasificadorPorUnidad/{id}', 'CuentaController@ajaxClasificadorPorUnidad')->name('cuenta.ajaxClasificadorPorUnidad');
Route::get('/ajax/Cliente/{ci}', 'ClienteController@ajaxCliente')->name('cuenta.ajaxCliente');

Route::get('/ajax/CuentaClasificador/{numero_clasificador}', 'CuentaClasificadorController@ajaxCuentaClasificador')->name('cuentaclas.ajaxCuentaClasificador');
Route::get('/ajax/CuentaClasificadorById/{id}', 'CuentaClasificadorController@ajaxCuentaClasificadorById')->name('cuentaclas.ajaxCuentaClasificadorById');
//pago
Route::post('/ajax/Pagar', 'PagoController@pagar')->name('pago.ajaxPagar');
//Route::post('/ajax/Pagar', 'PagoController@pagar')->name('pago.ajaxPagar');
Route::get('/ajax/Pagar2', 'PagoController@pagar2')->name('pago.ajaxPagar2');
Route::get('/ajax/ajaxRubros', 'RubroController@ajaxGetRubro')->name('rubro.ajaxGetRubro');
Route::get('/ajax/ajaxCuentaClasificador', 'CuentaClasificadorController@ajaxGetCuentaClasificador')->name('clasificador_cuenta.ajaxGetClasificadorCuenta');
//Route::get('/ajax/ajaxUnidad', 'CuentaClasificadorController@ajaxGetUnidad')->name('clasificador_cuenta.ajaxGetUnidad');
Route::get('/ajax/ajaxUnidad', 'UnidadController@ajaxGetUnidad')->name('unidad.ajaxGetUnidad');
Route::post('/ajax/ajaxGetReportePorClasificador', 'ReportesController@getReporteFromIniFinPorClasificador')->name('reportes.ajaxGetClasificador');
Route::post('/ajax/ajaxGetReportePorRubro', 'ReportesController@getReporteFromIniFinPorRubro')->name('reportes.ajaxGetRubro');
Route::post('/ajax/ajaxGetExtracto', 'ReportesController@getReporteFromIniFinExtracto')->name('reportes.ajaxGetExtracto');
Route::post('/ajax/ajaxStoreCliente', 'ClienteController@ajaxClienteStore')->name('cliente.ajaxClienteStore');
Route::post('/ajax/ajaxAsociarCuenta', 'CuentaProdClasificadorController@ajaxGuardarCuentaClasificada')->name('CuentaClasificadorCuenta.ajaxAsociarCuenta');

Route::get('/prueba','PagoController@getDocument');

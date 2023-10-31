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
// Route::get('/reportes', function () {
//     return view('reportes.reportes_index');
// });

Auth::routes();

//Route::get('/register', 'Auth\RegisterController@index')->middleware('auth');

Route::get('/detalle', function () {
    return view('auth.detalleuser');
});

Route::get('/authreg', function () {
    return view('auth.register');
})->name('authreg')->middleware('auth');

Route::get('/registro',[ RegisterController::class,'index']) ->name('registro.index')->middleware('auth');
Route::get('/usuario',[ UsuarioController::class,'index']) ->name('usuario.index')->middleware('auth');

//Route::get('/cuentaPC',[ CuentaProdClasificadorController::class,'create']) ->name('cuenta_prod_clasificador.create'); 
Route::get('/reportes','ReportesController@index')->name('reportes')->middleware('auth');
Route::post('/reporte','ReportesController@recoger')->name('reportes.recoger')->middleware('auth');

Route::post('/update/{id}',[ UsuarioController::class,'update']) ->name('update')->middleware('auth');


Route::delete('/cuenta_prod_clasificador/eliminar/{id}','CuentaProdClasificadorController@delete')->name('cuenta_clasificador.delete')->middleware('auth');



Route::get('/ajax/clasiPago', 'ClasificadorPagoController@ajaxclasiPago')->name('clasificadorPago.clasiPago')->middleware('auth');

Route::post('clasipago/registro', 'ClasificadorPagoController@register')->name('clasificadorPago.register')->middleware('auth');
Route::get('/cuentaByd/{id_user}', 'CuentaController@cargarBydUser')->name('cuenta.cargarBydUser')->middleware('auth');


Route::get('/permisos', 'ProductoUsuarioController@create')->name('prod_cuenta.create')->middleware('auth');

Route::post('/permisos/register', 'ProductoUsuarioController@ajaxproductoUsuario')->name('prodUser.ajaxproductoUsuario')->middleware('auth');

//Route::post('/permisos/register', 'ProductoUsuarioController@ajaxproductoUsuario')->name('prodUser.ajaxproductoUsuario');
Route::get('/permisos/user/{id_user}', 'ProductoUsuarioController@cargaProdUser')->name('prodUser.cargaProdUser')->middleware('auth');


Route::get('/cuentaPCI', 'CuentaProdClasificadorController@index')->name('cuenta_prod_clasificador.index')->middleware('auth');
Route::get('/cuentaPC', 'CuentaProdClasificadorController@create')->name('cuenta_prod_clasificador.create')->middleware('auth');

Route::get('/cuentaPCe/{idr}',[CuentaProdClasificadorController::class,'edit'])->where('id','[0-9]+')->name('cuenta_prod_clasificador.edit')->middleware('auth');
Route::get('/detalle/{id}',[UsuarioController::class,'show'])->where('id','[0-9]+')->name('usuario.detallado')->middleware('auth');

Route::get('/detalle',[ UsuarioController::class,'mostrarUltimo']) ->name('usuario.detalle')->middleware('auth');

Route::get('/usuario/edit/{id}', 'UsuarioController@edit')->name('usuario.edit')->middleware('auth');
Route::get('/usuario/contrasenha/{id}', 'UsuarioController@edit_contrasenha')->name('usuario.cambiar_contrasenha')->middleware('auth');
Route::put('/usuario/contrasenha/{id}', 'UsuarioController@update_contrasenha')->name('usuario.actualizar_contrasenha')->middleware('auth');
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
// para la cuenta
Route::get('/cuenta/index', 'CuentaController@index')->name('cuenta.index')->middleware('auth');
Route::get('/cuenta', 'CuentaController@create')->name('cuenta.create')->middleware('auth');
Route::post('/cuenta', 'CuentaController@store')->name('cuenta.store')->middleware('auth');
Route::get('/cuenta/{id}/edit', 'CuentaController@edit')->name('cuenta.edit')->middleware('auth');
Route::put('/cuenta/{id}', 'CuentaController@update')->name('cuenta.update')->middleware('auth');
Route::get('/cuenta/{id}', 'CuentaController@show')->name('cuenta.show')->middleware('auth');

//para el rubro


Route::get('/rubro/index', 'RubroController@index')->name('rubro.index')->middleware('auth');
Route::get('/rubro', 'RubroController@create')->name('rubro.create')->middleware('auth');
Route::post('/rubro', 'RubroController@store')->name('rubro.store')->middleware('auth');
Route::get('/rubro/{id}/edit', 'RubroController@edit')->name('rubro.edit')->middleware('auth');
Route::put('/rubro/{id}', 'RubroController@update')->name('rubro.update')->middleware('auth');
Route::get('/rubro/{id}', 'RubroController@show')->name('rubro.show')->middleware('auth');

//para el registro de la  unidad 
Route::get('/unidad/index', 'UnidadController@index')->name('unidad.index')->middleware('auth');
Route::get('/unidad', 'UnidadController@create')->name('unidad.create')->middleware('auth');
Route::post('/unidad', 'UnidadController@store')->name('unidad.store')->middleware('auth');
Route::get('/unidad/{id}/edit','UnidadController@edit')->name('unidad.edit')->middleware('auth');
Route::put('/unidad/{id}', 'UnidadController@update')->name('unidad.update')->middleware('auth');
Route::get('/unidad/{id}','UnidadController@show')->name('unidad.show')->middleware('auth');


//para el clasificador de cuenta


Route::get('/cuenta_clasificador/index', 'CuentaClasificadorController@index')->name('cuenta_clasificador.index')->middleware('auth');
Route::get('/cuenta_clasificador', 'CuentaClasificadorController@create')->name('cuenta_clasificador.create')->middleware('auth');
Route::post('/cuenta_clasificador', 'CuentaClasificadorController@store')->name('cuenta_clasificador.store')->middleware('auth');
Route::get('/cuenta_clasificador/{id}/edit', 'CuentaClasificadorController@edit')->name('cuenta_clasificador.edit')->middleware('auth');
Route::put('/cuenta_clasificador/{id}', 'CuentaClasificadorController@update')->name('cuenta_clasificador.update')->middleware('auth');
Route::get('/cuenta_clasificador/{id}', 'CuentaClasificadorController@show')->name('cuenta_clasificador.show')->middleware('auth');

//para el cliente
Route::get('/cliente/index', 'ClienteController@index')->name('cliente.index')->middleware('auth');
Route::get('/cliente', 'ClienteController@create')->name('cliente.create')->middleware('auth');
Route::post('/cliente', 'ClienteController@store')->name('cliente.store')->middleware('auth');
Route::get('/cliente/{id}/edit', 'ClienteController@edit')->name('cliente.edit')->middleware('auth');
Route::put('/cliente/{id}', 'ClienteController@update')->name('cliente.update')->middleware('auth');
Route::get('/cliente/{id}', 'ClienteController@show')->name('cliente.show')->middleware('auth');
//pago
Route::get('/Pago/index', 'PagoController@index')->name('pago.index')->middleware('auth');
Route::post('/PagoA', 'PagoController@anular')->name('pago.anular')->middleware('auth');
Route::get('/Pago/{id}', 'PagoController@show')->name('pago.show')->middleware('auth');
Route::get('/Pago', 'PagoController@create')->name('pago.create')->middleware('auth');
Route::get('/Pago/boleta/{id}', 'PagoController@getBoleta')->name('pago.getBoleta')->middleware('auth');
Route::get('/Pago2', 'PagoController@create2')->name('pago.create2')->middleware('auth');
Route::get('/Pago/Recibo/{id}', 'PagoController@getDocument')->name('pago.getDocument')->middleware('auth');
///Reportes
Route::get('/reportes/clasificador', 'ReportesController@reportes_clasificador')->name('reportes.reportes_clasificador')->middleware('auth');
Route::get('/reportes/rubro', 'ReportesController@reportes_rubro')->name('reportes.reportes_rubro')->middleware('auth');
Route::get('/reportes/extracto_pago', 'ReportesController@reportes_extracto')->name('reportes.reportes_extracto')->middleware('auth');
//servicios ajax
Route::get('/ajax/Cuente/{numero_cuenta}', 'CuentaController@ajaxCuente')->name('cuenta.ajaxCuente')->middleware('auth');
Route::get('/ajax/Cuenta', 'CuentaController@ajaxCuenta')->name('cuenta.ajaxCuenta')->middleware('auth');
Route::get('/ajax/cuentaPorClasificador/{id}', 'CuentaController@ajaxCuentaPorClasificador')->name('cuenta.ajaxCuentaPorClasificador')->middleware('auth');
Route::get('/ajax/clasificadorPorUnidad/{id}', 'CuentaController@ajaxClasificadorPorUnidad')->name('cuenta.ajaxClasificadorPorUnidad')->middleware('auth');
Route::get('/ajax/Cliente/{ci}', 'ClienteController@ajaxCliente')->name('cuenta.ajaxCliente')->middleware('auth');

Route::get('/ajax/CuentaClasificador/{numero_clasificador}', 'CuentaClasificadorController@ajaxCuentaClasificador')->name('cuentaclas.ajaxCuentaClasificador')->middleware('auth');
Route::get('/ajax/CuentaClasificadorById/{id}', 'CuentaClasificadorController@ajaxCuentaClasificadorById')->name('cuentaclas.ajaxCuentaClasificadorById')->middleware('auth');
//pago
Route::post('/ajax/Pagar', 'PagoController@pagar')->name('pago.ajaxPagar')->middleware('auth');
//Route::post('/ajax/Pagar', 'PagoController@pagar')->name('pago.ajaxPagar');
Route::get('/ajax/Pagar2', 'PagoController@pagar2')->name('pago.ajaxPagar2')->middleware('auth');
Route::get('/ajax/ajaxRubros', 'RubroController@ajaxGetRubro')->name('rubro.ajaxGetRubro')->middleware('auth');
Route::get('/ajax/ajaxCuentaClasificador', 'CuentaClasificadorController@ajaxGetCuentaClasificador')->name('clasificador_cuenta.ajaxGetClasificadorCuenta')->middleware('auth');
//Route::get('/ajax/ajaxUnidad', 'CuentaClasificadorController@ajaxGetUnidad')->name('clasificador_cuenta.ajaxGetUnidad');
Route::get('/ajax/ajaxUnidad', 'UnidadController@ajaxGetUnidad')->name('unidad.ajaxGetUnidad');
Route::post('/ajax/ajaxGetReportePorClasificador', 'ReportesController@getReporteFromIniFinPorClasificador')->name('reportes.ajaxGetClasificador')->middleware('auth');
Route::post('/ajax/ajaxGetReportePorRubro', 'ReportesController@getReporteFromIniFinPorRubro')->name('reportes.ajaxGetRubro')->middleware('auth');
Route::get('/ajax/ajaxGetExtracto', 'ReportesController@getReporteFromIniFinExtracto')->name('reportes.ajaxGetExtracto')->middleware('auth');
Route::post('/ajax/ajaxStoreCliente', 'ClienteController@ajaxClienteStore')->name('cliente.ajaxClienteStore')->middleware('auth');
Route::post('/ajax/ajaxAsociarCuenta', 'CuentaProdClasificadorController@ajaxGuardarCuentaClasificada')->name('CuentaClasificadorCuenta.ajaxAsociarCuenta')->middleware('auth');

Route::get('/prueba','PagoController@getDocument')->middleware('auth');

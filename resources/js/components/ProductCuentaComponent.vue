<template>
    <div class="col-md-12">  
      
      <form  action="" v-cloak method="POST"  mid="form-cuenta" @submit.prevent>
         
        <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">DATOS DE USUARIO</h3>
              </div>
              <!-- /.card-header -->              
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                      <label for="email">Correo</label>
                      <div class="form-group">                     
                        <div class="input-group">
                          <input type="text" class="form-control" id="email" placeholder="" name="email" value="" v-model="email">                         
                        </div>
                      </div>                                            
                  </div> 
                  <div class="col-md-8">
                    <div class="form-group">
                          <label for="name">Nombre De Usuario</label>                         
                          <input type="text" class="form-control" id="name" placeholder="" name="name" value=""  v-model="name">
                      </div>  
                  </div>
                </div>
                                                                               
              </div>
                <!-- /.card-body -->
              <div class="card-footer">                
              </div>                       
        </div>
        <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Selecione los productos que permiten su venta al usuario</h3>
              </div>
              <!-- /.card-header -->              
              <div class="card-body">    
                <div class="row">
                      <div class="col-md-3">                                                                  
                          <div class="col-md-12" >
                            <label for="iclasificador_cuenta">Productos</label>
                              <chosen-select v-model="cuenta_selecionada" placeholder="Productos" label="nombre_cuenta" 
                                    track-by="id" :options="lista_cuenta" id="cuenta_id" 

                                    @close="cargarCuenta()">
                             </chosen-select> 
                          </div>                                                  
                      </div>
                                                    
                </div>                                                                            
              </div>
                <!-- /.card-body -->
              <div class="card-footer">
                  <button type="button" id="button-pagar" class="btn btn-primary" @click="agregarProdCuenta">Permitir</button> 
                  <!-- <button type="button" id="button-pagar" class="btn btn-primary" @click="">Permitir Todo</button> -->
              </div> 
        </div>
        <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Productos/Cuentas</h3>
              </div>
              <!-- /.card-header -->              
              <div class="card-body">    
                <table class="table table-hover table-bordered">
                            <thead>
                            <tr>                        
                                    <th scope="col">Numero de Cuenta</th>
                                    <th scope="col">Nombre de Cuenta</th>                                    
                                    <th scope="col">Tipo de Producto</th>
                                    <th scope="col">Precio Por Unidad</th>
                                    <th scope="col">OPCIONES</th>
                            </tr>                  
                            </thead>
                            <tbody id="detalles">
                              <tr  v-for="(cuenta1,key) in lista_productos_usuarios"  v-bind:key="cuenta1.id"> 
                               
                                <td>{{cuenta1.numero_cuenta}}</td>
                                <td>{{cuenta1.nombre_cuenta}}</td>
                                <td v-if="(cuenta1.tipo_cuenta==2)">No es producto</td>
                                <td v-else>Es producto</td>
                                <td>{{cuenta1.precio_unitario}}</td>
                                <td><button class="btn btn-danger btn-sm" type="button"  @click="eliminarCuentaClasificador(cuenta1,key)">Eliminar</button></td>                            
                           
                               </tr>
                            </tbody>
                            <div class="row" id="ver">
                              </div>
               </table>                                                                            
             </div>
                <!-- /.card-body -->
              <div class="card-footer">
                 </div>                       
        </div>
      
      </form>
    </div>         
</template>
<script>
  import Modal from './ClienteComponent.vue';
    export default {
      name: 'ProductComp',
        components: {
          Modal,
        },
        props: ['id_user','email','name'],
        data(){
            return {
              // isModalVisible:false,
            
                lista_cuenta_clasificador:[
                    // {
                    //   id:0,
                    //   numero_identificador:1,                      
                    //   descripcion:'descripcion'
                    // }
                ],
                lista_datos_obtenidos:[
                    
                ],
                lista_productos_usuarios:[
               
                ],
                lista_cuenta:[{
                    id:0,
                    numero_cuenta:1,
                    nombre_cuenta:'nombre_cuenta',
                    descripcion:'descripcion',
                    precio_unitario:0

                }],                                                                
             
                cuenta_clasificador_selecionado:{
                  id:0,
                  descripcion:''
                },
                cuenta_selecionada:{
                  id:0,
                  numero_cuenta:0,
                  nombre_cuenta:0,
                  stock:0,
                  tipo_cuenta:0,
                  precio_unitario:50
                }, 
                
                datos_obtenidos:{
                  numero_cuenta:0,
                  nombre_cuenta:'',
                  tipo_cuenta:'',
                  stock:0,
                  descripcion:''
                }, 

                producto_usuarios:{
                  id:-1,
                  cuenta_id:0,
                  user_id:0

                },
                
                numero_clasificador:6,
              
                correo:'',
                nombre:'',
                numero_cuenta:1,
                nombre_cuenta:"",
                descripcion:"",
                numero_clasificador:0,

                productCuenta:{  

                    cuenta_id:-1,
                    numero_cuenta:1,
                    nombre_cuenta:'',
                    tipo_cuenta:1,
                    precio_unitario:0,
                    user_id:-1,
                },
                clasificador:{
                  id:-1,
                  numero_clasificador:0,
                  descripcion:""
                },
                id_clasificador:0,
             
            }
        },
        mounted() {
            console.log('Component mounted.')
        },        
        methods:{

          agregarProdCuenta(){   
            const cuenta={

              id:this.cuenta_selecionada.id,
              numero_cuenta:this.cuenta_selecionada.numero_cuenta,
              nombre_cuenta:this.cuenta_selecionada.nombre_cuenta,
              tipo_cuenta:this.cuenta_selecionada.tipo_cuenta,
              precio_unitario:this.cuenta_selecionada.precio_unitario
            };
                                             
            this.lista_productos_usuarios.push(cuenta);
            console.log("cuenta_agregada");
            let cuenta_asociada1={
              prod_users:{
                id:this.id_user,
                correo:this.email,
                nombre:this.name,
              },
              cuenta:{
                id:this.cuenta_selecionada.id,
                numero_cuenta:this.cuenta_selecionada.numero_cuenta,
                nombre_cuenta:this.cuenta_selecionada.nombre_cuenta,
                precio_unitario:this.cuenta_selecionada.precio_unitario,
                tipo_cuenta:this.cuenta_selecionada.tipo_cuenta,
              }
            }
            axios.post('/permisos/register',cuenta_asociada1).then(res=>{
                const error=res.data.error;
            let cuenta_asociada=res.data.cuenta_asociada;
             console.log(res.data);
                if(error==0){   
                   console.log(res.data);
                  // this.productCuenta.cuenta_id=cuenta_asociada.cuenta_id;
                  // this.productCuenta.numero_cuenta=cuenta_asociada.numero_cuenta;
                  // this.productCuenta.nombre_cuenta=cuenta_asociada.nombre_cuenta;
                  // this.productCuenta.tipo_cuenta=cuenta_asociada.tipo_cuenta;
                  //  this.productCuenta.id=cuenta_asociada.id;
                  alert('Permiso agregado '); 
                }else 
                if(error==2){
                  alert('Ya Registro el permiso, No se puede registrar 2 veces el mismo permiso');
                  this.lista_productos_usuarios.pop(cuenta);
                  //location.reload();
                }
            });
            
        },


          
          agregarCuenta(){               
            
                const cuenta={

                  id:this.cuenta_selecionada.id,
                  numero_cuenta:this.cuenta_selecionada.numero_cuenta,
                  nombre_cuenta:this.cuenta_selecionada.nombre_cuenta,
                  stock:this.cuenta_selecionada.stock,
                  tipo_cuenta:this.cuenta_selecionada.tipo_cuenta,
                  precio_unitario:this.cuenta_selecionada.precio_unitario
                            
                };
                                                 
                this.lista_cuenta_clasificador.push(cuenta);
                console.log("cuenta_agregada");
                let cuenta_asociada1={
                  cuenta_clasificador:{
                    id:this.clasificador.id,
                    numero_clasificador:this.clasificador.numero_clasificador,
                    descripcion:this.clasificador.descripcion
                  },
                  cuenta:{
                    id:this.cuenta_selecionada.id,
                    numero_cuenta:this.cuenta_selecionada.numero_cuenta,

                  }
                }
                axios.post('/ajax/ajaxAsociarCuenta',cuenta_asociada1).then(res=>{
                    const error=res.data.error;
                    let cuenta_asociada=res.data.cuenta_asociada;
                   
                    if(error==0){
                   
                      //  alert('Se realizo el pago exitosamente se con Nº DE SERIE '+cuenta_asociada);
                      console.log(res.data);
                      this.cuenta_clasificador_selecionado.id=cuenta_asociada.cuenta_clasificador_id;
                      this.cuenta_clasificador_selecionado.numero_clasificador=cuenta_asociada.numero_clasificador;
                      this.cuenta_clasificador_selecionado.descripcion=cuenta_asociada.descripcion;
                    }else if(error==2){
                       // alert('Ya Registro la Materia, No se puede registrar 2 veces en la misma gestion');
                    }
                });
                
            },

            pagar(){   
              this.cargarCliente();
              if(this.cliente_id==-1){
                alert("No se puede Realizar el pago por que no seleccion un usuario existente, debe registrar primero el usuario");
                return;
              }
                const pago1={
                                  id:-1,
                                  serie:-1,
                                  cliente_id:this.cliente_id,
                                  lugar:this.lugar,
                                  fecha_pago:this.fecha_pago,
                                  total:this.total,
                                  cuenta_clasificador_id:this.cuenta_clasificador_selecionado.id
                };
                const pago_info={
                  pago:pago1,
                  lista_pago_detalle:this.lista_pagos
                };       
                axios.post('/ajax/Pagar',pago_info).then(res=>{
                    const error=res.data.error;
                    const pago=res.data.pago;
                    //0 no error
                    //2 ya existe
                    console.log("Ingreso");
                    console.log(res.data);
                    $("#ver").html(res.data);
                    if(error==0){
                        // const resultado1=res.data.resultado;                                                
                        // var pago_detalle=resultado1.pago_detalle;
                        // var pago=resultado1.pago;                             
                        // this.lista_pagos.push(pago_detalle);
                        // console.log("pago");
                        alert('Se realizo el pago exitosamente se con Nº DE SERIE '+pago.serie);
                        window.location.href = "/Pago/"+pago.id;
                    }else if(error==2){
                       // alert('Ya Registro la Materia, No se puede registrar 2 veces en la misma gestion');
                    }
                });
            },
            eliminarPago(materia, index){
                const confirmacion = confirm(`Se va a eliminar esta materia y se perderan\n las notas registrada a esta notas ${materia.nombre}`);
                if(confirmacion){
                    // axios.delete(`/ajax/ajaxRegistrarMateriasAvanzada/${materia.id}`).then(()=>{
                    //     this.lista_registros_materias.splice(index, 1);
                    // });
                }
            },


            eliminarCuentaClasificador(cuenta1, index){
                const confirmacion = confirm(`Se va a eliminar este detalle y se perderan\n el clasificador ${cuenta1.nombre_cuenta}`);
                if(confirmacion){



                    axios.delete(`/permiso_prod/eliminar/${cuenta1.numero_cuenta}`).then((res)=>
                    {
                     
                      if(res.data.error==0){
                          this.lista_productos_usuarios.splice(index, 1);
                          this.clasificador.numero_clasificador='';
                          this.clasificador.descripcion='';
                           alert('Se elimino correctamente ');
                         
                      }else{
                        alert('No se encontro  ');
                      }
                        
                    
                     
                   });
                }
            },

            cargarCuenta(){
                axios.get(`/ajax/Cuenta`).then(
                    res=>{
                        this.lista_cuenta=res.data;
                      //  console.log(res.data );
                });
            },
            cargarCuentaPorClasificador(id){
                axios.get(`/ajax/cuentaPorClasificador/${id}`).then(
                    res=>{
                        this.lista_cuenta=res.data;
                        if(this.lista_cuenta.length==0){
                            this.cuenta_selecionada={id:-1,descripcion:""};
                        }
                        //console.log(this.lista_cuenta);
                });
            },
            cargarClasificadorPorUnidad(id){
                axios.get(`/ajax/clasificadorPorUnidad/${id}`).then(
                    res=>{
                        this.lista_cuenta_clasificador=res.data;
                        if(this.lista_cuenta_clasificador.length==0){
                            this.cuenta_clasificador_selecionado={id:-1,descripcion:""};
                        }
                        //console.log(this.lista_cuenta);
                });
            },
            cargarClasificadorCuenta(){
                axios.get(`/ajax/ajaxCuentaClasificador`).then(
                    res=>{
                        this.lista_cuenta_clasificador=res.data;
                      //  console.log(res.data );
                });
            },
            cargarUnidad(){
                axios.get(`ajax/ajaxUnidad`).then(
                    res=>{
                        this.lista_unidad=res.data;
                        console.log("Datos "+res.data );
                });
            },
            cargarCliente(){                
                axios.get(`/ajax/Cliente/${this.ci}`)
                .then(res=>{
                    console.log("no _ingreso");
                        this.cliente=res.data;    
                        this.cliente_id=this.cliente.id;                                            
                        this.nombres_apellidos=this.cliente.nombres+" "+this.cliente.apellidos;
                        console.log(this.cliente);
                });                                      
            },
            buscarDatosById(id){                
                axios.get(`/ajax/CuentaClasificadorById/${id}`)
                .then(res=>{
                    console.log("no _ingreso");
                        this.clasificador=res.data.cuenta_clasificador;    
                let _lista_cuentas=res.data.listado_cuentas;
                this.lista_cuenta_clasificador=[];
                _lista_cuentas.forEach(cuenta => {
                  let _cuenta={//cuenta asociada                    
                    id:cuenta.id_cuenta_clasificador,
                    numero_cuenta:cuenta.numero_cuenta,
                    nombre_cuenta:cuenta.nombre_cuenta,
                  
                    descripcion:cuenta.descripcion,
                    precio_unitario:cuenta.precio_unitario,
                    stock:cuenta.stock,
                    tipo_cuenta:cuenta.tipo_cuenta
                  }
                  this.lista_cuenta_clasificador.push(_cuenta)
                });
                  

                        console.log(this.cuenta_clasificador);
                        console.log(this.nombre_cuenta);
                      
                });                                      
            },

            buscarDatos(){                
                axios.get(`/ajax/CuentaClasificador/${this.clasificador.numero_clasificador}`)
                .then(res=>{
                    console.log("no _ingreso");
                        this.clasificador=res.data.cuenta_clasificador;    

                        // this.cuenta_clasificador_id=this.cuenta_clasificador.id;  
                        // this.numero_clasificador=this.cuenta_clasificador.numero_clasificador;                                           
                        // this.clasificador.descripcion=this.cuenta_clasificador.descripcion;
                        // this.lista_cuenta['nombre_cuenta']=this.cuenta_clasificador.nombre_cuenta;

                //         lista_cuenta:[{
                //     id:0,
                //     numero_cuenta:1,
                //     nombre_cuenta:'nombre_cuenta',
                //     descripcion:'descripcion',
                //     precio_unitario:0

                // }], 
                let _lista_cuentas=res.data.listado_cuentas;
                this.lista_cuenta_clasificador=[];
                _lista_cuentas.forEach(cuenta => {
                  let _cuenta={//cuenta asociada                    
                    id:cuenta.id_cuenta_clasificador,
                    numero_cuenta:cuenta.numero_cuenta,
                    nombre_cuenta:cuenta.nombre_cuenta,
                  
                    descripcion:cuenta.descripcion,
                    precio_unitario:cuenta.precio_unitario,
                    stock:cuenta.stock,
                    tipo_cuenta:cuenta.tipo_cuenta
                  }
                  this.lista_cuenta_clasificador.push(_cuenta)
                });
                  
                



                        console.log(this.cuenta_clasificador);
                        console.log(this.nombre_cuenta);
                      
                });                                      
            },

            
            buscarPermisos(){                
                axios.get(`/permisos/user/${this.id_user}`)
                .then(res=>{
                    console.log("no _ingreso");
                       // this.clasificador=res.data;    

                        // this.cuenta_clasificador_id=this.cuenta_clasificador.id;  
                        // this.numero_clasificador=this.cuenta_clasificador.numero_clasificador;                                           
                        // this.clasificador.descripcion=this.cuenta_clasificador.descripcion;
                        // this.lista_cuenta['nombre_cuenta']=this.cuenta_clasificador.nombre_cuenta;

                //         lista_cuenta:[{
                //     id:0,
                //     numero_cuenta:1,
                //     nombre_cuenta:'nombre_cuenta',
                //     descripcion:'descripcion',
                //     precio_unitario:0

                // }], 
             let _lista_cuentas=res.data;
                this.lista_productos_usuarios=[];
                _lista_cuentas.forEach(cuenta => {
                  let _cuenta={//
                   // cuenta_id:cuenta.id,           
                    numero_cuenta:cuenta.numero_cuenta,
                    nombre_cuenta:cuenta.nombre_cuenta,
                    precio_unitario:cuenta.precio_unitario,
                    tipo_cuenta:cuenta.tipo_cuenta

                  }
                  this.lista_productos_usuarios.push(_cuenta)
                });

                        console.log(this.cuenta_clasificador);
                        console.log(this.nombre_cuenta);
                      
                });                                      
            },


            cargarCuente(){                
                axios.get(`/ajax/Cuente/${this.clasificador.numero_clasificador}`)
                .then(res=>{
                    console.log("no _ingreso");
                    //console.log(res);
                        this.cuenta=res.data;    
                        this.cuenta_id=this.cuenta.id;                                            
                        this.nombre_cuenta=this.cuenta.nombre_cuenta;
                        console.log(this.cuenta);
                        console.log(this.nombre_cuenta);
                });                                      
            },
            cambiar_precio(){
              this.precio_unitario=this.cuenta_selecionada.precio_unitario;
            },
             getFormatFecha(today){                   
                  var dd = today.getDate();
                  var mm = today.getMonth()+1; 
                  var yyyy = today.getFullYear();
                  if(dd<10) 
                  {
                  dd='0'+dd;
                  } 

                  if(mm<10) 
                  {
                  mm='0'+mm;
                  }                   
                  return dd+'/'+mm+'/'+yyyy;                  
            },
            actualizarHoy(){
              this.fecha_pago=this.getFormatFecha(new Date());
            },
            abriModal(event){
               event.preventDefault();
              
              this.isModalVisible=true;
            },
            closeModal(){
              this.isModalVisible=false;
            },
            guardar(datos){
                  var error=datos.error;
                  console.log("ingresa por aqui =>")
                  if(error==0){                      
                      this.cliente=datos.cliente;                           
                      this.ci=this.cliente.ci;
                      this.cliente_id=this.cliente.id;                                            
                      this.nombres_apellidos=this.cliente.nombres+" "+this.cliente.apellidos;                    
                  }
            }
        },
        computed:{
             sub_total(){
                 this.monto=this.cantidad*this.precio_unitario;
                return this.monto+"";
            }
        },
        created(){           
          // this.cargarCuenta();
          //this.cargarClasificadorCuenta();
          this.cargarUnidad();
          this.actualizarHoy();
          // this.cargarCuente();
          this.cargarCuenta();

          this.buscarPermisos();

           this.total=0;
           this.id_clasificador=-1;
           if(this.pid_clasificador!=undefined){
            this.buscarDatosById(this.pid_clasificador);
          
           }
           console.log(this.pid_clasificador!='');
           console.log(this.pid_clasificador);
         //   $('select').chosen({no_results_text:"Not found"});
        }
    }    
</script>
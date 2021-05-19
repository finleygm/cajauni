<template>
    <div class="col-md-12">        
      <form  action="" v-cloakmethod="POST"  mid="form-cuenta" @submit.prevent>
         @csrf 
        <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">DATOS DE PAGOS</h3>
              </div>
              <!-- /.card-header -->              
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                      <label for="ci">CARNET DE IDENTIDAD</label>
                      <div class="input-group">
                              <input type="text" class="form-control" id="ci"
                                 name="ci" v-on:keyup.enter.prevent="cargarCliente" 
                                v-model="ci" v-bind:cliente_id="cliente.id"  
                                data-toggle="tooltip" 
                                data-placement="top" title="Escribe numero de registro y presiona enter"
                              >
                          <div class="input-group-append">
                            <Modal  v-show="isModalVisible" @close="closeModal" id="val4" @guardar="guardar"/>
                          <a href="#" @click="abriModal"><span class="input-group-text">+</span></a>
                            
                          </div>
                      </div>                                          
                  </div> 
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="nombres">NOMBRE Y APELLIDOS</label>
                        <input type="text" class="form-control" id="nombres" placeholder="NOMBRE Y APELLIDOS" name="nombres"  value="" readonly v-model="nombres_apellidos" >
                    </div>        
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="lugar">LUGAR</label>                         
                          <input type="text" class="form-control" id="lugar" placeholder="LUGAR" name="lugar" value="IVO" v-model="lugar">
                      </div>                                          
                  </div> 
                  <div class="col-md-8">
                      <label for="ifecha_pago">FECHA EMISION</label><br/>
                    <div class="input-group">
                      <input type="text" class="form-control" id="ifecha_pago" placeholder="dia/mes/año" v-model="fecha_pago" name="fecha_pago"  value="20/04/2021" >
                        <div class="input-group-append">                            
                            <button type="button" class="input-group-text" @click="actualizarHoy" >Hoy</button>
                        </div>
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
                <h3 class="card-title">DATOS CUENTA A PAGAR</h3>
              </div>
              <!-- /.card-header -->              
              <div class="card-body">    
                <div class="row">
                      <div class="col-md-3">                                                                  
                          <div class="col-md-12" >
                            <label for="iclasificador_cuenta">UNIDAD ADM</label>
                              <chosen-select v-model="unidad_selecionado" placeholder="Clasficador de cuenta" label="descripcion" 
                                    track-by="id" :options="lista_unidad" id="icuenta_clasificador"
                                    @close="cargarClasificadorPorUnidad(unidad_selecionado.id)">
                             </chosen-select> 
                          </div>                                                  
                      </div>
                      <div class="col-md-3">                                                                  
                          <div class="col-md-12" >
                            <label for="iclasificador_cuenta">CLASIFICADOR CUENTA</label>
                              <chosen-select v-model="cuenta_clasificador_selecionado" placeholder="Clasficador de cuenta" label="descripcion" 
                                    track-by="id" :options="lista_cuenta_clasificador" id="icuenta_clasificador"
                                    @close="cargarCuentaPorClasificador(cuenta_clasificador_selecionado.id)">
                             </chosen-select> 
                          </div>                                                  
                      </div>
                      <div class="col-md-3">
                        <label for="ci">CUENTA</label>
                        <div class="input-group">                                              
                          <div class="col-md-12" >
                              <chosen-select v-model="cuenta_selecionada" placeholder="cuenta" label="nombre_cuenta" 
                                    track-by="id" :options="lista_cuenta" id="cuenta"
                                    @close="cambiar_precio">
                             </chosen-select> 
                          </div>                          
                        </div>                                          
                      </div> 
                    <div class="form-group col-md-1">
                      <label for="monto">PRECIO UNI. </label>
                      <input type="text" class="form-control" id="precio_unitario" placeholder="precio unitario" name="precio_unitario" v-model="precio_unitario"  >
                    </div>   
                    <div class="form-group col-md-1">
                      <label for="monto">CANTIDAD</label>
                      <input type="text" class="form-control" id="cantidad" placeholder="cantidad" name="cantidad" v-model="cantidad" >
                    </div>     
                    <div class="form-group col-md-1">
                      <label for="monto">MONTO</label>
                      <input type="text" class="form-control" id="monto" placeholder="monto" name="monto"  v-model="sub_total" >
                    </div>                                   
                </div>                                                                            
              </div>
                <!-- /.card-body -->
              <div class="card-footer">
                  <button type="button" id="button-pagar" class="btn btn-primary" @click="agregarPago">Agregar</button>
              </div>                       
        </div>
        <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">DETALLE DE PAGO</h3>
              </div>
              <!-- /.card-header -->              
              <div class="card-body">    
                <table class="table table-hover table-bordered">
                            <thead>
                            <tr>                        
                                    <th scope="col">CANTIDAD</th>
                                    <th scope="col">CUENTA DESCRIPCION</th>                                    
                                    <th scope="col">VALOR UNITARIO</th>
                                    <th scope="col">VALOR TOTAL</th>
                                    <th scope="col">OPCIONES</th>
                            </tr>                  
                            </thead>
                            <tbody id="pagos_detalles">
                              <tr  v-for="(pago1,key) in lista_pagos"  v-bind:key="pago1.id"> 
                                <td>{{pago1.cantidad}}</td>
                                <td>{{pago1.descripcion}}</td>
                                <td>{{pago1.precio_unitario}}</td>
                                <td>{{pago1.monto}}</td>
                                <td><button class="btn btn-danger btn-sm" type="button"  @click="eliminarPago(pago,key)">Eliminar</button>  </td>                            
                              </tr>
                            </tbody>
                            <div class="row" id="ver">
                              </div>
               </table>                                                                            
             </div>
                <!-- /.card-body -->
              <div class="card-footer">
              <button type="button" id="button-pagar" class="btn btn-primary" @click="pagar">Pagar</button>
              </div>                       
        </div>
      </form>
    </div>         
</template>
<script>
  import Modal from './ClienteComponent.vue';
    export default {
      name: 'AppPago',
        components: {
          Modal,
        },
        data(){
            return {
              isModalVisible:false,
              lista_unidad:[
                    {
                      id:0,
                      numero_unidad:1,                      
                      descripcion:'descripcion',
                      rubro_id:1
                    }
                ],
                lista_cuenta_clasificador:[
                    {
                      id:0,
                      numero_identificador:1,                      
                      descripcion:'descripcion',
                      rubro:1
                    }
                ]
                ,
                lista_cuenta:[{
                    id:0,
                    numero_cuenta:1,
                    nombre_cuenta:'cuenta',
                    descripcion:'descripcion',
                    precio_unitario:0

                }],                                                                
                cliente:{
                    id:1,
                    ci:6304332,
                    apellidos:'guerra',                    
                    nombres:'finley'
                },      
                lista_pagos:[
                //   {
                //     id:1,
                //     pago_id:0,
                //     cantidad:0,
                //     valor_unitario:10,
                //     nombre_cuenta:'Certificado',
                //     total:50
                // }
                ],
                unidad_selecionada:{
                  id:0,
                  descripcion:'',
                  rubro_id:0

                },
                cuenta_clasificador_selecionado:{
                  id:0,
                  descripcion:''
                },
                cuenta_selecionada:{
                  id:0,
                  precio_unitario:50
                },  
                precio_unitario:50,
                cantidad:1,
                monto:0,
                total:0,
                fecha_pago:'12/04/2021',
                nombres_apellidos:"",
                ci:"6304332",
                cliente_id:-1,
                id_pago:-1,
                id_pago_detalle:1,
                numero:12,
                lugar:"IVO",
                pago:{
                  id:-1,
                  serie:-1,
                  cliente_id:1,
                  fecha_pago:'12/04/2021',
                  total:0
                },
                pago_detalle:{
                  id:-1,
                  monto:0,
                  descripcion:"",
                  pago_id:0,
                  cuenta_id:0                  
                }
                
            }
        },
        mounted() {
            console.log('Component mounted.')
        },        
        methods:{
            // agregarPago2(){               
            //     const pago1={
            //         id:-1,
            //         serie:-1,
            //         cliente_id:1,
            //         fecha_pago:'12/04/2021',
            //         total:0
            //     };
            //     const detalle_pago={
            //         cuenta_id:this.cuenta_selecionada.id,
            //         precio_unitario:this.precio_unitario,
            //         cantidad:this.cantidad,
            //         monto:this.monto,                    
            //     };
            //     const pago_info={
            //       pago:pago1,
            //       pago_detalle:detalle_pago
            //     };
                
            //     axios.post('/ajax/Pagar',pago_info).then(res=>{
            //         const error=res.data.error;
            //         //0 no error
            //         //2 ya existe
            //         console.log("Ingreso");
            //         console.log(res.data);
            //         $("#ver").html(res.data);
            //         if(error==0){
            //             const resultado1=res.data.resultado;                                                
            //             var pago_detalle=resultado1.pago_detalle;
            //             var pago=resultado1.pago;                             
            //             this.lista_pagos.push(pago_detalle);
            //             console.log("pago");
            //         }else if(error==2){
            //             alert('Ya Registro la Materia, No se puede registrar 2 veces en la misma gestion');
            //         }
            //     });
            // },
            agregarPago(){               
                // const pago1={
                //     id:-1,
                //     serie:-1,
                //     cliente_id:1,
                //     fecha_pago:'12/04/2021',
                //     total:0
                // };
                const detalle_pago={
                    cuenta_id:this.cuenta_selecionada.id,
                    precio_unitario:this.precio_unitario,
                    cantidad:this.cantidad,
                    descripcion:this.cuenta_selecionada.nombre_cuenta,
                    monto:this.monto,                    
                };
                this.total+=this.monto;
                // const pago_info={
                //   pago:pago1,
                //   pago_detalle:detalle_pago
                // };                                           
                this.lista_pagos.push(detalle_pago);
                console.log("pago");
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
           this.total=0;
         //   $('select').chosen({no_results_text:"Not found"});
        }
    }    
</script>
<!--<style scoped>
 @import '../../../node_modules/vue-multiselect/dist/vue-multiselect.min.css';
 </style>
 -->
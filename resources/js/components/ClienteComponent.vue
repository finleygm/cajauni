<template>
<transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="modal-header">
              <H3>REGISTRO DE CLIENTE</H3>
          </div>
          <div class="modal-body">
                  <div class="form-group">
                    <label for="ci">Carnet de identidad</label>
                    <input type="text" class="form-control" id="ci" placeholder="Carnet de Identidad" v-model="ci" name="ci" value="">
                  </div>
                  <div class="form-group">
                    <label for="inombre_cuenta">Carnet de identidad expedido</label>
                    <select name="ci_expedido" class="form-control" id="ci_expedido" v-model="ci_expedido">
                      <option value=""></option>
                      <option value="Bn">Beni</option>
                      <option value="Ch">Chuquisaca</option>
                      <option value="Cb">Cochabamba</option>
                      <option value="Lp">La Paz</option>
                      <option value="Or">Oruro</option>
                      <option value="Pn">Pando</option>
                      <option value="Pt">Potosi</option>
                      <option value="Sc">Santa Cruz</option>
                      <option value="Tj">Tarija</option>                      
                    </select>                    
                  </div>
                  <div class="form-group">
                    <label for="nombres">NOMBRES</label>
                    <input type="text" class="form-control" id="nombres" placeholder="Nombres" name="nombres" v-model="nombres" value="" >
                  </div>        
                  <div class="form-group">
                    <label for="apellidos">APELLIDOS</label>
                    <input type="text" class="form-control" id="apellidos" placeholder="Apellidos" name="apellidos" v-model="apellidos" value="" >
                  </div> 
          </div>
          <div class="modal-footer">            
              <button class="modal-default-button" @click="guardar">
                GUARDAR
              </button> 
              <button class="modal-default-button" @click="$emit('close')">
                CANCELAR
              </button>            
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>
<style scoped>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.8s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}
.modal-container {
  width: 400px;
  margin: 0px auto;
  padding: 20px 30px;
  padding-bottom: 40px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>


<script>
  export default {
    name: 'Modal',
    data(){
      return{
        ci:0,
        ci_expedido:'',
        nombres:'',
        apellidos:''
      }
    },
    methods: {
     limpiar(){
       this.ci='';
       this.ci_expedido="";
       this.nombres="";
       this.apellidos="";

     },
     guardar(){
       var cliente={
         ci:this.ci,
         ci_expedido:this.ci_expedido,
         nombres:this.nombres,
         apellidos:this.apellidos
       };
        axios.post('/ajax/ajaxStoreCliente',cliente).then(res=>{
            const error=res.data.error;
            const cliente1=res.data.cliente;
            //0 no error
            //2 ya existe                  
            console.log(res.data);  
            if(error==0){                    
                alert('SE REGISTRO AL CLIENTE '+cliente1.nombres+" "+cliente1.apellidos);
                                
            }else if(error==2){                
                // alert('Ya Registro la Materia, No se puede registrar 2 veces en la misma gestion');
            }
            var datos={
              error:error,
              cliente:cliente1
            }
            this.limpiar();
            this.$emit('guardar',datos)
       });
       this.$emit('close');
     }
    },
  };
</script>
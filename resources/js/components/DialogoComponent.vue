<template>
  <transition name="modal">
  <form  action="" v-cloak method="POST"  mid="form-cuenta" @submit.prevent>
  
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="modal-header">
              <H3>CONFIRMACION</H3>
          </div>
          <div class="modal-body">
            <p>
              {{ mensaje }}

            </p>
          </div>
          <div class="modal-footer">            
              <button class="modal-default-button" @click="aceptar">
                ACEPTAR
              </button> 
              <button class="modal-default-button" @click="$emit('close')">
                CANCELAR
              </button>            
          </div>
        </div>
      </div>
    </div>
  </form>
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
    props: ['mensaje'],
    data(){
      return{
      
      }
    },
    methods: {
     
    
    },
  };
</script>
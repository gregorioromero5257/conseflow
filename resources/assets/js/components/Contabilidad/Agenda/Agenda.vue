<template>
<div class="container-fluid">
  <template slot="id" slot-scope="props">
      <button type="button" @click="abrirModal('solicitud-agenda','actualizar',props.row)" class="btn btn-warning btn-sm">
        <i class="icon-pencil"></i>
      </button> &nbsp;
  </template>
<div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
<vue-element-loading :active="isLoading"/>
<div class="modal-dialog modal-dark modal-lg" role="document">
<div class="modal-content">
<div>
    
  <div class="modal-header">
    <h4 class="modal-title" v-text="tituloModal"></h4>
      <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
  </div>

  <!-- CLONAR -->
  <div id="divMultiInputs">

  <div class="modal-body">
    <input type="hidden" name="id">
    <input type="text"  name="id" v-model="AgendapagosRecu.id" class="form-control" hidden >
 <div class="form-group row">
    <label class="col-md-3 form-control-label" for="title">Título</label>
    <div class="col-md-9">
      <input type="text"  name="title" v-model="AgendapagosRecu.title" class="form-control" placeholder="Título" autocomplete="off" id="title" data-vv-as="Título" v-validate="'required'" >
      <span class="text-danger">{{ errors.first('title') }}</span>
    </div>
</div>
  <div class="form-group row">
    <label class="col-md-3 form-control-label" for="date">Fecha</label>
    <div class="col-md-9">
      <input type="date"  name="date" v-model="AgendapagosRecu.date" class="form-control" placeholder="Fecha" autocomplete="off" id="date" data-vv-as="Fecha" v-validate="'required'" @change="verificar()">
      <span class="text-danger">{{ errors.first('date') }}</span>
    </div>
  </div>    
  <div class="form-group row">
    <label class="col-md-3 form-control-label" for="descripcion">Descripción</label>
    <div class="col-md-9">
      <input type="text"  name="descripcion" v-model="AgendapagosRecu.descripcion" class="form-control" placeholder="Descripción" autocomplete="off" id="descripcion" data-vv-as="Descripción" v-validate="'required'" >
      <span class="text-danger">{{ errors.first('descripcion') }}</span>
    </div>
  </div> 

  <div class="form-group row">
    <label class="col-md-3 form-control-label" for="pagos_recurrentes_id">Pagos recurrentes</label>
      <div class="col-md-9">
        <select class="form-control" id="pagos_recurrentes_id"  name="pagos_recurrentes_id" v-model="AgendapagosRecu.pagos_recurrentes_id"  data-vv-as="Pagos" v-validate="'excluded:0'" >
        <option v-for="item in listaPagos" :value="item.id" :key="item.id">{{ item.nombre}}</option>
        </select>
        <span class="text-danger">{{ errors.first('pagos_recurrentes_id') }}</span>
      </div>
  </div> 
  </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
    <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarEventoAgenda(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
    <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarEventoAgenda(0)"><i class="fas fa-calendar-alt"></i>&nbsp;Reagendar</button>
    <!-- PARA DUBLIPAR EL MODAL INPUTS -->
    <label class="sr-only" for="">Agregar mas actividades</label>

    <button id="add-producto"  maxlength="2" name="numInputs" onclick="controlMultiInput()" onkeyup="multiplicarInputs(this)" size="5" class="btn btn-outline-secondary input-sm"><strong><i class="fas fa-plus"></i></strong></button>
     <!-- <div id="divMultiInputs">
             </div> -->

</div> 
</div>
</div>
</div>
</div>
    <!-- IMPORTANTE! PARA QUE FUNCIONE EL CALENDARIO -->
  <vue-event-calendar
    :events="demoEvents"
   @day-changed="handleDayChanged"
   @month-changed="handleMonthChanged">
  </vue-event-calendar> 
</div>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
import Vue from 'vue'
import 'vue-event-calendar/dist/style.css' //se puede editar.
import vueEventCalendar from 'vue-event-calendar'

// Soporta los siguientes idiomas locale can be 'zh' , 'en' , 'es', 'pt-br', 'ja', 'ko', 'fr', 'it', 'ru', 'de', 'vi', 'ua', 'no, 'no-nn'
Vue.use(vueEventCalendar, {
    locale: 'es',
    color: '#3a4972', //Color del calendario.
    weekStartOn: 0,
    className: 'Custom className for current clicked date',   
}) 
export default {

  data () {
    return {
    AgendapagosRecu:{
       id : 0, 
       title: '',
       date: '',
       descripcion: '',
       pagos_recurrentes_id: 0,

       inputs: [{
      id: 'fruit0',
      label: 'Enter Fruit Name',
      value: '',
    }],

      },
      fecha_actual : '',
      tipoAccion : 0,
      listaPagos: [],
      controlMultiInput : 0,
      listaPagosNoRecu: [],
      isLoading: false,
       modal : 0,
      tituloModal : '',
      url: '/agendasrecurrentes',
      pagos_recurrente : null,
      prueba : null,
    demoEvents: 
    [
      {
        date: '2018/12/15',
        title: 'Bienvenido a Conserflow',
        customClass: 'disabled highlight'       
      },
    ],
    }
  },
  
  methods : {
     //--------------------------METODO PARA CLONAR-------------------------------------------
//     clonar(){
//     function multiplicarInputs(text){
//       var num= text.value
//       var div='';
//       for (var i=0;i<num;i++){ 
//            var cont=i+1;
//            div+="<br> Input text "+cont+"<input maxlength='5' name='inputTextMulti[]' size='6' type='text' />&nbsp;";
//       }

//       document.getElementById("divMultiInputs").innerHTML=div;
// }
//     },

//-------------------------CLONAR INPUTS-------------------------------
//     clonarTomarDatos(){
//       function controlMultiInput(){
//       var numI=document.ejMultiInput.numInputs.value;
//       if (numI.length<1){ 
//             alert('Especifica el numero de Input text que quieres'); 
//             return false;
//       }
//       var text=document.ejMultiInput.elements['inputTextMulti[]'];//text sera un array de inputs text
//       if(text.length){//si devuelve algo es porque tiene mas de 1 elemento por lo que sera un array
//             for (var i=0;i<numI;i++){
//                   var cont=i+1;
//                   if(text[i].value.length<1){alert('Debes introducir contenido del Input text '+cont); return false;}
//             }
//       }else{
//             if(text.value.length<1){
//                   alert('Debes rellenar el unico Input text');
//                   return false;
//             }
//       }
//       alert('Formulario correcto');
//       console.log(data);
// }
//     },
    condiciondepago(){
    },
    getLista() {
        let me=this;
        axios.get('/pagosrecurrentesmen').then(response => {
            me.listaPagos = response.data;
        })
        .catch(function (error) {
            console.log(error);
        });
    },
    
    getListaPagosNoRecu(){
        let me=this;
        axios.get('/pagosnorecurrentes').then(response => {
            me.listaPagosNoRecu = response.data;
        })
        .catch(function (error) {
            console.log(error);
        });
    },
    cerrarModal(){
        this.modal=0;
        this.tituloModal='';
        Utilerias.resetObject(this.AgendapagosRecu);
    },
abrirModal(modelo, accion, data = []){
      switch(modelo){
        case "solicitud-agenda":
        {
          switch(accion){
            case 'registrar':
            {
               console.log(data);
              this.modal = 1;
              this.tituloModal = 'Agendar nueva actividad';
              Utilerias.resetObject(this.AgendapagosRecu);
              this.AgendapagosRecu.date = this.corregir_fecha(data['date']);
              let me = this;   
              console.log(this.corregir_fecha(data['date']));
              console.log(data);
              this.tipoAccion = 1;
              // this.modal = 0;
              break;
            }
            case 'actualizar':
            {   
              // console.log(data.events[0]['id']);
              this.modal=1;
              this.tituloModal='Actualizar actividad agendada';
              this.tipoAccion=2;
              this.AgendapagosRecu.id=data.events[0]['id'];
              this.AgendapagosRecu.title = data.events[0]['title'];
              this.AgendapagosRecu.date = (data.events[0]['date']).substr(0,4)+'-'+(data.events[0]['date']).substr(5,2)+'-'+(data.events[0]['date']).substr(8,2);
              this.AgendapagosRecu.descripcion = data.events[0]['desc'];
              this.AgendapagosRecu.pagos_recurrentes_id = data.events[0]['pagos_recurrentes_id'];                   
              break;
            }
          }
        }
      }
    },
    corregir_fecha(data){
      var  inicial = data;
      var dia = '';
      var mes = '';
      var date = '';
      var year = inicial.substr(0,4);
      if(inicial.length == 9)
      {
        var mesnuevo = inicial.substr(0,7);
        var porciones = mesnuevo.split('/');
      if(porciones.length == 2){
        mes = inicial.substr(5,2);
        dia = inicial.substr(8,1);
        date= year + '-' + mes + '-0' + dia;
      }
      if(porciones.length == 3){
        mes = inicial.substr(5,1);
        dia = inicial.substr(7,2);
        date= year + '-0' + mes + '-' + dia;
      }  
      }
      if(inicial.length == 10)
      {
        mes = inicial.substr(5,2);
        dia = inicial.substr(8,2);
        date= year + '-' + mes + '-' + dia;
      }
      if(inicial.length == 8)
      {
        mes = inicial.substr(5,1);
        dia = inicial.substr(7,1);
        date= year + '-0' + mes + '-0' + dia;
      }
      return date;
    },
    guardarEventoAgenda(nuevo){
        this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? me.url : me.url+'/'+this.id,
            data: {
              'id': this.AgendapagosRecu.id,
              'title': this.AgendapagosRecu.title,
              'date': this.AgendapagosRecu.date,
              'descripcion': this.AgendapagosRecu.descripcion,
              'pagos_recurrentes_id': this.AgendapagosRecu.pagos_recurrentes_id,
              
            }
            }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              me.cerrarModal();
              me.getData();
              if (!nuevo) {
                toastr.success('Actividad Agendada Actualizada Correctamente');
              }else {
                toastr.success('Actividad Agendada Agregada Correctamente');
              }
            } else {
              swal({
                type: 'error',
                html: response.data.errors.join('<br>'),
              });
            }
          }).catch(function (error) {
            console.log(error);
          });
        }
      });
    },
  getData()
  {
    let me= this;
    axios.get('/agendasrecurrentes').then(function (response){
    console.log(response.data);
    me.prueba = response.data[0];
    me.demoEvents = response.data[0];
    }).catch(function (error) {
        console.log(error);
      });    
  },
   fechaActual(){
            var hoy = new Date ();
            var dd = hoy.getDate();
            var mm = hoy.getMonth()+1; //hoy es 0!
            var yyyy = hoy.getFullYear();
            if(dd<10) {  dd='0'+dd   }
            if(mm<10) {  mm='0'+mm   }
            hoy = yyyy+'-'+mm+'-'+dd;
            this.fecha_actual = hoy;
            },
  handleDayChanged (data) {
    let me= this;
      console.log('date-changed', data);
      
      if(data.events.length != 0){
     
    (async function getFruit () {
const {value: fruit} = await Swal({
  title: 'Seleccione la actividad a realizar',
  input: 'select',
  inputOptions: {
    'agregar': 'Agregar',
    'editar': 'Editar',
  },
  inputPlaceholder: 'Seleccionar',
  showCancelButton: true,
  inputValidator: (value) => {
    console.log(value);
    if(value == 'agregar'){

      me.abrirModal('solicitud-agenda','registrar', data);

    }
    else if(value == 'editar'){
      me.abrirModal('solicitud-agenda','actualizar', data);
    }
   }})      
})()
}
      else{
      var b =me.corregir_fecha(data['date']);
      var a = me.fecha_actual;
       var fechaInicio = new Date(a).getTime();
                var fechaFin    = new Date(b).getTime();
                var diff = fechaFin - fechaInicio;
                var diferencia =diff/(1000*60*60*24);
                console.log(diferencia);
                if (diferencia < 0) {
                    toastr.error('La fecha para agendar no debe ser menor a la fecha de Inicio');
                }
                else{
                    me.abrirModal('solicitud-agenda','registrar',data);
}
      }
               },
               verificar(){
                  var b =this.AgendapagosRecu.date;
                  var a = this.fecha_actual;
                  var fechaInicio = new Date(a).getTime();
                  var fechaFin    = new Date(b).getTime();
                  var diff = fechaFin - fechaInicio;
                  var diferencia =diff/(1000*60*60*24);
                  console.log(diferencia);
                  if (diferencia < 0) {
                      toastr.error('No se puede reagendar a una fecha anterior a la fecha actual');
                  }
                  else{
                    toastr.info('Validacion Correcta');
                  }
               },
  handleMonthChanged (data) {
      console.log('month-changed', data)
    },   
  } ,
   mounted() {
    this.getData();  
    this.getLista();
    this.fechaActual();
    this.getListaPagosNoRecu();
  }
}
</script>

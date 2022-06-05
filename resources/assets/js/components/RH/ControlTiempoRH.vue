<template>
  <main class="main">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Registro Sabana Tiempo
        </div>
        <div class="card-body">
          <span class="text-danger">{{error}}</span>

          <div class="form-row">
            <div class="col-md-3 mb-3">
              <label>Fecha</label>
              <input type="date" class="form-control" v-model="fecha_reg" data-vv-name="Fecha">
              <span class="text-danger">{{errors.first('Fecha')}}</span>
            </div>
            <div class="col-md-4 mb-3">
              <label>Proyecto</label>
              <v-select :options="listaProyecto" v-model="proyecto" label="nombre_corto" data-vv-name="proyecto">
              </v-select>
              <span class="text-danger">{{errors.first('proyecto')}}</span>
            </div>
            <div class="col-md-4 mb-3">
              <label>Nave</label>
              <select class="form-control" v-model="nave">
                <option value="Nave 1">Nave 1</option>
                <option value="Nave 2">Nave 2</option>
                <option value="Nave 4">Nave 4</option>
              </select>
              <span class="text-danger">{{errors.first('proyecto')}}</span>
            </div>
          </div>
          <div class="form-row">
            <div class="col-md-3 mb-3">
              <template v-if="proyecto != ''">
                <qrcode-stream @decode="onDecode" @init="onInit" />
              </template>
            </div>
            <div class="col-md-3 mb-3">
              <h5 style="font-family: 'Share Tech Mono', monospace;text-align: center;">{{empleado}}</h5>
            </div>
          </div>
          <div v-show="PermisosCrud.Create">

            <div class="form-row">

              <div class="form-group col-md-8" style="text-align: center;">
                <label ><b>REGISTROS SIN SINCRONIZACIÓN</b></label>
              </div>
              <div class="form-group col-md-2">
                <button class="btn btn-outline-success" @click="sincronizarguardado()"><i class="fas fa-redo-alt"></i>Sincronizar</button>
              </div>

            </div>
            <li v-for="(vi, index) in registros_fail" class="list-group-item">
              <div class="form-row">
                <div class="form-group col-md-2">
                  <label>{{index + 1}}</label>
                </div>
                <div class="form-group col-md-8">
                  <label>{{vi}}</label>
                </div>
              </div>
            </li>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
import { QrcodeStream } from 'vue-qrcode-reader';

export default {
  data(){
    return{
      PermisosCrud : {},

      error : '',
      result : '',
      empleado : '',
      empleado_id : '',
      fecha_reg : '',
      proyecto : '',
      nave : '',

      registros_fail:[],
      listaProyecto : [],

    }
  },
  methods : {
    sincronizar(){
     this.interval = setInterval(() => {
       if (window.localStorage) {
         if (window.localStorage.getItem('satime') !== undefined && window.localStorage.getItem('satime')) {
           if (this.registros_fail.length != 0) {
             if(navigator.onLine) {
             this.sincronizarguardado();
             }
           }
         }
       }
     }, 60000);
    },

    sincronizarguardado(){
       if(navigator.onLine) {
         this.registros_fail.forEach((item, i) => {
           var porciones = item.split('|');
           axios.post('guardar/reg/control/tiempo/qr',{
             empleado : porciones[0],
             empleado_id : porciones[1],
             fecha : porciones[2],
             proyecto : porciones[3],
             nave : porciones[4],
           }).then(response => {
             this.SwalMensaje();
           }).catch(e => {
             console.error(e);
           });
           this.removeC(i);
         });
       }else {
         toastr.warning('No cuenta con conexión a internet');
       }

    },

    removeC(x) {
      this.registros_fail.splice(x,1);
      this.saveC();
    },


    inicial(){
      this.fecha_reg =this.Getdate();
      axios.get('seguridad/listado/proyectos').then(response => {
        this.listaProyecto = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    InitCrud(){
      this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
    },

    Getdate(){
      var d = new Date();
      return d.getFullYear() + '-' +((d.getMonth()+1) < 10 ? '0'+ (d.getMonth()+1) : (d.getMonth() + 1))
      + '-' + (d.getDate() < 10 ? ('0' + d.getDate()): d.getDate());
    },

    buscar (search, loading) {
      if (search.length > 2) {
        let me = this;
        setTimeout(function(){
          axios.post('buscar/proyecto/sabana/qr',{
            des : search,
          }).then(response => {
            me.listaProyecto = response.data;
          }).catch(e =>{
            console.error(e);
          });
        }, 900);
      }
    },

    async onInit (promise) {
      try {

        //  console.log(promise,'dj');
        await promise
      } catch (error) {
        if (error.name === 'NotAllowedError') {
          this.error = "ERROR: Permita el acceso a la camara"
        } else if (error.name === 'NotFoundError') {
          this.error = "ERROR: No existe camara en este dispositivo"
        } else if (error.name === 'NotSupportedError') {
          this.error = "ERROR: No es suguro la activacion de la camara (HTTPS, localhost)"
        } else if (error.name === 'NotReadableError') {
          this.error = "ERROR: la camra ya esta en uso?"
        } else if (error.name === 'OverconstrainedError') {
          this.error = "ERROR: installed cameras are not suitable"
        } else if (error.name === 'StreamApiNotSupportedError') {
          this.error = "ERROR: Stream API no soportado para este navegador"
        }
      }
    },

    onDecode (result) {
        this.result = result
        var porciones = this.result.split('|');

        if (porciones.length == 2) {

          this.empleado = porciones[1];
          this.id_empleado = porciones[0];

          if(navigator.onLine) {
            axios.post('guardar/reg/control/tiempo/qr',{
              empleado : this.empleado,
              empleado_id : this.id_empleado,
              fecha : this.fecha_reg,
              proyecto : this.proyecto.id,
              nave : this.nave,
            }).then(response => {
              this.SwalMensaje();
            }).catch(e => {
              this.addC();
              this.SwalMensaje();
            });
          } else {
            this.addC();
            this.SwalMensaje();
          }
        }
    },

    SwalMensaje(){
      swal({
        type: 'success',
        title: 'Correcto',
        position: 'center',
        showConfirmButton: false,
        timer: 1000,
      });
    },

    addC() {
      this.registros_fail.push(this.empleado + '|' + this.id_empleado + '|' + this.fecha_reg + '|' +this.proyecto.id + '|' + this.nave );
      this.saveC();
    },

    saveC() {
      let parsed = JSON.stringify(this.registros_fail);
      localStorage.setItem('satime', parsed);
    },

    localSt(){
      if(localStorage.getItem('satime')) {
        try {
          this.registros_fail = JSON.parse(localStorage.getItem('satime'));
        } catch(e) {
          localStorage.removeItem('satime');
        }
      }
    },


  },
  mounted (){
    this.inicial();
    this.localSt();
    this.InitCrud();
    // this.sincronizar();
  }
}
</script>

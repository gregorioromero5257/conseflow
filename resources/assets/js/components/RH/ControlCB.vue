<template >
  <main class="main">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body">

          <span class="text-danger">{{error}}</span>
          <div class="form-row">
            <div class="col-md-5 mb-3">

            </div>
            <div class="col-md-2 mb-3">
              <h3 style="text-align: center;">Checador QR </h3>
            </div>
            <div class="col-md-5 mb-3">

            </div>
          </div>
          <div class="form-row">
            <div class="col-md-6 mb-3">
              <qrcode-stream @decode="onDecode" @init="onInit" />
            </div>
            <div class="col-md-6 mb-3" >
              <br>
              <h1 style="font-family: 'Share Tech Mono', monospace;text-align: center;">
                <digital-clock :blink="true" :displaySeconds="true"/>
              </h1>
              <br>
              <h2 style="font-family: 'Share Tech Mono', monospace;text-align: center;">{{empleado}}</h2>
              <br>
              <h2 style="font-family: 'Share Tech Mono', monospace;text-align: center;">{{hora_reg}}</h2>
            </div>
          </div>
          <div v-show="PermisosCrud.Create">

            <div class="form-row">

              <div class="form-group col-md-8" style="text-align: center;">
                <label ><b>REGISTROS SIN SINCRONIZACIÓN</b></label>
              </div>
              <div class="form-group col-md-2">
                <button class="btn btn-outline-success" @click="sincronizar()"><i class="fas fa-redo-alt"></i>Sincronizar</button>
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
import DigitalClock from "vue-digital-clock";

export default{
  data(){
    return{
      message : '',
      counter:0,
     interval:null,
      PermisosCrud : {},
      fecha_reg : '',
      hora_reg : '',
      date : '',
      date_two :  '',
      registros_fail:[],

      fecha_i : '',
      fecha_f : '',
      result: '',
      error: '',
      empleado : '',
      id_empleado : 0,
      id : 0,
      cantidad : 1,
      tipo : 'Caretas',
      columns: ['empleado','tipo','cantidad','fecha'],
      tableData: [],
      options:
      {
        headings:
        {
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts: config.texts
      },
    }
  },
  components:
  {
    QrcodeStream,
    DigitalClock,
    // 'existenciasdetalle': ExistenciasDetalle,
  },

  methods : {
    // Start(){
    //  this.interval = setInterval(() => {
    //    if(navigator.onLine) {
    //      Swal.fire({
    //        position: 'center',
    //        type: 'success',
    //        title: 'Hay INTERNET',
    //        timer: 1000
    //      });
    //    }else {
    //      Swal.fire({
    //        position: 'center',
    //        type: 'error',
    //        title: 'NO Hay INTERNET',
    //        timer: 1000
    //      });
    //    }
    //  }, 60000);
    // },


    callFunction () {
      var v = this;
      setInterval(function () {
        // alert('ds');
        // v.message = new Date().toLocaleTimeString();
      }, 1000);
    },

    zeroPadding(num, digit) {
      var zero = '';
      for(var i = 0; i < digit; i++) {
        zero += '0';
      }
      return (zero + num).slice(-digit);
    },

    InitCrud(){
      this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
    },

    getData(){
      var cd = new Date();

      var week = ['DOMINGO', 'LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO'];
      this.date =  week[cd.getDay()];
      this.date_two = this.zeroPadding(cd.getFullYear(), 4) +
      '-' + this.zeroPadding(cd.getMonth()+1, 2) +
      '-' + this.zeroPadding(cd.getDate(), 2);

      if(navigator.onLine) {
        // el navegador está conectado a la red
      //  console.log('Online');

      } else {
        //console.log('OffLine');

        // el navegador NO está conectado a la red
      }

      axios.get('get/data/inicial/cb').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
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
        var cd = new Date();
        this.fecha_reg = this.zeroPadding(cd.getFullYear(), 4) +
        '-' + this.zeroPadding(cd.getMonth()+1, 2) +
        '-' + this.zeroPadding(cd.getDate(), 2);
        this.hora_reg = this.zeroPadding(cd.getHours(), 2) +
        ':' + this.zeroPadding(cd.getMinutes(), 2) +
        ':' + this.zeroPadding(cd.getSeconds(), 2);
        this.SwalMensaje();

        if(navigator.onLine) {
          // el navegador está conectado a la red
          axios.post('guardar/reg/checador',{
            empleado : this.empleado,
            empleado_id : this.id_empleado,
            fecha : this.fecha_reg,
            hora : this.hora_reg,
          }).then(response => {
            // this.SwalMensaje();
          }).catch(e => {
            this.addC();
            // this.SwalMensaje();
          });
        } else {
          // el navegador no está conectado a la red
          this.addC();
          // this.SwalMensaje();
        }
      }


    },

    SwalMensaje(){
      swal({
        type: 'success',
        title: 'Acceso Correcto',
        position: 'center',
        showConfirmButton: false,
        timer: 1500
      });
    },

    guardar(){
      axios.post('guardar/data/seg/cb',{
        empleado_id : this.id_empleado,
        cantidad : this.cantidad,
        tipo : this.tipo,
      }).then(response => {
        this.getData();
        toastr.success('Correctamente');
      }).catch(e => {
        console.error(e);
      });
    },

    buscarFechas(){
      axios.get('get/data/seg/cb/fechas/' + this.fecha_i + '&' + this.fecha_f).then(response => {
        this.tableData = response.data;
      }).catch(e =>{
        console.error(e);
      });
    },

    generarExcel(){
      window.open('get/data/seg/cb/fechas/excel/' + this.fecha_i + '&' + this.fecha_f , '_blank' );
    },

    localSt(){
      if(localStorage.getItem('regs')) {
        try {
          this.registros_fail = JSON.parse(localStorage.getItem('regs'));
        } catch(e) {
          localStorage.removeItem('regs');
        }
      }
    },

    addC() {
      // ensure they actually typed something
      // if(!this.newCat) return;
      this.registros_fail.push(this.empleado + '|' + this.id_empleado + '|' + this.fecha_reg + '|' +this.hora_reg );
      // this.empleado = '';
      // this.id_empleado = '';
      // this.fecha_reg = '';
      // this.hora_reg = '';
      this.saveC();
    },

    removeC(x) {
      this.registros_fail.splice(x,1);
      this.saveC();
    },

    saveC() {
      let parsed = JSON.stringify(this.registros_fail);
      localStorage.setItem('regs', parsed);
    },

    sincronizar(){
      if(navigator.onLine) {
      this.registros_fail.forEach((item, i) => {
        var porciones = item.split('|');
        axios.post('guardar/reg/checador',{
          empleado : porciones[0],
          empleado_id : porciones[1],
          fecha : porciones[2],
          hora : porciones[3],
        }).then(response => {
        }).catch(e => {
          console.error(e);
        });
        this.removeC(i);
      });
      }else {
        toastr.warning('NO SE PUEDE SINCRONIZAR REVISE SU CONEXION A INTERNET!!!');
      }
    }

  },
  mounted(){
    // this.Start();
    this.getData();
    this.localSt();
    this.InitCrud();
  }
}

</script>
<style>
#clock {
  font-family: 'Share Tech Mono', monospace;
  color: #000000;
  text-align: center;
  /* position: absolute; */
  /* left: 40%; */
  /* top: 60%; */
  transform: translate(-50%, -50%);
  color: #000000;
  /* text-shadow: 0 0 20px rgba(10, 175, 230, 1),  0 0 20px rgba(10, 175, 230, 0); */
  .time {
    letter-spacing: 0.05em;
    font-size: 150px;
    padding: 5px 0;
  }
  .date {
    letter-spacing: 0.1em;
    font-size: 150px;
  }

}
</style>

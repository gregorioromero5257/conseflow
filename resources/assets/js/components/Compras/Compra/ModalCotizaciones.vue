<template>
  <div class="card">
    <!-- Modal -->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalPDF}" style="display: none;" data-focus-on="input:first">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" >Cotización: "{{this.titulo}}"</h4>
            <button type="button" class="close" @click="cerrarModalPDF()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div style="text-align: center;">
              <iframe :src="this.nombre_archivo" width="450" height="500" frameborder="0"></iframe>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"  @click="cerrarModalPDF()">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

  <!--Inicio del modal agregar inpuestos-->
  <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" style="display: none;" data-focus-on="input:first">
    <div class="modal-dialog modal-dark modal-lg" role="document">

      <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title" >Cotizaciones</h4>
            <button type="button" class="close" @click="cerrarModalCotizacion()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
            <input type="hidden" name="id">
            <div class="modal-body">
              <div class="form-group row" v-show="mostrarmodal == 0">
                <div class="col-md-4" v-for="doc in cotizaciones">
                  <div class="form-ch eck checkbox" >
                    <input class="" :value="doc.id" :id="doc.id" type="checkbox" v-model="cot_req" >
                    <label class="form-check-label" for="doc.id">
                      {{doc.folio}}
                      <button type="button" class="btn btn-dark btn-sm" @click="abrirModalPDF(doc.folio, doc.adjunto)"><i class="fas fa-eye"></i></button>
                    </label>
                  </div><br>
                </div>
              </div>

              <div v-show="mostrarmodal == 1">
                <label>!No se encontraron cotizaciones¡</label>
              </div>

            </div>
          </div>

          <div class="modal-footer">
            <div>
              <button type="button" class="btn btn-info" @click="subirDocumento()">Nueva Cotización</button>
            </div>
            <div>
              <button type="button" class="btn btn-success" @click="guardarCotizaciones()">Confirmar</button>
            </div>
            <div>
              <button type="button" class="btn btn-secondary" @click="cerrarModalCotizacion()">Cerrar</button>
            </div>
          </div>


      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!--Fin del modal agregar inpuestos-->
</div>
</template>

<script>

const CardPrincipal = r => require.ensure([], () => r(require('./CardPrincipal.vue')), 'compras');
const ModalImpuestos = r => require.ensure([], () => r(require('./ModalImpuestos.vue')), 'compras');
const ModalCotizaciones = r => require.ensure([], () => r(require('./ModalCotizaciones.vue')), 'compras');

import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data() {
    return {
      url: '/cotizacion',
      titulo: '',
      nombre_archivo: '',
      validador: false,
      validador2: false,
      cot_req : [],
      cot_req_default : [],
      cot_req_default2 : [],
      mostrarmodal: 0,
      IDproyecto: 0,
      IDproveedor: 0,
      cotizaciones : null,
      id : 0,
      modal : 0,
      modalPDF: 0,
      estadomodalcotizacion : null,
      impuestocatalogo : [],
      columnsi : ['id','tipo','porcentaje'],
      nuevo : null,
    }
  },
  components : {
    'cardprincipal' : CardPrincipal,
    'modalimpuestos' : ModalImpuestos,
    'modalcotizaciones' : ModalCotizaciones,
  },
  methods: {
    getCotizaciones(cotizacionesnom){
      this.cot_req = cotizacionesnom;
      this.validador = true;
      this.cot_req_default = cotizacionesnom;
    },

    guardarCotizaciones(){
      let me = this;
      var data = this.cot_req;
      var cadena = [];
      var cadenauno = [];
      var Metodo = 1;

      for (var i = 0; i < data.length; i++) {
        axios.get('cotizacionesrequeridas/'+data[i]+'&'+Metodo).then(response =>{
          cadena.push(response.data[0].folio);
          cadenauno.push(response.data[0].id);
        }).catch(function (error){
          console.error(error);
        })
      }
      if (this.validador) {
        this.cot_req_default = cadenauno;
      }
      if (!this.validador) {
        this.validador2 = true;
        this.cot_req_default2 = cadenauno;
      }
      var arreglo = [];
      arreglo.push(cadena);
      arreglo.push(cadenauno);
      me.eliminarArchivos();

      this.$emit('modalcotizaciones:click',arreglo);
      this.modal = 0;
      this.validador = false;
    },

    cargarcotizacion(proyecto,proveedor){
      var method = 1;
      axios.get('/obtenerarchivos/'+ method + '&' + proyecto + '&' + proveedor).then(response => {
        //console.log(response.data);
      }).catch(error => {
        console.error(error);
      });
      //this.cot_req = [];
      this.modal = 1;
      this.IDproyecto = proyecto;
      this.IDproveedor = proveedor;
      var Metodo = 1;
      let me = this;
      axios.get('/cotizacion/'+proyecto+'&'+proveedor+'&'+Metodo+'/edit').then(response =>{
        //me.cotizaciones = response.data;
        if (response.data == '') {
          this.mostrarmodal = 1;
        }
        else{
          this.mostrarmodal = 0;
          me.cotizaciones = response.data;
        }
      })
      .catch(function (error){
        console.error();
      });
    },

    cerrarModalCotizacion(){
      if (this.validador) {
        this.cot_req = [];
        this.cot_req = this.cot_req_default;
      }
      if (this.validador2) {
        this.cot_req = [];
        this.cot_req = this.cot_req_default2;
      }
      if (!this.validador && !this.validador2) {
        this.limpiarDatos();
      }
      this.eliminarArchivos();
      this.IDproveedor = 0;
      this.IDproyecto = 0;
      this.modal = 0;
    },

    eliminarArchivos()
    {
      var method = 2;
      axios.get('/obtenerarchivos/'+ method + '&' + this.IDproyecto + '&' + this.IDproveedor).then(response => {
        //console.log(response.data);
      }).catch(error => {
        console.error(error);
      });
    },

    limpiarDatos(){
      this.cot_req = [];
      this.cot_req_default = [];
      this.cot_req_default2 = [];
      this.validador = false;
      this.validador2 = false;
      this.titulo = '';
    },

    cambio(){
      this.validador = true;
    },

    abrirModalPDF(titulo,archivo){
      var method = 1;
      axios.get('/obtenerarchivos/'+ method + '&' + this.IDproyecto  + '&' + this.IDproveedor).then(response => {
          //console.log(response.data);
      }).catch(error => {
          console.error(error);
      });
      this.titulo = titulo;
      this.nombre_archivo = 'FilesFTP/' + archivo;
      this.modalPDF = 1;
      this.modal = 0;
    },

    cerrarModalPDF(){
      var method = 2;
      axios.get('/obtenerarchivos/'+ method + '&' + this.IDproyecto + '&' + this.IDproveedor).then(response => {
        //console.log(response.data);
      }).catch(error => {
        console.error(error);
      });
      this.nombre_archivo = '';
      this.titulo = '';
      this.modalPDF = 0;
      this.modal = 1;
    },

    subirDocumento(data,metodo){
      swal({
        title: 'COTIZACIONES',
        html: '<br><div class="form-group"> <label for="folio"><h5>Folio:</h5></label> <input type="text" id="folio_cotizacion" name="folio" required/> </div>',
        input: 'file',
        inputAttributes: {
          'accept': 'application/pdf',
          'aria-label': 'Upload your profile picture'
        },
        confirmButtonText: 'Subir Cotización',
        showCancelButton: true,
        inputValidator: (file) => {
          return !file && 'Este Campo no puede estar Vacío'
        }
      }).then((file) => {
      let me = this;

      if(file.value) {
        console.log(file);
        let formData = new FormData();

        formData.append('folio',document.getElementById('folio_cotizacion').value);
        formData.append('cotizacion_file', file.value);
        formData.append('proveedor_id', this.IDproveedor);
        formData.append('proyecto_id', this.IDproyecto);
        //formData.append('id',data.id);
        //console.log(document.getElementById('folio_cotizacion').value);

        axios.post(me.url,formData
        ).then(function (response) {
        if (response.data.status) {
          toastr.success('Archivo Subido Correctamente');
          me.cargarcotizacion(response.data.proyecto, response.data.proveedor);
        }
        else {
          swal({
            type: 'error',
            html: response.data.errors.join('<br>'),
          });
        }
        }).catch(function (error) {
          console.log(error);
        });
      }
      else if (file.dismiss === swal.DismissReason.cancel){
      }
      })
    },
  },
  mounted() {
  }
}
</script>

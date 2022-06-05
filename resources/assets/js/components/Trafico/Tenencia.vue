<template>
  <div>
    <div class="card">
      <div class="card-body">
        <vue-element-loading :active="isLoading"/>
        <!-- Inicio de form -->
        <template>

          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Fecha</label>
              <input type="date" class="form-control" v-model="tenencia.fecha" name="fecha" data-vv-name="Fecha" v-validate="'required'"  >
              <span class="text-danger">{{errors.first('Fecha')}}</span>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Folio</label>
              <input type="text" class="form-control" v-model="tenencia.folio" name="folio" data-vv-name="Folio" v-validate="'required'"  >
              <span class="text-danger">{{errors.first('Folio')}}</span>
            </div>
            <div class="form-group col-md-4">
              <label>Año del ejercicio</label>
              <input type="number" class="form-control" v-model="tenencia.anio" name="anio" data-vv-name="Año del ejercicio" v-validate="'required'"  >
              <span class="text-danger">{{errors.first('Año del ejercicio')}}</span>
            </div>
            <div class="form-group col-md-4">
              <label>Costo</label>
              <input type="text" class="form-control" v-model="tenencia.costo" name="costo" data-vv-name="Costo" v-validate="'required|decimal:2'"  >
              <span class="text-danger">{{errors.first('Costo')}}</span>
            </div>
          </div>

          <div class="form-row">
            <template v-if="tenencia.comprobante != ''">
              <div class="form-group col-md-2">
                <label>&nbsp;</label>
              <button type="button" class="form-control" @click="descargar(tenencia.comprobante)">
                <i class="fas fa-file-download"></i>&nbsp;Descargar
              </button>
              </div>
              <div class="form-group col-md-2">
                <label>&nbsp;</label>
              <button type="button" class="form-control" @click="actualiza()">
                <i class="fas fa-file"></i>&nbsp;Actualizar Archivo
              </button>
              </div>
            </template>
            <template v-if="tenencia.comprobante == ''">
              <div class="form-group col-md-4">
                <label>Comprobante</label>
                <input type="file" class="form-control" name="comprobante" @change="onChange($event)">
              </div>
            </template>

          </div>
          <div class="modal-footer">
            <button type="button" v-if="Tipo_Edo == 2" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            <button type="button" v-if="Tipo_Edo == 1" class="btn btn-secondary" @click="guardarMto(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button" v-if="Tipo_Edo == 2" class="btn btn-secondary" @click="guardarMto(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
          </div>
        </template>
        <!-- Fin de form de  -->

        <!-- Inicio de tabla de  -->
        <template >
          <v-client-table :data="datatenencia" :options="optionstenencia" :columns="columnstenencia" ref="tablePoliza">
              <template slot="id" slot-scope="props">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                  <div class="btn-group dropup" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-grip-horizontal"></i> Acciones
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <button  type="button" @click="actualizar(props.row)" class="dropdown-item">
                        <i class="icon-pencil"></i>&nbsp; Actualizar
                      </button>
                      <!-- <button  type="button" v-show="props.row.timbrado == 0 && props.row.descripcion_tipofactura === 'Pago'" @click="agregarP(props.row)" class="dropdown-item">
                      <i class="fas fa-list-ol"></i>&nbsp; Agregar Conceptos
                    </button> -->
                  </div>
                </div>
              </div>
          </template>
          </v-client-table>
        </template>
        <!-- Fin de tabla de  -->
      </div>
    </div>
  </div>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
export default {
  data (){
    return{
      tipo : 0,
      Tipo_Edo : 1,
      operacion_array : '',
      id : '',
      datatenencia: [],
      columnstenencia: ['id','fecha','folio','anio','costo'],
      isLoading : false,
      tenencia : {
        id : 0,
        fecha : '',
        folio : '',
        anio : '',
        costo : '',
        comprobante : '',
      },
      optionstenencia: {
        headings: {
          id : 'Acciones',
        },
        perPage: 5,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['fecha','folio','anio','costo'],
        filterable: ['fecha','folio','anio','costo'],
        filterByColumn: true,
        listColumns: { },
        texts:config.texts
      },
    }
  },
  methods :{

    getData(){
      this.isLoading = true;
      axios.get('tenenciaunidades/'+this.id).then(response => {
        this.datatenencia = response.data;
        this.isLoading = false;
      }).catch(err => {
        console.error(err);
      });
    },

    getId(id){
      this.id = id;
      this.getData();
    },

    onChange(e){
      this.tenencia.comprobante = e.target.files[0];
    },

    guardarMto(nuevo){
      this.$validator.validate().then(result =>{
        if (result) {
          this.isLoading = true;
          let me = this;
          let formData = new FormData();
          formData.append('id',this.tenencia.id);
          formData.append('fecha',this.tenencia.fecha);
          formData.append('folio',this.tenencia.folio);
          formData.append('anio',this.tenencia.anio);
          formData.append('costo',this.tenencia.costo);
          formData.append('comprobante',this.tenencia.comprobante);
          formData.append('unidad_id',this.id);
          formData.append('metodo',nuevo);
          axios.post('tenenciaunidades',formData).then(response => {
            me.isLoading = false;
            if (response.data.status) {
              if(!nuevo){
                toastr.success('Tenencia Actualizada Correctamente');
                me.getData();
                me.cerrarModal();
              }
              else
              {
                toastr.success('Tenencia Registrada Correctamente');
                me.getData();
                me.cerrarModal();
              }
            }
            else {
              swal({
                type: 'error',
                html: response.data.errors.join('<br>'),
              });
            }
          }).catch(err => {
            console.error(err);
          })
        }
      });
    },

    actualizar(data){
      this.Tipo_Edo = 2;
      this.tenencia.id = data.id;
      this.tenencia.fecha = data.fecha;
      this.tenencia.folio = data.folio;
      this.tenencia.anio = data.anio;
      this.tenencia.costo = data.costo;
      this.tenencia.unidad_id = data.unidad_id;
      this.tenencia.comprobante = data.comprobante;
    },

    actualiza(){
      this.tenencia.comprobante = '';
    },

    cerrarModal(){
      Utilerias.resetObject(this.tenencia);
      this.Tipo_Edo =1;
    },

    descargar(archivo){
      let me=this;
      axios({
        url: '/UnidadesStore/' + archivo,
        method: 'GET',
        responseType: 'blob', // importante
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', archivo); //archivo = nombre del archivo alojado en el ftp
        document.body.appendChild(link);
        link.click();
        //--Llama el metodo para borrar el archivo local una ves descargado--//
        axios.get('/UnidadesStore/' + archivo + '/edit')
        .then(response => {
        })
        .catch(function (error) {
          console.log(error);
        });
        //--fin del metodo borrar--//
      });
    },


  },

  mounted () {

  }
}
</script>

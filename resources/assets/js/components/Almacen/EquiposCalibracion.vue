<template>

<main class="main">
  <div class="container-fluid">

    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Control de calibración de Equipos.
        <button type="button" class="btn btn-dark float-sm-right" @click="abrirModal(1)">
          <i class="fas fa-plus"></i>&nbsp;Nuevo
        </button>
      </div>
      <div class="card-body">
        <v-client-table :data="tableData" :columns="columns" :options="options">
          <template slot="equipos.frecuencia" slot-scope="props">

            <template v-if="props.row.equipos.frecuencia == 1">
              <span class="text-success">Anual</span>
            </template>
            <template v-if="props.row.equipos.frecuencia == 3">
              <span class="text-warning">Semestral</span>
            </template>
            <template v-if="props.row.equipos.frecuencia == 2">
              <span class="text-danger">Sin Info.</span>
            </template>
          </template>

          <template slot="equipos.id" slot-scope="props">
            <button type="button" @click="abrirModal(0,props.row)" class="btn btn-outline-dark">
              <i class="fas fa-pencil-alt"></i>&nbsp;
            </button>
          </template>

        </v-client-table>
      </div>
    </div>

    <!--Inicio del modal agregar almacen-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <vue-element-loading :active="isLoading" />
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">

          <div>

            <div class="modal-header">
              <h4 class="modal-title" v-text="tituloModal"></h4>
              <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="form-row">
                <div class="col-md-10 mb-3">
                  <label>Descripción</label>
                  <input type="text" class="form-control" v-validate="'required'" data-vv-name="Descripción" v-model="equipos.descripcion">
                  <span class="text-danger">{{errors.first('Descripción')}}</span>
                </div>

              </div>
              <div class="form-row">
                <div class="col-md-4 mb-3">
                  <label>Marca</label>
                  <input type="text" class="form-control" v-validate="'required'" data-vv-name="Marca" v-model="equipos.marca">
                  <span class="text-danger">{{errors.first('Marca')}}</span>
                </div>
                <div class="col-md-4 mb-3">
                  <label>Modelo</label>
                  <input type="text" class="form-control" v-validate="'required'" data-vv-name="Modelo" v-model="equipos.modelo">
                  <span class="text-danger">{{errors.first('Modelo')}}</span>
                </div>
                <div class="col-md-4 mb-3">
                  <label>No. Serie</label>
                  <input type="text" class="form-control" v-validate="'required'" data-vv-name="No. serie" v-model="equipos.numero_serie">
                  <span class="text-danger">{{errors.first('No. serie')}}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-10 mb-3">
                  <label>Rango Medición</label>
                  <input type="text" class="form-control" v-validate="'required'" data-vv-name="Rango Medición" v-model="equipos.rango_medicion">
                  <span class="text-danger">{{errors.first('Rango Medición')}}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-4 mb-3">
                  <label>Frecuencia</label>
                  <select class="form-control" v-model="equipos.frecuencia" v-validate="'required'" data-vv-name="Frecuencia" @change="vaciar()" >
                    <option value="1">Anual</option>
                    <option value="2">Sin Info.</option>
                    <option value="3">Semenstral</option>
                    <option value="4">Bimestral</option>
                    <option value="5">Mensual</option>
                  </select>
                  <span class="text-danger">{{errors.first('Frecuencia')}}</span>
                </div>
                <template v-if="equipos.frecuencia != '' && equipos.frecuencia != '2'">
                <div class="col-md-4 mb-3">
                  <label>Fecha Servicio</label>
                  <input type="date" class="form-control" v-validate="'required'" data-vv-name="Fecha Servicio" v-model="equipos.fecha_servicio" @change="cambiarFecha()">
                  <span class="text-danger">{{errors.first('Fecha Servicio')}}</span>
                </div>
                <div class="col-md-4 mb-3">
                  <label>Proxima fecha</label>
                  <input type="date" class="form-control" v-validate="'required'" data-vv-name="Proxima Fecha" v-model="equipos.proxima_fecha">
                  <span class="text-danger">{{errors.first('Proxima Fecha')}}</span>
                </div>
              </template>
              </div>
              <div class="form-row">
                <div class="col-md-6 mb-3">
                  <label>Resguardo</label>
                  <input type="text" class="form-control" v-model="equipos.resguardo">
                </div>
                <div class="col-md-6 mb-3">
                  <label>Observaciones</label>
                  <input type="text" class="form-control" v-model="equipos.observaciones">
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" v-if="tipoAccion == 1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion == 2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal agregar almacen-->

  </div>
</main>

</template>
<script>
import Utilerias from '../Herramientas/utilerias.js'
var config = require('../Herramientas/config-vuetables-client').call(this)


export default{
  data() {
    return {
      tableData : [],
      columns : [
      'equipos.id',
      'equipos.descripcion','equipos.marca',
      'equipos.modelo',
      'equipos.rango_medicion',
      'equipos.numero_serie',
      'equipos.frecuencia',
      'servicios.fecha_servicio',
      'servicios.proxima_fecha',
      'equipos.resguardo',
      'equipos.observaciones'
    ],
      options: {
        headings: {
          'equipos.id' : 'Acciones',
          'equipos.descripcion' : 'Descripcion',
          'equipos.marca' : 'Marca',
          'equipos.modelo' : 'Modelo',
          'equipos.rango_medicion' : 'Rango Medición',
          'equipos.numero_serie' : '# Serie',
          'equipos.frecuencia' : 'Frecuencia',
          'servicios.fecha_servicio' : 'Fecha Servicio',
          'servicios.proxima_fecha' : 'Proxima Fecha',
          'equipos.resguardo' : 'Resguardo',
          'equipos.observaciones' : 'Observaciones',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts: config.texts
      },
      isLoading : false,
      modal : 0,
      tituloModal : '',
      tipoAccion : 0,
      equipos : {
        id : 0,
        frecuencia : '',
        descripcion : '',
        marca : '',
        modelo : '',
        rango_medicion : '',
        fecha_servicio : '',
        proxima_fecha : '',
        resguardo : '',
        observaciones : '',
        numero_serie : '',
        servicio_id : 0,
      },
    }
  },
  methods : {
    /**
    **
    **/
    getData(){
      axios.get('get/calibracion/equipos/').then(response => {
        console.log(response);
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    abrirModal(estado, data = [])
    {

      if (estado == 1) {
        this.modal = 1;
        this.tituloModal = 'Guardar nuevo equipo calibración';
        this.tipoAccion = 1;
        Utilerias.resetObject(this.equipos);


      }else if (estado == 0) {
        this.modal = 1;
        this.tituloModal = 'Actualizar equipo calibración';
        this.tipoAccion = 2;
        Utilerias.resetObject(this.equipos);
        this.equipos.id = data['equipos']['id'];
        this.equipos.descripcion = data['equipos']['descripcion'];
        this.equipos.marca = data['equipos']['marca'];
        this.equipos.modelo = data['equipos']['modelo'];
        this.equipos.numero_serie = data['equipos']['numero_serie'];
        this.equipos.rango_medicion = data['equipos']['rango_medicion'];
        this.equipos.frecuencia = data['equipos']['frecuencia'];
        this.equipos.resguardo = data['equipos']['resguardo'];
        this.equipos.observaciones = data['equipos']['observaciones'];
        this.equipos.fecha_servicio = data['servicios']['fecha_servicio'];
        this.equipos.proxima_fecha = data['servicios']['proxima_fecha'];
        this.equipos.servicio_id = data['servicios']['id'];
      }

    },

    cerrarModal(){
      this.modal = 0;
    },

    guardar(nuevo){
      this.$validator.validate().then(result => {
        if (result) {

          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? 'guardar/calibracion/equipos' : 'actualizar/calibracion/equipos',
            data: {
              id : this.equipos.id,
              descripcion : this.equipos.descripcion,
              marca : this.equipos.marca,
              modelo : this.equipos.modelo,
              numero_serie : this.equipos.numero_serie,
              rango_medicion : this.equipos.rango_medicion,
              frecuencia : this.equipos.frecuencia,
              resguardo : this.equipos.resguardo,
              observaciones : this.equipos.observaciones,
              fecha_servicio : this.equipos.fecha_servicio,
              proxima_fecha : this.equipos.proxima_fecha,
              servicio_id : this.equipos.servicio_id,
            }
          }).then(result => {
            if(result.data.status)
            {
              this.getData();
              toastr.success('Correcto');
            }
            else
            {
              toastr.error(result.data.mensaje);
            }
            this.cerrarModal();
          }).catch(e => {
            console.error(e);
          });
        }
      });
    },

    cambiarFecha(){
  var TuFecha = new Date(this.equipos.fecha_servicio);

  if (this.equipos.frecuencia == 1) {//anual
    var dias = parseInt(365);
  }else if (this.equipos.frecuencia == 3) {//Semestral
    var dias = parseInt(182);
  }else if (this.equipos.frecuencia == 4) {//Bimestral
    var dias = parseInt(60);
  }else if (this.equipos.frecuencia == 5) {//Mensual
    var dias = parseInt(30);
  }

  //nueva fecha sumada
  TuFecha.setDate(TuFecha.getDate() + dias);
  //formato de salida para la fecha
  this.equipos.proxima_fecha = TuFecha.getFullYear() + '-'
  + this.str_pad((TuFecha.getMonth() + 1), 2 , '0', 'STR_PAD_LEFT') + '-'
  + this.str_pad((TuFecha.getDate() + 1), 2 , '0' , 'STR_PAD_LEFT');

  },

   str_pad(str, pad_length, pad_string, pad_type){
     console.log(String(str).length);
  	var len = pad_length - String(str).length;

  	if(len < 0) return str;

  	var pad = new Array(len + 1).join(pad_string);

  	if(pad_type == "STR_PAD_LEFT") return pad + str;

  	return str + pad;

  },

  vaciar(){
    this.equipos.fecha_servicio = '';
    this.equipos.proxima_fecha = '';
  }

  },
  mounted(){
    this.getData();
  }
}


</script>

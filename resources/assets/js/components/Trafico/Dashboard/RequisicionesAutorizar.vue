<template>
  <div>

    <div class="card border-dark">
      <div class="card-header bg-dark text-white">
        <i class="fa fa-align-justify"> </i> Requisiciones por autorizar:
        <button v-if="detalles_ver" type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
          <i class="icon-arrow-left"></i>&nbsp;Atras
        </button>
      </div>
      <div class="card-body">
        <v-client-table :columns="columns" :data="dataTable" :options="options" ref="myTable" v-show="!detalle">
          <vue-element-loading :active="isLoading"/>
          <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i> Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button type="button" @click="cargardetalle(props.row.id)" class="dropdown-item" >
                    <i class="fas fa-eye"></i>&nbsp; Ver partidas
                  </button>
                </div>
              </div>
            </div>
          </template>
          <template slot="condicion" slot-scope="props" >
            <button class="btn btn-sm btn-success" @click="autorizar(2, props.row.id)"><i class="far fa-check-square"></i>&nbsp; Si&nbsp;&nbsp;</button>
            <button v-show="props.row.condicion == 1" class="btn btn-sm btn-danger" @click="autorizar(0, props.row.id)"><i class="fas fa-window-close"></i>&nbsp; No</button>
          </template>


        </v-client-table>

        <div v-show="detalles_ver">
          <v-client-table :data="dataTableDetalle" :options="optionsDetalle" :columns="columnsDetalle">
            <template slot="req.id" slot-scope="props">

            </template>

          </v-client-table>

        </div>
      </div>


    </div>
  </div>
</div>

</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data() {
    return {
      isLoading: false,
      solicitudes: [],
      detalle: false,
      nuevo : null,
      solicitud: [],
      detalles_ver : false,
      detalles_solicitudes : null,
      tipo_cambio : 0,
      moneda : 0,
      columns : ['id','folio','nombrep','fecha_solicitud','nombre_solicita','descripcion_uso','condicion'],
      dataTable : [],
      options: {
        headings: {
          'id' : 'Acciones',
          'articulo_descripcion' : 'Artículo',
          'nombrep' : 'Proyecto',
          'nombre_solicita': 'Empleado que solicita',
          'descripcion_uso': 'Uso',
          'condicion' : 'Validar',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts,
      },
      dataTableDetalle : [],
      columnsDetalle : ['req.descripcion','req.cantidades','req.unidad_articulo','req.comentario_partida','req.frequerido'],
      optionsDetalle : {
        headings: {
          'req.descripcion' : 'Articulo',
          'req.cantidades' : 'Cantidad',
          'req.unidad_articulo' : 'Unidad',
          'req.comentario_partida': 'Comentario',
          'req.frequerido': 'Fecha requerida',
          'req.id' : '',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts,
      },
    }
  },
  methods: {
    getData() {
      this.isLoading = true;
      let me=this;
      axios.get('/requisicionesporautorizar').then(response => {
        me.solicitudes= response.data;
        me.dataTable = response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    cargarequisicion() {
      this.getData();
    },
    cargardetalle(id)
    {
      this.isLoading = true;
      this.detalles_ver = true;
      this.detalle = true;
      let me = this;
      axios.get('/consultadashp/' + id ).then(response => {
        me.detalles_solicitudes = response.data;
        me.dataTableDetalle = response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });

    },
    autorizar(estado , id)
    {
      let me = this;
      axios.post('/autorizarequisicionproyectos',{
        id : id,
        estado : estado,
      }).then(function (response){
        if (response.data == 2) {
          toastr.success('Correcto','Requisicion autorizada !!!');
        }
        else if (response.data == 0) {
          toastr.warning('Atención','Requisicion no autorizada !!!');
        }
        me.getData();
      }).catch(function (error){
        console.error(error);
      });
    },
    maestro(){
      this.detalles_ver = false;
      this.detalle = false;
      this.detalles_solicitudes = null;

    }
  },
  mounted() {
  }
}
</script>

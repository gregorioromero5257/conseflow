<template>
  <main class="main">
    <div class="container-fluid">
      <!-- detalle 1 listado de proyectos -->
      <div class="card" v-show="detalle == 1">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Proyectos

        </div>
        <div class="card-body">
          <v-client-table ref="myTable" :columns="columns_p" :data="tableData_p" :options="options_p">
            <template slot="condicion" slot-scope="props" >
              <template v-if="props.row.condicion == 1">
                <button type="button" class="btn btn-outline-success">Activo</button>
              </template>
              <template v-if="props.row.condicion == 0">
                <button type="button" class="btn btn-outline-danger">Terminado</button>
              </template>
              <template v-if="props.row.condicion == 2">
                <button type="button" class="btn btn-outline-warning">Pausa</button>
              </template>
              <template v-if="props.row.condicion == 3">
                <button type="button" class="btn btn-outline-dark">Rechazado</button>
              </template>
            </template>
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                    <button type="button" @click="verrequisiciones(props.row.id)"
                    class="dropdown-item" >
                    <i class="fas fa-eye"></i>&nbsp; Ver requisiciones
                  </button>

                </div>
              </div>
            </div>

          </template>
        </v-client-table>
      </div>
    </div>
    <!-- fin de detalle 1 listado de proyectos -->
    <!-- detalle 2 -->
    <div class="card" v-show="detalle == 2">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Requisiciones
        <button type="button" @click="maestro_uno()" class="btn btn-secondary float-sm-right">
          <i class="icon-arrow-left"></i>&nbsp;Atras
        </button>
      </div>
      <div class="card-body">
        <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">
          <template slot="requisicion.id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <i class="fas fa-grip-horizontal"></i>&nbsp;Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <template >
                    <a  class="dropdown-item" @click="verRequisiciones(props.row)" href="#">
                      <i class="fas fa-file-pdf"></i>&nbsp;Descargar Formato de Requisición
                    </a>
                  </template>
                </div>
              </div>
            </div>
          </template>
          <template slot="requisicion.estado_id" slot-scope="props">
            <template v-if="props.row.requisicion.estado_id == 0">
              <button type="button" class="btn btn-outline-info"><i class="fa fa-files-o"></i>&nbsp;Nuevo</button>
            </template>
            <template v-if="props.row.requisicion.estado_id == 1">
              <button type="button" class="btn btn-outline-warning"><i class="fa fa-exclamation-circle"></i>&nbsp;En espera (Validación)</button>
            </template>
            <template v-if="props.row.requisicion.estado_id == 2">
              <button type="button" class="btn btn-outline-warning"><i class="fa fa-exclamation-triangle"></i>&nbsp;En espera (Autorización)</button>
            </template>
            <template v-if="props.row.requisicion.estado_id == 3 || props.row.requisicion.estado_id == 6">
              <button type="button" class="btn btn-outline-success"><i class="fa fa-check-circle"></i>&nbsp;Autorizado</button>
            </template>
            <template v-if="props.row.requisicion.estado_id == 4">
              <button type="button" class="btn btn-outline-danger"><i class="fa fa-times"></i>&nbsp;No autorizado</button>
            </template>
            <template v-if="props.row.requisicion.estado_id == 5">
              <button type="button" class="btn btn-outline-primary"><i class="fa fa-caret-square-o-left"></i>&nbsp;Recibido</button>
            </template>
            <template v-if="props.row.requisicion.estado_id == 7">
              <button type="button" class="btn btn-outline-primary"><i class="fa fa-caret-square-o-left"></i>&nbsp;Por Equivalente</button>
            </template>
            <template v-if="props.row.requisicion.estado_id == 8">
              <button type="button" class="btn btn-outline-primary"><i class="fa fa-caret-square-o-left"></i>&nbsp;En Espera (Calidad)</button>
            </template>
            <template v-if="props.row.requisicion.estado_id == 9">
              <button type="button" class="btn btn-outline-primary"><i class="fa fa-caret-square-o-left"></i>&nbsp;Cambiado/Equivalente</button>
            </template>
          </template>
        </v-client-table>
      </div>

    </div>
    <!-- fin detalle 2 -->
    <!-- detalles 3  -->
    <div class="card" v-show="detalle == 3">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> {{datos_r == null ? '' : datos_r.requisicion.folio}}
        <button type="button" @click="maestro_dos()" class="btn btn-secondary float-sm-right">
          <i class="icon-arrow-left"></i>&nbsp;Atras
        </button>
      </div>
      <div class="card-body">
        <v-client-table :columns="columns_r" :data="tableData_r" :options="options_r">
          <template slot="condicion" slot-scope="props">
            <template v-if="props.row.condicion == 0">
              <button type="button" class="btn btn-outline-success"><i class="fa fa-files-o"></i>&nbsp;Comprado</button>
            </template>
            <template v-if="props.row.condicion == 2">
              <button type="button" class="btn btn-outline-warning"><i class="fa fa-files-o"></i>&nbsp;Cancelado</button>
            </template>
          </template>
        </v-client-table>
      </div>
    </div>
    <!-- fin de detalle 3 -->
  </div>
</main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      datos_r : null,
      detalle : 1,
      columns : ['requisicion.id','requisicion.folio','requisicion.nombrep','requisicion.fecha_solicitud','requisicion.descripcion_uso','requisicion.estado_id'],
      tableData : [],
      options : {
        headings: {
          'requisicion.id': 'Acción',
          'requisicion.folio': 'Folio',
          'requisicion.nombrep': 'Nombre proyecto',
          'requisicion.fecha_solicitud': 'Fecha solicitud',
          'requisicion.estado_id' :'Estado',
          'requisicion.descripcion_uso' : 'Descripción',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['requisicion.folio','requisicion.nombrep','requisicion.fecha_solicitud','requisicion.descripcion_uso','requisicion.estado_id'],
        filterable: ['requisicion.folio','requisicion.nombrep','requisicion.fecha_solicitud','requisicion.descripcion_uso','requisicion.estado_id'],
        filterByColumn: true,
        texts:config.texts
      },
      columns_p: ['id','nombre','nombre_corto','clave','ciudad','fecha_inicio','fecha_fin','condicion' ],
      tableData_p: [],
      options_p: {
        headings: {
          'id': 'Acciones',
          'nombre': 'Nombre',
          'nombre_corto': 'Nombre corto',
          'clave': 'Ord Comp',
          'ciudad': 'Ciudad',
          'fecha_inicio': 'F. Inicio',
          'fecha_fin': 'F. Fin',
          'condicion':'Condición',
          'updated_at' : 'Ultima Actualización',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['nombre','nombre_corto','clave','ciudad','fecha_inicio','fecha_fin','condicion'],
        filterable: ['nombre','nombre_corto','clave','ciudad','fecha_inicio','fecha_fin','condicion'],
        filterByColumn: true,
        texts:config.texts
      },
      columns_r : ['nombre','cantidad','condicion'],
      tableData_r : [],
      options_r : {
        headings: {

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['nombre','cantidad','condicion'],
        filterable: ['nombre','cantidad','condicion'],
        filterByColumn: true,
        texts:config.texts
      },
    }
  },
  methods:{
    getData(){
      let me = this;
      axios.get('/proyecto-listar').then(response => {
        me.tableData_p = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });

    },
    verrequisiciones(id){
      this.detalle = 2;
      let me = this;
      axios.get('/requisitadocomprado/'+ id).then(response => {
        me.tableData = response.data;
        console.log(response.data);
      }).catch(error => {
        console.error(error);
      });
    },

    maestro_uno(){
      this.detalle = 1;
    },

    maestro_dos(){
      this.detalle = 2;
    },

    verRequisiciones(data){
      console.log(data);
      this.datos_r = data;
      this.detalle = 3;
      axios.get('/compradoscancelados/'+data.requisicion.id).then(response => {
        console.log(response.data);
        this.tableData_r = response.data;
      }).catch(error => {
        console.error(error);
      });
    }
  },
  mounted(){
    this.getData();
  }
}
</script>

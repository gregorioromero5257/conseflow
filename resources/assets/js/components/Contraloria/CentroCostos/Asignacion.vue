<template>
  <main class="main">
    <div class="container-fluid">
      <!-- {{proyecto_id}} -->
      <!-- PRoyectos -->
      <div class="card" v-show="detalle == 1">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Proyectos
        </div>
        <div class="card-body">
          <v-server-table ref="myTable" :columns="columns" url="proyectos/buscar/centrocostos" :options="options">
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

                  <button type="button" @click="vercentrocostos(props.row.id)"
                   class="dropdown-item" >
                      <i class="fas fa-eye"></i>&nbsp;Centro Costos
                  </button>

              </div>
              </div>
              </div>

              </template>
          </v-server-table>
        </div>
      </div>

      <!-- CentroCostos de costo -->
      <div class="card" v-show="detalle == 2">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Centro Costos
          <button type="button" @click="maestroCentro()" class="btn btn-secondary float-sm-right" >
            <i class="fas fa-arrow-left"></i>&nbsp;Atras
          </button>

        </div>
        <div class="card-body">
          <v-client-table ref="myTable" :columns="columnsCentro" :data="tableData" :options="optionsCentro">
            <template slot="estado" slot-scope="props" >
                <template v-if="props.row.estado == 0">
                <button type="button" class="btn btn-outline-danger">Desactivado</button>
                </template>
                <template v-if="props.row.estado == 1">
                 <button type="button" class="btn btn-outline-success">Activo</button>
                </template>
            </template>
              <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <button  type="button" class="dropdown-item" @click="asignar(props.row)">
                    <i class="icon-plus"></i>&nbsp; Asignar
                </button>
                <button  type="button" class="dropdown-item" @click="verpartidas(props.row)" >
                    <i class="icon-eye"></i>&nbsp; Ver partidas
                </button>
              </div>

              </div>
              </div>
              </template>
          </v-client-table>
        </div>
      </div>
      <!-- fin de centrocostos -->

      <!-- inicio de partidas -->
      <div class="card" v-show="detalle == 3">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Partidas
          <button type="button" @click="maestropartidas()" class="btn btn-secondary float-sm-right" >
            <i class="fas fa-arrow-left"></i>&nbsp;Atras
          </button>

        </div>
        <div class="card-body">
          <v-client-table ref="myTable" :columns="columnsPartidas" :data="tableDataPartidas" :options="optionsPartidas">
              <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
              <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
              </button>
              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <button  type="button" class="dropdown-item" @click="eliminar(props.row.id)">
                    <i class="icon-trash"></i>&nbsp; Eliminar
                </button>
                <!-- <button  type="button" class="dropdown-item" >
                    <i class="icon-eye"></i>&nbsp; Ver partidas
                </button> -->
              </div>

              </div>
              </div>
              </template>
          </v-client-table>
        </div>
      </div>
      <!-- fin de partidas -->

      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div>

              <div class="modal-header">
                <h4 class="modal-title">Asignar partidas a centro de costos :<br><h5>{{data == null ? '' : data.nombre}}</h5></h4>
                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <select class="form-control" v-model="partida_costos_id"  name="">
                  <option v-for="t of partidas_costos"  :key="t.id" :value="t.id">{{t.nombre}}</option>
                </select>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-ouline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                <button type="button" class="btn btn-secondary" @click="guardarasignacion(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal-->

    </div>
  </main>
</template>
<script>

import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data(){
    return{
      listaProyecto : [],//
      detalle : 1,
      modal : 0,
      proyecto_id : 0,
      catalogo_centro_costos_id : 0,
      partida_costos_id : 0,
      data : null,
      catalogo : null,
      partidas_costos : [],
      columns: ['id','nombre','nombre_corto','clave','ciudad','fecha_inicio','fecha_fin','condicion' ],
      options: {
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
      columnsCentro: ['id','nombre','nombre_sub','estado'],
      tableData: [],
      optionsCentro: {
          headings: {
              'id': 'Acciones',
              'nombre_sub' : 'Pertenece',

          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: ['nombre','nombre_sub'],
          filterable: ['nombre','nombre_sub'  ],
          filterByColumn: true,
          listColumns: {
            'estado': [{
                id: 1,
                text: 'Activo'
            },
            {
                id: 0,
                text: 'Desactivado'
            },
            ]
          },
          texts:config.texts
      },
      columnsPartidas: ['id','nombre'],
      tableDataPartidas: [],
      optionsPartidas: {
          headings: {
              'id': 'Acciones',
          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: ['nombre'],
          filterable: ['nombre'],
          filterByColumn: true,
          texts:config.texts
      },
    }
  },
  methods : {
      vercentrocostos(id){
        let me=this;
        me.proyecto_id = id;
        me.detalle = 2;

        axios.get('catalogos/centrocostos').then(response => {
          var data = [];
          response.data.forEach((item, i) => {
            if(item.estado == 1){
              data.push(item);
            }
          });
          me.tableData = data;
        }).catch(error => {
          console.error(error);
        });


        this.partidasCostos();
      },

      partidasCostos(){
        let me = this;
        axios.get(`/partidascostos/${me.proyecto_id}`).then(response => {
          var partidas_costos = [];
          response.data.forEach((item, i) => {
            if (item.asignado == 0) {
              partidas_costos.push({id: item.id, nombre : item.nombre});
            }
          });

            me.partidas_costos = partidas_costos;
        })
        .catch(function (error) {
            console.log(error);
        });
      },

      verpartidas(data){
        this.catalogo = data;
        axios.get(`centrocostos/ver/${data.id}&${this.proyecto_id}`).then(response =>{
          this.tableDataPartidas = response.data;
        }).catch(error => {
          console.error(error);
        });
        this.detalle = 3;
      },

      maestroCentro(){
        this.detalle = 1;
      },

      maestropartidas(){
        this.detalle = 2;
      },

      asignar(data){
        this.modal = 1;
        this.data = data;
      },

      cerrarModal(){
        this.modal = 0;
      },

      guardarasignacion(){
        axios({
          method: 'POST',
          url: 'centrocostos/asignar',
          data: {
              'proyecto_id': this.proyecto_id,
              'catalogo_centro_costos_id' : this.data.id,
              'partida_costos_id' : this.partida_costos_id,
          }
        }).then(result =>{
          this.modal = 0;
          this.partidasCostos();
          toastr.success('Asignado correctamente','success');
        }).catch(error => {
          console.error(error);
        });
      },

      eliminar(id){
        axios.get('centrocostos/eliminar/'+id).then(response => {
          axios.get(`centrocostos/ver/${this.catalogo.id}&${this.proyecto_id}`).then(response =>{
            this.tableDataPartidas = response.data;
          }).catch(error => {
            console.error(error);
          });
          this.partidasCostos();
          toastr.success('Eliminado correctamente','success');
        }).catch(error => {
          console.error(error);
        });
      },


  },
  mounted(){
  }
}

</script>

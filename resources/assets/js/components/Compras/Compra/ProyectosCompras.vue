<template >

  <main class="main">
  <div class="container-fliud">

    <div class="card" v-if="detalle == 1">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Compras
        <button type="button" @click="abrirModal('compra','registrar')" class="btn btn-dark float-sm-right">
          <i class="icon-plus"></i>&nbsp;Nuevo
        </button>
      </div>
        <div class="card-body">
            <v-server-table ref="myTable" :columns="columns" url="/proyectos/buscarcompras" :options="options">
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

                    <button type="button" @click="verdetalles(props.row)"
                     class="dropdown-item" >
                        <i class="fas fa-eye"></i>&nbsp; Ver ordenes de compra
                    </button>

                </div>
                </div>
                </div>

                </template>
            </v-server-table>
        </div>
    </div>

    <div v-show="detalle == 2">
      <compras ref="compras" @compras:maestro="maestrob"></compras>
    </div>
    <div v-show="detalle == 3">
        <cardprincipal ref="cardprincipal" @cardprincipal:click="cerrar($event)"
         @cardprincipalproceso:click="cerrardos($event)"
         @cardprincipalprocesodos:click="cerrartres($event)"
         @cardprincipalcerrarmodal:click="cerrarModal()"></cardprincipal>
    </div>

   <div v-show="detalle == 4">
  <partidas ref="partidas" @partidas:click="maestro($event)"></partidas>
</div>

<div v-show="detalle == 5">
<partidasdos ref="partidasdos" @partidas:click="maestro($event)"></partidasdos>
</div>

  </div>
  </main>

</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const Compras = r => require.ensure([], () => r(require('./Compras.vue')), 'compras');
const CardPrincipal = r => require.ensure([], () => r(require('./CardPrincipal.vue')), 'compras');
const Partidas = r => require.ensure([], () => r(require('./Partidas.vue')), 'compras');
const PartidasDos = r => require.ensure([], () => r(require('./PartidasDos.vue')), 'compras');

export default {
  data(){
    return {
      detalle : 1,
      detalles : false,
      cardprincipal : false,
      columns: ['id','nombre_corto','ciudad','fecha_inicio','fecha_fin','condicion' ],
      tableData: [],
      options: {
          headings: {
              'id': 'Acciones',
              'nombre': 'Nombre',
              'nombre_corto': 'Nombre corto',
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
          sortable: ['nombre','nombre_corto','ciudad','fecha_inicio','fecha_fin','condicion'],
          filterable: ['nombre','nombre_corto','ciudad','fecha_inicio','fecha_fin','condicion'],
          filterByColumn: true,
          texts:config.texts
      },

    }
  },
  components: {
    'compras' : Compras,
    'cardprincipal': CardPrincipal,
     'partidas' : Partidas,
      'partidasdos' : PartidasDos,

  },
  methods : {
    getData(){
      // let me = this;
      // axios.get('/proyecto-listar').then(response => {
      //     me.tableData = response.data;
      // })
      // .catch(function (error) {
      //     console.log(error);
      // });
    },

    verdetalles(data){
      this.detalle = 2;
      var childCompras = this.$refs.compras;
      childCompras.getData(data.id);
    },

    maestro(data){
       this.detalle = 2;
      var childCompras = this.$refs.compras;
      childCompras.getData(data);
    },

     maestrob(){
      this.detalle = 1;

    },

    /**
     * [abrirModal description]
     * @param  {[String]} modelo    [description]
     * @param  {[String]} accion    [description]
     * @param  {Array}  [data=[]] [description]
     * @return {}           [description]
     */
      abrirModal(modelo, accion, data = []){
        let me = this;
        this.detalle = 3;
        var childcardprincipal = this.$refs.cardprincipal;
        childcardprincipal.cargarcardprincipal(modelo, accion, data, me.tableData);
      },

      cerrar(data){
        this.detalle = 2;
      },

      cerrarModal(){
        this.detalle = 1;
      },
      //no hay requisicion
      cerrardos(data){
      let me = this;
      var childpartidas = this.$refs.partidas;
      childpartidas.cargarpartidas(data);
      this.detalle = 4;
      },

      //tiene requisicion
      cerrartres(data){
      let me = this;
      //me.widgets.partidas = true;
      var childpartidasdos = this.$refs.partidasdos;
      childpartidasdos.cargarpartidas(data);
      this.detalle = 5;
      }


  },
  mounted() {
// this.getData();
  }
}
</script>

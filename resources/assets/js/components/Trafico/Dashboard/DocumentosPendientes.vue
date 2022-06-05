<template>

  <div class="card border-dark" >
    <div class="card-header bg-dark text-white">
      <i class="fa fa-align-justify"> </i> Requisiciones con documentación pendiente  :
      <button v-if="detalle" type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
        <i class="icon-arrow-left"></i>&nbsp;Atras
      </button>
    </div>

    <div class="card-body">
      <div v-show="!detalle">

        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
          <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i> Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button type="button" @click="cargardetalle(props.row)" class="dropdown-item" >
                    <i class="fas fa-eye"></i>&nbsp; Ver Detalles
                  </button>
                </div>
              </div>
            </div>
          </template>
        </v-client-table>

      </div>
      <div v-show="detalle">
        <table class="VueTables__table table table-hover table-sm" >
          <thead class="table-light">
            <tr>
              <th scope="col">Artículo/Servicio</th>
              <th scope="col">Total de documentos pendientes</th>
              <th scope="col">Documentos</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in partida" :value="item.req.id" >
              <td>{{item.req.descripcion}}</td>
              <td>{{item.doc.length}}</td>
              <td>
                <select class="form-control">
                  <option v-for="option in item.doc" >
                    {{ option.nombre }}
                  </option>
                </select>
              </td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>

  </div>




</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return {
      tableData : [],
      partida : [],
      detalle : false,
      datos : null,
      columns : ['id','folio','fecha_solicitud','descripcion_uso','nombre_proyecto'],
      options: {
        headings: {
          'id' : 'Acciones',
          'articulo_descripcion' : 'Artículo',
          'proyecto_nombre' : 'Proyecto',
          'cantidad': 'Cantidad',
          'precio_unitario': 'Precio Unitario',
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
  methods : {
    cargardocumentos(){
      let me = this;
      axios.get('/requisicionesdocumentospendientes').then(response => {
        me.tableData = response.data;
      }).catch(error => {
        console.error(error);
      });
    },

    cargardetalle(data){
      this.detalle = true;
      let me= this;
      axios.get('/documentosdashproyectos/'+ data.id).then(response => {
        me.datos = response.data;
        me.partida = response.data;
      }).catch(error => {
        console.error(error);
      });

    },

    maestro(){
      this.detalle= false;
    }

  },
  mounted(){

  }
}
</script>

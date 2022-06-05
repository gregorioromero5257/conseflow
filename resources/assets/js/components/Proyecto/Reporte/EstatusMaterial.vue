<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Estatus de material

        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label class="form-control-label" for="proyecto_id">Seleccionar:</label>
              <v-select :options="listaProyectos"  v-validate="'required'" v-model="proyecto_id" label="name" name="proyecto" data-vv-name="Proyecto" ></v-select> <!---->
              <span class="text-danger">{{ errors.first('Proyecto') }}</span>
            </div>
            <div class="form-group col-md-2">
              <label>&nbsp;</label>
              <button type="button" class="btn btn-primary btn-block" @click="buscarExistencias()">
                Buscar
              </button>
            </div>
            <div class="form-group col-md-2">
              <label>&nbsp;</label>
              <button type="button" class="btn btn-success btn-block" @click="descargar()">
                Descargar
              </button>
            </div>
          </div>
          <v-client-table :data="tableData" :options="options" :columns="columns"></v-client-table>
        </div>
      </div>

    </div>
  </main>
</template>
<script>

import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data(){
    return{
      listaProyectos : [],
      proyecto_id : '',
      columns: [
          'nombre','folio_requisicion','descripcion','cantidad','partida_orden_compra_cantidad','folio_orden_compra','fecha_orden','periodo_entrega'
      ],
      tableData: [],
      options: {
          headings: {
              'nombre': 'Tipo de material',
              'folio_requisicion' : 'Requisición',
              'folio_orden_compra':'Orden de compŕa',
              'periodo_entrega' : 'Tiempo de entrega',
              'cantidad' : 'Cant. Requisitada',
              'partida_orden_compra_cantidad' : 'Cant. Procurada',

          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: [
              'nombre','folio','descripcion','cantidad'
              ],
          filterable: [
                'nombre','folio','descripcion','cantidad'
          ],
          filterByColumn: true,
          listColumns: { },
          texts:config.texts
      },
    }
  },

  methods :{
    getData() {
      let me=this;
      axios.get('/proyecto-todos').then(response => {
        response.data.forEach((item, i) => {
          this.listaProyectos.push({
            id: item.proyecto.id,
            name : item.proyecto.nombre_corto
          });
        });
      })
      .catch(function (error) {
        console.log(error);
      });

    },

    buscarExistencias(){
      this.$validator.validate().then(result => {
        if (result) {
          axios.get('estatusmaterial/' + this.proyecto_id.id).then(response => {
            this.tableData = response.data;
          }).catch(err => {
            console.error(err);
          })
        }
      });
    },

    descargar(){
      window.open(`descargar-excel-comprado-requisitado/${this.proyecto_id.id}` , '_blank');
    },


  },
  mounted(){
    this.getData();
  }
}

</script>

<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          Trazabilidad
        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label >Proyecto</label>
              <v-select multiple id="proyectos" label="name"
              :options="listaProyectos" v-model="proyecto">
            </v-select>
            </div>
            <div class="form-group col-md-4">
            <label >Proveedor</label>
              <v-select multiple id="proyectos" label="name"
              :options="listaProveedores" v-model="proveedor" >
            </v-select>
            </div>
            <div class="form-group col-md-1">
              <label>&nbsp;</label><br>
              <button class="btn btn-dark" @click="listarCompras()">Buscar</button>
            </div>
            <div class="form-group col-md-1">
              <label>&nbsp;</label><br>
            <button class="btn btn-success" @click="generarExcel()">Exportar</button>
            </div>

          </div>

      <!-- {{proyecto}} -->


      <v-client-table :options="options" :columns="columns" :data="tableData">
      </v-client-table>
    </div>
  </div>


    </div>
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default{
  data(){
    return{
      listaProyectos : [],
      listaProveedores : [],
      proyecto : '',
      proveedor : '',
      tableData : [],
      columns :[],
      options : {
        headings: {

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['oc.folio'],
        filterable: ['oc.folio'],
        filterByColumn: true,
        texts:config.texts
      },
    }
  },
  watch: {
    proyecto(nuevoValor, valorAnterior){
      if (nuevoValor != '') {
        this.proveedor = '';
      }
    },
    proveedor(nuevoValor, valorAnterior){
      if (nuevoValor != '') {
        this.proyecto = '';
      }
    },
  },
  methods: {
    getData(){
      let me = this;
      axios.get('/proyecto-listar').then(response => {
        this.listaProyectos = [];
        response.data.forEach((item, i) => {
          this.listaProyectos.push({
            id : item.id,
            name : item.nombre_corto
          });
        });
      })
      .catch(function (error) {
        console.log(error);
      });
      axios.get('/proveedores').then(response => {
        this.listaProveedores = [];
        response.data.forEach((item, i) => {
          this.listaProveedores.push({
            id : item.id,
            name : item.razon_social + ' ' + item.nombre
          });
        });
      })
      .catch(function (error) {
        console.log(error);
      });


    },

    generarExcel(){
      if (this.proyecto == '' && this.proveedor == '') {
        toastr.warning('Seleccione un proyecto o proveedor');
      }
      else {
        var tipo = this.proyecto == '' ? 'Proveedor' : 'Proyecto';
        var ids = '';
        if (this.proyecto != '') {
          this.proyecto.map(function (value) {
            ids += value.id + '&';
          });
        }else if (this.proveedor != '') {
          this.proveedor.map(function (value) {
            ids += value.id + '&';
          });
        }
        // window.open('descargar-excel-rh/'+this.date_one+'&'+this.date_two, '_blank');
        window.open(`descargar/trazabilidad/almacen/${tipo}&${ids}` , '_blank');
      }

    },

  },
  mounted(){
    this.getData();
  }
}
</script>

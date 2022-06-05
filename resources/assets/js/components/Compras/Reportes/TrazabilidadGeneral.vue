<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          Trazabilidad
        </div>
        <div class="card-body">
          <div class="form-group row">
            <label class="col-md-1 form-control-label" for="tipo_partida"><br>Proyecto</label>
            <div class="col-md-4">
              <label>&nbsp;</label>
              <v-select multiple id="proyectos" label="name"
              :options="listaProyectos" v-model="proyecto">
            </v-select>
          </div>
          <label class="col-md-1 form-control-label" for="tipo_partida"><br>Proveedor</label>
          <div class="col-md-4">
            <label>&nbsp;</label>
            <v-select multiple id="proyectos" label="name"
            :options="listaProveedores" v-model="proveedor" >
          </v-select>
        </div>
          <div class="col-md-1"><br><button class="btn btn-dark" @click="listarCompras()">Buscar</button></div>
          <div class="col-md-1"><br><button class="btn btn-success" @click="generarExcel()">Exportar</button></div>
          <!-- {{proyecto}} -->
        </div>

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
export default {
  data(){
    return{
      datos_r : null,
      listaProyectos : [],
      listaProveedores : [],
      columns : ['oc.fecha_orden','oc.folio','oc.total','oc.moneda','oc.nombre_corto','factura',
      'oc.razon_social','total_factura','diferencia_costos','procentaje_entrada',
      'porcentaje_salidas','tipo_cambio','pago_mn','porcentaje_pago'],
      tableData : [],
      proyecto : '',
      proveedor : '',
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
  methods:{
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

    listarCompras(){
      if (this.proyecto == '' && this.proveedor == '') {
        toastr.warning('Seleccione un proyecto o proveedor');
      }else {
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
        axios.get(`trazabilidadcompras/${tipo}&${ids}`).then(response => {
          this.tableData = response.data;
          console.log(response);
        }).catch(error => {
          console.error(error);
        });
      }
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
        window.open(`descargar-excel-trazabilidadcomprasgeneral/${tipo}&${ids}` , '_blank');
      }

    },


  },
  mounted(){
    this.getData();
  }
}
</script>

<template >
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <label>Materiales</label>
          <button class="btn btn-success float-sm-right" @click="descargarExcel()">Descargar</button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>
          <div class="form-row">
            <div class="col-md-6 mb-3">
              <label>Proyecto</label>
              <v-select :options="proyectos_empresa"  v-model="proyecto" label="name" name="proyecto"
               data-vv-name="proyecto" @input="buscar()" ></v-select> <!---->
            </div>
          </div>
          <v-client-table :data="tableData" :options="options" :columns="columns">
            <template slot="salida.id" slot-scope="props">
              <button class="btn btn-outline-success" @click="asociarFactura(props.row)">Asociar</button>
            </template>
            <template slot="clave_prod_serv" slot-scope="props">

              <template v-if="props.row.factura == null">
                <span class="text-danger">No hay Factura</span>
              </template>
              <template v-else>
              {{props.row.factura.claveprodserv}}
              </template>
            </template>
            <template slot="descripcion" slot-scope="props">
              <template v-if="props.row.factura == null">
                <template v-if="props.row.comentario == null">
                  {{props.row.salida.descripcion}}
                </template>
                <template v-else>
                  {{props.row.comentario}}
                </template>
              </template>
              <template v-else>
              {{props.row.factura.descripcion}}
              </template>
            </template>
            <template slot="clave_unidad" slot-scope="props">
              <template v-if="props.row.factura == null">
                <span class="text-danger">No hay Factura</span>
              </template>
              <template v-else>
              {{props.row.factura.claveunidad}}
              </template>
            </template>

            <template slot="valorunitario" slot-scope="props">
              <template v-if="props.row.factura == null">
                <span class="text-danger">No hay Factura</span>
              </template>
              <template v-else>
              {{props.row.factura.valorunitario}}
              </template>
            </template>

            <template slot="importe" slot-scope="props">
              <template v-if="props.row.factura == null">
                <span class="text-danger">No hay Factura</span>
              </template>
              <template v-else>
              {{props.row.factura.importe}}
              </template>
            </template>


          </v-client-table>

        </div>
      </div>

    </div>
  </main>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default{
  data(){
    return {
      isLoading : false,
      proyectos_empresa : [],
      proyecto : '',
      tableData : [],
      // 'id','nombre_corto','folio','claveprodserv','descripcion','claveunidad','unidad','cantidad','valorunitario','importe'
      columns : ['salida.id','proyecto_origen','orden_compra','clave_prod_serv','descripcion','clave_unidad',
      'salida.cantidad_salida','valorunitario','importe'],
      options: {
        headings: {
          'salida.id' : 'Acciones',
          'clave_prod_serv' : 'Clave SAT',
          'salida.cantidad_salida' : 'Cantidad',
          'valorunitario' : 'Valor unitario'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },

    }
  },
  methods : {
    getData(){
      this.proyectos_empresa = [];
      axios.get('obtener/proyectos').then(response => {
        this.proyectos_empresa = response.data;
      }).catch(e => {
        console.log(e);
      });
    },

    buscar(){
      this.isLoading = true;
      axios.get('tesoreria/get/articulos/facturas/' + this.proyecto.id).then(response => {
        this.tableData = response.data;
        this.isLoading = false;
      }).catch(e => {
        this.isLoading = false;
        console.error(e);
      });
    },

    asociarFactura(data){
      console.log(data);
      Swal.mixin({
        input: 'text',
        confirmButtonText: 'Siguiente &rarr;',
        showCancelButton: true,
        progressSteps: ['1', '2', '3','4']
      }).queue([
        {
          title: 'Factura',
          text: 'Escriba el folio de factura a asociar'
        },
        {
          title: 'Cantidad',
          text: 'Escriba la cantidad a facturar',
          inputValue  : data.cantidad
        },
        {
          title: 'Precio Venta',
          text: 'Escriba el precio de venta',
        },
        {
          title: 'Empresa',
          text: 'CSCT - CONSERFLOW'
        },
      ]).then((result) => {
        if (result.value) {
            console.log(result.value);
        }
      });

    },

    descargarExcel(){
      window.open("descargar/salidas/materiales/" +this.proyecto.id ,"_blank");
    },

  },
  mounted(){
    this.getData();
  }
}

</script>

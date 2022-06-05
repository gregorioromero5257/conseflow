<template lang="html">
  <main class="main">
    <br>

    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Reporte de consumo de materiales
      </div>
      <div class="card-body">
        <vue-element-loading :active="isLoading"/>
        <div class="row">
            <div class="col-2">
                <label class="form-control-label" for="proyecto_id">Proyecto:</label>
            </div>
            <div class="col-4">
              <v-select :options="listaProyectos" v-model="proyectoId" label="name" ></v-select>

                <!-- <select class="form-control custom-select" id="proyecto_id"  name="proyecto_id" v-model="proyectoId" v-validate="'required'" data-vv-as="Proyecto">
                    <option v-for="item in listaProyectos" :value="item.proyecto.id" :key="item.proyecto.id">{{ item.proyecto.nombre_corto }}</option>
                </select> -->
                <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
            </div>

        </div>
        <br>
        <div class="row">
          <div class="col-2">
            <button type="button" class="btn btn-pill btn-success" @click="descargar()">
              <i class="fas fa-file-pdf"></i>
              Descargar Reporte.
            </button>
          </div>
            <div class="col-8"></div>
            <div class="col-2">
                <button class="btn btn-primary btn-block" @click="getSalidasProyecto()">Buscar</button>
            </div>
        </div>

      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Reporte de consumo de materiales
      </div>
      <div class="card-body">
        <vue-element-loading :active="isLoading"/>
        <!-- {{salidas}} -->

        <template slot="precio_unitario" slot-scope="props">
            ${{new Intl.NumberFormat("en-US").format(props.row.precio_unitario)}}
        </template>

        <v-client-table :columns="columns" :data="salidas" :options="options" ref="myTabledescuento">

        </v-client-table>
        <h6><b>Total del proyecto : $ {{new Intl.NumberFormat("en-US").format(total)}}</b></h6>
        </div>
    </div>

  </main>
</template>


<script>

import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data() {
    return {
      isLoading: false,
      listaProyectos: [],
      proyectoId : 0,
      salidas : [],
      columns : ['descripcion','nombre','tipo_salida_nombre','lote_id','cantidad','precio_unitario','total'],
      options: {
          headings: {
          'descripcion':'Artículo',
          'nombre' : 'proyecto',
          'tipo_salida_nombre':'Salida',
          'lote_id':'Lote',
          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          //sortable: ['empleado', 'ap_paterno', 'ap_materno', 'puesto'],
          //filterable: ['empleado', 'ap_paterno', 'ap_materno', 'puesto'],
          filterByColumn: true,
          texts:config.texts
      },
    }
  },
  computed:{
    total () {
      return this.salidas.reduce((sum, item) => {
        return (sum + (item.cantidad * item.precio_unitario))
      }, 0)
    }
  },
  methods : {
    getData() {
        this.isLoading = true;
        let me = this;
        axios.get('/proyecto').then(response => {
            // me.listaProyectos = response.data;
            this.listaProyectos = [];
            response.data.forEach(data =>{
              this.listaProyectos.push({
                id: data.proyecto.id,
                name: data.proyecto.nombre_corto ,
              });
            });
            me.isLoading = false;
        })
        .catch(function (error) {
            console.log(error);
        });
    },
    getSalidasProyecto(){
      let me = this;
      axios.get('/salidasporproyecto/'+this.proyectoId.id).then(response => {
        me.salidas = response.data;
      }).catch(function (error) {
        console.log(error);
      });
    },
    descargar() {
      window.open('descargar-materiales/'+this.proyectoId.id, '_blank');
    },

    },
  mounted() {
    this.getData();
  },

}
</script>

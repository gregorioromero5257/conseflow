<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Formulario de busqueda -->
      <br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Búsqueda de nómina {{titulo}}
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>
          <div class="form-row">
            <div class="col-md-4 mb-3">
              <label for="tipon">Tipo de nómina :</label>
              <div class="input-group">
                <select class="form-control custom-select" id="tipo_nomina"  name="tipo_nomina" v-model="tipo_nomina" @change="TipoNomina" >
                  <option value="0">Elegir...</option>
                  <option value="1">Quincenal</option>
                  <option value="2">Semanal</option>
                  <option value="0.0">Todos</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-row">
            <div class="col-md-4 mb-3">
              <label for="fechai">Fecha inicial :</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <span class="input-group-text" id="inputGroupPrepend">Del:</span>
                </div>
                <input type="date" name="fecha_inicial" id="fecha-inicial" class="form-control"  v-model="fechaInicial" data-vv-as="Fecha inicial">
              </div>
            </div>

            <div class="col-md-4 mb-3">
              <label for="fechaf">Fecha final :</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <span class="input-group-text" id="inputGroupPrepend">Al:</span>
                </div>
              <input type="date" name="fecha_final" id="fecha-final" class="form-control" v-model="fechaFinal" data-vv-as="Fecha final" >
              </div>
            </div>
          </div>

          <div class="form-row">
            <div class="col-md-4 mb-3">
              <label for="proyecto">Proyecto :</label>
              <div class="input-group">
                <v-select id="proyectos" name="proyectos" label="nombre_corto" v-model="proyecto"
                      :options="listaProyectos">
                </v-select>
              </div>
            </div>
          </div>

          <div class="form-row">
            <div class="col-md-4 mb-3">
              <label for="tipop">Tipo de pago :</label>
              <div class="input-group">
                <select class="form-control custom-select" id="tipo_pago" name="tipo_pago" v-model="tipo_pago" >
                  <option value="0">Ambos</option>
                  <option value="1">Efectivo</option>
                  <option value="2">Transferencia</option>
                </select>
              </div>
            </div>
          </div>

          <br>
          <div class="row">
            <div class="col-2">
              <div v-show="tiponomina == 1">
                <button class="btn btn-success btn-block" @click="descargar()" v-bind:disabled = "desabilitado"><i class="fas fa-file-excel"></i>&nbsp;Exportar</button>
              </div>
              <div v-show="tiponomina == 2">
                <button class="btn btn-success btn-block" @click="descargaruno()" v-bind:disabled = "desabilitado"><i class="fas fa-file-excel"></i>&nbsp;Exportar</button>
              </div>
              <div v-show="tiponomina == 0.0">
                <button class="btn btn-success btn-block" @click="descargarGeneral()" v-bind:disabled = "desabilitado"><i class="fas fa-file-excel"></i>&nbsp;Exportar</button>
              </div>
            </div>
            <div class="col-8"></div>

            <div class="col-2">
              <div v-show="tiponomina == 1">
                <button class="btn btn-primary btn-block" @click="getNomina()"><i class="fas fa-search"></i>&nbsp;Buscar</button>
              </div>
              <div v-show="tiponomina == 2">
                <button class="btn btn-primary btn-block" @click="getNominaUno()"><i class="fas fa-search"></i>&nbsp;Buscar</button>
              </div>
              <div v-show="tiponomina == 0.0">
                <button class="btn btn-primary btn-block" @click="getNominaGeneral()"><i class="fas fa-search"></i>&nbsp;Buscar</button>
              </div>
            </div>
          </div>

        </div>
      </div>
      <!-- Fin Formulario de busqueda -->
      <!-- Lista de finiquitos -->
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Nómina
        </div>
        <div class="card-body">

          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
          </v-client-table>
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th>Total Transferencias : </th>
                <th>Total Efectivos :</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{sumas != null ?  sumas.sumat : ''}}</td>
                <td>{{sumas != null ?  sumas.sumae : ''}}</td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
      <!-- Fin lista de nomina -->

    </div>
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data(){
    return {
      proyecto:{},
      isLoading: false,
      proyectoId: 0,
      fechaInicial: null,
      fechaFinal: null,
      sumas : null,
      titulo : '',
      tipo_pago : 0,
      tipo_nomina : 0,
      isLoading: false,
      desabilitado : true,
      tiponomina : 0,
      columns: ['e_nombre','dias_trabajados','nombrep','transferencias','efectivos','totales'],
      tableData: [],
      options: {
        headings: {
          e_nombre: 'Empleado',
          dias_trabajados : 'Dias trabajados',
          nombrep : 'Proyecto',
          efectivos : 'Efectivo',
          transferencias : 'Tranferencia',
          totales : 'Total',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        // sortable: [],
        // filterable: [],
        filterByColumn: true,
        texts:config.texts
      },
      listaProyectos: []
    }
  },
  methods: {
    getData() {
      this.isLoading = true;
      let me = this;
      axios.get('/proyecto').then(response => {

        let aux={id:0,nombre_corto:"Seleccione proyecto"};
        me.listaProyectos.push(aux);
        me.proyecto=aux;
        response.data.forEach(p=>
        {
          me.listaProyectos.push
          ({
              id:p.proyecto.id,
              nombre_corto:p.proyecto.nombre_corto,
          });
        });
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });

    },

    descargar() {
      window.open('nominaexcelq/' + this.fechaInicial + '&' + this.fechaFinal + '&' + this.proyecto.id + '&' + this.tipo_pago, '_blank' );
    },
    descargaruno() {
      window.open('nominaexcels/' + this.fechaInicial + '&' + this.fechaFinal + '&' + this.proyecto.id + '&' + this.tipo_pago, '_blank' );
    },
    descargarGeneral() {
      window.open('nominaexcelg/' + this.fechaInicial + '&' + this.fechaFinal + '&' + this.proyecto.id + '&' + this.tipo_pago, '_blank' );
    },
    getNomina()
    {
      let me = this;
      axios.get('/nominaqbusqueda/'+ this.fechaInicial + '&' + this.fechaFinal + '&' + this.proyecto.id + '&' + this.tipo_pago).then(response =>{
        if(response.data.length != 0){
          me.tableData = response.data[0].movimientos;
          me.sumas = response.data[0].suma;
        }
        else {
          me.tableData = [];
          me.sumas = null;
        }
      })
      this.desabilitado = false;

    },
    getNominaGeneral()
    {
      let me = this;
      axios.get('/nominagbusqueda/'+ this.fechaInicial + '&' + this.fechaFinal + '&' + this.proyecto.id + '&' + this.tipo_pago).then(response =>{
        if(response.data.length != 0){
          me.tableData = response.data[0].movimientos;
          me.sumas = response.data[0].suma;
        }
        else {
          me.tableData = [];
          me.sumas = null;
        }
      })
      this.desabilitado = false;

    },
    TipoNomina(){
      this.tiponomina = this.tipo_nomina;
      if (this.tipo_nomina == 1) {
        this.titulo = 'Quincenal';
      }
      else if(this.tipo_nomina == 2){
        this.titulo = 'Semanal';
      }
      else if(this.tipo_nomina == 0.0){
        this.titulo = 'Ambos';
      }
    },
    getNominaUno()
    {
      let me = this;
      axios.get('/nominasbusqueda/'+ this.fechaInicial + '&' + this.fechaFinal + '&' + this.proyecto.id + '&' + this.tipo_pago).then(response =>{
        if(response.data.length != 0){
          me.tableData = response.data[0].movimientos;
          me.sumas = response.data[0].suma;
        }
        else {
          me.tableData = [];
          me.sumas = null;
        }
      })
      this.desabilitado = false;

    }
  },
  mounted() {
    this.getData();
  }
}
</script>

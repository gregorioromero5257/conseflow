<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Formulario de busqueda -->
      <br>
      <div class="card" v-show="!detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Búsqueda Retardos

        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>
          <div class="row">

            <div class="col-1">Fecha inicial:</div>
            <div class="col-3">
              <input type="date" name="fecha_inicial" id="fecha-inicial" class="form-control" v-validate="'required'" v-model="fechaInicial" data-vv-as="Fecha inicial">
              <span class="text-danger">{{ errors.first('fecha_inicial') }}</span>
            </div>
            <div class="col-1">Fecha final:</div>
            <div class="col-3">
              <input type="date" name="fecha_final" id="fecha-final" class="form-control" v-validate="'required'" v-model="fechaFinal" data-vv-as="Fecha final">
              <span class="text-danger">{{ errors.first('fecha_final') }}</span>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-1">
                <label class="form-control-label" for="proyecto_id">Proyecto:</label>
            </div>
            <div class="col-3">
                <select class="form-control custom-select" id="proyecto_id"  name="proyecto_id" v-model="proyectoId" v-validate="'required'" data-vv-as="Proyecto">
                    <option value="0">---TODOS---</option>
                    <option v-for="item in listaProyectos" :value="item.proyecto.id" :key="item.proyecto.id">{{ item.proyecto.nombre }}</option>
                </select>
                <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
            </div>
            <div class="col-1">
                <label class="form-control-label" for="empresa_id">Empresa:</label>
            </div>
            <div class="col-3">
                <select class="form-control custom-select" id="empresa_id"  name="empresa_id" v-model="empresaId" v-validate="'required'" data-vv-as="Empresa">
                    <option value="0">---TODOS---</option>
                    <option v-for="item in listaEmpresas" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                </select>
                <span class="text-danger">{{ errors.first('empresa_id') }}</span>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-lg">
                <button type="button" class="btn btn-outline-secondary" @click="descargar()"><i class="far fa-file-pdf"></i>&nbsp;Exportar</button>
            </div>
            <div class="col-8"></div>
            <div class="col-2">
              <button class="btn btn-dark btn-block" @click="getRetardos()">Buscar</button>
            </div>
          </div>

        </div>
      </div>
      <!-- Fin Formulario de busqueda -->
      <!-- Lista de Retardos -->
      <div class="card" v-show="!detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Retardos
        </div>
        <div class="card-body">

          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="empleado.id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> 
              <button type="button" @click="cargarRetardos(props.row.empleado, fechaInicial,fechaFinal)" class="dropdown-item">
                <i class="fas fa-eye"></i>&nbsp; Ver Retardos
              </button>
                </div>
              </div>
              </div>
            </template>
          </v-client-table>

        </div>
      </div>
      <!-- Fin lista de retardos -->
      <!-- Lista de busqueda de retardos por empleado -->
      <div class="card" v-show="detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Retardos pertenecientes a : {{ empleado == null ? '': empleado.nombre + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno }} del {{fechaInicial}} al {{fechaFinal}}
          <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
            <i class="fa fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">
          <v-client-table :columns="columnsRetardo" :data="tableDataRetardo" :options="optionsRetardo" >
          </v-client-table>
        </div>
      </div>
      <!-- Fin lista de busqueda de retardos por empleado -->

    </div>
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data(){
    return {
      isLoading: false,
      fechaInicial: null,
      fechaFinal: null,
      isLoading: false,
      detalle : false,
      empleado : null,
      proyectoId : 0,
      empresaId : 0,
      columns: ['empleado.id','empleado.nombre','empleado.ap_paterno','empleado.ap_materno','puesto.nombre','departamento.nombre',],
      columnsRetardo : ['fecha','dia','tiempo_retardo_entrada','tiempo_retardo_comida','horas_trabajadas','hora_entrada','hora_salida'],
      tableData: [],
      listaProyectos : [],
      listaEmpresas : [],
      tableDataRetardo : [],
      options: {
        headings: {
          'empleado.id': 'Acciones',
          'empleado.nombre': 'Nombre',
          'empleado.ap_paterno': 'Apellido Paterno',
          'empleado.ap_materno': 'Apellido Materno',
          'puesto.nombre': 'Puesto',
          'departamento.nombre': 'Departamento',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        // filterable: [],
        filterByColumn: true,
        texts:config.texts
        // sortable: [],
      },
      optionsRetardo: {
        headings: {
          'dia' : 'Día'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        // filterable: [],
        filterByColumn: true,
        texts:config.texts
        // sortable: [],
      },
    }
  },
  methods: {
    /**
     * [getData description]
     * @return {object} [response del post guardado en un array]
     */
    getData(){
      this.isLoading = true;
      let me = this;
      axios.get('/proyecto').then(response => {
          me.listaProyectos = response.data;
          me.isLoading = false;
      })
      .catch(function (error) {
          console.log(error);
      });
      axios.get('/empresas').then(response => {
          me.listaEmpresas = response.data;
          me.isLoading = false;
      })
      .catch(function (error) {
          console.log(error);
      });
    },
    /**
    * [getRetardos Metodo que realiza un post]
    * @return {object} [response del post guardado en un array]
    */
    getRetardos() {
      this.isLoading = true;
      let me = this;
      axios({
        method: 'POST',
        url: '/retardosbuscar',
        data: {
          'fecha_inicial': this.fechaInicial,
          'fecha_final': this.fechaFinal,
          'proyecto_id' : this.proyectoId,
          'empresa_id' : this.empresaId,
        }
      }).then(function (response) {
        me.isLoading = false;
        me.tableData = response.data;
      }).catch(function (error) {
        console.log(error);

      });
    },
    /**
    * [cargarRetardos realiza un post]
    * @param  {Array}  [data=[]]    [array de la columna de la tabla consultada]
    * @param  {date} fechaInicial [fecha inicial de busqueda]
    * @param  {date} fechaFinal   [fecha final de busqueda]
    * @return {object}              [objeto de un response guardado en array]
    */
    cargarRetardos(data = [],fechaInicial,fechaFinal ){
      this.detalle = true;
      this.empleado = data;
      this.isLoading = true;
      let me = this;
      axios({
        method: 'POST',
        url: '/retardosbuscarempleado',
        data: {
          'fecha_inicial': fechaInicial,
          'fecha_final': fechaFinal,
          'empleado_id' : data['id'],
          'proyecto_id' : this.proyectoId,
          'empresa_id' : this.empresaId,
        }
      }).then(function (response) {
        me.isLoading = false;
        me.tableDataRetardo = response.data;

      }).catch(function (error) {
        console.log(error);
      });
    },
    maestro(){
      this.detalle = false;
    },
    /**
     * [descargar exportar a pdf]
     */
    descargar() {
        this.$validator.validate().then(result => {
            if (result) {
                window.open('descargaretardo/' + this.fechaInicial + '&' + this.fechaFinal + '&' + this.proyectoId + '&' + this.empresaId, '_blank' );
            }
        });
    }

  },
  mounted() {
    this.getData();

  }
}
</script>

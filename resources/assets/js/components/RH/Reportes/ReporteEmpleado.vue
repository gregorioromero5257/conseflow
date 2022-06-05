<template>
<main class="main">
  <div class="container-fluid">
    <!-- Formulario de busqueda -->
    <br>
     <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Historial de permisos de empleados.

      </div>
      <div class="card-body">
        <vue-element-loading :active="isLoading" />
        <div class="row">
          <div class="col-1">
            <label class="form-control-label" for="empleado">Empleado:</label>
          </div>
          <div class="col-3">
<!--            <select class="form-control custom-select" id="empleado_id" name="empleado"  v-model="ej.empleado_id" v-validate="'required'" data-vv-name="empleado">
<!- -
              <option value="0">-&#45;&#45;MOSTRAR TODOS-&#45;&#45;</option>
- ->
              <option v-for="item in listaEmpleado" :value="item.id" :key="item.id">{{ item.e_nombre }}</option>
            </select>
            -->
            <v-select id="empleado" name="empleado" label="nombre" :options="listaEmpleado" v-model="empleado">
            </v-select>
            <span class="text-danger">{{ errors.first('empleado') }}</span>
          </div>
          <div class="col-1">Fecha inicial:</div>

          <div class="col-3">

            <input type="date" name="fecha_inicial" id="fecha-inicial" class="form-control" v-validate="'required'" v-model="ej.fechaInicial" data-vv-name="fecha_inicial">
            <span class="text-danger">{{ errors.first('fecha_inicial') }}</span>
          </div>
          <div class="col-1">Fecha final:</div>
          <div class="col-3">
            <input type="date" name="fecha_final" id="fecha-final" class="form-control" v-validate="'required'" v-model="ej.fechaFinal" data-vv-name="fecha_final">
            <span class="text-danger">{{ errors.first('fecha_final') }}</span>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-2">
            <button class="btn btn-light btn-block" @click="resetear()"><i class="fas fa-paint-brush"></i>&nbsp;Limpiar</button>
          </div>
          <div class="col-8"></div>

          <div class="col-2">
            <button class="btn btn-primary btn-block" v-model="boton"  :disabled="isDisabled" @click="getBusqueda()"><i class="fas fa-search-plus"></i>&nbsp;Buscar</button>
          </div>
        </div>

      </div>
    </div>
    <!-- Fin Formulario de busqueda -->
    <!-- Lista de historico de permisos de empleados -->
    <div class="card">
      <div class="card-header">
        <i class="fa fa-align-justify"></i> Detalle de los permisos.
      </div>
      <div class="card-body">

        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
        </v-client-table>

      </div>
    </div>
    <!-- Fin lista de finiquitos -->

  </div>
</main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data() {
    return {
      empleado:{},
      isLoading: false,
      listaEmpleado: [],
      proyecto_id: 0,
      empn: null,
      motivo: null,
      tipo_salida: 0,
      boton: null,
      ej: {
        empleado_id: '',
        fecha_inicio: null,
        fecha_fin: null,
        fecha_solicitud: null,
        fechaInicial: null,
        fechaFinal: null,
      },

      isLoading: false,
      columns: ['fecha_solicitud', 'fecha_inicio', 'tipo_salida', 'aut_nom', 'motivo'],
      tableData: [],

      options: {
        headings: {
          fecha_solicitud: 'Fecha de solicitud',
          fecha_inicio: 'Inicio de permiso.',
          aut_nom: 'Autorizó:',
          motivo: 'Motivo',
          tipo_salida: 'Tipo de salida',
        },

        perPage: 10,
        groupBy: ['sol_nom'],
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        // sortable: [],
        // filterable: [],
        filterByColumn: true,
        listColumns: {
          tipo_salida: [{

              id: 1,
              text: 'Dia Completo'
            },
            {
              id: 0,
              text: 'Temporal'
            }
          ]

        },
        texts: config.texts
      },

    }
  },
  computed: {

    isDisabled() {
      if (this.ej.fechaInicial != null && this.ej.fechaFinal != null) {
      return false;
      } else {
        return true;
      }
    }
  },


  methods: {

    getData() {
      this.isLoading = true;
      let me = this;
      axios.get('/permisoem').then(response => {
        // me.listaEmpleado = response.data;
        // console.error(response.data);
        // return;
        response.data.forEach(em=>
        {
          // console.error(em.id);
          // console.error(em);
          me.listaEmpleado.push
          ({
            id: em.id,
            nombre: em.e_nombre
          });
        });
        me.isLoading = false;
      }).catch(function (error) {
        console.log(error);
      });
    },

    getBusqueda() {
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: 'POST',
            url: 'busqueda/',
            data: {
              'fecha_inicio': this.ej.fechaInicial,
              'fecha_fin': this.ej.fechaFinal,
              'empleados_id': this.empleado.id,
            }
          }).then(function (response) {
            me.isLoading = false;
            me.tableData = response.data;

          }).catch(function (error) {
            console.log(error);
          });
        }
      });
    },

    resetear() {
      Utilerias.resetObject(this.ej);
      this.empleado={};
    },
  },

  mounted() {
    this.getData();
  }
}
</script>

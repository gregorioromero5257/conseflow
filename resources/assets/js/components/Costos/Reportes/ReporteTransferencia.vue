<template>
    <main class="main">
        <div class="container-fluid">
            <!-- Formulario de busqueda -->
            <br>
            <!--   <div class="card">

             <div class="card-body">
                      <vue-element-loading :active="isLoading" />
                      <div class="row">
                          <div class="col-1">
                              <label class="form-control-label" for="empleado_id">Empleado:</label>
                          </div>
                          <div class="col-3">
                              <select class="form-control custom-select" id="empleado_id" name="empleado"  v-model="ej.empleado_id" v-validate="'required'" data-vv-name="empleado">
                                  &lt;!&ndash;
                                                <option value="0">-&#45;&#45;MOSTRAR TODOS-&#45;&#45;</option>
                                  &ndash;&gt;
                                  <option v-for="item in listaEmpleado" :value="item.id" :key="item.id">{{ item.e_nombre }}</option>
                              </select>
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
              </div>-->
            <!-- Fin Formulario de busqueda -->
            <!-- Lista de historico de permisos de empleados -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Reporte de transferencia de materiales.
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
                isLoading: false,
                id:1,
                isLoading: false,
                columns: ['codigo', 'articulos', 'precio_unitario', 'cantidad', 'importe'],
                tableData: [[]],
                options: {
                    perPage: 10,
                    groupBy: ['sol_nom'],
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    // sortable: [],
                    // filterable: [],
                    filterByColumn: true,
                    texts: config.texts
                },

            }
        },


        methods: {

            getData() {
                this.isLoading = true;
                let me = this;
                axios.get('/transferencia/').then(response => {
                   me.isLoading = false;
                    me.tableData = response.data;
                    console.log(response);
                })
                    .catch(function (error) {
                        console.log(error);
                    });
                    },

/*
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
                                'empleados_id': this.ej.empleado_id,
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
*/

        },

        mounted() {
            this.getData();
        }
    }
</script>

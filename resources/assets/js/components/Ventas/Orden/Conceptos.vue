<template>
    <!-- Listado de partidas -->
    <div class="card" v-show="detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Partidas de venta
          <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
            <i class="fas fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>
          <v-client-table :columns="columnspartida" :data="tableDataPartida" :options="optionspartida" ref="myTable">
          </v-client-table>
        </div>
        <!-- Nuevo y editar partidas requisiciones -->
        <div class="card" ref="formLote">
          <vue-element-loading />
          <div class="card-header">
          </div>
          <div class="card-body">
            <div class="row justify-content-center">
              <h4  class="col-sm-12 form-control-label">Registro de partidas de venta</h4></div><hr><br>
              <form>
                <!-- <input type="text" class="form-control" id="stocke_id" name="stocke_id" v-model="partidasalida.stocke_id" placeholder="Stoke"  > -->
                <input type="text" class="form-control" id="salida_id" name="salida_id" v-model="partidasalida.salida_id" placeholder="Salida" hidden>
                <input type="text" class="form-control" id="lote_id" name="lote_id" v-model="partidasalida.lote_id" placeholder="Lote" hidden>
                <input type="text" class="form-control" id="tiposalida_id" name="tiposalida_id" v-model="partidasalida.tiposalida_id" placeholder="tiposalida_id" hidden >
                <input type="text" class="form-control" id="lote_temporal_id" name="lote_temporal_id" v-model="partidasalida.lote_temporal_id" hidden>

                <!-- <input type="text" class="form-control" id="proveedore_id" name="proveedore_id" v-model="" hidden> -->
                <!-- {{salidav}} -->
                <div class="form-group row">
                  <label for="inputArticulo" class="col-md-1 form-control-label">Artículo</label>
                  <div class="col-md-8">
                    <div class="input-group mb-3">
                      <input type="text" class="form-control"  v-model="partidasalida.articulo" placeholder="Articulo" readonly>
                      <div class="input-group-append">
                        <button class="btn btn-secondary" type="button" v-bind:disabled="desabilitarBuscarM" @click="abrirModalA('articulo','registrar')">Buscar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-1 form-control-label" for="cantidad_existente">Cantidad existente</label>
                  <div class="col-md-4">
                    <input type="text" name="cantidad_existente"  v-model="partidasalida.cantidad_existente" class="form-control" readonly>
                    <span class="text-danger">{{ errors.first('cantidad_existente') }}</span>
                  </div>
                  <label class="col-md-1 form-control-label" for="cantidad">Cantidad</label>
                  <div class="col-md-4">
                    <input type="text" name="cantidad"  v-model="partidasalida.cantidad" v-bind:class="'form-control '+clasescantidad" @blur="validacioncantidad" placeholder="Cantidad" autocomplete="off" id="cantidad">
                    <span class="text-danger">{{ errors.first('cantidad') }}</span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-1 form-control-label" for="precio_real">Precio Real</label>
                  <div class="col-md-4">
                    <input type="text" name="precio_real"  v-model="partidasalida.precio_real" class="form-control" readonly>
                    <span class="text-danger">{{ errors.first('precio_real') }}</span>
                  </div>
                  <label class="col-md-1 form-control-label" for="precio_sugerido">Precio Sugerido</label>
                  <div class="col-md-4">
                    <input type="text" name="precio_sugerido"  v-model="partidasalida.precio_sugerido" v-bind:class="'form-control '+clasescantidad" @blur="validacioncantidad" placeholder="Precio Sugerido" autocomplete="off" id="precio_sugerido" readonly>
                    <span class="text-danger">{{ errors.first('precio_sugerido') }}</span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-1 form-control-label" for="precio_final">Precio Final</label>
                  <div class="col-md-4">
                    <input type="text" name="precio_final"  v-model="partidasalida.precio_final" class="form-control" readonly>
                    <span class="text-danger">{{ errors.first('precio_final') }}</span>
                  </div>
                </div>


              </form>
            </div>
            <div class="card-footer">
              <button type="button" class="btn btn-secondary" @click="cancelar()"><i class="fas fa-times"></i>&nbsp;Cancelar</button>
              <button type="button" v-if="tipoAccionpr==1" class="btn btn-dark" @click="validacioncantidaduno(); guardarPR();"><i class="fas fa-plus"></i>&nbsp;Agregar</button>
            </div>
          </div>
          <!-- Fin  de Nuevo y editar detalle requisicion correspondiente ala tabla partidas_requisiciones -->

          <!--Inicio del modal agregar/actualizar articulos-->
          <div class="modal fade" tabindex="-1" :class="{'mostrar' : modala}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dark modal-lg" role="document">
              <div class="modal-content">
                <div>

                  <vue-element-loading :active="isLoadingg"/>
                  <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModala"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">

                    <div class="accordion" id="accordion">

                      <div class="card">
                        <div class="card-header bg-dark" id="headingUno">
                          <h5 class="mb-0">
                            <button class="text-white btn btn-dark collapsed" type="button" data-toggle="collapse" data-target="#collapseUno" aria-expanded="false" aria-controls="collapseUno">
                              Articulos
                            </button>

                          </h5>
                        </div>
                        <div id="collapseUno" class="collapse" aria-labelledby="headingUno" data-parent="#accordion">
                          <v-client-table ref="myTable" :data="dataTableArticulo" :columns="columnsa" :options="optionsa" @row-click="seleccionarArticulo">
                          </v-client-table>
                        </div>
                      </div>

                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                  </div>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!--Fin del modal agregar articulo-->

        </div>

        <!-- Fin Listado de partidas -->
      </div>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
    data(){
        return {
            detalle: false,
            isLoading: false,
            columnspartida: [],
            tableDataPartida: [],
            titulosalidas: '',
            partidasalida: {
              salida_id: 0,
              lote_id : 0,
              tiposalida_id : 0,
              lote_temporal_id : 0,
              articulo : '',
              cantidad_existente : 0,
              cantidad : 0,
              precio_real : 0,
              precio_sugerido : 0,
              precio_final : 0,
            },
            desabilitarBuscarM: false,
            lotes_TGP: [],
            clasescantidad: '',
            validacioncantidad: false,
            tipoAccionpr: '',
            modala: false,
            isLoadingg: false,
            tituloModala: '',
            dataTableArticulo: [],
            columnsa: ['acodigo','anombre','adescripcion', 'amarca'],
            optionsa: {
              headings: {
                'acodigo' : 'Código',
                'anombre' : 'Nombre',
                'adescripcion' : 'Descripción',
                'amarca' : 'Marca',
                },
            },
            optionspartida: {
                headings: {
                id: 'Acciones',
                cantidad: 'Cantidad requerida',
                anombre : 'Nombre',
                acodigo : 'Codígo',
                adescripcion : 'Descripción',
                amarca : 'Marca',
                aunidad : 'Unidad',
                alnombre : 'Nombre almacén',
                pnombre : 'Proyecto'

                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['id','cantidad','anombre','acodigo','adescripcion','amarca','aunidad'],
                filterable: ['id','cantidad','anombre','acodigo','adescripcion','amarca','aunidad'],
                filterByColumn: true,
                listColumns: {

                },
                texts:config.texts
            },
            seleccionarArticulodos: [],
        }
    },
    methods: {
        getData() {
            this.detalle = true;
        },
        maestro() {
            this.$emit('maestro:conceptos');
        },
        cerrarModal(){
          this.modala = 0;
          this.tipoAccionpr = 0;
        },
        seleccionarArticulo(data){
          console.log(data.row);
          this.partidasalida.articulo = data.row.adescripcion;
          this.partidasalida.cantidad_existente = data.row.cantidad;
          this.cerrarModal();
        },
        abrirModalA(modelo, accion){
            switch(modelo){
                case "articulo":
                {
                switch(accion){
                    case 'registrar':
                    {
                    let me= this;
                    this.modala = 1;
                    this.tipoAccionpr = 1;
                    axios.get('/ventasarticulos').then(response => {
                        me.dataTableArticulo = response.data;
                    }).catch(function (error){
                        console.error(error);
                    });
                    me.cancelar();

                    break;
                    }
                }
                }
            }
        },
    }
}
</script>

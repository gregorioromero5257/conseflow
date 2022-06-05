<template>
<main class="main">
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <br>
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Evaluación de Proveedores
            </div>
            <div class="card-body">
                <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                    <!-- Como usar un if cuando se tiene tres condiciones-->
                    <template slot="total_eval" slot-scope="props">
                      <template v-if="total_eval != null">
                        0
                      </template>
                      <template v-else>
                        <template v-if="props.row.total_eval >= 54">
                          <span class="text-success">IMPORTANTE</span>
                        </template>
                        <template v-if="props.row.total_eval >= 36 && props.row.total_eval <= 53">
                          <span class="text-warning">NO CRÍTICO</span>
                        </template>
                        <template v-if="props.row.total_eval >= 18 && props.row.total_eval <= 35">
                          <span class="text-danger">CRÍTICO</span>
                        </template>
                      </template>
                    </template>
                    <template slot="id" slot-scope="props">
                      <button  type="button" class="btn btn-outline-dark" @click="abrirModal('solicitud-proveedores','registrar',props.row)">
                          <i class="fas fa-tasks"></i>&nbsp;
                      </button>
                      <button  type="button" class="btn btn-outline-dark" @click="descargar(props.row)">
                          <i class="fas fa-download"></i>&nbsp;
                      </button>
                        <!-- <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group dropup" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i> Acciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <button v-show="PermisosCrud.Update" type="button" @click="abrirModal('solicitud-proveedores','actualizar',props.row)" class="dropdown-item">
                                        <i class="icon-pencil"></i>&nbsp;Actualizar proveedor.
                                    </button>
                                    <template v-if="props.row.condicion">
                                        <button v-show="PermisosCrud.Delete" type="button" class="btn btn-outline-dark" @click="actdesact(props.row.id, 0)">
                                            <i class="fas fa-tasks"></i>&nbsp;Desactivar proveedor.
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="dropdown-item" @click="actdesact(props.row.id, 1)">
                                            <i class="icon-check"></i>&nbsp;Activar proveedor.
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div> -->
                    </template>

                </v-client-table>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">
                <div>
                    <vue-element-loading :active="isLoading" />
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                      <div class="form-row">
                        <div class="form-group col-md-2">
                          <label>&nbsp;</label>
                        </div>
                        <div class="form-group col-md-10">
                          <label><b>INSTRUCCIONES: Selecionar el puntaje correspondiente al desempeño observado.</b></label>


                        </div>
                      </div>
                      <div class="accordion" id="accordion">
                        <div class="card">
                          <div class="card-header" id="headingOne">
                            <h5 class="mb-0" >
                              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                      A) ATENCIÓN TELEFÓNICA / CORREO ELECTRÓNICO
                              </button>
                            </h5>
                          </div>
                          <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label">Atención recibida (cortesía, amabilidad)</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.uno">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label"> Rapidez en la atención</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.dos">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label">Agilidad ante un problema, duda, sugerencia o requerimiento.</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.tres">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>


                      <div class="accordion" id="accordionTwo">
                        <div class="card">
                          <div class="card-header" id="headingTwo">
                            <h5 class="mb-0" >
                              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                            B) ATENCIÓN COMERCIAL
                              </button>
                            </h5>
                          </div>
                          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionTwo">
                            <div class="card-body">

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label">Trato personal recibido (cortesía, amabilidad)</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.cuatro">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label"> Actitud y atención a la hora de hacer una consulta o reclamación</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.cinco">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label">Facilidad para contactar con la persona adecuada</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.seis">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label">Nivel de información recibido sobre los servicios</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.siete">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label">Claridad de las cotizaciones, cumple con sus requisitos y requerimientos de forma</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.ocho">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label">Cotización oportuna</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.nueve">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="accordion" id="accordionThree">
                        <div class="card">
                          <div class="card-header" id="headingThree">
                            <h5 class="mb-0" >
                              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                              C) SERVICIO DE ADMINISTRACIÓN Y FACTURACION
                              </button>
                            </h5>
                          </div>
                          <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionThree">
                            <div class="card-body">

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label">Tiempo de respuesta respecto a su factura</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.diez">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label"> Actitud y atención a la hora de hacer una consulta o reclamación</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.once">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label">Facilidad para contactar con la persona adecuada</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.doce">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label">Nivel de información de las facturas enviadas</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.trece">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label">Nivel de satisfacción general con este servicio</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.catorce">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="accordion" id="accordionFour">
                        <div class="card">
                          <div class="card-header" id="headingFour">
                            <h5 class="mb-0" >
                              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                D) PRODUCTO/SERVICIO
                              </button>
                            </h5>
                          </div>
                          <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionFour">
                            <div class="card-body">

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label">Cumplimiento del plazo de entrega acordado</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.quince">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label">Cumplimiento en las cantidades entregadas de acuerdo con lo acordado</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.diesiseis">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label">Cumplimiento a las especificaciones de embalaje y transporte de acuerdo con lo solicitado</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.diesisiete">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                              <div class="form-group row">
                                  <label class="col-md-6 form-control-label">Cumplimiento a las especificaciones del producto de acuerdo con lo solicitado</label>
                                  <div class="col-md-3">
                                      <select class="form-control" v-validate="'required'" data-vv-name="_" v-model="calificacion.diesiocho">
                                      <option value="4">Excelente</option>
                                      <option value="3">Bien</option>
                                      <option value="2">Regular</option>
                                      <option value="1">Deficiente</option>
                                      </select>
                                      <span class="text-danger">{{ errors.first('_') }}</span>
                                  </div>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label>Evaluador</label>
                          <v-select :options="listaEmpleados" id="elabora_empleado_id" v-validate="'required'" name="elabora_empleado_id" v-model="calificacion.evaluador" label="name" data-vv-name="Elabora empleado" ></v-select>

                        </div>
                        <div class="form-group col-md-6">
                          <label>Fecha de evaluacion</label>
                          <input type="date" class="form-control" v-model="calificacion.fecha">
                        </div>
                      </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarProveedores(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarProveedores(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->

    <!-- Modal registro de provedor -->

    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalProveedor}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Ingresar proveedor</h4>
                    <button type="button" class="close" @click="cerrarModalProveedor()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="banco_transferencia">Banco de transferencia</label>
                        <div class="col-md-9">
                            <input type="text" name="banco_transferencia" v-model="temp_proveedor_banco" class="form-control" placeholder="Banco de transferencia" autocomplete="off" id="banco_transferencia" data-vv-as="banco transferencia">
                            <span class="text-danger">{{ errors.first('banco_transferencia') }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="numero_cuenta">Número de cuenta</label>
                        <div class="col-md-9">
                            <input type="text" pattern="^[0-9]+" name="numero_cuenta" v-model="temp_proveedor_cuenta" class="form-control" placeholder="Número de cuenta" autocomplete="off" id="numero_cuenta" data-vv-as="número cuenta">
                            <span class="text-danger">{{ errors.first('numero_cuenta') }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="clabe">Clabe</label>
                        <div class="col-md-9">
                            <input type="text" pattern="^[0-9]+" name="clabe" v-model="temp_proveedor_clabe" class="form-control" placeholder="Clabe" autocomplete="off" id="clabe" data-vv-as="clabe">
                            <span class="text-danger">{{ errors.first('clabe') }}</span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" @click="cerrarModalProveedor()"><i class="fas fa-window-close"></i>&nbsp;Cancelar</button>
                    <button v-if="tipo_guardar==1" type="button" class="btn btn-secondary" @click="guardarProveedoresTemp(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                    <button v-else type="button" class="btn btn-secondary" @click="guardarProveedoresTemp(2)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                </div>
            </div>

            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
    data() {
        return {
            n_temp: 0,
            banco_edit: {},
            tipo_guardar: 1,
            ListBancos_temp: [],
            ListBancos: [],
            listaEmpleados : [],
            temp_proveedor_cuenta: '',
            temp_proveedor_clabe: '',
            temp_proveedor_banco: '',
            url: '/proveedores',
            columnsProvedores: ["id", "banco", "cuenta", "clabe"],
            tableDataProveedores: [],
            optionsProveedores: {
                headings: {
                    clabe: "Clabe",
                    banco: "Banco de transferencia",
                    cuenta: "No. de Cuenta",
                    id: 'Acciones',
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                texts: config.texts,
                filterable: false,
            },
            modalProveedor: 0,
            PermisosCrud: {},
            Proveedor: {
                nombre: '',
                razon_social: '',
                direccion: '',
                banco_transferencia: '',
                // titular_cuenta : '',
                numero_cuenta: '',
                clabe: '',
                dia_credito: '',
                limite_credito: 0,
                condicion: 0,

                categoria: '',
                condicion_pago: '',
                giro: '',
                rfc: '',
                ciudad: '',
                estado: '',
                contacto: '',
                telefono: '',
                correo: '',
                pagina: '',
                descripcion: '',
                tipo_moneda: 0,
                tipo_cambio: '',
            },
            calificacion : {
              id : 0,
              uno : '',
              dos : '',
              tres : '',
              cuatro : '',
              cinco : '',
              seis : '',
              siete : '',
              ocho : '',
              nueve : '',
              diez : '',
              once : '',
              doce : '',
              trece : '',
              catorce : '',
              quince : '',
              diesiseis : '',
              diesisiete : '',
              diesiocho : '',
              fecha : '',
              evaluador : '',
            },
            listaProveedores: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            isLoading: false,
            columns: ['id', 'nombre', 'razon_social','rfc','total_eval','direccion'],
            tableData: [],
            options: {
                headings: {
                    id: 'Acciones',
                    nombre: 'Nombre',
                    razon_social: 'Razón Social',
                    direccion: 'Dirección',
                    total_eval : 'Calificación',

                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['nombre', 'razon_social','rfc', 'direccion'],
                filterable: ['nombre', 'razon_social','rfc', 'direccion'],
                filterByColumn: true,
                texts: config.texts
            },
        }
    },
    computed: {},
    methods: {
        getData() {
            let me = this;
            this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
            axios.get('get/proveedores/cfw').then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });

                me.listaEmpleados.push(
             {
                       id: 6,
                     name: 'JOEL MACHORRO MARTINEZ'
                                       },
             {
                 id: 241,
                 name: 'RODOLFO RODRIGUEZ ALVARADO'
             },
             {
                 id: 205,
                 name: 'RAMON CRUZ MARTINEZ'
             },
                                         );
         var f = new Date();

            me.calificacion.fecha = (f.getFullYear() + '-' + (f.getMonth() +1) + '-' + f.getDate() );
        },
        getListaProveedores() {
            let me = this;
            axios.get('/proveedores').then(response => {
                    me.listaProveedores = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        guardarProveedores(nuevo) {
          // console.log(this.calificacion);
            this.$validator.validate().then(result => {
                if (result) {
                    this.isLoading = true;
                    let me = this;
                    axios({
                        method: nuevo ? 'POST' : 'PUT',
                        url: nuevo ? 'guardar/evaluacion/proveedor' : 'evaluacion/proveedor/'  + this.calificacion.id,
                        data: {
                          'id': this.calificacion.id,
                          'uno': this.calificacion.uno,
                          'dos': this.calificacion.dos,
                          'tres': this.calificacion.tres,
                          'cuatro': this.calificacion.cuatro,
                          'cinco': this.calificacion.cinco,
                          'seis': this.calificacion.seis,
                          'siete': this.calificacion.siete,
                          'ocho': this.calificacion.ocho,
                          'nueve': this.calificacion.nueve,
                          'diez': this.calificacion.diez,
                          'once': this.calificacion.once,
                          'doce': this.calificacion.doce,
                          'trece': this.calificacion.trece,
                          'catorce': this.calificacion.catorce,
                          'quince': this.calificacion.quince,
                          'diesiseis': this.calificacion.diesiseis,
                          'diesisiete': this.calificacion.diesisiete,
                          'diesiocho': this.calificacion.diesiocho,
                          'evaluador': this.calificacion.evaluador.id,
                          'fecha': this.calificacion.fecha,
                          'proveedor_id' : this.calificacion.proveedor_id,

                        }
                    }).then(function (response) {
                        me.isLoading = false;
                        if (response.data.status) {
                            me.cerrarModal();
                            me.getData();
                            // me.ListBancos = [];
                            if (!nuevo) {

                                toastr.success('Calificación Actualizada Correctamente');
                            } else {
                                toastr.success('Calificación Agregada Correctamente');
                            }
                        } else {
                            swal({
                                type: 'error',
                                html: response.data.errors.join('<br>'),
                            });
                        }
                    }).catch(function (error) {
                        console.error(error);
                    });
                }
            });
        },
        actdesact(id, activar) {
            if (activar) {
                this.swal_titulo = 'Esta seguro de activar este proveedor?';
                this.swal_tle = 'Activado';
                this.swal_msg = 'El registro ha sido activado con éxito.';
            } else {
                this.swal_titulo = 'Esta seguro de desactivar este proveedor?';
                this.swal_tle = 'Desactivado!';
                this.swal_msg = 'El registro ha sido desactivado con éxito.';
            }
            swal({
                title: this.swal_titulo,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    let me = this;
                    axios.get(me.url + '/' + id + '/edit').then(function (response) {
                        if (activar) {
                            toastr.success(me.swal_tle, me.swal_msg, 'success');

                        } else {
                            toastr.error(me.swal_tle, me.swal_msg, 'error');

                        }
                        toastr.options = {
                            "closeButton": false,
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": true,
                            "progressBar": true,
                            "positionClass": "toast-top-center",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        me.getData();
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
            })
        },
        cerrarModal() {
            this.modal = 0;
            this.tituloModal = '';
            this.n_temp = 0;
            let tempBancos = this.ListBancos.filter(b => b.id > 100);

            tempBancos.forEach(b => {
                let s = this.ListBancos.indexOf(b);
                const index = this.ListBancos.indexOf(b);
                console.error(index);
                this.ListBancos.splice(index, 1);

            });

            Utilerias.resetObject(this.Proveedor);
        },
        abrirModal(modelo, accion, data = []) {
            this.n_temp = 100;
            // console.log(data,'hhhd');
            switch (modelo) {
                case "solicitud-proveedores": {
                    switch (accion) {
                        case 'registrar': {
                            // this.ListBancos = [];
                            this.modal = 1;
                            this.tituloModal = 'Registrar Evaluacion ' + data.razon_social;
                            Utilerias.resetObject(this.calificacion);
                            this.calificacion.proveedor_id =  data.id;
                            // console.log(data.id);
                            axios.get('get/evaluacion/proveedor/' + data.id).then(response => {
                              // console.log(Object.keys(response.data).length);
                              if (Object.keys(response.data).length == 0) {
                                this.tipoAccion = 1;
                              }else {
                                this.tipoAccion = 2;
                                this.calificacion.id = response.data.id;
                                this.calificacion.uno = response.data.uno;
                                this.calificacion.dos = response.data.dos;
                                this.calificacion.tres = response.data.tres;
                                this.calificacion.cuatro = response.data.cuatro;
                                this.calificacion.cinco = response.data.cinco;
                                this.calificacion.seis = response.data.seis;
                                this.calificacion.siete = response.data.siete;
                                this.calificacion.ocho = response.data.ocho;
                                this.calificacion.nueve = response.data.nueve;
                                this.calificacion.diez = response.data.diez;
                                this.calificacion.once = response.data.once;
                                this.calificacion.doce = response.data.doce;
                                this.calificacion.trece = response.data.trece;
                                this.calificacion.catorce = response.data.catorce;
                                this.calificacion.quince = response.data.quince;
                                this.calificacion.diesiseis = response.data.diesiseis;
                                this.calificacion.diesisiete = response.data.diesisiete;
                                this.calificacion.diesiocho = response.data.diesiocho;
                                this.calificacion.evaluador = {id : response.data.evaluador, name : response.data.empleado};
                                this.calificacion.fecha = response.data.fecha;
                                this.calificacion.proveedor_id = response.data.proveedor_id;
                              }
                            }).catch(error => {
                              console.error(error);
                            });
                            break;
                        }

                    }
                }
            }
        },

        abrirModalProveedor(nuevo, model) {
            this.modalProveedor = 1;
            if (nuevo == 1) //Crear nuevo
            {
                this.tipo_guardar = 1;
            } else //actualizar
            {
                this.tipo_guardar = 2;
                this.banco_edit = model;
                this.temp_proveedor_banco = model.banco;
                this.temp_proveedor_clabe = model.clabe;
                this.temp_proveedor_cuenta = model.cuenta;
            }
        },
        cerrarModalProveedor() {
            this.modalProveedor = 0;
        },
        guardarProveedoresTemp(tipo) {
            let t = this;
            if (t.temp_proveedor_banco == '') {
                toastr.warning("Ingrese un banco");
                return;
            }
            if (t.temp_proveedor_cuenta == '') {
                toastr.warning("Ingrese una cuenta");
                return;
            }
            if (t.temp_proveedor_clabe == '') {
                toastr.warning("Ingrese una clabe");
                return;
            }

            if (tipo == 1) //nuevo
            {
                // Guardar temporal
                let nuevo = {
                    id: t.n_temp,
                    banco: t.temp_proveedor_banco,
                    cuenta: t.temp_proveedor_cuenta,
                    clabe: t.temp_proveedor_clabe,
                    temp: true
                };
                this.ListBancos.push(nuevo);
                t.temp_proveedor_banco = "";
                t.temp_proveedor_cuenta = "";
                t.temp_proveedor_clabe = "";
                this.cerrarModalProveedor();
                toastr.success("Banco registrado temporalmente");
                t.n_temp += 1;
            } else // actualizar
            {
                let anterior = t.banco_edit;
                let nuevo = {
                    id: t.banco_edit.id,
                    banco: t.temp_proveedor_banco,
                    cuenta: t.temp_proveedor_cuenta,
                    clabe: t.temp_proveedor_clabe,
                    proveedor_id: t.banco_edit.proveedor_id,
                };
                const index = this.ListBancos.findIndex(b => b.id == t.banco_edit.id);
                if (index >= 0) {
                    this.ListBancos.splice(index, 1, nuevo);
                    console.log("Editado");
                } else {
                    toastr.error("Datos bancarios no encontrados");
                }
                t.temp_proveedor_banco = "";
                t.temp_proveedor_cuenta = "";
                t.temp_proveedor_clabe = "";
                this.cerrarModalProveedor();
            }

        },
        DesactivarBanco(model) {
            if (model.temp) {

                //temporal
                const index = this.ListBancos.indexOf(model);
                this.ListBancos.splice(index, 1);
                toastr.success("Banco temporal eliminado");
                this.cerrarModalProveedor();
            }
        },
        descargar(data){
          window.open('descargar/evaluacion/proveedor/' + data.id, '_blank');
        }
    },
    mounted() {
        this.getData();
        this.getListaProveedores();
    }
}
</script>

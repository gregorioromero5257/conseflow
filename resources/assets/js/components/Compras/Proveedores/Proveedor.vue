<template>
<main class="main">
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <br>
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Registro de Proveedores
                <button v-show="PermisosCrud.Create" type="button" @click="abrirModal('solicitud-proveedores','registrar')" class="btn btn-dark  float-sm-right">
                    <i class="fas fa-plus"></i>&nbsp;Nuevo
                </button>
                <button  type="button" @click="descargarReporte()" class="btn btn-success  float-sm-right mr-1">
                    <i class="fas fa-file-excel"></i>&nbsp;Reporte
                </button>
            </div>
            <div class="card-body">
                <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
                    <!-- Como usar un if cuando se tiene tres condiciones-->
                    <template slot="condicion" slot-scope="props">
                        <template v-if="props.row.condicion == 1">
                            <span class="btn btn-outline-success">Activo</span>
                        </template>
                        <template v-if="props.row.condicion == 0">
                            <span class="btn btn-outline-danger">Desactivado</span>
                        </template>
                    </template>
                    <template slot="id" slot-scope="props">
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group dropup" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <button v-show="PermisosCrud.Update" type="button" @click="abrirModal('solicitud-proveedores','actualizar',props.row)" class="dropdown-item">
                                        <i class="icon-pencil"></i>&nbsp;Actualizar proveedor.
                                    </button>
                                    <template v-if="props.row.condicion">
                                        <button v-show="PermisosCrud.Delete" type="button" class="dropdown-item" @click="actdesact(props.row.id, 0)">
                                            <i class="fas fa-ban"></i>&nbsp;Desactivar proveedor.
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="dropdown-item" @click="actdesact(props.row.id, 1)">
                                            <i class="icon-check"></i>&nbsp;Activar proveedor.
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template slot="categoria" slot-scope="props">
                      <template v-if="props.row.total_eval == null">
                        SIN EVALUACIÓN
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

                    <template slot="total_eval" slot-scope="props">
                      <template v-if="props.row.total_eval == null">
                        0
                      </template>
                      <template v-if="props.row.total_eval != null">
                        {{props.row.total_eval}}
                      </template>
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
                        <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
                        <input type="hidden" name="id">

                        <!-- <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="categoria">Categoría</label>
                            <div class="col-md-6">
                                <input type="text" name="categoria" v-model="Proveedor.categoria" class="form-control" placeholder="Categoria" autocomplete="off" id="categoria" data-vv-as="Categoria">
                                <span class="text-danger">{{ errors.first('categoria') }}</span>
                            </div>
                        </div> -->
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="razon_social">Razón Social</label>
                            <div class="col-md-6">
                                <input type="text" name="razon_social" v-model="Proveedor.razon_social" class="form-control" placeholder="Razón Social" autocomplete="off" id="razon_social" data-vv-as="Razon Social" v-validate="'required'">
                                <span class="text-danger">{{ errors.first('razon_social') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="nombre">Nombre Comercial</label>
                            <div class="col-md-6">
                                <input type="text" name="nombre" v-model="Proveedor.nombre" class="form-control" placeholder="Nombre" autocomplete="off" id="nombre" data-vv-as="Nombre" v-validate="'required'">
                                <span class="text-danger">{{ errors.first('nombre') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="rfc">RFC</label>
                            <div class="col-md-6">
                                <input type="text" name="rfc" v-model="Proveedor.rfc" class="form-control" placeholder="RFC" autocomplete="off" id="rfc" data-vv-as="RFC">
                                <span class="text-danger">{{ errors.first('rfc') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="regimen">Régimen Fiscal</label>
                            <div class="col-md-6">
                                <input type="text" name="rfc" v-model="Proveedor.regimen" class="form-control" placeholder="Régimen Fiscal" autocomplete="off" id="regimen" data-vv-as="regimen"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="giro">Giro</label>
                            <div class="col-md-6">
                                <input type="text" name="giro" v-model="Proveedor.giro" class="form-control" placeholder="Giro" autocomplete="off" id="giro" data-vv-as="Giro">
                                <span class="text-danger">{{ errors.first('giro') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="direccion">Dirección</label>
                            <div class="col-md-9">
                                <textarea type="text" name="direccion" v-model="Proveedor.direccion" class="form-control" placeholder="Dirección" autocomplete="off" id="direccion" data-vv-as="Direccion" v-validate="'required'"></textarea>
                                <span class="text-danger">{{ errors.first('direccion') }}</span>
                            </div>
                        </div>

<!--

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="condicion_pago">Condición pago</label>
                            <div class="col-md-6">
                                <input type="text" name="condicion_pago" v-model="Proveedor.condicion_pago" class="form-control" placeholder="Condición pago" autocomplete="off" id="condicion_pago" data-vv-as="Condición pago">
                                <span class="text-danger">{{ errors.first('condicion_pago') }}</span>
                            </div>
                        </div> -->









                        <!-- <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="ciudad">Ciudad</label>
                            <div class="col-md-3">
                                <input type="text" name="ciudad" v-model="Proveedor.ciudad" class="form-control" placeholder="Ciudad" autocomplete="off" id="ciudad" data-vv-as="Ciudad">
                                <span class="text-danger">{{ errors.first('ciudad') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="estado">Estado</label>
                            <div class="col-md-3">
                                <input type="text" name="estado" v-model="Proveedor.estado" class="form-control" placeholder="Estado" autocomplete="off" id="estado" data-vv-as="Estado">
                                <span class="text-danger">{{ errors.first('estado') }}</span>
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="contacto">Contacto</label>
                            <div class="col-md-6">
                                <input type="text" name="contacto" v-model="Proveedor.contacto" class="form-control" placeholder="Contacto" autocomplete="off" id="contacto" data-vv-as="Contacto">
                                <span class="text-danger">{{ errors.first('contacto') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="telefono">Telefono</label>
                            <div class="col-md-6">
                                <input type="text" name="telefono" v-model="Proveedor.telefono" class="form-control" placeholder="Telefono" autocomplete="off" id="telefono" data-vv-as="Telefono">
                                <span class="text-danger">{{ errors.first('telefono') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="correo">Correo electronico</label>
                            <div class="col-md-6">
                                <input type="text" name="correo" v-model="Proveedor.correo" class="form-control" placeholder="Correo" autocomplete="off" id="correo" data-vv-as="Correo">
                                <span class="text-danger">{{ errors.first('correo') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="pagina">Página</label>
                            <div class="col-md-6">
                                <input type="text" name="pagina" v-model="Proveedor.pagina" class="form-control" placeholder="Página" autocomplete="off" id="pagina" data-vv-as="Página">
                                <span class="text-danger">{{ errors.first('pagina') }}</span>
                            </div>
                        </div>

                        <!--
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="banco_transferencia">Banco de transferencia</label>
                            <div class="col-md-9">
                                <input type="text" name="banco_transferencia" v-model="Proveedor.banco_transferencia" class="form-control" placeholder="Banco de transferencia" autocomplete="off" id="banco_transferencia" data-vv-as="banco transferencia">
                                <span class="text-danger">{{ errors.first('banco_transferencia') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                <label class="col-md-3 form-control-label" for="titular_cuenta">Titular de la cuenta</label>
                <div class="col-md-9">
                  <input type="text"  name="titular_cuenta" v-model="Proveedor.titular_cuenta" class="form-control" placeholder="Titular de la cuenta" autocomplete="off" id="titular_cuenta" data-vv-as="titular cuenta" >
                  <span class="text-danger">{{ errors.first('titular_cuenta') }}</span>
                </div>
              </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="numero_cuenta">Número de cuenta</label>
                            <div class="col-md-9">
                                <input type="text" pattern="^[0-9]+" name="numero_cuenta" v-model="Proveedor.numero_cuenta" class="form-control" placeholder="Número de cuenta" autocomplete="off" id="numero_cuenta" data-vv-as="número cuenta">
                                <span class="text-danger">{{ errors.first('numero_cuenta') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="clabe">Clabe</label>
                            <div class="col-md-9">
                                <input type="text" pattern="^[0-9]+" name="clabe" v-model="Proveedor.clabe" class="form-control" placeholder="Clabe" autocomplete="off" id="clabe" data-vv-as="clabe">
                                <span class="text-danger">{{ errors.first('clabe') }}</span>
                            </div>
                        </div>
              -->
                        <!-- <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="dia_credito">Días de crédito</label>
                            <div class="col-md-2">
                                <input type="number" name="dia_credito" v-model="Proveedor.dia_credito" class="form-control" placeholder="Días" autocomplete="off" id="dia_credito" data-vv-as="dia_credito">
                                <span class="text-danger">{{ errors.first('dia_credito') }}</span>
                            </div>
                        </div> -->

                        <!-- <div class="form-group row">
                            <label for="moneda" class="col-md-3 form-control-label">Moneda de pago</label>
                            <div class="col-md-3">
                                <select class="form-control" v-validate="'required'" name="moneda" id="moneda" v-model="Proveedor.tipo_moneda">
                                    <option value="1">Dolares (USD)</option>
                                    <option value="2">Moneda Nacional (MXN)</option>
                                    <option value="3">Euros (EUR)</option>
                                </select>
                                <span class="text-danger">{{ errors.first('moneda') }}</span>
                            </div>
                        </div> -->
                        <!-- <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="tipo_cambio">Tipo de cambio</label>
                            <div class="col-md-2">
                                <input type="text" name="tipo_cambio" v-model="Proveedor.tipo_cambio" class="form-control" placeholder="Tipo cambio" autocomplete="off" id="tipo_cambio" data-vv-as="tipo_cambio">
                                <span class="text-danger">{{ errors.first('tipo_cambio') }}</span>
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="limite_credito">Límite de crédito</label>
                            <div class="col-md-6">
                                <input type="text" min="0" pattern="^[0-9]+" v-validate="'required|decimal:2'" name="limite_credito" v-model="Proveedor.limite_credito" class="form-control" autocomplete="off" id="limite_credito" data-vv-as="limite_credito">
                                <span class="text-danger">{{ errors.first('limite_credito') }}</span>
                            </div>
                        </div>

                        <!-- <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="descripcion">Descripción</label>
                            <div class="col-md-9">
                                <textarea name="descripcion" v-model="Proveedor.descripcion" class="form-control" placeholder="Descripción" autocomplete="off" id="descripcion" data-vv-as="Descripción"></textarea>
                                <span class="text-danger">{{ errors.first('descripcion') }}</span>
                            </div>
                        </div> -->

                        <div class="form-group row">

                            <div class="col-md-11 mx-auto">
                                <div class="form-row">
                                    <div class="col mx-auto">
                                        <label class="font-weight-bold">Datos bancarios</label>
                                    </div>

                                    <div class="col">
                                        <button type="button" @click="abrirModalProveedor(1)" class="btn btn-dark  mb-3 float-sm-right">
                                            <i class="fas fa-plus"></i>&nbsp;Agregar
                                        </button>
                                    </div>
                                </div>
                                <v-client-table :columns="columnsProvedores" :data="ListBancos" :options="optionsProveedores">
                                    <template slot="id" slot-scope="props">
                                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                            <div class="btn-group dropup" role="group">
                                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-grip-horizontal"></i> Acciones
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <button @click="abrirModalProveedor(2,props.row)" class="dropdown-item">
                                                        <i class="icon-pencil"></i>&nbsp;Actualizar.
                                                    </button>
                                                    <template>
                                                        <button v-if="props.row.temp" type="button" class="dropdown-item" @click="DesactivarBanco(props.row,)">
                                                            <i class="fas fa-ban"></i>&nbsp;Eliminar temporal
                                                        </button>

                                                        <button v-if="false" type="button" class="dropdown-item" @click="DesactivarBanco(props.row,)">
                                                            <i class="fas fa-ban"></i>&nbsp;Eliminar
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </v-client-table>
                            </div>
                        </div>
                        <!-- </form> -->
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
                regimen:"",
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
            listaProveedores: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            isLoading: false,
            columns: ['id', 'nombre', 'razon_social', 'giro', 'categoria','total_eval',"regimen_fiscal"],
            tableData: [],
            options: {
                headings: {
                    id: 'Acciones',
                    nombre: 'Nombre Comercial',
                    razon_social: 'Razón Social',
                    direccion: 'Dirección',
                    condicion: 'Estado',
                    banco_transferencia: 'Banco Transferencia',
                    dia_credito: 'Días de credito',
                    limite_credito: 'Límite de crédito',
                    regimen_fiscal: "Régimen Fiscal",
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['nombre', 'razon_social', 'giro', 'banco_transferencia'],
                filterable: ['nombre', 'razon_social', 'giro', 'banco_transferencia'],
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
        },
        // getListaProveedores() {
        //     let me = this;
        //     axios.get('/proveedores').then(response => {
        //             me.listaProveedores = response.data;
        //         })
        //         .catch(function (error) {
        //             console.log(error);
        //         });
        // },
        guardarProveedores(nuevo) {
            this.$validator.validate().then(result => {
                if (result) {
                    this.isLoading = true;
                    let me = this;
                    axios({
                        method: nuevo ? 'POST' : 'PUT',
                        url: nuevo ? me.url : me.url + '/' + this.Proveedor.id,
                        data: {
                            'id': this.Proveedor.id,
                            'nombre': this.Proveedor.nombre,
                            'razon_social': this.Proveedor.razon_social,
                            'direccion': this.Proveedor.direccion,
                            'banco_transferencia': this.Proveedor.banco_transferencia,
                            // 'titular_cuenta' :this.Proveedor.titular_cuenta,
                            'numero_cuenta': this.Proveedor.numero_cuenta,
                            'clabe': this.Proveedor.clabe,
                            'dia_credito': this.Proveedor.dia_credito,
                            'limite_credito': this.Proveedor.limite_credito,
                            'categoria': this.Proveedor.categoria,
                            'condicion_pago': this.Proveedor.condicion_pago,
                            'giro': this.Proveedor.giro,
                            'rfc': this.Proveedor.rfc,
                            'ciudad': this.Proveedor.ciudad,
                            'estado': this.Proveedor.estado,
                            'contacto': this.Proveedor.contacto,
                            'telefono': this.Proveedor.telefono,
                            'correo': this.Proveedor.correo,
                            'pagina': this.Proveedor.pagina,
                            'descripcion': this.Proveedor.descripcion,
                            'tipo_moneda': this.Proveedor.tipo_moneda,
                            'tipo_cambio': this.Proveedor.tipo_cambio,
                            'lista_bancos': this.ListBancos,
                            "regimen_fiscal": this.Proveedor.regimen,

                        }
                    }).then(function (response) {
                        me.isLoading = false;
                        if (response.data.status) {
                            me.cerrarModal();
                            me.getData();
                            me.ListBancos = [];
                            if (!nuevo) {

                                toastr.success('Proveedor Actualizado Correctamente');
                            } else {
                                toastr.success('Proveedor Agregado Correctamente');
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
            switch (modelo) {
                case "solicitud-proveedores": {
                    switch (accion) {
                        case 'registrar': {
                            this.ListBancos = [];
                            this.modal = 1;
                            this.tituloModal = 'Registrar Nuevo Proveedor';
                            Utilerias.resetObject(this.Proveedor);
                            this.tipoAccion = 1;
                            break;
                        }
                        case 'actualizar': {
                            this.ListBancos = data[0];
                            this.modal = 1;
                            this.tituloModal = 'Actualizar Proveedor';
                            this.tipoAccion = 2;
                            this.Proveedor.id = data['id'];
                            this.Proveedor.nombre = data['nombre'];
                            this.Proveedor.razon_social = data['razon_social'];
                            this.Proveedor.direccion = data['direccion'];
                            this.Proveedor.banco_transferencia = data['banco_transferencia'];
                            // this.Proveedor.titular_cuenta = data['titular_cuenta'];
                            this.Proveedor.numero_cuenta = data['numero_cuenta'];
                            this.Proveedor.clabe = data['clabe'];
                            this.Proveedor.dia_credito = data['dia_credito'];
                            this.Proveedor.limite_credito = data['limite_credito'];

                            this.Proveedor.categoria = data['categoria'];
                            this.Proveedor.condicion_pago = data['condicion_pago'];
                            this.Proveedor.giro = data['giro'];
                            this.Proveedor.rfc = data['rfc'];
                            this.Proveedor.ciudad = data['ciudad'];
                            this.Proveedor.estado = data['estado'];
                            this.Proveedor.contacto = data['contacto'];
                            this.Proveedor.telefono = data['telefono'];
                            this.Proveedor.correo = data['correo'];
                            this.Proveedor.pagina = data['pagina'];
                            this.Proveedor.descripcion = data['descripcion'];
                            this.Proveedor.tipo_moneda = data['tipo_moneda'];
                            this.Proveedor.tipo_cambio = data['tipo_cambio'];
                            this.Proveedor.regimen = data['regimen_fiscal'];

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
        descargarReporte()
        {
            window.open('descargar-excel-proveedores/','_blank');
        }
    },
    mounted() {
        this.getData();
        // this.getListaProveedores();
    }
}
</script>

<template>
    <main class="main">
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->

        <br>
        <div class="card" v-show="!detalle">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Empleados
                <button  v-show="PermisosCRUD.Create" type="button" @click="cargarEmpleado('registrar', null)" class="btn btn-dark float-sm-right">
                    <i class="fas fa-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div >
                <!--
                este es el boton de exportar a excel
                download-excel
                parametros
                :data   = los datos en formato json
                :fields = los campos de la cabecera
                 -->
               <download-excel
                    class   = "btn btn-default"
                    :data   = "json_data"
                    :fields = "json_fields"
                    name    = "empleados.xls">
                    <button type="button" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i>&nbsp;Exportar a Excel
                    </button>
                </download-excel>
            </div>
            <div class="card-body">
                <!--
                v-client-table es la tabla que se muestra en la vista principal del empleado
                Parametros
                columns las columnas que se van a ver
                data la informacion que se va a mostrar
                options las opciones de la tabla
                -->
                <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">
                    <template slot="empleado.condicion" slot-scope="props" >
                        <template v-if="props.row.empleado.condicion">
                            <button type="button" class="btn btn-outline-success">Activo</button>
                        </template>
                        <template v-else>
                            <button type="button" class="btn btn-outline-danger">Dado de Baja</button>
                        </template>
                    </template>
                    <template slot="empleado.sexo" slot-scope="props" >
                        <template v-if="props.row.empleado.sexo == 1">
                            <span class="badge badge-light">Masculino</span>
                        </template>
                        <template v-if="props.row.empleado.sexo == 0">
                            <span class="badge badge-light">Femenino</span>
                        </template>
                        <template v-if="props.row.empleado.sexo == null">
                            <span class="badge badge-light">Sin Dato</span>
                        </template>
                    </template>
                    <template slot="empleado.id_checador" slot-scope="props" >
                        <template v-if="props.row.empleado.id_checador==1">
                            <button type="button" class="btn btn-outline-primary">Conserflow</button>
                        </template>
                        <template v-else-if="props.row.empleado.id_checador==2">
                            <button type="button" class="btn btn-outline-warning">Conserflow</button>
                        </template>
                        <template v-else-if="props.row.empleado.id_checador==3">
                            <button type="button" class="btn btn-outline-success">CSCT</button>
                        </template>
                        <template v-else-if="props.row.empleado.id_checador==4">
                            <button type="button" class="btn btn-outline-info">CSCT</button>
                        </template>
                        <template v-else>
                            <button type="button" class="btn btn-outline-danger">No asignado</button>
                        </template>
                    </template>
                    <template slot="empleado.id" slot-scope="props">
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group dropup" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <button v-show="PermisosCRUD.Read" type="button" class="dropdown-item" @click="descargar1(props.row.empleado)" >
                                <i class="far fa-file-pdf"></i>&nbsp;Formato Alta Empleado</button>

                        <button v-show="PermisosCRUD.Read" type="button" class="dropdown-item" @click="cargarEmpleado('actualizar',props.row)" >
                            <i class="fas fa-diagnoses"></i>&nbsp;Detalles Empleado</button>

                        <button v-show="PermisosCRUD.Read" v-if="props.row.empleado.condicion==1" type="button" class="dropdown-item" @click="AsignarChecador(props.row)" >
                            <i class="fas fa-user-clock"></i>&nbsp;Asignar Checador</button>

                        <template v-if="props.row.empleado.condicion">

                            <button v-show="PermisosCRUD.Update" type="button" class="dropdown-item"  @click="actdesact(props.row.empleado.id,0)" >
                                <i class="fas fa-ban"></i>&nbsp;Desactivar</button>
                        </template>
                        <template v-else>

                            <button type="button" class="dropdown-item"  @click="actdesact(props.row.empleado.id,1)" >
                                <i class="icon-check"></i>&nbsp;Activar</button>

                        </template>
                                    </div>
                                </div>
                                </div>
                            </template>
                            <template slot="qr" slot-scope="props">
                              <button type="button" class="btn btn-outline-dark"  @click="descargarqr(props.row.empleado.id)" >
                                  <i class="fas fa-qrcode"></i>&nbsp;</button>
                            </template>
                </v-client-table>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
        <!-- Listado de componentes hijos -->
        <div class="card" v-show="detalle">
            <div class="card-header">
                <h6 style="text-transform: uppercase;"><i class="fa fa-align-justify"></i> {{ empleado == null ? 'Nuevo' : empleado.nombre + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno }}</h6>
                <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
                    <i class="fa fa-arrow-left"></i>&nbsp;Atras
                </button>
            </div>
            <div class="accordion">

                <div class="card ">
                    <div class="card-header bg-dark" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-dark btn callout callout-light btn-lg btn-block text-left " data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                             Empleado
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse show"   aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <alta ref="alta" @clicked="onClickRegistrado"></alta>
                        </div>
                    </div>
                </div>
                 <div class="card" v-show="ver">
                    <div class="card-header  bg-dark" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-dark btn callout callout-light btn-lg btn-block text-left" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Contacto
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse"  aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <contacto ref="contacto"></contacto>
                        </div>
                    </div>
                </div>
                <div class="card"  v-show="ver">
                    <div class="card-header bg-dark" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-dark btn callout callout-light btn-lg btn-block text-left" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Datos Bancarios
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse"  aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <datosbancarios ref="datosbancarios"></datosbancarios>
                        </div>
                    </div>
                </div>
                <div class="card"  v-show="ver">
                    <div class="card-header bg-dark" id="headingFour">
                        <h5 class="mb-0">
                            <button class="btn btn-dark btn callout callout-light btn-lg btn-block text-left" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Familiares
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFour" class="collapse"  aria-labelledby="headingFour" data-parent="#accordion">
                        <div class="card-body">
                            <familiares ref="familiares"></familiares>
                        </div>
                    </div>
                </div>
                <div class="card"  v-show="ver">
                    <div class="card-header bg-dark" id="headingFive">
                        <h5 class="mb-0">
                            <button class="btn btn-dark btn callout callout-light btn-lg btn-block text-left" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Conocidos de la Empresa
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFive" class="collapse"  aria-labelledby="headingFive" data-parent="#accordion">
                        <div class="card-body">
                            <conocidos ref="conocidos"></conocidos>
                        </div>
                    </div>
                </div>
                <div class="card"  v-show="ver">
                    <div class="card-header bg-dark" id="headingSix">
                        <h5 class="mb-0">
                            <button class="btn btn-dark btn callout callout-light btn-lg btn-block text-left" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            Referencias
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                        <div class="card-body">
                            <referencias ref="referencias"></referencias>
                        </div>
                    </div>
                </div>
                <div class="card"  v-show="ver">
                    <div class="card-header bg-dark" id="headingSeven">
                        <h5 class="mb-0">
                            <button class="btn btn-dark btn callout callout-light btn-lg btn-block text-left" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            Expedientes
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSeven" class="collapse"  aria-labelledby="headingSeven" data-parent="#accordion">
                        <div class="card-body">
                            <expedientes ref="expedientes"></expedientes>
                        </div>
                    </div>
                </div>
                <div class="card"  v-show="ver">
                    <div class="card-header bg-dark" id="headingEight">
                        <h5 class="mb-0">
                            <button class="btn btn-dark btn callout callout-light btn-lg btn-block text-left" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            Dirección
                            </button>
                        </h5>
                    </div>
                    <div id="collapseEight" class="collapse"  aria-labelledby="headingEight" data-parent="#accordion">
                        <div class="card-body">
                            <direccion ref="direccion"></direccion>
                        </div>
                    </div>
                </div>
                <div class="card"  v-show="ver">
                    <div class="card-header bg-dark" id="headingNine">
                        <h5 class="mb-0">
                            <button class="btn btn-dark btn callout callout-light btn-lg btn-block text-left" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            Beneficiarios
                            </button>
                        </h5>
                    </div>
                    <div id="collapseNine" class="collapse"  aria-labelledby="headingNine" data-parent="#accordion">
                        <div class="card-body">
                            <beneficiarios ref="beneficiarios"></beneficiarios>
                        </div>
                    </div>
                </div>
                <div class="card"  v-show="ver">
                    <div class="card-header bg-dark" id="headingTen">
                        <h5 class="mb-0">
                            <button class="btn btn-dark btn callout callout-light btn-lg btn-block text-left" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                            Teléfonos Corporativos
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordion">
                        <div class="card-body">
                            <telefonos ref="telefonos"></telefonos>
                        </div>
                    </div>
                </div>
                <div class="card"  v-show="ver">
                    <div class="card-header bg-dark" id="headingEleven">
                        <h5 class="mb-0">
                            <button class="btn btn-dark btn callout callout-light btn-lg btn-block text-left" data-toggle="collapse" data-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                            Correos Corporativos
                            </button>
                        </h5>
                    </div>
                    <div id="collapseEleven" class="collapse"  aria-labelledby="headingEleven" data-parent="#accordion">
                        <div class="card-body">
                            <correos ref="correos"></correos>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin de listado componentes -->
    </div>

    </main>
</template>

<script>
    import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const Conocidos = r => require.ensure([], () => r(require('./Conocidos.vue')), 'rh');
const Referencias = r => require.ensure([], () => r(require('./Referencias.vue')), 'rh');
const Familiares = r => require.ensure([], () => r(require('./../Familiares/Familiares.vue')), 'rh');
const Beneficiarios = r => require.ensure([], () => r(require('./../Beneficiarios/Beneficiarios.vue')), 'rh');
const Contacto = r => require.ensure([], () => r(require('./Contactos.vue')), 'rh');
const DatosBancarios = r => require.ensure([], () => r(require('./Datos-ban-emp.vue')), 'rh');
const Expedientes = r => require.ensure([], () => r(require('./Expedientes.vue')), 'rh');
const Alta = r => require.ensure([], () => r(require('./Alta.vue')), 'rh');
const Direccion = r => require.ensure([], () => r(require('./Direccion-Empleado.vue')), 'rh');
const Telefonos = r => require.ensure([], () => r(require('./Telefonos.vue')), 'rh');
const Correos = r => require.ensure([], () => r(require('./Correos.vue')), 'rh');

    export default {
        //NOTA muy importante para exportar a excel
        computed:{
            updated: function(){
                return this.exportar(this.$refs.myTable.allFilteredData);
            },
        },
        components: {
            'conocidos': Conocidos,
            'referencias': Referencias,
            'familiares': Familiares,
            'contacto': Contacto,
            'datosbancarios': DatosBancarios,
            'expedientes':  Expedientes,
            'alta': Alta,
            'direccion': Direccion,
            'beneficiarios' : Beneficiarios,
            'telefonos' : Telefonos,
            'correos' : Correos,
        },
        data (){
            return {
                PermisosCRUD: {},
                detalle: false,
                url: '/empleado',
                ver :false,
                swal_titulo:'',
                swal_msg :'',
                swal_tle :'',
                empleado: null,
                tipoAccion : 0,
                error : 0,
                errorMostrarMsj : [],
                arreglo :{
                    titulo: 'Empledos',
                    emit:   'abrirModal',
                    modulo: 'empleado',
                    accion: 'registrar',
                },
                json_fields : {
                        'nombre' : 'String',
                        'ap_paterno' : 'String',
                        'ap_materno' : 'String',
                        'puesto' : 'String',
                        'departamento' : 'String',
                        'condicion' : 'String',
                        'sexo' : 'String',
                        'lug_nac' : 'String',
                        'fech_nac' : 'String',
                        'updated_at' : 'String'
                },
                json_data : [],
                json_meta: [
                    [{
                        "key": "charset",
                        "value": "utf-8"
                    }]
                ],
                columns: [
                    'empleado.id',
                    'empleado.nombre',
                    'empleado.ap_paterno',
                    'empleado.ap_materno',
                    'empleado.sexo',
                    'empleado.tipo_sangre',
                    'puesto.nombre',
                    'departamento.nombre',
                    'estados.nombre',
                    'empleado.condicion',
                    'empleado.id_checador',
                    'qr',
                    'empleado.updated_at'
                ],
                tableData: [],
                options: {
                    headings: {
                        'empleado.id': '',
                        'empleado.nombre': 'Nombre',
                        'empleado.ap_paterno': 'Apellido Paterno',
                        'empleado.ap_materno': 'Apellido Materno',
                        'empleado.sexo': 'Sexo',
                        'empleado.tipo_sangre': 'Tipo de Sangre',
                        'puesto.nombre': 'Puesto',
                        'departamento.nombre': 'Departamento',
                        'estados.nombre': 'Estado Civil',
                        'empleado.condicion':'Condicion',
                        'empleado.id_checador':"Empresa",
                        'empleado.updated_at' : 'Ultima Actualización',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: [
                        'empleado.nombre',
                        'empleado.ap_paterno',
                        'empleado.ap_materno',
                        'empleado.p_nombre',
                        'empleado.d_nombre',
                        'empleado.condicion',
                        'empleado.updated_at',
                        'empleado.id_checador'
                        ],
                    filterable: [
                        'empleado.nombre',
                        'empleado.ap_paterno',
                        'empleado.ap_materno',
                        'empleado.p_nombre',
                        'empleado.d_nombre',
                        'empleado.condicion',
                        'empleado.sexo',
                        'empleado.id_checador'
                    ],
                    filterByColumn: true,
                    listColumns: {
                        'empleado.condicion': [{
                            id: 1,
                            text: 'Activo'
                        },
                        {
                            id: 0,
                            text: 'Dado de Baja'
                        }
                        ],
                        'empleado.sexo': [{
                            id: 1,
                            text: 'Masculino'
                        },
                        {
                            id: 0,
                            text: 'Femenino'
                        },
                        {
                            id: null,
                            text: 'Sin Dato'
                        }
                      ],
                      'empleado.id_checador': [{
                          id: 1,
                          text: 'Conserflow Semanal'
                      },
                      {
                          id: 2,
                          text: 'Conserflow Quincenal'
                      },
                      {
                          id: 3,
                          text: 'CSCT Semanal'
                      },
                      {
                          id: 4,
                          text: 'CSCT Quincenal'
                      }
                      ]
                    },
                    texts:config.texts
                },
            }
        },
        methods : {
            cargarEmpleado(accion, dataEmpleado = [])
            {
                this.detalle = true;

                if (accion == 'actualizar') {
                    // this.ver = false;
                    this.tipoAccion = 2;
                    this.empleado = dataEmpleado.empleado;
                    var childAlta = this.$refs.alta;
                    childAlta.cargarEmpleado(this.empleado);
                    this.mostrarTabs();
                    // this.setTab('tab1');
                } else {
                    this.ver = false;
                    this.empleado = null;
                    this.tipoAccion = 1;
                    var childAlta = this.$refs.alta;
                    childAlta.cargarEmpleado(null);
                    // this.setTab('tab1');
                }
            },
            descargar1(data) {
                console.log(data);
                window.open('descargar-alt/' + data.id, '_blank');

            },

            descargarqr(data) {
                // console.log(data);
                window.open('generar/codigos/qr/emp/' + data, '_blank');

            },
            mostrarTabs() {
                this.ver = true;

                var childConocidos = this.$refs.conocidos;
                childConocidos.cargarConocidos(this.empleado);

                var childReferencias = this.$refs.referencias;
                childReferencias.cargarreferencia(this.empleado);

                var childFamiliares = this.$refs.familiares;
                childFamiliares.cargardireccion(this.empleado);

                var childContacto = this.$refs.contacto;
                childContacto.cargarContacto(this.empleado);

                var childDatosBan = this.$refs.datosbancarios;
                childDatosBan.cargarDatosBan(this.empleado);

                var childExpedientes = this.$refs.expedientes;
                childExpedientes.cargarExpediente(this.empleado);

                var childDirecciones = this.$refs.direccion;
                childDirecciones.cargarDirecciones(this.empleado);

                var childBeneficiarios = this.$refs.beneficiarios;
                childBeneficiarios.cargarbeneficiario(this.empleado);

                var childTelefonos = this.$refs.telefonos;
                childTelefonos.cargarTelefonos(this.empleado);

                var childCorreos = this.$refs.correos;
                childCorreos.cargarCorreos(this.empleado);
            },
            listar(){

                let me=this;
                this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
                axios.get(me.url).then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            actdesact(id,tipo){
                if(tipo){
                    this.swal_titulo = 'Esta seguro de activar al empleado?';
                    this.swal_tle = 'Activado';
                    this.swal_msg = 'El registro ha sido activado con éxito.';
                }else{
                    this.swal_titulo = 'Esta seguro de desactivar al empleado?';
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
                    axios.get(me.url+'/'+id+'/edit').then(function (response) {
                        swal(me.swal_tle,me.swal_msg,'success')
                        me.listar();
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
                })
            },
            exportar(datos){
                var condicion = '';
                var sexo = '';
                this.json_data=[];
                this.json_data.push(
                    {
                        'nombre':'Nombre',
                        'ap_paterno':'A Paterno',
                        'ap_materno':'A Materno',
                        'puesto':'Puesto',
                        'departamento':'Departamento',
                        'condicion':'Estado',
                        'sexo':'Sexo',
                        'lug_nac':'Lugar de Nacimiento',
                        'fech_nac':'Fecha de Nacimiento',
                        'nss_imss':'N° de seguridad social',
                        'updated_at':'Actualizado por ultima vez',
                    });
                datos.forEach(dato =>{
                    condicion = 0;
                    sexo = 0;
                    if (dato.empleado.condicion) {
                        condicion = 'Activo';
                    }
                    if (dato.empleado.condicion == 0) {
                        condicion = 'Dado de Baja';
                    }
                    if (dato.empleado.sexo == 0) {
                        sexo = 'Femenino';
                    }
                    if (dato.empleado.sexo == null) {
                        sexo = 'Null';
                    }
                    if (dato.empleado.sexo == 1) {
                        sexo = 'Masculino';
                    }
                    this.json_data.push(
                    {
                        'nombre':dato.empleado.nombre,
                        'ap_paterno':dato.empleado.ap_paterno,
                        'ap_materno':dato.empleado.ap_materno,
                        'puesto':dato.puesto.nombre,
                        'departamento':dato.departamento.nombre,
                        'condicion':condicion,
                        'sexo':sexo,
                        'lug_nac':dato.empleado.lug_nac,
                        'fech_nac':dato.empleado.fech_nac,
                        'nss_imss':dato.empleado.nss_imss,
                        'updated_at':dato.empleado.updated_at == null ? '' : dato.empleado.updated_at

                    });
                });
                return this.json_data;
            },
            maestro(){
                this.detalle = false;
            },
            // setTab(tabName) {
            //     this.activeTab = tabName;
            // },
            onClickRegistrado(data, registrado)
            {
                this.empleado = data;
                this.tipoAccion = 2;
                this.listar();

                if (registrado)
                    this.mostrarTabs();
            },
            AsignarChecador(data)
            {
                let x=this;
                Swal.fire({
                    title: 'Asignar checador',
                    input: 'select',
                    inputOptions: {
                        '1': 'Conserflow - Semanal',
                        '2': 'Conserflow - Quincenal',
                        '3': 'CSCT - Semanal',
                        '4': 'CSCT - Quincenal'
                    },
                    showCancelButton: true
                    }).then(function (result) {
                    if (result.value)
                    {
                        if (result.value == null) return;
                        if (result.value != "")
                        {
                            axios(
                            {
                                method: "POST",
                                url: x.url + "/asignarchecador",
                                data:
                                {
                                    id_checador:result.value,
                                    empleado_id:data.empleado.id
                                },
                            }).then((res) =>
                            {
                                if (res.data.status)
                                {
                                    toastr.success("", "Guardado correctamente");
                                    x.listar();
                                }
                                else
                                {
                                    toastr.error("", "Error");
                                    console.error(res);
                                }
                            });
                        }
                    }
                    });
            }
        },
        mounted() {
            this.listar();
        }
    }
</script>

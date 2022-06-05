<template>
<main class="main">
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <br>
        <div class="card" v-show="!detalle && !detalleContrato">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Contratos {{empresa == 1 ? 'CONSERFLOW' : 'CSCT'}}
                <div class="dropdown">
                  <button class="btn btn-secondary dropdown-toggle float-sm-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Empresa
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenu2" v-model="empresa">
                    <button class="dropdown-item" type="button" @click="empresa = 1; getDataCFW();">Conserflow</button>
                    <button class="dropdown-item" type="button" @click="empresa = 2; getDataCSCT();">Constructora</button>
                  </div>
                </div>
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
                                    <button type="button" @click="cargarTipoContrato(props.row.empleado)" class="dropdown-item">
                                        <i class="fas fa-eye"></i>Ver Contratos Activos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </v-client-table>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
        <!-- Listado de escolaridades del empleado -->
        <br>
        <div class="card" v-show="detalle && !detalleContrato">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Contratos pertenecientes a: {{ empleado == null ? '': empleado.nombre + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno }} de {{empresa == 1 ? 'CONSERFLOW' : 'CSCT'}}
                <button type="button" @click="abrirModal('tipo-contrato','registrar',empleado.id)" class="btn btn-dark float-sm-right">
                    <i class="fas fa-plus"></i>&nbsp;Nuevo
                </button>
                <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
                    <i class="fa fa-arrow-left"></i>&nbsp;Atras
                </button>
            </div>
            <div class="card-body">
                <vue-element-loading :active="isLoadingDetalle" />
                <v-client-table :columns="columnsTipoContrato" :data="tableDataTipoContrato" :options="optionsTipoContrato" ref="myTableTipoContrato">
                    <template slot="condicion" slot-scope="props">
                        <template v-if="props.row.condicion">
                            <button type="button" class="btn btn-outline-success">Activo</button>
                        </template>
                        <template v-else>
                            <button type="button" class="btn btn-outline-danger">{{props.row.fin_de_contratos}}</button>
                        </template>
                    </template>
                    <template slot="id" slot-scope="props">
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group dropup" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <button type="button" @click="abrirModal('tipo-contrato','actualizar',empleado.id,props.row)" class="dropdown-item">
                                        <i class="icon-pencil"></i>&nbsp; Actualizar Contrato
                                    </button>
                                    <template v-if="props.row.condicion">
                                        <button type="button" class="dropdown-item" @click="activarContrato(props.row.id, 0,props.row.empleado_id,props.row.tipo_nomina_id)">
                                            <i class="far fa-calendar-times"></i>&nbsp; Finalizar Contrato
                                        </button>
                                        <button type="button" class="dropdown-item" @click="descargar(props.row.id)">
                                            <i class="icon-cloud-download"></i> &nbsp; Descargar Contrato
                                        </button>
                                    </template>
                                    <!-- <template v-else>
                <button type="button" class="dropdown-item" @click="activarContrato(props.row.id, 1,props.row.empleado_id)">
                  <i class="icon-check"></i>&nbsp; Activar
                </button>
              </template> -->
                                </div>
                            </div>
                        </div>
                    </template>
                </v-client-table>
                {{tipoContrato.motivo}}
            </div>
        </div>
        <!-- Fin Listado de escolaridades del empleado -->
        <div class="card" v-show="detalleContrato">
            <div class="card-header">
                <i class="fa fa-align-justify"></i>{{ tituloModal }} {{ empleado == null ? '': empleado.nombre + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno }}
                <button type="button" @click="maestroContratos()" class="btn btn-secondary float-sm-right">
                    <i class="fa fa-arrow-left"></i>&nbsp;Atras
                </button>
            </div>
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab" @click="setTab('tab1')">Contrato de Empleado</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" v-show="(tipoAccion == 2)" data-toggle="tab" href="#tab2" role="tab" @click="setTab('tab2')">Sueldo</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" :class="(activeTab == 'tab1') ? 'active show': ''" id="tab1" role="tabpanel">
                    <vue-element-loading :active="isLoading" />
                    <div class="form-group row">
                        <div class="col-md-7">
                            <span class="text-danger">{{ errors.first('empleado_id') }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="proyecto_id">Proyecto</label>
                        <div class="col-md-3">
                            <select class="form-control" id="proyecto_id" name="proyecto_id" v-model="tipoContrato.proyecto_id" v-validate="'excluded:0'" data-vv-as="Proyecto">
                                <option v-for="item in listaProyectos" :value="item.proyecto.id" :key="item.proyecto.id">{{ item.proyecto.nombre_corto }}</option>
                            </select>
                            <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
                        </div>
                        <label class="col-md-3 form-control-label" for="tipo_contrato_id">Tipo de Contrato</label>
                        <div class="col-md-3">
                            <select class="form-control" id="tipo_contrato_id" name="tipo_contrato_id" @change="onChange($event)" v-model="tipoContrato.tipo_contrato_id" v-validate="'excluded:0'" data-vv-as="Tipo de Contrato">
                                <option v-for="item in listaTipoContrato" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                            </select>
                            <span class="text-danger">{{ errors.first('tipo_contrato_id') }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="tipo_nomina_id">Tipo de Nómina</label>
                        <div class="col-md-3">
                            <select class="form-control" id="tipo_nomina_id" name="tipo_nomina_id" v-model="tipoContrato.tipo_nomina_id" v-validate="'excluded:0'" data-vv-as="Tipo de Nómina">
                                <option v-for="item in listaTipoNomina" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                            </select>
                            <span class="text-danger">{{ errors.first('tipo_nomina_id') }}</span>
                        </div>
                        <label class="col-md-3 form-control-label" for="horario_id">Horario de Trabajo</label>
                        <div class="col-md-3">
                            <select class="form-control" id="horario_id" name="horario_id" v-model="tipoContrato.horario_id" v-validate="'excluded:0'" data-vv-as="Horario de Trabajo">
                                <!--<option v-for="item in listaHorarios" :value="item.id" :key="item.id" v-if="item.id <= 2">
                    De: {{ item.hora_entrada }} A {{ item.hora_salida }}
                  </option>
                  <option v-for="item in listaHorarios" :value="item.id" :key="item.id" v-if="item.id == 3">
                    Sin Horario
                  </option>
                  <option v-for="item in listaHorarios" :value="item.id" :key="item.id" v-if="item.id == 4">
                    De: {{ item.hora_entrada }} A {{ item.hora_salida }}
                  </option>-->
                                <option v-for="item in listaHorarios" :value="item.id" :key="item.id">
                                    {{ item.nombre }}
                                </option>
                            </select>
                            <span class="text-danger">{{ errors.first('horario_id') }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3" for="text-input">Inicio de Contrato</label>
                        <div class="col-md-3">
                            <input type="date" v-validate="'required'" name="fecha_ingreso" v-model="tipoContrato.fecha_ingreso" class="form-control" placeholder="Fecha de Inicio">
                            <span class="text-danger">{{ errors.first('fecha_ingreso') }}</span>
                        </div>
                        <label class="col-md-3" for="text-input">Fin de Contrato</label>
                        <div class="col-md-3">
                            <input id="fechafin" type="date" v-validate="'required'" name="fecha_fin" v-model="tipoContrato.fecha_fin" class="form-control" placeholder="Fecha de Finalización" @change="validar">
                            <span class="text-danger">{{ errors.first('fecha_fin') }}</span>
                        </div>
                    </div>
                    <div class="form-group row">

                        <label class="col-md-3 form-control-label" for="tipo_ubicacion_id">Ubicación</label>
                        <div class="col-md-3">
                            <select class="form-control" id="tipo_ubicacion_id" name="tipo_ubicacion_id" v-model="tipoContrato.tipo_ubicacion_id" v-validate="'excluded:0'" data-vv-as="Ubicación">
                                <option v-for="item in listaTipoUbicacion" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                            </select>
                            <span class="text-danger">{{ errors.first('tipo_ubicacion_id') }}</span>
                        </div>
                    </div>
                    <!-- Testigos -->
                    <hr class="mx-3">
                    <div class="form-group row">
                        <template>
                            <div class="col-5" v-if="tiene_testigo_1">
                                <label class="form-control-label col-3">Testigo 1</label>

                                <v-select class="col" :options="listaEmpleadosTestigo" v-model="testigo1" label="nombre"></v-select>
                            </div>
                            <div v-else class="col-5">
                                <button class="btn btn-sm btn-dark" @click="ActivarTestigo(1)">
                                    <i class="fas fa-plus mr-1"></i> Añadir testigo
                                </button>
                            </div>
                        </template>
                        <template>
                            <div class="col-5" v-if="tiene_testigo_2">

                                <label class="form-control-label col-3">Testigo 1</label>

                                <v-select class="col" :options="listaEmpleadosTestigo" v-model="testigo2" label="nombre"></v-select>
                            </div>
                            <div v-if="tiene_testigo_1 && !tiene_testigo_2" class="col-5">
                                <button class="btn btn-sm btn-dark" @click="ActivarTestigo(2)">
                                    <i class="fas fa-plus mr-1"></i> Añadir testigo
                                </button>
                            </div>
                        </template>
                    </div>
                    <br>

                    <div class="card-footer">
                        <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" v-bind:disabled="desabilotarboton == 1 || desabilotarbotonn == 1" @click="guardarTipoContrato(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarTipoContrato(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                    </div>
                    <!-- </form> -->
                </div>
                <div class="tab-content">
                    <div class="tab-pane" :class="(activeTab == 'tab2') ? 'active show': ''" id="tab2" role="tabpane2">
                        <sueldo ref="sueldo"></sueldo>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Inicio del modal agregar/actualizar-->
</main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
const Sueldo = r => require.ensure([], () => r(require('./Sueldo.vue')), 'rh');

export default
{
    components:
    {
        'sueldo': Sueldo,
    },
    data()
    {
        return {
            empresa : 1,
            activeTab: 'tab1',
            url: '/contrato',
            contrato_sueldo: null,
            ideditar: 0,
            empleado: null,
            detalle: false,
            desabilotarboton: 0,
            desabilotarbotonn: 0,
            detalleContrato: false,
            tipoContrato:
            {
                id: 0,
                fecha_ingreso: '',
                fecha_fin: '',
                tipo_nomina_id: 0,
                empleado_id: 0,
                tipo_ubicacion_id: 0,
                horario_id: 0,
                tipo_contrato_id: 0,
                proyecto_id: 0,
                motivo: '',
            },
            Metodo: '',
            renovacion: ['SI', 'NO'],
            renovacionid: [1, 0],
            listaTipoNomina: [],
            listaEmpleados: [],
            listaTipoContrato: [],
            listaTipoUbicacion: [],
            listaHorarios: [],
            listaProyectos: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            disabled: 0,
            fechavalidar: 0,
            guardarValidacion: 0,
            isLoading: false,
            isLoadingDetalle: false,
            columns: [
                'empleado.id',
                'empleado.nombre',
                'empleado.ap_paterno',
                'empleado.ap_materno',
                'puesto.nombre',
                'departamento.nombre',
            ],
            tableData: [],
            columnsTipoContrato: [
                'id',
                'e_nombre',
                'tu_nombre',
                'p_nombre',
                'fecha_ingreso',
                'fecha_fin',
                'tn_nombre',
                'h_he',
                'h_hs',
                'th_nombre',
                'tc_nombre',
                'em_nombre',
                'condicion',
            ],
            tableDataTipoContrato: [],
            options:
            {
                headings:
                {
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
                sortable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno', 'puesto.nombre', 'departamento.nombre'],
                filterable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno', 'puesto.nombre', 'departamento.nombre'],
                filterByColumn: true,
                texts: config.texts
            },
            optionsTipoContrato:
            {
                headings:
                {
                    'id': 'Acciones',
                    'e_nombre': 'Nombre',
                    'tu_nombre': 'Ubicación',
                    'p_nombre': 'Proyecto',
                    'fecha_ingreso': 'Inicio',
                    'fecha_fin': 'Finaliza',
                    'tn_nombre': 'Tipo de Nómina',
                    'h_he': 'Hora de Entrada',
                    'h_hs': 'Hora de Salida',
                    'th_nombre': 'Tipo de Horario',
                    'tc_nombre': 'Tipo de Contrato',
                    'condicion': 'Estado',
                    'em_nombre': 'Empresa',
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['tipo_nomina_id', 'fecha_ingreso', 'fecha_fin', 'tc_nombre', 'p_nombre'],
                filterable: ['tipo_nomina_id', 'fecha_ingreso', 'fecha_fin', 'tc_nombre', , 'p_nombre', 'condicion'],
                filterByColumn: true,
                listColumns:
                {
                    condicion: config.columnCondicion
                },
                texts: config.texts
            },
            // Testigos
            listaEmpleadosTestigo: [],
            tiene_testigo_1: false,
            tiene_testigo_2: false,
            testigo1:
            {},
            testigo2:
            {},
        }
    },
    computed:
    {},
    methods:
    {
        validar()
        {
            var a = this.tipoContrato.fecha_ingreso;
            var b = this.tipoContrato.fecha_fin;
            var fechaIngreso = new Date(a).getTime();
            var fechaFin = new Date(b).getTime();
            var diff = fechaFin - fechaIngreso;
            var diferencia = diff / (1000 * 60 * 60 * 24);
            if (diferencia < 0)
            {
                this.fechavalidar = 1;
                toastr.error('La fecha de finalización del Contrato no debe ser menor a la fecha de Inicio');
            }
            else
            {
                this.fechavalidar = 0;
            }
        },
        onChange(event)
        {
            this.tipoContrato.fecha_fin = '';
            document.getElementById("fechafin").valueAsDate = null;
            console.log(event.target.value)
        },
        horaActual()
        {
            var hoy = new Date();
            var dd = hoy.getDate();
            var mm = hoy.getMonth() + 1; //hoy es 0!
            var yyyy = hoy.getFullYear();
            if (dd < 10)
            {
                dd = '0' + dd
            }
            if (mm < 10)
            {
                mm = '0' + mm
            }
            hoy = yyyy + '-' + mm + '-' + dd;
            this.tipoContrato.fecha_ingreso = hoy;
        },
        getDataCFW()
        {
            let me = this;
            axios.get('get/contratos/empleado/cfw').then(response =>
                {
                    me.tableData = response.data;
                })
                .catch(function (error)
                {
                    console.log(error);
                });
        },
        getDataCSCT()
        {
            let me = this;
            axios.get('get/contratos/empleado/csct').then(response =>
                {
                    me.tableData = response.data;
                })
                .catch(function (error)
                {
                    console.log(error);
                });
        },
        descargar(data)
        {
            var id = data;
            if (this.empresa == 1) {
              window.open('descargar-contratop/' + id, '_blank');
            }else if (this.empresa == 2) {
              window.open('descargar-contratop-csct/' + id, '_blank');
            }
        },
        listar()
        {
            let me = this;
            axios.get(me.url).then(response =>
                {
                    me.tableData = response.data;
                })
                .catch(function (error)
                {
                    console.log(error);
                });
        },
        getLista()
        {
            let me = this;
            axios.get('/empleado').then(response =>
                {
                    me.listaEmpleados = response.data;
                })
                .catch(function (error)
                {
                    console.log(error);
                });

            axios.get('/tiponomina').then(response =>
                {
                    me.listaTipoNomina = response.data;
                })
                .catch(function (error)
                {
                    console.log(error);
                });

            axios.get('/tipocontrato').then(response =>
                {
                    me.listaTipoContrato = response.data;
                })
                .catch(function (error)
                {
                    console.log(error);
                });

            axios.get('/tipoubicacion').then(response =>
                {
                    me.listaTipoUbicacion = response.data;
                })
                .catch(function (error)
                {
                    console.log(error);
                });

            axios.get('tipohorario').then(response =>
                {
                    me.listaHorarios = response.data;
                })
                .catch(function (error)
                {
                    console.log(error);
                });

            axios.get('/proyecto-todos').then(response =>
                {
                    me.listaProyectos = response.data;
                })
                .catch(function (error)
                {
                    console.log(error);
                });
        },
        guardarTipoContrato(nuevo)
        {
            if (this.fechavalidar == 0)
            {
                this.$validator.validate().then(result =>
                {
                    if (result)
                    {
                        console.error(this.testigo1);
                        console.error(this.testigo2);

                        if (this.tiene_testigo_1 & this.testigo1.id == null)
                        {
                            toastr.warning("Seleccione el testigo 1");
                            return;
                        }
                        if (this.tiene_testigo_2 & this.testigo2.id == null)
                        {
                            toastr.warning("Seleccione el testigo 2");
                            return;
                        }
                        this.isLoading = true;
                        let me = this;
                        axios(
                        {
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ?
                                me.url + '/' + this.tipoContrato.empleado_id + '/contratos' : me.url + '/' + this.tipoContrato.id + '/' + 'contratos/' + this.tipoContrato.empleado_id,
                            data:
                            {
                                'metodo': this.Metodo,
                                'tipo_ubicacion_id': this.tipoContrato.tipo_ubicacion_id,
                                'fecha_ingreso': this.tipoContrato.fecha_ingreso,
                                'fecha_fin': this.tipoContrato.fecha_fin,
                                'tipo_nomina_id': this.tipoContrato.tipo_nomina_id,
                                'empleado_id': this.tipoContrato.empleado_id,
                                'horario_id': this.tipoContrato.horario_id,
                                'tipo_contrato_id': this.tipoContrato.tipo_contrato_id,
                                'proyecto_id': this.tipoContrato.proyecto_id,
                                'id': this.tipoContrato.id,
                                "testigo_1_id": this.testigo1.id,
                                "testigo_2_id": this.testigo2.id,
                                'empresa' : this.empresa,
                            }
                        }).then(function (response)
                        {
                            console.log(response.data);
                            me.isLoading = false;
                            if (response.data.status)
                            {
                                me.cargarTipoContrato(me.empleado);
                                me.maestroContratos();
                                if (!nuevo)
                                {
                                    toastr.success('Contrato Actualizado Correctamente');
                                }
                                else
                                {
                                    toastr.success('Contrato Registrado Correctamente');
                                    toastr.info('Ahora Debe Registrar un Sueldo');
                                }
                            }
                            else
                            {
                                swal(
                                {
                                    type: 'error',
                                    html: response.data.errors.join('<br>'),
                                });
                            }
                        }).catch(function (error)
                        {
                            console.log(error);
                        });
                    }
                });
            }
        },
        activarContrato(id, activar, empleado, nomina)
        {
            var cadena = [];
            var cadenaid = [];
            var renovacion = ['SI', 'NO'];
            var renovacionid = [1, 0];
            var adjunto = '';
            var metodo = 'Bajas';
            var renovar = 0;
            swal(
            {
                title: activar ? '¿Esta seguro de activar este Contrato?' : '¿Esta seguro de finalizar este Contrato?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#f86c6b',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) =>
            {
                if (result.value)
                {
                    let me = this;
                    if (activar)
                    {
                        toastr.success('Contrato Activado con Éxito');
                    }
                    else
                    {
                        /*consulta de tipofinalizacion*/
                        axios.get('/tipofinalizacion/create').then(function (response)
                        {

                            for (var j = 0; j < response.data.length; j++)
                            {
                                cadena.push(response.data[j].nombre);
                                cadenaid.push(response.data[j].id);
                            }
                            swal(
                            {
                                title: 'Motivo de la Baja',
                                input: 'select',
                                inputOptions: cadena,
                                inputPlaceholder: 'Seleccionar Motivo',
                                confirmButtonText: 'Continuar <i class="fa fa-arrow-right></i>',
                                showCancelButton: true,
                                customClass: 'form-check form-check-inline',
                                inputValidator: (result) =>
                                {
                                    return !result && 'Se Requiere Elegir un Elemento'
                                }
                            }).then((result) =>
                            {

                                //formData.append('renovar', renovar);
                                //       formData.append('metodo', metodo);
                                //       formData.append('adjunto', adjunto);
                                //       formData.append('tipo_nomina_id', nomina);
                                //       formData.append('motivo_fecha_fin', cadenaid[result.value]);
                                //       formData.append('id',id);
                                // let me = this;
                                if (result.value)
                                {
                                    axios.post('finalizar/contrato/',
                                    {
                                        'tipo_nomina_id': nomina,
                                        'motivo_fecha_fin': cadenaid[result.value],
                                        'id': id,
                                    }).then(response =>
                                    {
                                        if (response.data.status)
                                        {
                                            if (response.data.status === 'error')
                                            {
                                                swal(
                                                {
                                                    type: 'error',
                                                    html: 'Ha ocurrido un error, notifique al administrador y recargue la página',
                                                });
                                            }
                                            else
                                            {
                                                toastr.success('Contrato Finalizado Correctamente');
                                                me.cargarTipoContrato(me.empleado);

                                            }
                                        }
                                    }).catch(e =>
                                    {
                                        console.error(e);
                                    });

                                }

                                // if (cadena[result.value] == 'Fin de Proyecto.') {
                                //   swal({
                                //     title:'¿Desea Renovar Contrato?',
                                //     input:'radio',
                                //     inputOptions: renovacion,
                                //     confirmButtonText:
                                //       'Continuar <i class="fa fa-arrow-right></i>',
                                //     showCancelButton: true,
                                //     customClass: 'form-check form-check-inline',
                                //     inputValidator: (resultado) => {
                                //       return !resultado && 'Se Requiere Elegir un Elemento'
                                //     }
                                //   }).then((resultado) => {
                                //     if (resultado.value != undefined) {
                                //       if (renovacionid[resultado.value] == 1) {
                                //         renovar = 1;
                                //       }
                                //       let formData = new FormData();
                                //
                                //       formData.append('renovar', renovar);
                                //       formData.append('metodo', metodo);
                                //       formData.append('adjunto', adjunto);
                                //       formData.append('tipo_nomina_id', nomina);
                                //       formData.append('motivo_fecha_fin', cadenaid[result.value]);
                                //       formData.append('id',id);
                                //
                                //       axios.post(me.url+'/'+empleado+'/contratos',formData
                                //       ).then(function (response) {
                                //         if (response.data.status) {
                                //
                                //           if (response.data.condicion == 1) {
                                //             toastr.success('Desactivado Correctamente')
                                //             me.cargarTipoContrato(me.empleado);
                                //           }
                                //
                                //           else {
                                //             let formData = new FormData();
                                //             formData.append('contrato_id', response.data.contrato);
                                //             axios.post('/finiquito',formData
                                //               ).then(function (response) {
                                //               if (response.data.status) {
                                //                 toastr.success('Desactivado Correctamente')
                                //                 me.cargarTipoContrato(me.empleado);
                                //               }
                                //               else {
                                //                 swal({
                                //                   type: 'error',
                                //                   html: response.data.errors.join('<br>'),
                                //                 });
                                //               }
                                //             }).catch(function (error) {
                                //               console.log(error);
                                //             });
                                //           }
                                //
                                //         } else {
                                //           swal({
                                //             type: 'error',
                                //             html: response.data.errors.join('<br>'),
                                //           });
                                //         }
                                //       }).catch(function (error) {
                                //         console.log(error);
                                //       });
                                //
                                //     }
                                //   })
                                // }
                                // else if (cadena[result.value] == 'Renuncia.') {
                                //   swal({
                                //     title: 'Formato de Renuncia',
                                //     input: 'file',
                                //     inputAttributes: {
                                //       'accept': 'application/pdf',
                                //       'aria-label': 'Upload your profile picture'
                                //     },
                                //     confirmButtonText: 'Enviar',
                                //     showCancelButton: true,
                                //     inputValidator: (file) => {
                                //       return !file && 'Este Campo no puede estar Vacío'
                                //     }
                                //   }).then((file) => {
                                //     if (file.value != undefined) {
                                //       let formData = new FormData();
                                //
                                //       formData.append('renovar', renovar);
                                //       formData.append('metodo', metodo);
                                //       formData.append('adjunto', file.value);
                                //       formData.append('tipo_nomina_id', nomina);
                                //       formData.append('motivo_fecha_fin', cadenaid[result.value]);
                                //       formData.append('id',id);
                                //
                                //       axios.post(me.url+'/'+empleado+'/contratos',formData
                                //       ).then(function (response) {
                                //         if (response.data.status) {
                                //
                                //           if (response.data.condicion == 1) {
                                //             toastr.success('Desactivado Correctamente')
                                //             me.cargarTipoContrato(me.empleado);
                                //           }
                                //
                                //           else {
                                //             let formData = new FormData();
                                //             formData.append('contrato_id', response.data.contrato);
                                //             axios.post('/finiquito',formData
                                //               ).then(function (response) {
                                //               if (response.data.status) {
                                //                 toastr.success('Desactivado Correctamente')
                                //                 me.cargarTipoContrato(me.empleado);
                                //               }
                                //               else {
                                //                 swal({
                                //                   type: 'error',
                                //                   html: response.data.errors.join('<br>'),
                                //                 });
                                //               }
                                //             }).catch(function (error) {
                                //               console.log(error);
                                //             });
                                //           }
                                //
                                //         }
                                //         else {
                                //           swal({
                                //             type: 'error',
                                //             html: response.data.errors.join('<br>'),
                                //           });
                                //         }
                                //       }).catch(function (error) {
                                //         console.log(error);
                                //       });
                                //     }
                                //   })
                                //   /**/
                                // }
                                // else {
                                //   if (result.value != undefined) {
                                //     let formData = new FormData();
                                //
                                //     formData.append('renovar', renovar);
                                //     formData.append('metodo', metodo);
                                //     formData.append('adjunto', adjunto);
                                //     formData.append('tipo_nomina_id', nomina);
                                //     formData.append('motivo_fecha_fin', cadenaid[result.value]);
                                //     formData.append('id',id);
                                //
                                //     axios.post(me.url+'/'+empleado+'/contratos',formData
                                //     ).then(function (response) {
                                //       if (response.data.status) {
                                //
                                //         if (response.data.condicion == 1) {
                                //             toastr.success('Desactivado Correctamente')
                                //             me.cargarTipoContrato(me.empleado);
                                //           }
                                //
                                //           else {
                                //             let formData = new FormData();
                                //             formData.append('contrato_id', response.data.contrato);
                                //             axios.post('/finiquito',formData
                                //               ).then(function (response) {
                                //               if (response.data.status) {
                                //                 toastr.success('Desactivado Correctamente')
                                //                 me.cargarTipoContrato(me.empleado);
                                //               }
                                //               else {
                                //                 swal({
                                //                   type: 'error',
                                //                   html: response.data.errors.join('<br>'),
                                //                 });
                                //               }
                                //             }).catch(function (error) {
                                //               console.log(error);
                                //             });
                                //           }
                                //
                                //       } else {
                                //         swal({
                                //           type: 'error',
                                //           html: response.data.errors.join('<br>'),
                                //         });
                                //       }
                                //     }).catch(function (error) {
                                //       console.log(error);
                                //     });
                                //   }
                                // }

                            })

                        }).catch(function (error)
                        {
                            console.log(error);
                        }); /*fin del axios tipofinalizacion*/

                    }
                }
                else if (
                    result.dismiss === swal.DismissReason.cancel
                )
                {}
            })
        },
        cerrarModal()
        {
            this.modal = 0;
            this.tituloModal = '';
            Utilerias.resetObject(this.tipoContrato);
        },
        abrirModal(modelo, accion, id, data = [])
        {
            switch (modelo)
            {
                case "tipo-contrato":
                {
                    switch (accion)
                    {
                        case 'registrar':
                        {
                            this.modal = 1;
                            this.Metodo = 'Nuevo';
                            let me = this;
                            this.tituloModal = 'Registrar Contrato de: ';
                            Utilerias.resetObject(this.tipoContrato);
                            this.tipoContrato.empleado_id = id;
                            me.horaActual();
                            // me.validaciontres();
                            this.tipoAccion = 1;
                            this.detalle = false;
                            this.detalleContrato = true;
                            break;
                        }
                        case 'actualizar':
                        {
                            Utilerias.resetObject(this.tipoContrato);
                            this.modal = 1;
                            this.tituloModal = 'Actualizar Contrato de:';
                            this.tipoAccion = 2;
                            this.disabled = 1;
                            this.ideditar = data['id'];
                            this.contrato_sueldo = data;
                            this.tipoContrato.id = data['id'];
                            this.tipoContrato.empleado_id = data['empleado_id'];
                            this.tipoContrato.tipo_nomina_id = data['tipo_nomina_id'];
                            this.tipoContrato.fecha_ingreso = data['fecha_ingreso'];
                            this.tipoContrato.fecha_fin = data['fecha_fin'];
                            this.tipoContrato.tipo_ubicacion_id = data['tipo_ubicacion_id'];
                            this.tipoContrato.horario_id = data['horario_id'];
                            this.tipoContrato.tipo_contrato_id = data['tipo_contrato_id'];
                            this.tipoContrato.proyecto_id = data['proyecto_id'];
                            this.detalle = false;
                            this.detalleContrato = true;
                            this.mostrarTabs();
                            // Mostrar los testigos
                            let testigos = data["testigos"];
                            let n = testigos.length;
                            if (n == 0) // T 0
                            {
                                this.testigo1 = {};
                                this.tiene_testigo_1 = false;
                                this.tiene_testigo_2 = false;
                                this.testigo2 = {};
                            }
                            else if (n == 1) // T 1
                            {
                                this.testigo1 = {
                                    id: testigos[0].empleado_id,
                                    nombre: testigos[0].nombre_testigo
                                };
                                this.tiene_testigo_1 = true;
                                this.testigo2 = {};
                                this.tiene_testigo_2 = false;
                            }
                            else // T 2
                            {
                                this.testigo1 = {
                                    id: testigos[0].empleado_id,
                                    nombre: testigos[0].nombre_testigo
                                };
                                this.tiene_testigo_1 = true;
                                this.testigo2 = {
                                    id: testigos[1].empleado_id,
                                    nombre: testigos[1].nombre_testigo
                            };
                            this.tiene_testigo_2 = true;
                        }
                        break;
                    }
                }
            }
        }
    },
    cargarTipoContrato(dataEmpleado = [])
    {

        this.detalle = true;
        this.isLoadingDetalle = true;
        let me = this;
        this.empleado = dataEmpleado;
        this.setTab('tab1');
        axios.get(me.url + '/' + dataEmpleado.id + '&' + this.empresa + '/' + 'contratosactivos').then(response =>
            {
                me.tableDataTipoContrato = response.data;
                me.isLoadingDetalle = false;
            })
            .catch(function (error)
            {
                console.log(error);
            });
    },
    maestro()
    {

        Utilerias.resetObject(this.tipoContrato);
        this.$refs.myTableTipoContrato.setFilter(
        {
            'tn_nombre': '',
            'fecha_ingreso': '',
            'fecha_fin': '',
            'tc_nombre': '',
            'condicion': '',
            'em_nombre': '',
        });
        this.detalle = false;
    },
    maestroContratos()
    {
        this.activeTab = 'tab1';
        Utilerias.resetObject(this.tipoContrato);
        this.detalle = true;
        this.detalleContrato = false;
    },

    /*validaciondos(){
      var id_empleado =this.tipoContrato.empleado_id;
      var array = [];
      let me = this;
      axios.get('/contrato/'+id_empleado +'/condicionc').then(response=> {
        for (var i = 0; i < response.data.length; i++){
          array.push(response.data[i].empresa_id);
        }
        for(var j = 0; j < array.length; j++){
          if(this.tipoContrato.empresa_id === array[j]){
            toastr.error('Error Empresa', 'No se puede crear nuevo contrato, No se puede seleccionar una empresa con la cual ya se tiene un contrato');
            this.desabilotarboton=1;
            break;
          }
          else{
            this.desabilotarboton=0;
          }
        }
      })
      .catch(function (error){
        console.log(error);
      });
    },*/
    // validaciontres()
    // {
    //     var tamanio_empresas = 0;
    //     var id_empleado = this.tipoContrato.empleado_id;
    //     let me = this;
    //     axios.get('/empresas').then(response =>
    //     {
    //         tamanio_empresas = response.data.length;
    //         axios.get('/contrato/' + id_empleado + '/condicionc').then(response =>
    //             {
    //                 if (response.data.length == tamanio_empresas)
    //                 {
    //                     toastr.error('Error cantidad de contrato', 'No se puede crear nuevo contrato, la cantidad minima de contratos es ' + tamanio_empresas);
    //                     this.desabilotarbotonn = 1;
    //                     this.detalle = true;
    //                     this.detalleContrato = false;
    //                     // me.cargarTipoContrato(me.empleado);
    //                 }
    //                 else
    //                 {
    //                     this.desabilotarbotonn = 0;
    //                 }
    //             })
    //             .catch(function (error)
    //             {
    //                 console.log(error);
    //             });
    //     }).catch(function (error)
    //     {
    //         console.log(error);
    //     });
    //
    // },
    setTab(tabName)
    {
        this.activeTab = tabName;
    },
    mostrarTabs()
    {
        let me = this;
        var childSueldo = this.$refs.sueldo;
        childSueldo.cargarsueldo(me.contrato_sueldo, me.empresa);

    },
    onClickRegistrado(data, registrado)
    {
        this.empleado = data;
        this.tipoAccion = 2;
        this.listar();

        if (registrado)
            this.mostrarTabs();
    },
    ObterEmpleados()
    {
        axios.get("empleado").then(res =>
        {
            let empleados1=[];
            console.error(res.data)
            res.data.forEach(x => {
                let e=x.empleado;
                empleados1.push({
                    id:e.id,
                    nombre:e.nombre+ " " +e.ap_paterno+" "+ e.ap_materno
                });
            });
            this.listaEmpleadosTestigo = empleados1;
        })
    },
    ActivarTestigo(n)
    {
        if (n == 1)
        {
            this.tiene_testigo_1 = true;
        }
        else
        {
            this.tiene_testigo_2 = true;
        }
    },

},
mounted()
{
    if (this.empresa == 1) {
      this.getDataCFW();
    }else if (this.empresa == 2) {
      this.getDataCSCT();
    }
    this.getLista();
    this.horaActual();
    this.ObterEmpleados();
}
}
</script>

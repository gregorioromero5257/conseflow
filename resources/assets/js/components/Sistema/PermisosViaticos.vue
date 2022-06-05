<template>
<div class='main'>
    <!-- Card Inicio Conser-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Permisos Viaticos Conserflow
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalPermisosviaticos(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Permisosviaticos-->
                <div class=''>
                    <v-client-table :columns='columns_permisosviaticos' :data='list_permisosviaticos' :options='options_permisosviaticos' ref='tbl_permisosviaticos'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalPermisosviaticos(false, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template slot="empresa" slot-scope="props">
                            <button v-if="props.row.empresa==1" class="btn btn-primary">Conserflow</button>
                            <button v-else class="btn btn-secondary">Constructora</button>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <!-- Modal Permisosviaticos -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_permisosviaticos}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_permisosviaticos'></h4>
                    <button type='button' class='close' @click='CerrarModalPermisosviaticos()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form action='' method='' enctype='multipart/form-data' class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Empresa</label>
                            <div class='col-md-9'>
                                <select class="form-control" v-validate="'required'" name="empresa" v-model="permisosviaticos_modal.empresa_id">
                                    <option value="1">Conserflow</option>
                                    <option value="2">Constructora</option>
                                </select>
                                <span class="text-danger">{{errors.first('empresa')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Propietario</label>
                            <div class='col-md-9'>
                                <v-select id="proyectos" v-validate="'required'" name="propietario" label="nombre" :options="listaEmpleados" v-model="permisosviaticos_modal.propietario">
                                </v-select>
                                <span class="text-danger">{{errors.first('propietario')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Permitir a</label>
                            <div class='col-md-9'>
                                <v-select id="proyectos" v-validate="'required'" name="permitido" label="nombre" :options="listaEmpleados" v-model="permisosviaticos_modal.empleado_permitido">
                                </v-select>
                                <span class="text-danger">{{errors.first('permitido')}}</span>
                            </div>
                        </div>

                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalPermisosviaticos()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_permisosviaticos== 1' class='btn btn-secondary' @click='GuardarPermisosviaticos(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_permisosviaticos==2' class='btn btn-secondary' @click='GuardarPermisosviaticos(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div> <!-- Main -->
</template>

<script>
/* CAMBIAR UBICACIÓN  */
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
export default
{
    data()
    {
        return {
            url: "/sis/soliviaticos",
            // Tabla
            ver_modal_permisosviaticos: 0,
            columns_permisosviaticos: ['propietario','permitido','empresa'],
            list_permisosviaticos: [],
            options_permisosviaticos:
            {
                headings:
                {
                    // id: 'Acciones',
                    propietario_id: 'Propietario',
                    empleado_permitido_id: 'Permiso a'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['propietario', 'permitido','empresa'],
                filterable: ['propietario', 'permitido','empresa'],
                filterByColumn: true,
                texts: config.texts
            }, //options
            // Modal
            titulo_modal_permisosviaticos: '',
            tipoAccion_modal_permisosviaticos: 0,
            permisosviaticos_modal:
            {
                empleado_permitido:
                {},
                empleado_permitido:
                {},
            },
            listaEmpleados: [],
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        CargarPermisos()
        {
            axios.get(this.url).then(res =>
            {
                if (res.data.status)
                {
                    this.list_permisosviaticos = res.data.permisos;
                }
                else
                {
                    this.Error("cargar los permisos");
                }

            })
        },
        AbrirModalPermisosviaticos(nuevo, model = [])
        {
            this.ver_modal_permisosviaticos = true;
            this.CargarEmpleados();
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_permisosviaticos = 'Registrar Permisosviaticos';
                this.tipoAccion_modal_permisosviaticos = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_permisosviaticos = 'Actualizar Permisosviaticos';
                this.tipoAccion_modal_permisosviaticos = 2;
                this.permisosviaticos_modal = model;
            } // Fin if
        },
        GuardarPermisosviaticos()
        {
            this.permisosviaticos_modal.propietario_id
            this.$validator.validate().then(result =>
            {
                if (!result) return;
                if(this.permisosviaticos_modal.propietario.id==this.permisosviaticos_modal.empleado_permitido.id)
                {
                    toastr.warning("Los empleados no pueden ser iguales");
                    return;
                }
                axios.post(this.url,
                {
                    "empresa":this.permisosviaticos_modal.empresa_id,
                    "propietario_id": this.permisosviaticos_modal.propietario.id,
                    "empleado_permitido_id": this.permisosviaticos_modal.empleado_permitido.id
                }).then(res =>
                {
                    if (res.data.status)
                    {
                        this.CerrarModalPermisosviaticos();
                        this.CargarPermisos();
                        toastr.success("Permisos registrados correctamente");
                    }
                    else
                    {
                        this.Error("registrar los permisos");
                    }
                })
            });
        },
        CargarEmpleados()
        {
            axios.get('vertodosempleados').then(res =>
            {
                if (res.data)
                {
                    this.listaEmpleados = [];
                    res.data.forEach((item, i) => {
                      this.listaEmpleados.push({
                        id : item.id,
                        nombre : item.nombre + ' ' + item.ap_paterno + ' ' + item.ap_materno,
                      });
                    });

                }
                else
                {
                    this.Error("obtener los empleados");
                }
            })
        },
        CerrarModalPermisosviaticos()
        {
            this.ver_modal_permisosviaticos = false;
            Utilerias.resetObject(this.permisosviaticos_modal);
        },
        Error(ms)
        {
            toastr.error("Error al " + ms);
        }
    }, // Fin metodos
    mounted()
    {
        this.CargarPermisos();
    }
}
</script>

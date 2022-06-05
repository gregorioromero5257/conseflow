<template>
<main>
    <button type="button" @click="abrirModal('direccion','registrar', empleado.id)" class="btn btn-dark float-sm-right">
        <i class="fas fa-plus"></i>&nbsp;Nuevo
    </button>
    <vue-element-loading :active="isLoadingDetalle" />
    <v-client-table :columns="columnsdireccion" :data="tableDatadireccion" :options="optionsdireccion" ref="myTabledireccion">

        <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <button type="button" @click="abrirModal('direccion','actualizar',empleado.id,props.row)" class="dropdown-item">
                            <i class="icon-pencil"></i>&nbsp;Actualizar Familiar
                        </button>
                        <button type="button" @click="eliminarFamiliar(empleado.id,props.row)" class="dropdown-item">
                            <i class="fas fa-times"></i>&nbsp;Eliminar Familiar
                        </button>
                    </div>
                </div>
            </div>
        </template>
        <!-- Condicion del campo vive -->
        <template slot="vive" slot-scope="props">
            <template v-if="props.row.vive == 1">
                <button type="button" class="btn">Vivo</button>
            </template>
            <template v-if="props.row.vive == 0">
                <button type="button" class="btn ">Fallecido</button>
            </template>
        </template>
    </v-client-table>

    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <vue-element-loading :active="isLoading" />
        <div class="modal-dialog modal-dark modal-lg" role="document">
            <div class="modal-content">
                <div>

                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
                        <input type="hidden" name="id">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" v-validate="'required|max:50'" name="nombre" v-model="familiare.nombre" class="form-control" placeholder="Nombre Completo" autocomplete="off" id="nombre">
                                <span class="text-danger">{{ errors.first('nombre') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="parentesco">Parentesco</label>
                            <div class="col-md-9">
                                <select class="form-control" id="parentesco" name="parentesco" v-model="familiare.parentesco" v-validate="'excluded:0'" data-vv-as="Solicitante">
                                    <option value="PADRE">PADRE</option>
                                    <option value="MADRE">MADRE</option>
                                    <option value="HERMANO">HERMANO</option>
                                    <option value="HERMANA">HERMANA</option>
                                    <option value="CÓNYUGE">CÓNYUGE</option>

                                </select>
                                <span class="text-danger">{{ errors.first('parentesco') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="edad">Edad</label>
                            <div class="col-md-9">
                                <input type="number" v-validate="''" min="1" max="100" name="edad" v-model="familiare.edad" class="form-control" placeholder="Edad" autocomplete="off" id="edad">
                                <span class="text-danger">{{ errors.first('edad') }}</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="vive">¿Vive?</label>
                            <div class="col-md-9">
                                <select class="form-control" id="vive" name="vive" v-model="familiare.vive" data-vv-as="Vive">
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                                <span class="text-danger">{{ errors.first('vive') }}</span>
                            </div>
                        </div>

                        <!-- </form> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarFamiliares(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarFamiliares(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
</main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default
{
    data()
    {
        return {
            url: '/familiare',
            empleado: null,
            detalle: false,
            familiare:
            {
                nombre: '',
                parentesco: '',
                edad: 0,
                vive: '',
                condicion: 0,
                empleado_id: 0,

            },
            listaTipoNomina: [],
            listadescuento: [],
            listaTipoDescuento: [],
            listaHorarios: [],
            listaProyectos: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            disabled: 0,
            isLoading: false,
            isLoadingDetalle: false,
            columnsdireccion: ['id', 'nombre', 'parentesco', 'edad', 'vive'],
            tableDatadireccion: [],
            optionsdireccion:
            {
                headings:
                {
                    id: 'Acciones',
                    nombre: 'Nombre Completo',
                    parentesco: 'Parentesco',
                    edad: 'Edad',
                    vive: 'Estado',
                    empleado_id: 'Empleado',
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['nombre', 'parentesco', 'edad', 'vive'],
                filterable: ['nombre', 'parentesco'],
                filterByColumn: true,
                listColumns:
                {
                    condicion: config.columnCondicion
                },
                texts: config.texts
            },
        }
    },
    computed:
    {},
    methods:
    {
        guardarFamiliares(nuevo)
        {
            this.$validator.validate().then(result =>
            {
                if (result)
                {
                    this.isLoading = true;
                    let me = this;
                    axios(
                    {
                        method: nuevo ? 'POST' : 'PUT',
                        url: nuevo ? me.url : me.url + '/' + this.familiare.id,
                        data:
                        {
                            'id': this.familiare.id,
                            'nombre': this.familiare.nombre,
                            'parentesco': this.familiare.parentesco,
                            'edad': this.familiare.edad,
                            'vive': this.familiare.vive,
                            'empleado_id': this.familiare.empleado_id,

                        }
                    }).then(function (response)
                    {
                        me.isLoading = false;
                        if (response.data.status)
                        {
                            me.cerrarModal();
                            me.cargardireccion(me.empleado);
                            if (!nuevo)
                            {
                                toastr.success('Familiar Actualizado Correctamente');
                            }
                            else
                            {
                                toastr.success('Familiar Registrado Correctamente');

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
        },
        cerrarModal()
        {
            this.modal = 0;
            this.tituloModal = '';
            Utilerias.resetObject(this.familiare);
        },
        abrirModal(modelo, accion, id, data = [])
        {
            switch (modelo)
            {
                case "direccion":
                {
                    switch (accion)
                    {
                        case 'registrar':
                        {
                            this.modal = 1;
                            this.tituloModal = 'Registrar Familiares';
                            Utilerias.resetObject(this.familiare);
                            this.familiare.empleado_id = id;
                            this.tipoAccion = 1;
                            break;

                        }
                        case 'actualizar':
                        {
                            this.modal = 1;
                            this.tituloModal = 'Actualizar Familiar';
                            this.familiare.id = data['id'];
                            this.tipoAccion = 2;
                            this.familiare.nombre = data['nombre'];
                            this.familiare.parentesco = data['parentesco'];
                            this.familiare.edad = data['edad'];
                            this.familiare.vive = data['vive'];
                            this.familiare.empleado_id = data['empleado_id'];

                            break;
                        }
                    }
                }
            }
        },
        cargardireccion(dataEmpleado = [])
        {
            this.detalle = true;
            this.isLoadingDetalle = true;
            let me = this;
            this.empleado = dataEmpleado;

            axios.get('/familiare' + '/' + dataEmpleado.id + '/' + 'familiare').then(response =>
                {
                    me.tableDatadireccion = response.data;
                    me.isLoadingDetalle = false;
                    this.familiare.empleado_id = me.empleado.id;
                })
                .catch(function (error)
                {
                    console.log(error);
                });
        },
        eliminarFamiliar(id_emp, row)
        {
            console.error(id_emp);
            console.error(row);
            axios.put(this.url + '/' + row.id,
            {
                'id': row.id,
                'nombre': row.nombre,
                'parentesco': row.parentesco,
                'edad': row.edad,
                "condicion": 0,
                'vive': row.vive,
                'empleado_id': id_emp,
            }).then(res =>
            {
                if (res.data.status)
                {
                  toastr.success("Familiar eliminado correctamente");
                  this.cargardireccion({id:id_emp});
                }
                else
                {
                    this.Error();
                }
            })
        },
        Error()
        {
            toastr.error("Error", "Notifique al administrador e intente más tarde")
        }
    },
    mounted()
    {}
}
</script>

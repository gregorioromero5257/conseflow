<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Accesorio
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalAccesorio(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Accesorio-->
                <div class=''>
                    <v-client-table :columns='columns_accesorio' :data='list_accesorio' :options='options_accesorio' ref='tbl_accesorio'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalAccesorio(false, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                        <button v-if="props.row.condicion==0" type='button' @click='Activar(props.row,1)' class='dropdown-item'>
                                            <i class='fas fa-check'></i>&nbsp;Activar
                                        </button>
                                        <button v-if="props.row.condicion==1" type='button' @click='Activar(props.row,0)' class='dropdown-item'>
                                            <i class='fas fa-check'></i>&nbsp;Desactivar
                                        </button>
                                        <button  type='button' @click='Eliminar(props.row)' class='dropdown-item'>
                                            <i class='fas fa-times'></i>&nbsp;Eliminar
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <template slot='condicion' slot-scope='props'>
                            <button v-if="props.row.condicion==1" class="btn btn-success">Activo</button>
                            <button v-if="props.row.condicion==0" class="btn btn-warning">Inactivo</button>
                            <button v-if="props.row.condicion==2" class="btn btn-primary">En resguardo</button>
                            
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <!-- Modal Accesorio -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_accesorio}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_accesorio'></h4>
                    <button type='button' class='close' @click='CerrarModalAccesorio()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form action='' method='post' enctype='multipart/form-data' class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Descripción</label>
                            <div class='col-md-9'>
                                <input type='text' name="descripcion" v-validate="'required'" class='form-control' v-model='accesorio_modal.descripcion'>
                                <span class="text-danger">{{errors.first('descripcion')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Modelo</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' v-validate="'required'" name="modelo" v-model='accesorio_modal.modelo'>
                                <span class="text-danger">{{errors.first('modelo')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Marca</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' v-validate="'required'" name="marca" v-model='accesorio_modal.marca'>
                                <span class="text-danger">{{errors.first('marca')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>No. Serie</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' name="ns" v-validate="'required'" v-model='accesorio_modal.no_serie'>
                                <span class="text-danger">{{errors.first('ns')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Cantidad</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' name="cantidad" v-validate="'required|decimal:2'" v-model='accesorio_modal.cantidad'>
                                <span class="text-danger">{{errors.first('cantidad')}}</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalAccesorio()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_accesorio== 1' class='btn btn-secondary' @click='GuardarAccesorio(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_accesorio==2' class='btn btn-secondary' @click='GuardarAccesorio(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
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
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default
{
    data()
    {
        return {
            url: "ti/inventario/accesorios",
            // Tabla
            ver_modal_accesorio: 0,
            columns_accesorio: ['id', 'descripcion', 'modelo', 'marca', 'no_serie', 'cantidad', 'condicion'],
            list_accesorio: [],
            options_accesorio:
            {
                headings:
                {
                    id: 'Acciones',
                    descripcion: 'Descripción',
                    modelo: 'Modelo',
                    marca: 'Marca',
                    no_serie: 'No. Serie',
                    condicion: 'Estado'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                // sortable: ['id', 'descripcion', 'modelo', 'marca', 'no_serie', 'condicion'],
                // filterable: ['id', 'descripcion', 'modelo', 'marca', 'no_serie', 'condicion'],
                filterByColumn: true,
                listColumns: {
                  'condicion': [{
                    id: 0,
                    text: 'Inactivo'
                  },
                  {
                    id: 1,
                    text: 'Activo'
                  },
                  {
                    id: 2,
                    text: 'En resguardo'
                  },
                  {
                    id: 3,
                    text: 'En sitio'
                  },
                ],
              },
                texts: config.texts
            }, //options
            // Modal
            titulo_modal_accesorio: '',
            tipoAccion_modal_accesorio: 0,
            accesorio_modal:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        CargarAccesorios()
        {
            axios.get(this.url + "/obtener").then(res =>
            {
                if (res.data.status)
                {
                    this.list_accesorio = res.data.accesorios;
                }
                else
                {
                    this.Error(res.data.mensaje);
                }
            })
        },
        AbrirModalAccesorio(nuevo, model = [])
        {
            this.ver_modal_accesorio = true;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_accesorio = 'Registrar Accesorio';
                this.tipoAccion_modal_accesorio = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_accesorio = 'Actualizar Accesorio';
                this.tipoAccion_modal_accesorio = 2;
                this.accesorio_modal.id = model.id;
                this.accesorio_modal.descripcion = model.descripcion;
                this.accesorio_modal.modelo = model.modelo;
                this.accesorio_modal.marca = model.marca;
                this.accesorio_modal.no_serie = model.no_serie;
                this.accesorio_modal.cantidad = model.cantidad;
                // console.error(this.accesorio_modal);
            } // Fin if
        },

        CerrarModalAccesorio()
        {
            this.ver_modal_accesorio = false;
            Utilerias.resetObject(this.accesorio_modal);
        },
        GuardarAccesorio(nuevo)
        {
            this.$validator.validate().then((res) =>
            {
                if (!res) return;

                let ms = nuevo ? "registrda" : "actualizada";
                axios.post(this.url + "/guardar",
                {
                    "id": this.accesorio_modal.id,
                    "descripcion": this.accesorio_modal.descripcion,
                    "marca": this.accesorio_modal.marca,
                    "modelo": this.accesorio_modal.modelo,
                    "no_serie": this.accesorio_modal.no_serie,
                    "cantidad": this.accesorio_modal.cantidad,
                }).then(res =>
                {
                    if (res.data.status)
                    {
                        this.CerrarModalAccesorio();
                        this.CargarAccesorios();
                        toastr.success("Accesorio " + ms + " correctamente");
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                });
            })
        },

        // Cambia el estado(activado|desactivado)
        Activar(accesorio, condicion)
        {
            axios.post(this.url + "/activar",
            {
                id: accesorio.id,
                condicion
            }).then(res =>
            {
                if (res.data.status)
                {
                    this.CargarAccesorios();
                    toastr.success("Estado cambiado correctamente");
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },

        Eliminar(data){
          if (data.condicion > 1) {
            toastr.warning('No se puede eliminar al contar con asignacion');
          }else if (data.condicion <= 1) {
            axios.get('ti/inventario/accesorios/eliminar/' + data.id).then(response => {
              this.CargarAccesorios();
              toastr.success('Eliminado Correctamente !!!');
            }).catch(e => {
              console.error(e);
            });
          }
        }
    }, // Fin metodos
    mounted()
    {
        this.CargarAccesorios();
    }
}
</script>

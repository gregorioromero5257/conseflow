<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Equipo de red
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalEquipoRed(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Equipo de red-->
                <div class=''>
                    <v-client-table :columns='columns_equipo_red' :data='list_equipo_red' :options='options_equipo_red' ref='tbl_equipo_red'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalEquipoRed(false, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                        <button v-if="props.row.condicion==0" type='button' @click='Activar(props.row,1)' class='dropdown-item'>
                                            <i class='fas fa-check'></i>&nbsp;Activar
                                        </button>
                                        <button v-if="props.row.condicion==1" type='button' @click='Activar(props.row,0)' class='dropdown-item'>
                                            <i class='fas fa-times'></i>&nbsp;Desactivar
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
                            <button v-if="props.row.condicion == 3" class="btn btn-primary">Sito</button>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <!-- Modal Equipo de red -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_equipo_red}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_equipo_red'></h4>
                    <button type='button' class='close' @click='CerrarModalEquipoRed()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form action='' method='post' enctype='multipart/form-data' class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Descripción</label>
                            <div class='col-md-9'>
                                <input type='text' name="descripcion" v-validate="'required'" class='form-control' v-model='equipo_red_modal.descripcion'>
                                <span class="text-danger">{{errors.first('descripcion')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Marca</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' v-validate="'required'" name="marca" v-model='equipo_red_modal.marca'>
                                <span class="text-danger">{{errors.first('marca')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>No. Serie</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' name="ns" v-validate="'required'" v-model='equipo_red_modal.no_serie'>
                                <span class="text-danger">{{errors.first('ns')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>MAC</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' name="mac" v-validate="'required'" v-model='equipo_red_modal.mac'>
                                <span class="text-danger">{{errors.first('mac')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>IP</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' name="ip" v-validate="'required'" v-model='equipo_red_modal.ip'>
                                <span class="text-danger">{{errors.first('ip')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Observaciones</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' name="observaciones" v-validate="'required'" v-model='equipo_red_modal.observaciones'>
                                <span class="text-danger">{{errors.first('observaciones')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Cantidad</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' name="cantidad" v-validate="'required|decimal:2'" v-model='equipo_red_modal.cantidad'>
                                <span class="text-danger">{{errors.first('cantidad')}}</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalEquipoRed()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_equipo_red== 1' class='btn btn-secondary' @click='GuardarEquipoRed(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_equipo_red==2' class='btn btn-secondary' @click='GuardarEquipoRed(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
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
            url: "ti/inventario/red",
            // Tabla
            ver_modal_equipo_red: 0,
            columns_equipo_red: ['id', 'descripcion', 'marca', 'no_serie', 'mac', 'ip', 'observaciones', 'cantidad', 'condicion'],
            list_equipo_red: [],
            options_equipo_red:
            {
                headings:
                {
                    id: 'Acciones',
                    descripcion: 'Descripción',
                    modelo: 'Modelo',
                    marca: 'Marca',
                    no_serie: 'No. Serie',
                    condicion: 'Estado',
                    mac: "MAC",
                    ip: "IP",
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
            titulo_modal_equipo_red: '',
            tipoAccion_modal_equipo_red: 0,
            equipo_red_modal:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        CargarEquipoReds()
        {
            axios.get(this.url + "/obtener").then(res =>
            {
                if (res.data.status)
                {
                    this.list_equipo_red = res.data.equipo_reds;
                }
                else
                {
                    this.Error(res.data.mensaje);
                }
            })
        },
        AbrirModalEquipoRed(nuevo, model = [])
        {
            this.ver_modal_equipo_red = true;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_equipo_red = 'Registrar Equipo de red';
                this.tipoAccion_modal_equipo_red = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_equipo_red = 'Actualizar Equipo de red';
                this.tipoAccion_modal_equipo_red = 2;
                this.equipo_red_modal.id = model.id;
                this.equipo_red_modal.descripcion = model.descripcion;
                this.equipo_red_modal.modelo = model.modelo;
                this.equipo_red_modal.marca = model.marca;
                this.equipo_red_modal.no_serie = model.no_serie;
                this.equipo_red_modal.mac = model.mac;
                this.equipo_red_modal.ip = model.ip;
                this.equipo_red_modal.observaciones = model.observaciones;
                this.equipo_red_modal.cantidad = model.cantidad;
                // console.error(this.equipo_red_modal);
            } // Fin if
        },

        CerrarModalEquipoRed()
        {
            this.ver_modal_equipo_red = false;
            Utilerias.resetObject(this.equipo_red_modal);
        },
        GuardarEquipoRed(nuevo)
        {
            this.$validator.validate().then((res) =>
            {
                if (!res) return;

                let ms = nuevo ? "registrda" : "actualizada";
                axios.post(this.url + "/guardar",
                {
                    "id": this.equipo_red_modal.id,
                    "descripcion": this.equipo_red_modal.descripcion,
                    "marca": this.equipo_red_modal.marca,
                    "no_serie": this.equipo_red_modal.no_serie,
                    "mac": this.equipo_red_modal.mac,
                    "ip": this.equipo_red_modal.ip,
                    "observaciones": this.equipo_red_modal.observaciones,
                    "cantidad": this.equipo_red_modal.cantidad,
                }).then(res =>
                {
                    if (res.data.status)
                    {
                        this.CerrarModalEquipoRed();
                        this.CargarEquipoReds();
                        toastr.success("Equipo de red " + ms + " correctamente");
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                });
            })
        },
        // Cambia el estado(activado|desactivado)
        Activar(red, condicion)
        {
            axios.post(this.url + "/activar",
            {
                id: red.id,
                condicion
            }).then(res =>
            {
                if (res.data.status)
                {
                    this.CargarEquipoReds();
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
            axios.get('ti/inventario/red/eliminar/' + data.id).then(response => {
              this.CargarEquipoReds();
              toastr.success('Eliminado Correctamente !!!');
            }).catch(e => {
              console.error(e);
            });
          }
        }
    }, // Fin metodos
    mounted()
    {
        this.CargarEquipoReds();
    }
}
</script>

<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Impresora
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalImpresora(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Impresora-->
                <div class=''>
                    <v-client-table :columns='columns_impresora' :data='list_impresora' :options='options_impresora' ref='tbl_impresora'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalImpresora(false, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                        <button v-if="props.row.condicion==1" type='button' @click='Activar(props.row,0)' class='dropdown-item'>
                                            <i class='fas fa-times'></i>&nbsp;Desactivar
                                        </button>
                                        <button v-if="props.row.condicion==0" type='button' @click='Activar(props.row,1)' class='dropdown-item'>
                                            <i class='fas fa-check'></i>&nbsp;Activar
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

    <!-- Modal Impresora -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_impresora}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_impresora'></h4>
                    <button type='button' class='close' @click='CerrarModalImpresora()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form action='' method='post' enctype='multipart/form-data' class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Descripción</label>
                            <div class='col-md-9'>
                                <input type='text' name="descripcion" v-validate="'required'" class='form-control' v-model='impresora_modal.descripcion'>
                                <span class="text-danger">{{errors.first('descripcion')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Modelo</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' v-validate="'required'" name="modelo" v-model='impresora_modal.modelo'>
                                <span class="text-danger">{{errors.first('modelo')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Marca</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' v-validate="'required'" name="marca" v-model='impresora_modal.marca'>
                                <span class="text-danger">{{errors.first('marca')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>No. Serie</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' name="ns" v-validate="'required'" v-model='impresora_modal.no_serie'>
                                <span class="text-danger">{{errors.first('ns')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>MAC</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' name="mac" v-validate="'required'" v-model='impresora_modal.mac'>
                                <span class="text-danger">{{errors.first('mac')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Cantidad</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' name="cantidad" v-validate="'required|decimal:2'" v-model='impresora_modal.cantidad'>
                                <span class="text-danger">{{errors.first('cantidad')}}</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalImpresora()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_impresora== 1' class='btn btn-secondary' @click='GuardarImpresora(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_impresora==2' class='btn btn-secondary' @click='GuardarImpresora(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
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
            url: "ti/inventario/impresoras",
            // Tabla
            ver_modal_impresora: 0,
            columns_impresora: ['id', 'descripcion', 'modelo', 'marca', 'no_serie', 'cantidad', 'condicion','mac'],
            list_impresora: [],
            options_impresora:
            {
                headings:
                {
                    id: 'Acciones',
                    descripcion: 'Descripción',
                    modelo: 'Modelo',
                    marca: 'Marca',
                    no_serie: 'No. Serie',
                    mac:"MAC",
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
            titulo_modal_impresora: '',
            tipoAccion_modal_impresora: 0,
            impresora_modal:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        CargarImpresoras()
        {
            axios.get(this.url + "/obtener").then(res =>
            {
                if (res.data.status)
                {
                    this.list_impresora = res.data.impresoras;
                }
                else
                {
                    this.Error(res.data.mensaje);
                }
            })
        },
        AbrirModalImpresora(nuevo, model = [])
        {
            this.ver_modal_impresora = true;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_impresora = 'Registrar Impresora';
                this.tipoAccion_modal_impresora = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_impresora = 'Actualizar Impresora';
                this.tipoAccion_modal_impresora = 2;
                this.impresora_modal.id = model.id;
                this.impresora_modal.descripcion = model.descripcion;
                this.impresora_modal.modelo = model.modelo;
                this.impresora_modal.marca = model.marca;
                this.impresora_modal.no_serie = model.no_serie;
                this.impresora_modal.cantidad = model.cantidad;
                this.impresora_modal.mac = model.mac;
                // console.error(this.impresora_modal);
            } // Fin if
        },

        CerrarModalImpresora()
        {
            this.ver_modal_impresora = false;
            this.impresora_modal = {};
        },
        GuardarImpresora(nuevo)
        {
            this.$validator.validate().then((res) =>
            {
                if (!res) return;

                let ms = nuevo ? "registrda" : "actualizada";
                axios.post(this.url + "/guardar",
                {
                    "id": this.impresora_modal.id,
                    "descripcion": this.impresora_modal.descripcion,
                    "marca": this.impresora_modal.marca,
                    "modelo": this.impresora_modal.modelo,
                    "no_serie": this.impresora_modal.no_serie,
                    "cantidad": this.impresora_modal.cantidad,
                    "mac": this.impresora_modal.mac,
                }).then(res =>
                {
                    if (res.data.status)
                    {
                        this.CargarImpresoras();
                        this.CerrarModalImpresora();
                        toastr.success("Impresora " + ms + " correctamente");
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                });
            })
        },

        // Cambia el estado de la impresora (activado|desactivado)
        Activar(impresora, estado)
        {
            axios.post(this.url + "/activar",
            {
                id: impresora.id,
                condicion: estado
            }).then(res =>
            {
                if (res.data.status)
                {
                    this.CargarImpresoras();
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
            axios.get('ti/inventario/impresoras/eliminar/' + data.id).then(response => {
              this.CargarImpresoras();
              toastr.success('Eliminado Correctamente !!!');
            }).catch(e => {
              console.error(e);
            });
          }
        }
    }, // Fin metodos
    mounted()
    {
        this.CargarImpresoras();
    }
}
</script>

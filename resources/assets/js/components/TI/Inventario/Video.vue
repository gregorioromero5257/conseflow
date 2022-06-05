<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Equipo de vídeo
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalEquipoVideo(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Equipo de vídeo-->
                <div class=''>
                    <v-client-table :columns='columns_equipo_video' :data='list_equipo_video' :options='options_equipo_video' ref='tbl_equipo_video'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalEquipoVideo(false, props.row)' class='dropdown-item'>
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

    <!-- Modal Equipo de vídeo -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_equipo_video}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_equipo_video'></h4>
                    <button type='button' class='close' @click='CerrarModalEquipoVideo()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <form action='' method='post' enctype='multipart/form-data' class='form-horizontal'>
                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Descripción</label>
                            <div class='col-md-9'>
                                <input type='text' name="descripcion" v-validate="'required'" class='form-control' v-model='equipo_video_modal.descripcion'>
                                <span class="text-danger">{{errors.first('descripcion')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>No. Serie</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' name="ns" v-validate="'required'" v-model='equipo_video_modal.no_serie'>
                                <span class="text-danger">{{errors.first('ns')}}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Cantidad</label>
                            <div class='col-md-9'>
                                <input type='text' class='form-control' name="cantidad" v-validate="'required|decimal:2'" v-model='equipo_video_modal.cantidad'>
                                <span class="text-danger">{{errors.first('cantidad')}}</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalEquipoVideo()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_equipo_video== 1' class='btn btn-secondary' @click='GuardarEquipoVideo(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_equipo_video==2' class='btn btn-secondary' @click='GuardarEquipoVideo(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
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
            url: "ti/inventario/video",
            // Tabla
            ver_modal_equipo_video: 0,
            columns_equipo_video: ['id', 'descripcion', 'no_serie', 'cantidad', 'condicion'],
            list_equipo_video: [],
            options_equipo_video:
            {
                headings:
                {
                    id: 'Acciones',
                    descripcion: 'Descripción',
                    no_serie: 'No. Serie',
                    condicion: 'Estado'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                // sortable: ['id', 'descripcion', 'marca', 'no_serie', 'condicion'],
                // filterable: ['id', 'descripcion', 'no_serie', 'condicion'],
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
            titulo_modal_equipo_video: '',
            tipoAccion_modal_equipo_video: 0,
            equipo_video_modal:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        CargarEquipoVideos()
        {
            axios.get(this.url + "/obtener").then(res =>
            {
                if (res.data.status)
                {
                    this.list_equipo_video = res.data.equipo_video;
                }
                else
                {
                    this.Error(res.data.mensaje);
                }
            })
        },
        AbrirModalEquipoVideo(nuevo, model = [])
        {
            this.ver_modal_equipo_video = true;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_equipo_video = 'Registrar Equipo de vídeo';
                this.tipoAccion_modal_equipo_video = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_equipo_video = 'Actualizar Equipo de vídeo';
                this.tipoAccion_modal_equipo_video = 2;
                this.equipo_video_modal.id = model.id;
                this.equipo_video_modal.descripcion = model.descripcion;
                this.equipo_video_modal.no_serie = model.no_serie;
                this.equipo_video_modal.cantidad = model.cantidad;
            } // Fin if
        },

        CerrarModalEquipoVideo()
        {
            this.ver_modal_equipo_video = false;
            Utilerias.resetObject(this.equipo_video_modal);
        },
        GuardarEquipoVideo(nuevo)
        {
            this.$validator.validate().then((res) =>
            {
                if (!res) return;

                let ms = nuevo ? "registrda" : "actualizada";
                axios.post(this.url + "/guardar",
                {
                    "id": this.equipo_video_modal.id,
                    "descripcion": this.equipo_video_modal.descripcion,
                    "no_serie": this.equipo_video_modal.no_serie,
                    "cantidad": this.equipo_video_modal.cantidad,
                }).then(res =>
                {
                    if (res.data.status)
                    {
                        this.CerrarModalEquipoVideo();
                        this.CargarEquipoVideos();
                        toastr.success("Equipo de vídeo " + ms + " correctamente");
                    }
                    else
                    {
                        toastr.error(res.data.mensaje);
                    }
                });
            })
        },
        // Cambia el estado de la (activado|desactivado)
        Activar(video, condicion)
        {
            axios.post(this.url + "/activar",
            {
                id: video.id,
                condicion
            }).then(res =>
            {
                if (res.data.status)
                {
                    this.CargarEquipoVideos();
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
            axios.get('ti/inventario/video/eliminar/' + data.id).then(response => {
              toastr.success('Eliminado Correctamente !!!');
              this.CargarEquipoVideos();
            }).catch(e => {
              console.error(e);
            });
          }
        }
    }, // Fin metodos
    mounted()
    {
        this.CargarEquipoVideos();
    }
}
</script>

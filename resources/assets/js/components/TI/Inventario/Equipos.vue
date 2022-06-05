<template>
<div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
        <!-- Inicio card-->
        <div class='card-header'>
            <i class='fa fa-align-justify'></i> Equipo de Cómputo {{company == 1 ? 'Conserflow' : 'CSCT'}}
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle float-sm-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Empresa
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenu2" >
                <button class="dropdown-item" type="button" @click="company = 1; CargarEquipos();">Conserflow</button>
                <button class="dropdown-item" type="button" @click="company = 2; CargarEquipos();">CSCT</button>
              </div>
            </div>
            <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalComputadoras(true)'>
                <i class='fas fa-plus'>&nbsp;</i>Nuevo
            </button>
            <button type="button" @click="descargar()" class="btn btn-dark float-sm-right">
              <i class="fas fa-file-excel"></i>&nbsp;Descargar
            </button>
        </div>
        <div class='card-body'>
            <div class=''>
                <!-- Tabla de Equipo de Cómputo-->
                <div class=''>
                    <v-client-table :columns='columns_computadoras' :data='list_computadoras' :options='options_computadoras' ref='tbl_computadoras'>
                        <template slot='id' slot-scope='props'>
                            <div class='btn-group' role='group'>
                                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fas fa-grip-horizontal'></i>&nbsp;
                                </button>
                                <div class='dropdown-menu'>
                                    <template>
                                        <button type='button' @click='AbrirModalComputadoras(false, props.row)' class='dropdown-item'>
                                            <i class='icon-pencil'></i>&nbsp;Actualizar
                                        </button>
                                        <button v-if="props.row.condicion==0" type='button' @click='Activar(1, props.row)' class='dropdown-item'>
                                            <i class='fas fa-check'></i>&nbsp;Activar
                                        </button>
                                        <button v-if="props.row.condicion==1" type='button' @click='Activar(0, props.row)' class='dropdown-item'>
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
                            <button v-if="props.row.condicion == 1" class="btn btn-success">Activo</button>
                            <button v-if="props.row.condicion == 0" class="btn btn-warning">Inactivo</button>
                            <button v-if="props.row.condicion == 2" class="btn btn-primary">En resguardo</button>
                            <button v-if="props.row.condicion == 3" class="btn btn-primary">Sito</button>
                        </template>
                    </v-client-table>
                </div>
                <!--Card body -->
            </div> <!-- card-->
        </div>
    </div>

    <!-- Modal Equipo de Cómputo -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_computadoras}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title' v-text='titulo_modal_computadoras'></h4>
                    <button type='button' class='close' @click='CerrarModalComputadoras()' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'>
                    <div class="form-row">

                      <div class="col-md-4 mb-3">
                        <label>No. Serie</label>
                        <input type='text' name="no_serie" v-validate="'required'" class='form-control' v-model='computadoras_modal.no_serie'>
                        <span class="text-danger">{{ errors.first("no_serie") }}</span>
                      </div>

                      <div class="col-md-4 mb-3">
                        <label>Marca/Modelo</label>
                        <input type='text' v-validate="'required'" name="marca_modelo" class='form-control' v-model='computadoras_modal.marca_modelo'>
                        <span class="text-danger">{{ errors.first("marca_modelo") }}</span>
                      </div>

                      <div class="col-md-4 mb-3">
                        <label>CPU</label>
                        <input type='text' v-validate="'required'" name="cpu" class='form-control' v-model='computadoras_modal.cpu'>
                        <span class="text-danger">{{ errors.first("cpu") }}</span>
                      </div>

                    </div>

                    <div class="form-row">

                      <div class="col-md-4 mb-3">
                        <label>SO</label>
                        <input type='text' v-validate="'required'" name="so" class='form-control' v-model='computadoras_modal.so'>
                        <span class="text-danger">{{ errors.first("so") }}</span>
                      </div>

                      <div class="col-md-4 mb-3">
                        <label>RAM</label>
                        <input type='text' v-validate="'required'" name="ram" class='form-control' v-model='computadoras_modal.ram'>
                        <span class="text-danger">{{ errors.first("ram") }}</span>
                      </div>

                      <div class="col-md-4 mb-3">
                        <label>Almacenamiento</label>
                        <input type='text' v-validate="'required'" name="almacenamiento" class='form-control' v-model='computadoras_modal.almacenamiento'>
                        <span class="text-danger">{{ errors.first("almacenamiento") }}</span>
                      </div>

                    </div>


                    <div class="form-row">

                      <div class='col-md-4 mb-3'>
                          <label>Tarjeta de video</label>
                          <input type='text' v-validate="'required'" name="tarjeta_video" class='form-control' v-model='computadoras_modal.tarjeta_video'>
                          <span class="text-danger">{{ errors.first("tarjeta_video") }}</span>
                      </div>

                      <div class='col-md-4 mb-3'>
                          <label>Tarjeta de red</label>
                          <input type='text' class='form-control' v-validate="'required'" name="tarjeta_red" v-model='computadoras_modal.tarjeta_red'>
                          <span class="text-danger">{{ errors.first("tarjeta_red") }}</span>
                      </div>

                      <div class='col-md-4 mb-3'>
                          <label>MAC</label>
                          <input type='text' class='form-control' v-validate="'required'" name="mac" v-model='computadoras_modal.mac'>
                          <span class="text-danger">{{ errors.first("mac") }}</span>
                      </div>

                    </div>
                        <!-- <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>No. serie</label>
                            <div class='col-md-4'>
                                <input type='text' name="no_serie" v-validate="'required'" class='form-control' v-model='computadoras_modal.no_serie'>
                                <span class="text-danger">{{ errors.first("no_serie") }}</span>
                            </div>
                        </div> -->

                        <!-- <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Marca/Modelo</label>
                            <div class='col-md-4'>
                                <input type='text' v-validate="'required'" name="marca_modelo" class='form-control' v-model='computadoras_modal.marca_modelo'>
                                <span class="text-danger">{{ errors.first("marca_modelo") }}</span>
                            </div>
                        </div> -->

                        <!-- <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>CPU</label>
                            <div class='col-md-4'>
                                <input type='text' v-validate="'required'" name="cpu" class='form-control' v-model='computadoras_modal.cpu'>
                                <span class="text-danger">{{ errors.first("cpu") }}</span>
                            </div>
                        </div> -->
                        <!-- <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>SO</label>
                            <div class='col-md-4'>
                                <input type='text' v-validate="'required'" name="so" class='form-control' v-model='computadoras_modal.so'>
                                <span class="text-danger">{{ errors.first("so") }}</span>
                            </div>
                        </div> -->

                        <!-- <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>RAM</label>
                            <div class='col-md-4'>
                                <input type='text' v-validate="'required'" name="ram" class='form-control' v-model='computadoras_modal.ram'>
                                <span class="text-danger">{{ errors.first("ram") }}</span>
                            </div>
                        </div> -->

                        <!-- <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Almacenamiento</label>
                            <div class='col-md-5'>
                                <input type='text' v-validate="'required'" name="almacenamiento" class='form-control' v-model='computadoras_modal.almacenamiento'>
                                <span class="text-danger">{{ errors.first("almacenamiento") }}</span>
                            </div>
                        </div> -->

                        <!-- <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Tarjeta de video</label>
                            <div class='col-md-5'>
                                <input type='text' v-validate="'required'" name="tarjeta_video" class='form-control' v-model='computadoras_modal.tarjeta_video'>
                                <span class="text-danger">{{ errors.first("tarjeta_video") }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>Tarjeta de red</label>
                            <div class='col-md-5'>
                                <input type='text' class='form-control' v-validate="'required'" name="tarjeta_red" v-model='computadoras_modal.tarjeta_red'>
                                <span class="text-danger">{{ errors.first("tarjeta_red") }}</span>
                            </div>
                        </div>

                        <div class='form-group row'>
                            <label class='col-md-3 form-control-label'>MAC</label>
                            <div class='col-md-3'>
                                <input type='text' class='form-control' v-validate="'required'" name="mac" v-model='computadoras_modal.mac'>
                                <span class="text-danger">{{ errors.first("mac") }}</span>
                            </div>
                        </div> -->

                        <div class='form-row'>
                          <div class='col-md-4 mb-3'>
                            <label>Código de barras</label>
                                <input type='text' class='form-control' v-validate="'required'" name="codigo_barras" v-model='computadoras_modal.codigo_barras'>
                                <span class="text-danger">{{ errors.first("codigo_barras") }}</span>
                            </div>

                          <div class='col-md-4 mb-3'>
                            <label>Cantidad</label>
                                <input type='text' class='form-control' v-validate="'required|decimal:2'" name="cantidad" v-model='computadoras_modal.cantidad'>
                                <span class="text-danger">{{ errors.first("cantidad") }}</span>
                            </div>

                            <div class='col-md-4 mb-3'>
                              <label>Color</label>
                                  <input type='text' class='form-control' v-validate="'required'" name="color" v-model='computadoras_modal.color'>
                                  <span class="text-danger">{{ errors.first("color") }}</span>
                              </div>
                        </div>

                        <div class='form-row'>
                          <div class='col-md-8 mb-3'>
                            <label>Observaciones</label>
                                <textarea rows="4" class='form-control' v-validate="'required'" data-vv-name="observaciones" v-model='computadoras_modal.observaciones'>
                                </textarea>
                                <span class="text-danger">{{ errors.first("observaciones") }}</span>
                            </div>
                        </div>

                        <div class='form-row'>
                          <div class='col-md-4 mb-3'>
                            <label>Empresa</label>
                              <select class="form-control" v-model="computadoras_modal.empresa">
                                <option value="1">Conserflow</option>
                                <option value="2">CSCT</option>
                              </select>
                            </div>
                        </div>

                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-outline-dark' @click='CerrarModalComputadoras()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
                    <button type='button' v-if='tipoAccion_modal_computadoras== 1' class='btn btn-secondary' @click='GuardarComputadoras(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
                    <button type='button' v-if='tipoAccion_modal_computadoras==2' class='btn btn-secondary' @click='GuardarComputadoras(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
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
            url: "ti/inv/equipos",
            // Tabla
            company : 1,
            ver_modal_computadoras: 0,
            columns_computadoras: [
                'id',
                'no_serie',
                'marca_modelo',
                'cpu',
                'ram',
                'almacenamiento',
                'tarjeta_video',
                'tarjeta_red',
                'mac',
                'codigo_barras',
                'observaciones',
                'cantidad',
                'condicion'
            ],
            list_computadoras: [],
            options_computadoras:
            {
                headings:
                {
                    id: 'Acciones',
                    no_serie: 'No. serie',
                    marca_modelo: 'Marca/Modelo',
                    cpu: 'CPU',
                    ram: 'RAM',
                    almacenamiento: 'Almacenamiento',
                    tarjeta_video: 'Tarjeta de video',
                    tarjeta_red: 'Tarjeta de red',
                    mac: 'MAC',
                    observaciones: 'Observaciones',
                    codigo_barras: 'Código de barras'
                }, // Headings,
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                // sortable: ['no_serie', 'marca_modelo', 'cpu', 'ram', 'almacenamiento', 'tarjeta_video', 'tarjeta_red', 'mac', 'observaciones', 'codigo_barras', 'condicion'],
                // filterable: ['no_serie', 'marca_modelo', 'cpu', 'ram', 'almacenamiento', 'tarjeta_video', 'tarjeta_red', 'mac', 'observaciones', 'codigo_barras', 'condicion'],
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
            titulo_modal_computadoras: '',
            tipoAccion_modal_computadoras: 0,
            computadoras_modal:
            {},
        } // return
    }, //data
    computed:
    {},
    methods:
    {
        CargarEquipos()
        {
            axios.get(this.url + "/obtener/" + this.company).then(res =>
            {
                if (res.data.status)
                {
                    this.list_computadoras = res.data.equipos;
                }
                else
                {
                    this.Error("obtener los equipos");
                }
            });
        },
        AbrirModalComputadoras(nuevo, model = [])
        {
            this.ver_modal_computadoras = true;
            if (nuevo)
            {
                // Crear nuevo
                this.titulo_modal_computadoras = 'Registrar Equipo de Cómputo';
                this.tipoAccion_modal_computadoras = 1;
            }
            else
            {
                // Actualizar
                this.titulo_modal_computadoras = 'Actualizar Equipo de Cómputo';
                this.tipoAccion_modal_computadoras = 2;
                this.computadoras_modal = model;
            } // Fin if
        },

        CerrarModalComputadoras()
        {
            this.ver_modal_computadoras = false;
            Utilerias.resetObject(this.computadoras_modal);
        },
        GuardarComputadoras(nuevo)
        {
            let x = this;
            this.$validator.validate().then((res) =>
            {
                if (!res) return;
                //Validar formato de MAC
                if (this.computadoras_modal.mac.match("^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$") == null)
                {
                    toastr.warning("Ingresa una MAC válida");
                    return;
                }
                let nurl = nuevo ? "/registrar" : "/actualizar";
                axios.post(this.url + nurl, this.computadoras_modal).then(res =>
                {
                    if (res.data.status)
                    {
                        toastr.success("Equipo guardado correctemente");
                        this.CargarEquipos();
                        this.CerrarModalComputadoras();
                    }
                    else
                        this.Error("guardar el equipo")
                });
            });
        },
        descargar(){

        window.open('ti/inv/equipos/descargar/inv/comp/' + this.company , '_blank');
        },
        Activar(n, row)
        {
            axios.post(this.url + "/cambiarestado",
            {
                n,
                id: row.id
            }).then(res =>
            {
                if (res.data.status)
                {
                    toastr.success("Equipo guardado correctemente");
                    this.CargarEquipos();

                }
                else
                {
                    this.Error("cambiar estado");
                }
            });
        },
        Error(ms)
        {
            toastr.error("Error al " + ms);
        },
        Eliminar(data){
          if (data.condicion > 1) {
            toastr.warning('No se puede eliminar al contar con asignacion');
          }else if (data.condicion <= 1) {
            axios.get('ti/inv/equipos/eliminar/' + data.id).then(response => {
              this.CargarEquipos();
              toastr.success('Eliminado Correctamente !!!');
            }).catch(e => {
              console.error(e);
            });
          }
        }

    }, // Fin metodos
    mounted()
    {
        this.CargarEquipos();
    }
}
</script>

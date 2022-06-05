<template>
  <div>

    <div class="card border-dark">
      <vue-element-loading :active="isLoading"/>
      <div class="card-header bg-dark text-white">
        <i class="fa fa-align-justify"> </i> Requisiciones por autorizar:
        <button v-if="detalles_ver" type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
          <i class="icon-arrow-left"></i>&nbsp;Atras
        </button>
        <button class="btn btn-outline-white float-sm-right" @click="descargar(id_requisicion)" v-if="detalles_ver">
          <i class="fas fa-file-pdf"></i>&nbsp;<i class="fas fa-download"></i>&nbsp;Descargar
        </button>
      </div>
      <div class="card-body">
        <v-client-table :columns="columns" :data="dataTable" :options="options" ref="myTable" v-show="!detalle">
          <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i> Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button type="button" @click="cargardetalle(props.row.id)" class="dropdown-item" >
                    <i class="fas fa-eye"></i>&nbsp; Ver partidas
                  </button>
                </div>
              </div>
            </div>
          </template>
          <!-- <template slot="descargar" slot-scope="props" v-if="props.row.formato_requisiciones == null || props.row.formato_requisiciones == '' ">
            <button class="btn btn-outline-dark" @click="descargar(props.row)">
              <i class="fas fa-file-pdf"></i>&nbsp;<i class="fas fa-download"></i>
            </button>
          </template> -->
          <template slot="condicion" slot-scope="props" >
            <button class="btn btn-sm btn-success" @click="autorizar(3, props.row.id)"><i class="far fa-check-square"></i>&nbsp; Si&nbsp;&nbsp;</button>
            <button v-show="props.row.condicion == 1" class="btn btn-sm btn-danger" @click="autorizar(0, props.row.id)"><i class="fas fa-window-close"></i>&nbsp; No</button>
          </template>


        </v-client-table>

        <div v-show="detalles_ver">
          <v-client-table :data="dataTableDetalle" :options="optionsDetalle" :columns="columnsDetalle">
            <template slot="autorizar" slot-scope="props">
              <template v-if="props.row.correccion == null">
                <button class="btn btn-sm btn-danger" @click="guardarcorrecion(props.row.req)"><i class="fas fa-window-close"></i>&nbsp; No</button>
              </template>
              <template v-if="props.row.correccion != null">
                {{props.row.correccion.comentario}}
              </template>
            </template>
            <template slot="req.comentario_partida" slot-scope="props">
              {{props.row.req.comentario_partida}}<br>
              <template v-if="props.row.regresados != null">
                <span class="badge badge-pill badge-warning">Corregido</span>
              </template>
              <template v-if="props.row.regresados == null">
                <span class="badge badge-pill badge-info">Sin observaciones</span>
              </template>
            </template>

            <!-- <template slot="req.descripcion" slot-scope="props">
              <textarea name="name" rows="6" cols="40" :value="props.row.correccion != null ? props.row.correccion.comentario : props.row.req.descripcion" @keyup.enter="guardarcorrecion($event, props.row.req)"></textarea>
            </template> -->
          </v-client-table>

        </div>
      </div>


    </div>
  </div>
</div>

</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {
  data() {
    return {
      id_requisicion : 0,
      isLoading: false,
      solicitudes: [],
      detalle: false,
      nuevo : null,
      solicitud: [],
      detalles_ver : false,
      detalles_solicitudes : null,
      tipo_cambio : 0,
      moneda : 0,
      columns : ['id','folio','nombrep','fecha_solicitud','nombre_solicita','descripcion_uso','condicion'],
      dataTable : [],
      options: {
        headings: {
          'id' : 'Acciones',
          'articulo_descripcion' : 'Artículo',
          'nombrep' : 'Proyecto',
          'nombre_solicita': 'Empleado que solicita',
          'descripcion_uso': 'Uso',
          'condicion' : 'Validar',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts,
      },
      dataTableDetalle : [],
      columnsDetalle : ['req.descripcion','req.cantidades','req.cantidad_almacen','req.cantidad_compra','req.unidad_articulo','req.comentario_partida','req.frequerido','autorizar'],
      optionsDetalle : {
        headings: {
          'req.descripcion' : 'Articulo',
          'req.cantidades' : 'Cantidad solicitada',
          'req.cantidad_compra' : 'Cantidad a comprar',
          'req.cantidad_almacen' : 'Cantidad en almacén',
          'req.unidad_articulo' : 'Unidad',
          'req.comentario_partida': 'Comentario',
          'req.frequerido': 'Fecha requerida',
          'req.id' : '',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts,
      },
    }
  },
  methods: {
    getData() {
      this.isLoading = true;
      let me=this;
      axios.get('/requisicionesporautorizar').then(response => {
        me.solicitudes= response.data;
        me.dataTable = response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    cargarequisicion() {
      this.getData();
    },
    cargardetalle(id)
    {
      this.isLoading = true;
      this.detalles_ver = true;
      this.detalle = true;
      this.id_requisicion = id;
      let me = this;
      axios.get('/consultadashp/' + id ).then(response => {
        me.detalles_solicitudes = response.data;
        me.dataTableDetalle = response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });

    },
    autorizar(estado , id)
    {
      this.isLoading = true;
      let me = this;
      var cadena = ['Almacén','Supervisor'];
      var cadenaid = [12,14];
      if (estado == 3) {
        swal({
          title: estado != 0  ? 'Esta seguro(a) autorizar la requisición?' : 'Esta seguro(a) de rechazar la requisición',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#4dbd74',
          cancelButtonColor: '#f86c6b',
          confirmButtonText: 'Aceptar!',
          cancelButtonText: 'Cancelar',
          reverseButtons: true
        }).then(result =>{
          if (result.value) {
            axios.post('/autorizarequisicionproyectos',{
              id : id,
              estado : estado,
            }).then(function (response){
              me.isLoading = false;
              if (response.data == 3) {
                toastr.success('Correcto','Requisicion autorizada !!!');
              }
              else if (response.data == 0) {
                toastr.warning('Atención','Requisicion no autorizada !!!');
              }
              me.getData();
              me.isLoading = false;
            }).catch(function (error){
              console.error(error);
            });
          }else {
            me.isLoading = false;
          }
        });
      }else {



        swal({
          title: 'Motivo del rechazo de la requisición',
          input: 'textarea',
          // inputAttributes: {
          //   autocapitalize: 'off'
          // },
          inputValue : 'Indicado en cada partida',
          showCancelButton: true,
          confirmButtonText: 'Continuar',
          showLoaderOnConfirm: true,
          }).then((result) => {
            if (result.value) {

              axios.post('enviarcomentariorechazorequi',{
                id : id,
                data : result.value,
              }).then(response => {
                  console.log('Guardado correctamente');
              });
                      swal({
                        title: 'Direccionar a...',
                        input: 'select',
                        inputOptions: cadena,
                        inputPlaceholder: 'Seleccionar Estado',
                        confirmButtonText:
                        'Continuar <i class="fa fa-arrow-right></i>',
                        showCancelButton: true,
                        customClass: 'form-check form-check-inline',
                        inputValidator: (result) => {
                          return !result && 'Se Requiere Elegir un Elemento'
                        }
                      }).then((result) => {
                        if (result.value) {
                          axios.post('/autorizarequisicionproyectos',{
                            id : id,
                            estado : cadenaid[result.value],
                          }).then(function (response){
                            me.isLoading = false;
                            if (response == 3) {
                              toastr.success('Correcto','Requisición recibida correctamente');
                            }
                            else {
                              toastr.warning('Atención','Requisición no recibida');
                            }
                            me.getData();
                          }).catch(function (error){
                            console.error(error);
                          });
                        }else {
                          me.isLoading = false;
                        }
                      }).catch(e => {
                        console.error(e);
                      });
            }
            console.log(result);
            me.isLoading = false;

          })



        }
      },

      maestro(){
        this.detalles_ver = false;
        this.detalle = false;
        this.detalles_solicitudes = null;
      },

      /**
      * [descargar description]
      * @param  {[type]} data [description]
      * @return {[type]}      [description]
      */
      descargar(data) {
        // window.open('descargar-requisicion/' + data.id, '_blank');
        window.open('descargar-requisicionew/' + data, '_blank');

      },

      guardarcorrecion(data){

        swal({
          title: 'Motivo del rechazo de la requisición',
          input: 'textarea',
          inputAttributes: {
            autocapitalize: 'off'
          },
          showCancelButton: true,
          confirmButtonText: 'Continuar',
          showLoaderOnConfirm: true,
        }).then(result => {
          if (result.value) {

            axios.post('agregar/correciones/partidas',{
              requisicion_id : data.id,
              pda : data.pda,
              articulo_servicio : (data.articulo_id != null ? 1 : 0),
              articulo_servicio_id : (data.articulo_id != null ? data.articulo_id : data.servicio_id),
              comentario : result.value,
            }).then(response => {
              // console.log(response);
              this.cargardetalle(this.id_requisicion);
            }).catch(e => {
              console.error(e);
            });

          }
        })


      },

    },
    mounted() {
    }
  }
  </script>

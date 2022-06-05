<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <button type="button" v-show="!detalle" @click="abrirModal(1)" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
          <button type="button" v-show="detalle" @click="atras()" class="btn btn-secondary float-sm-right">
            <i class="fa fa-arrow-left"></i>&nbsp;Atras
          </button>
          <i class="fa fa-align-justify"></i> {{data_detalle == '' ? '' : 'Reguardos de ' + data_detalle.empleado_solicita}}

        </div>
        <div class="card-body">
          <template v-if="!detalle">

            <v-client-table :data="tableData" :options="options" :columns="columns">

              <template slot="id" slot-scope="props">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                  <div class="btn-group dropup" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-grip-horizontal"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <button type="button" @click="detalles(props.row)" class="dropdown-item" href="#">
                        <i class="fas fa-eye"></i>&nbsp;Detalle
                      </button>
                    </div>
                  </div>
                </div>
              </template>

              <template slot="descargar" slot-scope="props">
                <button type="button" @click="descargar(props.row)" class="btn btn-outline-dark" >
                  <i class="fas fa-download"></i>&nbsp;Descargar
                </button>
              </template>

            </v-client-table>
          </template>

          <template v-if="detalle">
            <v-client-table :data="dataTableDetalle" :columns="columnsDetalle" :options="optionsDetalle">
              <template slot="id" slot-scope="props">
                <button type="button" @click="eliminar(props.row)" class="btn btn-outline-dark" href="#">
                  <i class="fas fa-trash"></i>&nbsp;
                </button>
                <button type="button" @click="retorno(props.row)" class="btn btn-outline-dark" href="#">
                  <i class="fas fa-retweet"></i>&nbsp;
                </button>
              </template>
            </v-client-table>
          </template>

        </div>
      </div>

      <!--Inicio del modal agregar/actualizar articulos-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                <vue-element-loading :active="isLoading"/>

                <div class="form-row">
                  <div class="col-md-3 mb-3">
                    <label>Fecha</label>
                    <input type="date" class="form-control" v-model="fecha">
                  </div>
                  <div class="col-md-5 mb-3">
                    <label>Proyecto</label>
                    <v-select :options="listaProyectos"  v-validate="'required'"
                    v-model="proyecto_id" label="nombre_corto" name="proyecto" data-vv-name="proyecto" ></v-select> <!---->
                    <span class="text-danger">{{errors.first('proyecto')}}</span>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-8 mb-3">
                    <label>Nombre empleado</label>
                    <v-select :options="listaEmpleados" label="name" v-model="empleado"></v-select>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-2">
                    <label>&nbsp;</label>
                    <label>&nbsp;</label>
                    <button @click="abrirModal(3)" class="btn btn-outline-dark" name="button">Buscar</button>
                  </div>
                  <div class="col-md-10 mb-3">
                    <label>Seleccione</label>
                    <textarea name="name" class="form-control" :placeholder="catalogo_descripcion" rows="2" cols="80"></textarea>
                    <!-- <v-select :options="listaCatalogo" v-model="catalogo" label="descripcion" -->
                    <!-- data-vv-name="catalogo" @search="buscar" @input="asignar"> -->
                    <!-- </v-select> -->
                    <span class="text-danger">{{errors.first('catalogo')}}</span>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-2 mb-3">
                    <label >Cantidad Existente</label>
                    <input type="text" class="form-control" readonly v-model="cantidad_existente">
                  </div>
                  <div class="col-md-2 mb-3">
                    <label >Cantidad Salida</label>
                    <input type="text" class="form-control" v-model="cantidad_salida">
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Unidad</label>
                    <input type="text" class="form-control" v-model="unidad">
                  </div>
                  <div class="col-md-1 mb-3">
                    <label>&nbsp;</label><br>
                    <button @click="guardarasignacion()" class="btn btn-outline-dark" name="button">Crear</button>
                  </div>
                </div>
                <hr>
                <div class="form-row">

                  <div class="form-group col-md-6">
                    <label><b>Articulo</b></label>
                  </div>
                  <div class="form-group col-md-2">
                    <label><b>Cantidad</b></label>
                  </div>
                  <div class="form-group col-md-2">
                    <label><b>Unidad</b></label>
                  </div>
                  <div class="form-group col-md-1">
                    <label><b>.</b></label>
                  </div>
                </div>
                <li v-for="(vi, index) in listado_data" class="list-group-item">
                  <div class="form-row">

                    <div class="form-group col-md-6">
                      <label>{{vi.descripcion}}</label>
                    </div>
                    <div class="form-group col-md-2">
                      <label>{{vi.cantidad}}</label>
                    </div>
                    <div class="form-group col-md-2">
                      <label>{{vi.unidad}}</label>
                    </div>
                    <div class="form-group col-md-1">
                      <a @click="deleteu(index)">
                        <span class="fas fa-trash" arial-hidden="true"></span>
                      </a>
                    </div>
                  </div>
                </li>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                <button v-show="tipoAccion == 1" type="button" class="btn btn-secondary" @click="Guardar(1)">
                  Guardar
                </button>
                <button v-show="tipoAccion == 2" type="button" class="btn btn-secondary" @click="Guardar(0)">
                  Actualizar
                </button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal agregar articulo-->


      <!--Inicio del modal agregar/actualizar articulos-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modala}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div>
              <div class="modal-header">
                <h4 class="modal-title" v-text="tituloModala"></h4>
                <button type="button" class="close" @click="cerrarModalArt()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">

                <div class="accordion" id="accordion">
                  <input type="text" class="form-control" v-model="articulo_busqueda" @keyup.enter="buscarArticulo()">
                  <div class="card">
                    <div class="card-header bg-dark" id="headingUno">
                      <h5 class="mb-0">
                        <!-- <button class="text-white btn btn-dark collapsed" type="button" data-toggle="collapse" data-target="#collapseDos" aria-expanded="false" aria-controls="collapseDos">
                        Apartados
                      </button> -->
                      <button class="text-white btn btn-dark collapsed" type="button" data-toggle="collapse" data-target="#collapseUno" aria-expanded="false" aria-controls="collapseUno">
                        General y Proyecto
                      </button>

                    </h5>
                  </div>
                  <div id="collapseUno" class="collapse" aria-labelledby="headingUno" data-parent="#accordion">
                    <v-client-table ref="myTable" :data="dataTableArticulo" :columns="columnsa" :options="optionsa" @row-click="seleccionarArticulo">
                    </v-client-table>
                  </div>
                </div>

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="cerrarModalArt()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal agregar articulo-->

  </div>
</main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default{
  data(){
    return {
      isLoading : false,
      modal : 0,
      tituloModal : '',
      detalle : false,
      tipoAccion : 0,
      articulo_busqueda : '',
      data_detalle : '',

      listaProyectos : [],
      listaEmpleados : [],
      listaCatalogo : [],
      listado_data : [],

      modala : 0,
      tituloModala : '',

      dataTableArticulo : [],
      columnsa : ['cantidad','nombre_corto','nombre','codigo','descripcion','marca','unidad'],
      optionsa: {
        headings: {
          'nombre_corto': 'Proyecto',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        //  sortable: ['empleado.folio'],
        //  filterable: ['empleado.folio'],
        filterByColumn: true,
        texts:config.texts
      },

      tableData : [],
      columns : ['id','empleado_solicita','descargar'],
      options: {
        headings: {
          'id' : 'Acciones',
          'nombre_corto' : 'Proyecto',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        //  sortable: ['empleado.folio'],
        //  filterable: ['empleado.folio'],
        filterByColumn: true,
        texts:config.texts
      },

      dataTableDetalle : [],
      columnsDetalle : ['id','fecha','nombre_corto','descripcion','cantidad','cantidad_retorno'],
      optionsDetalle: {
        headings: {
          'id' : 'Acciones',
          'descripcion' : 'Articulo',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },


      proyecto_id : '',
      fecha : '',
      empleado : '',
      catalogo_id : '',
      catalogo_descripcion : '',
      unidad : '',
      cantidad_existente : '',
      cantidad_salida : '',
    }
  },
  methods : {
    getLista(){
      var d = new Date();
      var dia = d.getFullYear() + '-' +((d.getMonth()+1) < 10 ? '0'+ (d.getMonth()+1) : (d.getMonth() + 1))
      + '-' + (d.getDate() < 10 ? ('0' + d.getDate()): d.getDate());
      this.fecha = dia;

      this.listaProyectos = [];
      axios.get('seguridad/listado/proyectos').then(response => {
        this.listaProyectos = response.data;
      }).catch(e => {
        console.error(e);
      });

      this.listaEmpleados = [];
      axios.get('/vertodosempleados').then(response =>{
        response.data.forEach(data =>{
          this.listaEmpleados.push({
            id: data.id,
            name: data.nombre + ' ' + data.ap_paterno + ' ' + data.ap_materno
          });
        });
      })
      .catch(function (error)
      {
        console.log(error);
      });

    },

    abrirModal(edo){
      if (edo == 1) {
        this.modal = 1;
        this.tituloModal = 'Guardar Resguardo';
        this.tipoAccion = 1;
      }else if (edo == 3) {
        this.modala = 1;
        this.tituloModala = 'Seleccione';
      }
    },

    cerrarModal(){
      this.modal = 0;
    },

    cerrarModalArt(){
      this.modala = 0;
    },

    buscarArticulo () {
      this.dataTableArticulo = [];
      let me = this;
      axios.post('get/lotes/salida/resguardo',{
        des : this.articulo_busqueda,
      }).then(response => {
        me.dataTableArticulo = response.data;
      }).catch(e =>{
        console.error(e);
      });


    },

    guardarasignacion(){
      if (this.catalogo != '' && this.cantidad != '' && this.unidad != '') {
        if (this.cantidad_salida > this.cantidad_salida) {
          toastr.warning('No puede salir material mayor al existente');
        }else {
          this.listado_data.push(
            {
              id : this.catalogo_id,
              descripcion : this.catalogo_descripcion,
              cantidad : this.cantidad_salida,
              unidad : this.unidad,
            },
          );
          this.catalogo_id = '';
          this.catalogo_descripcion = '';
          this.cantidad_salida = '';
          this.cantidad_existente = '';
          this.unidad = '';

        }
      }else {
        toastr.warning('Complete todos los campos');
      }
    },

    deleteu(index){
      this.listado_data.splice(index, 1);
    },

    asignar(){
      this.cantidad = this.catalogo.cantidad;
      this.unidad = this.catalogo.unidad;
    },

    Guardar(nuevo){
      this.$validator.validate().then(result=> {
        if (result) {

          axios({
            method : nuevo ? 'POST' : 'PUT',
            url : nuevo ? 'salida/reguardo/guardar/data' : 'salida/reguardo/actualizar/data',
            data : {
              id : 0,
              proyecto : this.proyecto_id.id,
              empleado : this.empleado.id,
              fecha : this.fecha,
              data : this.listado_data,
            }
          }).then(response => {
            this.getData();
            this.modal = 0;
            this.listado_data = [];
            this.proyecto_id = '';
            this.empleado = '';
          }).catch(e => {
            console.error(e);
          });

        }
      })
    },

    seleccionarArticulo(data){
      console.log(data);
      this.catalogo_id = data.row.id;
      this.catalogo_descripcion = data.row.descripcion;
      this.cantidad_salida = data.row.cantidad;
      this.cantidad_existente = data.row.cantidad;
      this.unidad = data.row.unidad;
      this.modala = 0;
    },

    getData(){
      axios.get('get/encabezados/salida/resguardo').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    detalles(data){
      this.detalle = true;
      this.data_detalle = data;
      axios.get('partidas/salida/resguardo/' + data.id).then(response => {
        this.dataTableDetalle = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    atras(){
      this.detalle = false;
    },

    eliminar(data){
      // axios.get('partidas/salida/resguardo/eliminar/' + data.id).then(response => {
      //   this.detalles(this.data_detalle);
      // }).catch(e => {
      //   console.error(e);
      // });
    },

    retorno(data){
      // console.log(data);
      Swal.mixin({
        input: 'text',
        confirmButtonText: 'Siguiente &rarr;',
        showCancelButton: true,
        progressSteps: ['1', '2']
      }).queue([
        {
          title: 'Cantidad Retorno',
          inputValue: data.cantidad,
        },
        {
          title: 'Cantidad Defectuosos',
          inputValue: '0',
        },
      ]).then((result) => {
        if (result.value) {
          // console.log(result.value);
          if (result.value[1] > result.value[0] ) {
            Swal.fire({
              title: 'No se puede cargar una cantidad defectuosa mayor a la cantidad saliente',
              showConfirmButton: true,
              timer: 2000
            })
          }else {
            axios.post('guardar/retorno/reguardo',{
              id : data.id,
              cantidad : result.value[0],
              cantidad_defectuoso : result.value[1],
            }).then(response =>{
              Swal.fire({
                type: 'success',
                title: 'Correcto !!!',
                showConfirmButton: false,
                timer: 1000
              });
              this.detalles(this.data_detalle);
            }).catch(e => {
              console.error(e);
            });
          }


        }
      })
      // console.log(data);
    },

    descargar(data){
      window.open('descargar/salida/resguardo/' + data.id, '_blank');
    },

  },
  mounted(){
    this.getLista();
    this.getData();
  }
}

</script>

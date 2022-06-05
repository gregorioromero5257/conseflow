<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->

      <br>
      <div class="card" v-show="!detalle" >
        <div class="card-header">
          <i class="fa fa-align-justify"></i> {{tituloprincipal}}
          <button type="button" v-if="tablas.sitio == true" @click="abrirModal('salida','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>

          <button type="button" v-if="tablas.sitio == true" @click="retorno()" class="btn btn-secondary float-sm-right">
            <i class="fas fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">
          <div v-show="tablas.taller">
            <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
              <!-- Como usar un if cuando se tiene 2 condiciones-->
              <template slot="proyecto.condicion" slot-scope="props" >
                <template v-if="props.row.proyecto.condicion == 1">
                  <button type="button" class="btn btn-outline-success">Activo</button>
                </template>
                <template v-if="props.row.proyecto.condicion == 0">
                  <button type="button" class="btn btn-outline-danger">Finalizado</button>
                </template>
              </template>
              <template slot="proyecto.id" slot-scope="props">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                  <div class="btn-group dropup" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <button type="button" @click="SalidasT(props.row.proyecto.id)" class="dropdown-item" >
                  <i class="fas fa-eye"></i>&nbsp; Ver detalles
                </button>
                    </div>
                  </div>
                </div>
              </template>
            </v-client-table>
          </div>
          <div v-show="tablas.sitio">
            <v-client-table :columns="columnss" :data="tableDatas" :options="optionss" ref="myTable">
              <!-- Como usar un if cuando se tiene 2 condiciones-->
              <!-- <template slot="condicion" slot-scope="props" >
                <template v-if="props.row.condicion == 1">
                  <span class="badge badge-success">Activo</span>
                </template>
                <template v-if="props.row.condicion == 0">
                  <span class="badge badge-danger">Dado de Baja</span>
                </template>
              </template> -->

              <template slot="id" slot-scope="props">
               <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                <button type="button" @click="cargardetallesalida(props.row)" class="dropdown-item" >
                  <i class="fas fa-eye"></i>&nbsp; Ver detalles
                </button>
                <button type="button" @click="abrirModal('salida','actualizar',props.row)" class="dropdown-item" >
                  <i class="icon-pencil"></i>&nbsp; Actualizar
                </button>
                <!-- <template v-if="props.row.condicion">
                  <button type="button" class="btn btn-danger btn-sm" @click="actdesact(props.row.id, 0)">
                    <i class="icon-trash"></i>
                  </button>
                </template>
                <template v-else>
                  <button type="button" class="btn btn-info btn-sm" @click="actdesact(props.row.id, 1)">
                    <i class="icon-check"></i>
                  </button>
                </template> -->
                <template>
                  <button type="button" class="dropdown-item" @click="descargars(props.row)" >
                    <i class="fas fa-file-pdf"></i>&nbsp; Descargar PDF Salida Sitio
                  </button>
                </template>
                  </div>
                </div>
               </div>

              </template>
            </v-client-table>
          </div>
        </div>

      </div>
      <!-- Fin ejemplo de tabla Listado -->
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card" v-show="detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Salidas
          <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
            <i class="fas fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>
          <v-client-table :columns="columnspartida" :data="tableDataPartida" :options="optionspartida" ref="myTable">

          </v-client-table>


        </div>
        <!-- Nuevo y editar partidas requisiciones -->
        <div class="card" ref="formLote">
          <!-- <vue-element-loading /> -->
          <div class="card-header">
          </div>
          <div class="card-body">
            <div class="row justify-content-center">
              <h4  class="col-sm-12 form-control-label">Registro de salidas a : {{titulosalidas}}</h4></div><hr><br>
              <form>
                <!-- <input type="text" class="form-control" id="stocke_id" name="stocke_id" v-model="partidasalida.stocke_id" placeholder="Stoke"  > -->
                <input type="text" class="form-control" id="salida_id" name="salida_id" v-model="partidasalida.salida_id" placeholder="Salida" hidden>
                <input type="text" class="form-control" id="lote_id" name="lote_id" v-model="partidasalida.lote_id" placeholder="Lote" hidden>
                <input type="text" class="form-control" id="tiposalida_id" name="tiposalida_id" v-model="partidasalida.tiposalida_id" placeholder="tiposalida_id" hidden >
                <input type="text" class="form-control" id="lote_temporal_id" name="lote_temporal_id" v-model="partidasalida.lote_temporal_id" hidden>

                <!-- <input type="text" class="form-control" id="proveedore_id" name="proveedore_id" v-model="" hidden> -->
                <!-- {{salidav}} -->
                <div class="form-group row">
                  <label for="inputArticulo" class="col-md-1 form-control-label">Artículo</label>
                  <div class="col-md-8">
                    <div class="input-group mb-3">
                      <input type="text" class="form-control"  v-model="partidasalida.articulo" placeholder="Articulo" readonly>
                      <div class="input-group-append">
                        <button class="btn btn-secondary" type="button" v-bind:disabled="desabilitarBuscarM" @click="abrirModalA('articulo','registrar')">Buscar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputArticulo" class="col-md-1 form-control-label">Lote</label>
                  <div class="col-md-4">
                    <div class="input-group mb-3">
                      <input type="text" class="form-control"  v-model="partidasalida.lote_nombre_input" placeholder="Lote" >
                      <div class="input-group-append">
                        <button class="btn btn-secondary" type="button" @click="buscarLoteNombre()">Buscar</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="input-group mb-3">
                      <select class="form-control" v-model="partidasalida.lotes_tgp" v-validate="'excluded:0'" v-bind:disabled="!desabilitarBuscarM" data-vv-as="Resguardo" @change = "seleccionarLote()">
                        <option value="0">---Seleccione---</option>
                        <option v-for="item in lotes_TGP" :value="item" :key="item.id">{{ item.nombre }}</option>
                      </select>
                      <!-- <input type="text" class="form-control"  v-model="partidasalida.articulo" placeholder="Articulo" readonly> -->
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-1 form-control-label" for="cantidad_existente">Cantidad existente</label>
                  <div class="col-md-4">
                    <input type="text" name="cantidad_existente"  v-model="partidasalida.cantidad_existente" class="form-control" readonly>
                    <span class="text-danger">{{ errors.first('cantidad_existente') }}</span>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-1 form-control-label" for="cantidad">Cantidad</label>
                  <div class="col-md-4">
                    <input type="text" name="cantidad"  v-model="partidasalida.cantidad" v-bind:class="'form-control '+clasescantidad" @blur="validacioncantidad" placeholder="Cantidad" autocomplete="off" id="cantidad">
                    <span class="text-danger">{{ errors.first('cantidad') }}</span>
                  </div>
                </div>


              </form>
            </div>
            <div class="card-footer">
              <button type="button" class="btn btn-secondary" @click="cancelar()"><i class="fas fa-times"></i>&nbsp;Cancelar</button>
              <button type="button" v-if="tipoAccionpr==1" class="btn btn-dark" @click="validacioncantidaduno(); guardarPR();"><i class="fas fa-plus"></i>&nbsp;Agregar</button>
            </div>
          </div>
          <!-- Fin  de Nuevo y editar detalle requisicion correspondiente ala tabla partidas_requisiciones -->

          <!--Inicio del modal agregar/actualizar articulos-->
          <div class="modal fade" tabindex="-1" :class="{'mostrar' : modala}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dark modal-lg" role="document">
              <div class="modal-content">
                <div>

                  <vue-element-loading :active="isLoadingg"/>
                  <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModala"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">

                    <div class="accordion" id="accordion">

                      <div class="card">
                        <div class="card-header bg-dark" id="headingUno">
                          <h5 class="mb-0">
                            <button class="text-white btn btn-dark collapsed" type="button" data-toggle="collapse" data-target="#collapseDos" aria-expanded="false" aria-controls="collapseDos">
                              Apartados
                            </button>
                            <button class="text-white btn btn-dark collapsed" type="button" data-toggle="collapse" data-target="#collapseUno" aria-expanded="false" aria-controls="collapseUno">
                              General y Proyecto
                            </button>

                          </h5>
                        </div>
                        <div id="collapseUno" class="collapse" aria-labelledby="headingUno" data-parent="#accordion">
                          <v-client-table ref="myTable" :data="dataTableArticulo" :columns="columnsa" :options="optionsa" @row-click="seleccionarArticulo">
                          </v-client-table>
                        </div>
                        <div id="collapseDos" class="collapse" aria-labelledby="headingDos" data-parent="#accordion">
                          <v-client-table ref="myTable" :data="dataTableArticulodos" :columns="columnsados" :options="optionsa" @row-click="seleccionarArticulodos">
                          </v-client-table>
                        </div>
                      </div>

                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                  </div>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!--Fin del modal agregar articulo-->

        </div>

        <!-- Fin ejemplo de tabla Listado -->
      </div>
      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <vue-element-loading :active="isLoading"/>
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
                  <label class="col-md-3 form-control-label" for="fecha">Fecha</label>
                  <div class="col-md-9">
                    <input type="date" name="fecha" v-model="salida.fecha" class="form-control" placeholder="Fecha de Entrada" autocomplete="off" id="fecha" @change="validarFecha(salida.fecha)">
                    <span class="text-danger">{{ errors.first('fecha') }}</span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="folio">Folio</label>
                  <div class="col-md-9">
                    <input type="text" name="folio" v-validate="'required'" v-model="salida.folio" class="form-control" placeholder="Folio" autocomplete="off" id="folio">
                    <span class="text-danger">{{ errors.first('folio') }}</span>
                  </div>
                </div>


                <div class="form-group row">
                  <label class="col-md-3 form-control-label"  for="ubicacion">Ubicacion</label>
                  <div class="col-md-9">
                    <input type="text" name="ubicacion" v-validate="'required'" v-model="salida.ubicacion" class="form-control" placeholder="Ubicacion" autocomplete="off" id="ubicacion">
                    <span class="text-danger">{{ errors.first('ubicacion') }}</span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="proyecto_id">Proyecto</label>
                  <div class="col-md-9">
                    <select class="form-control" id="proyecto_id"  name="proyecto_id" v-model="salida.proyecto_id" v-bind:disabled="desabilitado == 1" v-validate="'excluded:0'" data-vv-as="Proyecto">
                      <option v-for="item in listaProyectos" :value="item.proyecto.id" :key="item.id">{{item.proyecto.nombre}}</option>
                    </select>
                    <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="tiposalida_id">Tipo Salida</label>
                  <div class="col-md-9">
                    <select class="form-control" id="tiposalida_id"  name="tiposalida_id" v-model="salida.tiposalida_id" v-bind:disabled="desabilitado == 1" v-validate="'excluded:0'" data-vv-as="Salida">
                      <option v-for="item in listaTipoSalida" :value="item.id" :key="item.id">{{item.nombre}}</option>
                    </select>
                    <span class="text-danger">{{ errors.first('tiposalida_id') }}</span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="empleado_id">Nombre del Solicitante:</label>
                  <div class="col-md-9">
                    <v-select :options="optionsvs" v-model="salida.empleado_id" name="empleado_id" label="name" v-bind:disabled="tipoAccion == 2" v-validate="'excluded:0'" data-vv-as="Empleado" ></v-select>
                    <span class="text-danger">{{ errors.first('empleado_id') }}</span>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="empleado_id">Empleado Entrega:</label>
                  <div class="col-md-9">
                    <v-select :options="optionsvs" v-model="salida.empleado_uno" name="empleado_id" label="name" v-bind:disabled="desabilitado == 1" v-validate="'excluded:0'" data-vv-as="Empleado" ></v-select>
                    <span class="text-danger">{{ errors.first('empleado_id') }}</span>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="empleado_id">Empleado Autoriza:</label>
                  <div class="col-md-9">
                    <v-select :options="optionsvs" v-model="salida.empleado_dos" name="empleado_id" label="name" v-bind:disabled="tipoAccion == 2" v-validate="'excluded:0'" data-vv-as="Empleado" ></v-select>
                    <span class="text-danger">{{ errors.first('empleado_id') }}</span>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-md-3 form-control-label" for="empleado_id">Empleado Recibe:</label>
                  <div class="col-md-9">
                    <v-select :options="optionsvs" v-model="salida.empleado_tres" name="empleado_id" label="name" v-bind:disabled="tipoAccion == 2" v-validate="'excluded:0'" data-vv-as="Empleado" ></v-select>
                    <span class="text-danger">{{ errors.first('empleado_id') }}</span>
                  </div>
                </div>



                <!-- </form> -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
                <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarSalida(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarSalida(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
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

  export default {
    data (){
      return {
        url: '/salida',
        Auth:[],
        salidav : null,
        detalle : false,
        titulosalidas : '',
        tituloprincipal : 'Proyectos',
        optionsvs: [],
        tipoAccionpr : 0,
        desabilitado : 0,
        tituloempleado : '',
        valorfecha:'',
        valorproyecto:0,
        tablas : {
          taller : true,
          sitio : false,
        },
        salida: {
          fecha :'',
          folio	 :'',
          format_salida : '',
          descripcion : '',
          ubicacion: '',
          proyecto_id: 0,
          tiposalida_id: 0,
          empleado_id : '',
          empleado_uno : '',
          empleado_dos : '',
          empleado_tres : '',
          condicion :0,
          nombrep: '',
          empleado: '',
          salidan: '',
        },
        partidasalida:{
          stocke_id :'',
          salida_id : '',
          lote_id : '',
          cantidad : '',
          cantidad_existente : '',
          articulo : '',
          tiposalida_id:'',
          lote_temporal_id : 0,
          lote_nombre_input : '',
          lotes_tgp : 0,

        },
        desabilitarBuscarM : false,
        lotes_TGP : [],
        listaProyectos: [],
        listaTipoSalida: [],
        dataTableArticulo : [],
        dataTableArticulodos : [],
        modal : 0,
        modala : 0,
        tituloModal : '',
        tituloModala : '',
        tipoAccion : 0,
        clasescantidad : '',
        validado : 0,
        isLoading: false,
        isLoadingg: false,
        columns: ['proyecto.id','proyecto.nombre_corto','proyecto.clave','proyecto.ciudad'],
        columnss : ['id','fecha','folio','nombrep','salidan'],
        columnsados : ['cantidad','proyecton','anombre','acodigo','adescripcion','amarca','aunidad'],
        columnsa : ['cantidad','proyecton','anombre','acodigo','adescripcion','amarca','aunidad'],
        columnspartida : ['cantidad','anombre','acodigo','adescripcion','amarca','aunidad','alnombre','pnombre'],
        tableData: [],
        tableDatas : [],
        tableDataPartida : [],
        optionsa: {
          headings: {
            'anombre': 'Nombre',
            'acodigo': 'Codigo',
            'adescripcion' : 'Descripción',
            'amarca' : 'Marca',
            'aunidad' : 'Unidad',
            'proyecton' : 'Proyecto',
            'nombre_stock' : 'Stock',
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
        options: {
          headings: {
            'proyecto.id': 'Acciones',
            'proyecto.nombre': 'Nombre de Proyecto',
            'proyecto.nombre_corto': 'Nombre Corto',
            'proyecto.clave': 'Clave Proyecto',
            'proyecto.ciudad': 'Ciudad',
            // 'proyecto.condicion': 'Estado',
          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: ['proyecto.nombre_corto','proyecto.clave','proyecto.ciudad'],
          filterable: ['proyecto.nombre_corto','proyecto.clave','proyecto.ciudad'],
          filterByColumn: true,
        //   listColumns: {
        //     condicion: [{
        //       id: 1,
        //       text: 'Activo'
        //     },
        //     {
        //       id: 0,
        //       text: 'Dado de Baja'
        //     }
        //   ]
        // },
        texts:config.texts
      },
      optionss: {
        headings: {
          id: 'Acciones',
          fecha: 'Fecha',
          folio: 'Folio',
          nombrep: 'Nombre Proyecto',
          salidan: 'Tipo de Salida',
          empleado: 'Nombre Empleado',
          // condicion: 'Estado',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['fecha','folio','condicion'],
        filterable: ['fecha','folio'],
        filterByColumn: true,
      //   listColumns: {
      //     condicion: [{
      //       id: 1,
      //       text: 'Activo'
      //     },
      //     {
      //       id: 0,
      //       text: 'Dado de Baja'
      //     }
      //   ]
      // },
      texts:config.texts
    },
  optionspartida: {
    headings: {
      id: 'Acciones',
      cantidad: 'Cantidad requerida',
      anombre : 'Nombre',
      acodigo : 'Codígo',
      adescripcion : 'Descripción',
      amarca : 'Marca',
      aunidad : 'Unidad',
      alnombre : 'Nombre almacén',
      pnombre : 'Proyecto'

    },
    perPage: 10,
    perPageValues: [],
    skin: config.skin,
    sortIcon: config.sortIcon,
    sortable: ['id','cantidad','anombre','acodigo','adescripcion','amarca','aunidad'],
    filterable: ['id','cantidad','anombre','acodigo','adescripcion','amarca','aunidad'],
    filterByColumn: true,
    listColumns: {

    },
    texts:config.texts
  },
}
},
computed:{
},
methods : {
  SalidasT(idproyecto)
  {
    let me=this;
    axios.get(me.url+'/'+idproyecto+'/sitios').then(response => {
      me.tableDatas = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });

    this.valorproyecto = idproyecto;
    this.tituloprincipal = 'Salidas a Sitio';
    this.tablas.taller = false;
    this.tablas.sitio = true;
  },
  getData() {
    let me=this;
    me.tableData = [];
    me.listaProyectos = [];
    me.Auth = [];
    axios.get('/proyecto/salidas/get').then(response => {
      me.tableData = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });

    axios.get('/proyecto').then(response => {
      me.listaProyectos = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });

    axios.get(me.url).then(response => {
      me.Auth = response.data[0];
    })
    .catch(function (error) {
      console.log(error);
    });
  },
  getListaEmpleados() {
    let me= this;
    axios.get('/empleado').then(response => {
      for (var i = 0; i < response.data.length; i++) {
        this.optionsvs.push(
          {
            id:response.data[i].empleado.id,
            name:response.data[i].empleado.nombre+' '+response.data[i].empleado.ap_paterno+' '+response.data[i].empleado.ap_materno,
            puesto:response.data[i].puesto.nombre
          });
        }
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getTipoSalida() {
      let me=this;
      axios.get('/tiposalida').then(response => {
        me.listaTipoSalida = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    validacioncantidaduno(){
      if (this.partidasalida.cantidad == '') {
        swal('Error!','Complete los campos','warning' )
        this.validado = 0;
        this.clasescantidad = ' is-invalid';
      }
      else {
        this.validado = 1;
      }
    },
    validacioncantidad(){
      if (this.partidasalida.cantidad > this.partidasalida.cantidad_existente) {
        swal('Error!','No se puede pedir una cantidad mayor a la existente!','warning' )
        this.partidasalida.cantidad = '';
        this.validado = 0;
      }
      else {
        this.validado = 1;
      }
    },

    /**
    * [guardarPR guarda las partidas de la salidas a taller y verifica que el artículo sea o no suseptible a resguardo]
    * @return {[type]} [description]
    */
    guardarPR(){
      let me= this;

      if (this.validado == 1) {
        /**/
        me.isLoading = true;
        axios.post('/registroresguardo', {
          salida_id: this.partidasalida.salida_id,
          tiposalida_id : this.partidasalida.tiposalida_id,
          lote_id: this.partidasalida.lote_id,
          cantidad : this.partidasalida.cantidad,
        })
        .then(function (response) {
        })
        .catch(function (error) {
          console.log(error);
        });
        /**/
        if (this.partidasalida.lote_temporal_id != 0) {
          axios.post('/partidasalidaapartados', {
            lote_temporal_id : this.partidasalida.lote_temporal_id,
            cantidad : this.partidasalida.cantidad,
            salida_id: this.partidasalida.salida_id,
            tiposalida_id : this.partidasalida.tiposalida_id,
            lote_id: this.partidasalida.lote_id,
          }).then(function (response){
            me.cargardetallesalida(me.salidav);
            me.cancelar();
            toastr.success('Correcto','Partida agregada correctamente');
            me.isLoading = false;
          }).catch(function (error){
            console.error(error);
          });
        }
        else {
          axios.post('/partidasalida', {
            salida_id: this.partidasalida.salida_id,
            tiposalida_id : this.partidasalida.tiposalida_id,
            lote_id: this.partidasalida.lote_id,
            cantidad : this.partidasalida.cantidad,
          })
          .then(function (response) {
            me.cargardetallesalida(me.salidav);
            me.cancelar();
            toastr.success('Correcto','Partida agregada correctamente');
            me.isLoading = false;
          })
          .catch(function (error) {
            console.log(error);
          });
        }
      }
    },

    fechaActual(){
      let me=this;
      axios.get('/FechaActual').then(response => {
        this.valorfecha = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    validarFecha(dato){
      if (dato < this.valorfecha) {
        toastr.error('La Fecha no Puede ser Anterior a la Actual');
        this.salida.fecha = this.valorfecha;
      }
    },
    guardarSalida(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? me.url : me.url+'/'+this.id,
            data: {
              'id': this.salida.id,
              'fecha': this.salida.fecha,
              'folio': this.salida.folio,
              'ubicacion': this.salida.ubicacion,
              'proyecto_id': this.salida.proyecto_id,
              'tiposalida_id': this.salida.tiposalida_id,
              'empleado_id': this.salida.empleado_id.id,
              'empleado_uno' : this.salida.empleado_uno.id,
              'empleado_dos' : this.salida.empleado_dos.id,
              'empleado_tres' : this.salida.empleado_tres.id,
            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              me.SalidasT(me.salida.proyecto_id);
              me.cerrarModal();
              //me.getData();
              if(!nuevo){
              toastr.success('Salida Actualizada Correctamente');
            }
            else
            {
              toastr.success('Salida Registrada Correctamente');
            }

            } else {
              swal({
                type: 'error',
                html: response.data.errors.join('<br>'),
              });
            }
          }).catch(function (error) {
            console.log(error);
          });
        }
      });
    },
    // actdesact(id,activar,idb,n,ap,am){
    //   if(activar){
    //     this.swal_titulo = 'Esta seguro de activar esta Salida?';
    //     this.swal_tle = 'Activado';
    //     this.swal_msg = 'El registro ha sido activado con éxito.';
    //   }else{
    //     this.swal_titulo = 'Esta seguro de desactivar esta Salida?';
    //     this.swal_tle = 'Desactivado!';
    //     this.swal_msg = 'El registro ha sido desactivado con éxito.';
    //   }
    //   swal({
    //     title: this.swal_titulo,
    //     type: 'warning',
    //     showCancelButton: true,
    //     confirmButtonColor: '#3085d6',
    //     cancelButtonColor: '#d33',
    //     confirmButtonText: 'Aceptar!',
    //     cancelButtonText: 'Cancelar',
    //     confirmButtonClass: 'btn btn-success',
    //     cancelButtonClass: 'btn btn-danger',
    //     buttonsStyling: false,
    //     reverseButtons: true
    //   }).then((result) => {
    //     if (result.value) {
    //       let me = this;
    //       axios.get(me.url+'/'+id+'/edit').then(function (response) {
    //         if(activar){
    //           me.SalidasT(me.empleado);
    //           toastr.success(me.swal_tle,me.swal_msg,'success')

    //         me.getData();
    //       }).catch(function (error) {
    //         console.log(error);
    //       });
    //     } else if (
    //       result.dismiss === swal.DismissReason.cancel
    //     ) {

    //     }
    //   })
    // },
    cerrarModal(){
      this.modal=0;
      this.modala = 0;
      this.tituloModal='';
      Utilerias.resetObject(this.salida);
    },
    cancelar(){
      this.partidasalida.cantidad = '';
      this.tipoAccionpr = 0;
      this.partidasalida.articulo = '';
      this.partidasalida.cantidad_existente = '';
      this.partidasalida.lote_nombre_input = '';
      this.partidasalida.lotes_tgp = 0;
      this.desabilitarBuscarM = false;
    },
    cargardetallesalida (dataSalida = []) {
      this.salidav = dataSalida;
      this.detalle = true;
      let me = this;
      this.isLoadingg = true;
      this.partidasalida.salida_id = dataSalida.id;
      this.partidasalida.tiposalida_id = dataSalida.tiposalida_id;
      this.titulosalidas = dataSalida.salidan;
      axios.get('/partidasalida/' + dataSalida.id+'&'+dataSalida.tiposalida_id +'/ver' ).then(response => {
        me.tableDataPartida = response.data;
        this.isLoadingg = false;
      })
      .catch(function (error){
        console.error();
      });
    },
    seleccionarArticulo(data){
      let me = this;
      this.partidasalida.lote_id = data.row.id;
      this.partidasalida.articulo = data.row.adescripcion;
      this.partidasalida.cantidad_existente = data.row.cantidad;
      // this.partidasalida.tiposalida_id = data.row.tiposalida_id;
      this.modala = 0;
    },

    /**
    * [seleccionarArticulo selecciona la fila de la tabla articulos y carga los datos o los inputs correspondientes]
    * @param  {Array} data [description]
    * @return {[type]}      [description]
    */
    seleccionarArticulodos(data){
      let me = this;
      // console.log(data);
      this.partidasalida.lote_id = data.row.id;
      this.partidasalida.articulo = data.row.adescripcion;
      this.partidasalida.cantidad_existente = data.row.cantidad;
      this.partidasalida.cantidad = data.row.cantidad;
      this.partidasalida.lote_temporal_id = data.row.lt_id;
      this.modala = 0;
    },

    retorno(){
      this.tituloprincipal = 'Proyectos';
      this.tablas.taller = true;
      this.tablas.sitio = false;
    },
    maestro(){
      this.detalle = false;
      this.cancelar();
    },

    /**
    * [abrirModalA Abre el modal donde se eligen los articulos para la salida ya sea del stock general o de un proyecto especifico]
    * @param  {String} modelo [description]
    * @param  {String} accion [description]
    * @return {[type]}        [description]
    */
    abrirModalA(modelo, accion){
      switch(modelo){
        case "articulo":
        {
          switch(accion){
            case 'registrar':
            {
              let me= this;
              me.cancelar();
              this.modala = 1;
              this.tipoAccionpr = 1;
              this.tituloModala = 'Registrar artículo a la salida ';
              axios.get('/partidasalida/'+me.salidav.proyecto_id).then(response => {
                me.dataTableArticulo = response.data;
              })
              .catch(function (error) {
                console.log(error);
              });

              axios.get('/getlotetemporal/'+me.salidav.proyecto_id).then(response => {
                me.dataTableArticulodos = response.data;
              }).catch(function (error){
                console.error(error);
              });

              break;
            }
          }
        }
      }
    },

    abrirModal(modelo, accion, data = []){
      var ts = data['tiposalida_id'];
      switch(modelo){
        case "salida":
        {
          switch(accion){
            case 'registrar':
            {
              this.modal = 1;
              this.tituloModal = 'Registrar Salida';
              Utilerias.resetObject(this.salida);
              this.tipoAccion = 1;
              this.desabilitado = 1;
              this.salida.tiposalida_id = 2;
              this.salida.proyecto_id = this.valorproyecto;
              this.salida.fecha = this.valorfecha;
              this.salida.empleado_uno = { id: this.Auth.id, name: this.Auth.empleado};
              break;
            }
            case 'actualizar':
            {
              Utilerias.resetObject(this.salida);
              this.modal=1;
              this.desabilitado = 1;
              this.tituloModal='Actualizar Salida';
              this.salida.id=data['id'];
              this.tipoAccion=2;
              this.salida.fecha = data['fecha'];
              this.salida.folio = data['folio'];
              this.salida.ubicacion = data['ubicacion'];
              this.salida.proyecto_id = data['proyecto_id'];
              this.salida.tiposalida_id = data['tiposalida_id'];

              this.salida.empleado_id = data['empleadoES'];
              this.salida.empleado_uno = data['empleadoEE'];
              this.salida.empleado_dos = data['empleadoEA'];
              this.salida.empleado_tres = data['empleadoER'];
              break;
            }
          }
        }
      }
    },

    buscarLoteNombre(){
      let me = this;
      // console.log(me.salidas);
      this.desabilitarBuscarM = true;
      this.partidasalida.lotes_tgp = 0;
      axios.post('/buscarlotenombre',{
        'lotenombreinput' : this.partidasalida.lote_nombre_input,
        'proyecto_id' : me.salidav.proyecto_id,
      }).then(response => {
        this.lotes_TGP = response.data;

      }).catch(error => {
        console.log(error);
      });
    },

    seleccionarLote(){
      if(this.partidasalida.lotes_tgp.nombre != 'Apartado por requisicion'){
        axios.get('/obtenerarticulog/'+ this.partidasalida.lotes_tgp.id).then(response => {
          this.tipoAccionpr = 1;
          this.partidasalida.lote_id = response.data.id;
          this.partidasalida.articulo = response.data.adescripcion;
          this.partidasalida.cantidad_existente = response.data.cantidad;
          this.partidasalida.lote_temporal_id = 0;
        }).catch(error => {
          console.log(error);
        });

      }

      else {
        this.tipoAccionpr = 1;
        axios.get('/obtenerarticuloa/'+ this.partidasalida.lotes_tgp.id).then(response => {
          this.partidasalida.lote_id = response.data.id;
          this.partidasalida.articulo = response.data.adescripcion;
          this.partidasalida.cantidad_existente = response.data.cantidad;
          this.partidasalida.lote_temporal_id = response.data.lt_id;
        }).catch(error => {
          console.log(error);
        });

      }
    },

    descargars(data) {
      window.open('descargar-salidasitio-new/' + data.id, '_blank');
      let me = this;
      me.getData();
    }
  },
  mounted() {
    this.getData();
    this.getTipoSalida();
    this.fechaActual();
    this.getListaEmpleados();
  }
}
</script>

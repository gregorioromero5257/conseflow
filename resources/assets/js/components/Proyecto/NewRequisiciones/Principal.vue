<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Requisiciones
        </div>
        <div class="card-body">

          <div class="form-row">
            <div class="col-md-6 mb-3">
              <label class="form-control-label" >Proyecto:</label>
              <v-select :options="listaProyectos" label="name" v-model="proyecto"></v-select>
            </div>
            <div class="col-md-1 mb-3">
              <label>&nbsp;</label><br>
              <button class="btn btn-outline-primary" @click="getRequisicionProyecto()"><i class="fas fa-search"></i>&nbsp;Buscar</button>
            </div>
            &nbsp;
            <div class="col-md-1 mb-3">
              <label>&nbsp;</label><br>
              <button class="btn btn-outline-dark" @click="abrirModal(1)"><i class="fas fa-cart-plus"></i>&nbsp;Nuevo</button>
            </div>
          </div>
          <hr>
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="requisicion.partida" slot-scope="props">
              <button @click="cargardetalle(props.row.requisicion)" class="btn btn-outline-dark" >
                <i class="fas fa-list"></i>&nbsp;Partidas
              </button>
            </template>

            <template slot="requisicion.descargar" slot-scope="props" >
              <button class="btn btn-outline-dark" @click="descargarnew(props.row.requisicion)">
                <i class="fas fa-file-pdf"></i>&nbsp;<i class="fas fa-download"></i>
              </button>
            </template>

            <template slot="requisicion.id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>&nbsp;
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                    <div v-if="props.row.requisicion.estado_id == 0 || props.row.requisicion.estado_id == 4">
                      <button  type="button" @click="abrirModal('requisicion','actualizar',props.row.requisicion)" class="dropdown-item" href="#">
                        <i class="icon-pencil"></i>&nbsp;Actualizar requisición
                      </button>
                    </div>

                    <div v-if="(props.row.requisicion.estado_id == 0 || props.row.requisicion.estado_id == 4 || props.row.requisicion.estado_id == 7) ">
                      <button  href="#" @click="cerrarRequisicion(props.row.requisicion)" class="dropdown-item">
                        <i class="far fa-window-close"></i>&nbsp;Cerrar requisición
                      </button>
                    </div>

                  </div>
                </div>
              </div>
            </template>

            <template slot="requisicion.folio" slot-scope="props">
              <template v-if="props.row.comentario != null">
                <span class="text-danger">{{props.row.requisicion.folio}}<br> </span>
              </template>
              <template v-else>
                {{props.row.requisicion.folio}}
              </template>
            </template>

            <template slot="requisicion.estado_id" slot-scope="props">
              <template v-if="props.row.requisicion.estado_id == 0">
                <button type="button" class="btn btn-uno"><i class="fas fa-plus"></i>&nbsp;Nuevo</button>
              </template>

              <template v-if="props.row.requisicion.estado_id == 1">
                <button type="button" class="btn btn-dos"><i class="fa fa-exclamation-circle"></i>&nbsp;En espera de validación por</button>
              </template>

              <template v-if="props.row.requisicion.estado_id == 2">
                <button type="button" class="btn btn-tres"><i class="fa fa-exclamation-triangle"></i>&nbsp;En espera de autorización</button>
              </template>

              <template v-if="props.row.requisicion.estado_id == 3">
                <button type="button" class="btn btn-cuatro"><i class="fa fa-check-circle"></i>&nbsp;En revisión por almacén</button>
              </template>

              <template v-if="props.row.requisicion.estado_id == 5">
                <button type="button" class="btn btn-nueve"><i class="fa fa-check-circle"></i>&nbsp;Autorizado por compras</button>
              </template>

              <template v-if="props.row.requisicion.estado_id == 4">
                <button type="button" class="btn btn-ocho" @click="vercomentarios(props.row.comentario.comentario)"><i class="fa fa-times"></i>&nbsp;No autorizado</button>
              </template>

              <template v-if="props.row.requisicion.estado_id == 6">
                <button type="button" class="btn btn-siete"><i class="fa fa-caret-square-o-left"></i>&nbsp;Espera autorización compras</button>
              </template>

              <template v-if="props.row.requisicion.estado_id == 7">
                <button type="button" class="btn btn-seis"><i class="fa fa-caret-square-o-left"></i>&nbsp;Por Equivalente</button>
              </template>

              <template v-if="props.row.requisicion.estado_id == 8">
                <button type="button" class="btn btn-cinco"><i class="fa fa-caret-square-o-left"></i>&nbsp;En Espera (Calidad)</button>
              </template>

            </template>
          </v-client-table>

        </div>
      </div>

      <!--Inicio del modal agregar/actualizar requisicion-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_principal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg modal-lg-new" role="document">
          <div class="modal-content">
            <div>
              <div class="modal-header">
                <h4 class="modal-title" v-text="tituloModal"></h4>
                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">

                <ul class="nav nav-tabs" role="tablist" ref="tabs">
                  <li class="nav-item" >
                    <a class="nav-link active" data-toggle="tab" href="#menu1" role="tab" @click="setTab(1)"><i class="fas fa-book"></i>&nbsp;Datos Generales</a>
                  </li>
                  <li class="nav-item"  >
                    <a class="nav-link" data-toggle="tab" href="#menu2" role="tab" @click="setTab(2)"><i class="fas fa-tools"></i>&nbsp;Artículos</a>
                  </li>
                  <li class="nav-item"  >
                    <a class="nav-link" data-toggle="tab" href="#menu3" role="tab" @click="setTab(3)"><i class="fas fa-tools"></i>&nbsp;Servicios</a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div id="menu1" class="tab-pane fade in active show" v-show="tabs == 1">
                    <div class="form-row">
                      <div class="col-md-4 mb-3">
                        <label>Area Solicitante</label>
                        <select class="form-control" v-validate="'required'" v-model="requisicion.area_solicita_id" data-vv-name="área solicitante">
                          <option v-for="item in listaAreasSol" :value="item.id" :key="item.id">{{item.nombre}} </option>
                        </select>
                        <span class="text-danger">{{ errors.first('área solicitante') }}</span>
                      </div>
                      <div class="col-md-2 mb-3">
                        <label>Fecha solicitud</label>
                        <input type="date" class="form-control" v-model="requisicion.fecha_solicitud" v-validate="'required'" data-vv-name="Fecha">
                        <span class="text-danger">{{errors.first('Fecha')}}</span>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label>Descripcion de uso</label>
                        <input type="text" name="descripcion_uso" v-validate="'required'" v-model="requisicion.descripcion_uso" class="form-control"
                        placeholder="Uso" autocomplete="off" data-vv-name="descripcion uso" id="descripción uso">
                        <span class="text-danger">{{ errors.first('descripcion uso') }}</span>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-md-6 mb-3">
                        <label>Proyecto</label>
                        <v-select :options="listaProyectos" label="name" v-model="requisicion.proyecto" v-validate="'required'" data-vv-name="Proyecto"></v-select>
                        <span class="text-danger">{{ errors.first('Proyecto') }}</span>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-md-4 mb-3">
                        <label >Solicitado por</label>
                        <v-select :options="listadoEmpleados" v-validate="'required'" v-model="requisicion.solicita_empleado_id"
                        label="name" data-vv-name="empleado que solicita" ></v-select>
                        <span class="text-danger">{{ errors.first('empleado que solicita') }}</span>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label>Revisado por</label>
                        <v-select :options="listadoEmpleados" v-validate="'required'" v-model="requisicion.valida_empleado_id"
                        label="name" data-vv-name="empleado que valida" ></v-select>
                        <span class="text-danger">{{ errors.first('empleado que valida') }}</span>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label>Autorizado por </label>
                        <v-select :options="listadoEmpleados" v-validate="'required'" v-model="requisicion.autoriza_empleado_id"
                        label="name" data-vv-name="empleado que autoriza" ></v-select>
                        <span class="text-danger">{{ errors.first('empleado que autoriza') }}</span>
                      </div>
                    </div>

                  </div>
                  <div id="menu2" class="tab-pane fade" v-show="tabs == 2">
                    <!-- <div class="form-row">
                      <div class="col-md-10 mb-3">
                        <label>Seleccione</label>
                        <div class="input-group">
                          <textarea name="name" rows="2" cols="80" class="form-control" v-model="partida_articulos.nombre_articulo" readonly></textarea>
                          <div class="input-group-append">
                            <button class="btn btn-secondary" type="button" @click="abrirModalAddArt(1)">Buscar</button>
                            <button class="btn btn-secondary" type="button" @click="agregarArt()"> Agregar Artículo</button>
                          </div>
                        </div>
                      </div>
                    </div> -->
                    <div class="form-row">
                      <div class="col-md-10 mb-3">
                        <label>Descripción</label>
                        <textarea class="form-control" v-model="partida_articulos.nombre_articulo" rows="2" cols="80"></textarea>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-md-4 mb-3">
                        <label>Marca</label>
                        <input type="text" class="form-control" v-model="partida_articulos.marca" >
                      </div>
                      <div class="col-md-3 mb-3">
                        <label>Unidad Medida</label>
                        <v-select name="unidad" label="nombre" v-model="partida_articulos.unidad" :options="listUnidadesM"></v-select>
                      </div>
                      <div class="col-md-3 mb-3">
                        <label>Tipo Resguardo</label>
                        <select class="form-control" v-model="partida_articulos.tipo_resguardo_id">
                          <option v-for="item in listaTipoResguardo" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-md-3 mb-3">
                        <label>Cantidad</label>
                        <input type="text" v-validate="'decimal:4'" v-model="partida_articulos.cantidad" class="form-control" data-vv-name="Cantidad">
                        <span class="text-danger">{{errors.first('Cantidad')}}</span>
                      </div>
                      <div class="col-md-3 mb-3">
                        <label>Fecha Requerida</label>
                        <input type="date" v-model="partida_articulos.fecha_requerido" class="form-control">
                      </div>
                      <div class="col-md-2 mb-3">
                        <label>&nbsp;</label><br>
                        <button class="btn btn-outline-dark" @click="AgregarArtTable()"> Agregar</button>
                      </div>
                    </div>
                    <hr>
                    <v-client-table :columns="columnsdetallearticulos" :data="tableDatadetallearticulos" :options="optionsdetallearticulos" ref="myTabledescuento">
                      <template slot="id" slot-scope="props">
                        <button class="btn btn-outline-danger" @click="deleteArticuloTable(props.row)"><i class="far fa-window-close"></i></button>
                        <button class="btn btn-outline-warning"><i class="far fa-edit"></i></button>
                      </template>
                    </v-client-table>
                  </div>

                  <div id="menu3" class="tab-pane fade" v-show="tabs == 3">
                    <!-- <div class="form-row">
                      <div class="col-md-10 mb-3">
                        <label>Seleccione</label>
                        <div class="input-group">
                          <textarea name="name" rows="2" cols="80" class="form-control" v-model="partida_servicios.nombre_servicio" readonly></textarea>
                          <div class="input-group-append">
                            <button class="btn btn-secondary" type="button" @click="abrirModalAddServ(1)">Buscar</button>
                            <button class="btn btn-secondary" type="button" @click="agregarServ()"> Agregar Artículo</button>
                          </div>
                        </div>
                      </div>
                    </div> -->
                    <div class="form-row">
                      <div class="col-md-10 mb-3">
                        <label>Descripción</label>
                        <textarea class="form-control" v-model="partida_servicios.nombre_servicio" rows="2" cols="80"></textarea>
                      </div>
                    </div>
                    <div class="form-row">
                      <div class="col-md-2 mb-3">
                        <label>Cantidad</label>
                        <input type="text" v-validate="'decimal:4'" v-model="partida_servicios.cantidad" class="form-control" data-vv-name="Cantidad">
                        <span class="text-danger">{{errors.first('Cantidad')}}</span>
                      </div>
                      <div class="col-md-3 mb-3">
                        <label>Marca</label>
                        <input type="text" class="form-control" v-model="partida_servicios.marca" >
                      </div>
                      <div class="col-md-2 mb-3">
                        <label>Fecha Requerida</label>
                        <input type="date" v-model="partida_servicios.fecha_requerido" class="form-control">
                      </div>
                      <div class="col-md-2 mb-3">
                        <label>&nbsp;</label><br>
                        <button class="btn btn-outline-dark" @click="AgregarServTable()"> Agregar</button>
                      </div>
                    </div>
                    <hr>
                    <v-client-table :columns="columnsdetalleservicios" :data="tableDatadetalleservicios" :options="optionsdetalleservicios" ref="myTabledescuento">
                      <template slot="id" slot-scope="props">
                        <button class="btn btn-outline-danger" @click="deleteServicioTable(props.row)"><i class="far fa-window-close"></i></button>
                        <button class="btn btn-outline-warning"><i class="far fa-edit"></i></button>
                      </template>
                    </v-client-table>
                  </div>

                </div>

              </div>
              <div class="modal-footer">
                <!-- <vue-element-loading :active="isLoading"/> -->
                <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarRequisicion(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarRequisicion(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal-->

      <articulo ref="articulo" @cerrarAbrir="abrirBusArticulo()"></articulo>

      <!--Inicio del modal agregar/actualizar articulos-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_articulo}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg modal-lg-new" role="document">
          <div class="modal-content">
            <div>
              <div class="modal-header">
                <h4 class="modal-title">Busqueda de artículo</h4>
                <button type="button" class="close" @click="cerrarModalArticulo()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-row">
                  <div class="col-md-12 mb-3">
                    <label>Articulo a buscar</label>
                    <input type="text" class="form-control" v-model="articulo_busqueda" @keyup.enter="buscarArticulo()">
                  </div>
                  <v-client-table class="table-fix" :data="tableDataArticulos" :columns="columnsArticulos" :options="optionsArticulos" @row-click="seleccionarArticulo">
                    <template slot="child_row" slot-scope="props">
                      <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group" role="group">
                          <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-grip-horizontal"></i>&nbsp;Acciones
                          </button>
                          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <button type="button" @click="actualizarArt(props.row)" class="dropdown-item">
                              <i class="icon-pencil"></i>&nbsp;Actualizar Articulo
                            </button>
                          </div>
                        </div>
                      </div>
                    </template>
                  </v-client-table>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="cerrarModalArticulo()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
              </div>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal agregar articulo-->

      <!--Inicio del modal agregar/actualizar articulos-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_servicio}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg modal-lg-new" role="document">
          <div class="modal-content">
            <div>
              <div class="modal-header">
                <h4 class="modal-title">Busqueda de servicio</h4>
                <button type="button" class="close" @click="cerrarModalServicio()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <v-server-table ref="myTable" url="/catservicio/busqueda" :columns="columnsServicios" :options="optionsServicios" @row-click="seleccionarServicio">
                </v-server-table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="cerrarModalServicio()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
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

export default {
  data(){
    return{
      listaProyectos: [],
      listaAreasSol : [],
      listadoEmpleados : [],
      listUnidadesM:[],
      listaTipoResguardo:[],

      proyecto : '',
      articulo_busqueda : '',
      tabs : 1,

      modal_principal : 0,
      tituloModal : '',
      tipoAccion : '',

      modal_articulo : 0,
      modal_servicio : 0,

      requisicion : {
        id : 0,
        fecha_solicitud : '',
        area_solicita_id : '',
        descripcion_uso : '',
        proyecto : '',
        solicita_empleado_id : '',
        valida_empleado_id : '',
        autoriza_empleado_id : '',
      },

      partida_articulos : {
        nombre_articulo : '',
        articulo_id : '',
        id : 0,
        pda : '',
        cantidad : '',
        fecha_requerido : '',
        tipo_resguardo_id  : 5,
        marca : '',
        unidad : '',
      },

      partida_servicios :  {
        nombre_servicio : '',
        servicio_id : '',
        id : 0,
        pda : '',
        cantidad : '',
        fecha_requerido : '',
        marca : '',
      },

      columns: [ 'requisicion.id','requisicion.partida','requisicion.folio','requisicion.p_nombre_corto',
      'requisicion.fecha_solicitud','requisicion.descripcion_uso','requisicion.nombre_empleado_solicita',
      'requisicion.descargar','requisicion.estado_id','requisicion.responsable'],
      tableData: [],
      options: {
        headings: {
          'requisicion.id': 'Acción',
          'requisicion.partida' : 'Partidas',
          'requisicion.descargar' : 'Descargar',
          'requisicion.folio': 'Folio',
          'requisicion.p_nombre_corto': 'Nombre proyecto',
          'requisicion.fecha_solicitud': 'Fecha solicitud',
          'requisicion.estado_id' :'Estado',
          'requisicion.descripcion_uso' : 'Descripción',
          'requisicion.nombre_empleado_solicita' : 'Solicita',
          'requisicion.responsable' : 'Responsable(s)',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        listColumns: {
          'requisicion.estado_id': [{
            id: 0,
            text: 'Nuevo'
          },
          {
            id: 1,
            text: 'En espera (Validación)'
          },
          {
            id: 2,
            text: 'En espera (Autorización)'
          },
          {
            id: 3,
            text: 'Autorizado(En Almacén)'
          },
          {
            id: 4,
            text: 'No autorizado'
          },
          {
            id: 5,
            text: 'Recibido (Compras)'
          },
          {
            id: 6,
            text: 'Autorizado(En Compras)'
          },
          {
            id: 7,
            text: 'Por Equivalente'
          },
          {
            id: 8,
            text: 'En Espera (Calidad)'
          },
        ]

      },
      texts:config.texts
    },

    tableDataArticulos : [],
    columnsArticulos: ['codigo', 'nombre', 'descripcion', 'unidad', 'marca'],
    optionsArticulos:
    {
      headings:
      {
        nombre: 'Nombre',
        descripcion: 'Descripción',
        marca: 'Marca',
        codigo: 'Codigo',
        id: 'Acciones',
      },
      perPage: 10,
      perPageValues: [],
      skin: config.skin,
      sortIcon: config.sortIcon,
      filterByColumn: true,
      texts: config.texts,
      childRow: true,
    },

    columnsdetallearticulos: ['id','nombre_articulo','cantidad','fecha_requerido'],
    tableDatadetallearticulos: [],
    optionsdetallearticulos:
    {
      headings:
      {
        'id' : 'Acciones',
      },
      perPage: 5,
      perPageValues: [],
      skin: config.skin,
      sortIcon: config.sortIcon,
      filterByColumn: true,
      texts: config.texts
      },

      columnsdetalleservicios: ['id','nombre_servicio','cantidad','fecha_requerido'],
      tableDatadetalleservicios: [],
      optionsdetalleservicios:
      {
        headings:
        {
          'id' : 'Acciones',
        },
        perPage: 5,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts: config.texts
        },

      columnsServicios: ['nombre_servicio', 'proveedor_marca', 'unidad_medida'],
      optionsServicios:
      {
          headings:
          {
              nombre_servicio: 'Nombre del Servicio',
              proveedor_marca: 'Proveedor/Marca2',
              unidad_medida: 'Unidad de Medida',
          },
          perPage: 10,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          filterByColumn: true,
          texts: config.texts
      },

    }
  },
  components:
  {
    'articulo': require('./Articulo.vue')
  },
  methods : {
    //OBTIENE EL O LOS LISTADOS DE DATOS CARGADOS INICIALMENTE
    getData(){

      axios.get('obtener/proyectos').then(response => {
        this.listaProyectos = response.data;
      }).catch(function (error) {
        console.log(error);
      });

      axios.get('/departamento/get/all').then(response => {
        this.listaAreasSol = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });

      axios.get('/vertodosempleados').then(response => {
        for (var i = 0; i < response.data.length; i++) {
          this.listadoEmpleados.push({
            id:response.data[i].id,
            name:response.data[i].nombre+' '+response.data[i].ap_paterno+' '+response.data[i].ap_materno,
          });
        }
      }).catch(function (error) {
        console.log(error);
      });

      axios.get("/almacen/unidadesm/obtener").then(res=>{
        if(res.status){
          this.listUnidadesM=res.data.unidades;
        }else{
          toastr.error("Error al obtener las unidades","Error");
        }
      });

      axios.get('/tipoResguardo').then(response =>{
        this.listaTipoResguardo = response.data;
      });

    },

    setTab(data){
      this.tabs = data;
    },

    /**
    ** OBTIENE LAS REQUISICIONES DEL PROYECTO SELECIONADO
    **/
    getRequisicionProyecto(){
      if (this.proyecto === '') {
        toastr.warning('Seleccione proyecto...!!!');
      }else {
        this.requisicion.proyecto = {id : this.proyecto.id , name: this.proyecto.name};
        axios.get('/requisicion/' + this.proyecto.id).then(response => {
          this.tableData = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
      }
    },

    abrirModal(estado, data = []){
      if (estado == 1) {
        this.modal_principal = 1;
        this.tituloModal = 'Nueva Requisicion';
        this.tipoAccion = 1;
      }
    },

    cerrarModal(){
      this.modal_principal = 0;
    },

    diaActual(){
      var hoy = new Date ();
      var dd = hoy.getDate();
      var mm = hoy.getMonth()+1; //hoy es 0!
      var yyyy = hoy.getFullYear();

      if(dd<10) { dd='0'+dd }
      if(mm<10) { mm='0'+mm }

      hoy = yyyy+'-'+mm+'-'+dd;
      this.requisicion.fecha_solicitud = hoy;
    },

    agregarArt(){
      let me = this;
      me.$refs.articulo.abrirModal('articulo', 'registrar');
    },

    actualizarArt(data){
      let me = this;
      me.$refs.articulo.abrirModal('articulo', 'actualizar', data);
      me.cerrarModalArticulo();
    },

    abrirModalAddArt(estado){
      this.modal_articulo = 1;
    },

    abrirModalAddServ(estado){
      this.modal_servicio = 1;
    },

    cerrarModalArticulo(){
      this.modal_articulo = 0;
    },

    cerrarModalServicio(){
      this.modal_servicio = 0;
    },

    buscarArticulo(){
      axios.post('buscar/concepto/articulo/requisicion',{
        nombre : this.articulo_busqueda,
      }).then(response => {
        this.tableDataArticulos = response.data;
        console.log(response);
      }).catch(e => {
        toastr.error('HA OCURRIDO UN ERROR EN LA BUSQUEDA, REVISE EL CAMPO ESCRITO O CONTACTE AL ADMINISTRADOR');
        console.error(e);
      });
    },

    seleccionarArticulo(data){
      this.partida_articulos.articulo_id = data.row.id;
      this.partida_articulos.nombre_articulo = data.row.descripcion;
      let me = this;
      me.cerrarModalArticulo();
    },

    seleccionarServicio(data){
      this.partida_servicios.articulo_id = data.row.id;
      this.partida_servicios.nombre_servicio = data.row.nombre_servicio;
      let me = this;
      me.cerrarModalServicio();
    },

    abrirBusArticulo(){
      this.buscarArticulo();
    },

    AgregarArtTable(){
      if (this.partida_articulos.nombre_articulo === '') {
        toastr.warning('Seleccionar un articulo !!!');
      }else if (this.partida_articulos.cantidad === '') {
        toastr.warning('Defina una cantidad !!!');
      }else if (this.partida_articulos.marca === '') {
        toastr.warning('Defina una marca !!!');
      }else if (this.partida_articulos.unidad === '') {
        toastr.warning('Defina una unidad de medida !!!');
      }else if (this.partida_articulos.fecha_requerido === '') {
        toastr.warning('Defina una fecha !!!');
      }else {
        this.tableDatadetallearticulos.push({
          id : this.tableDatadetallearticulos.length + 1,
          pda : this.partida_articulos.pda,
          nombre_articulo : this.partida_articulos.nombre_articulo,
          cantidad : this.partida_articulos.cantidad,
          fecha_requerido : this.partida_articulos.fecha_requerido,
          marca : this.partida_articulos.marca,
          unidad : this.partida_articulos.unidad,
          tipo_resguardo_id : this.partida_articulos.tipo_resguardo_id,
        });
        this.vaciarArt();
      }
    },

    AgregarServTable(){
      if (this.partida_servicios.nombre_servicio === '') {
        toastr.warning('Seleccionar un articulo !!!');
      }else if (this.partida_servicios.cantidad === '') {
        toastr.warning('Defina una cantidad !!!');
      }else if (this.partida_servicios.fecha_requerido === '') {
        toastr.warning('Defina una fecha !!!');
      }else {
        this.tableDatadetalleservicios.push({
          id : this.tableDatadetalleservicios.length + 1,
          pda : this.partida_servicios.pda,
          nombre_servicio : this.partida_servicios.nombre_servicio,
          cantidad : this.partida_servicios.cantidad,
          fecha_requerido : this.partida_servicios.fecha_requerido,
          marca : this.partida_servicios.marca,
        });
        this.vaciarServ();
      }
    },

    vaciarArt(){
      this.partida_articulos.nombre_articulo = "";
      this.partida_articulos.id = 0;
      this.partida_articulos.pda = '';
      this.partida_articulos.cantidad = "";
      this.partida_articulos.fecha_requerido = "";
      this.partida_articulos.marca = "";
      this.partida_articulos.unidad = "";
      this.partida_articulos.tipo_resguardo_id = "";
    },

    vaciarServ(){
      this.partida_servicios.nombre_servicio = "";
      this.partida_servicios.id = 0;
      this.partida_servicios.pda = '';
      this.partida_servicios.cantidad = "";
      this.partida_servicios.fecha_requerido = "";
      this.partida_servicios.marca = "";
    },

    deleteArticuloTable(data){
      swal({
        title: 'Esta seguro de eliminar',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar!',
        cancelButtonText: 'Cancelar',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false,
        reverseButtons: true
      }).then(result => {
        if (data.pda === '') {
          if (result.value) {
            const index = this.tableDatadetallearticulos.findIndex(element =>  (element.id == data.id && element.pda == data.pda ))
            this.tableDatadetallearticulos.splice(index, 1);
            toastr.warning('Eliminado Correctamente !!!');
          }
        }else {
          toastr.warning('Eliminar');
        }

      });
    },

    deleteServicioTable(data){
      swal({
        title: 'Esta seguro de eliminar',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar!',
        cancelButtonText: 'Cancelar',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false,
        reverseButtons: true
      }).then(result => {
        if (data.pda === '') {
          if (result.value) {
            const index = this.tableDatadetalleservicios.findIndex(element =>  (element.id == data.id && element.pda == data.pda ))
            this.tableDatadetalleservicios.splice(index, 1);
            toastr.warning('Eliminado Correctamente !!!');
          }
        }else {
          toastr.warning('Eliminar');
        }

      });
    },

    guardarRequisicion(tipo){
      this.$validator.validate().then(result =>{
        if (result) {
          axios.post('guardar/nueva/requisicion/',{
            requisicion : this.requisicion,
            articulos : this.tableDatadetallearticulos,
            servicios : this.tableDatadetalleservicios,
            tipo : tipo
          }).then(response => {
            console.log(response);
          }).catch(e => {
            console.error(e);
          });
        }else {
          toastr.warning('Complete todos los campos');
        }
      });
    },

  },
  mounted(){
    this.getData();
    this.diaActual();
  }
}


</script>
<style>

.btn-uno {
  color: rgb(255, 255, 255);
  background-color: #f6a152;
  border-color: #f6a152;
  border-radius: 2px;
}
.btn-dos {
  color: rgb(0, 0, 0);
  background-color: #e9b443;
  border-color: #e9b443;
  border-radius: 2px;
}
/* no */
.btn-tres {
  color: rgb(0, 0, 0);
  background-color: #8bbbef;
  border-color: #8bbbef;
  border-radius: 2px;
}

.btn-cuatro {
  color: rgb(255, 255, 255);
  background-color: #40d0d0;
  border-color: #40d0d0;
  border-radius: 2px;
}
.btn-cinco {
  color: rgb(255, 255, 255);
  background-color: #0d4da3;
  border-color: #0d4da3;
  border-radius: 2px;
}
/* no */
.btn-seis {
  color: rgb(255, 255, 255);
  background-color: #0d98a3;
  border-color: #0d98a3;
  border-radius: 2px;
}
.btn-siete {
  color: rgb(255, 255, 255);
  background-color: #25a30d;
  border-color: #25a30d;
  border-radius: 2px;
}
.btn-ocho {
  color: rgb(255, 255, 255);
  background-color: #d04040;
  border-color: #d04040;
  border-radius: 2px;
}
.btn-nueve {
  color: rgb(0, 0, 0);
  background-color: #40d088;
  border-color: #40d088;
  border-radius: 2px;
}

.modal-lg-new {
  max-width: 1000px;
}

.VueTables__child-row-toggler--closed::before {
  content: "+";
}

.VueTables__child-row-toggler--open::before {
  content: "-";
}

</style>

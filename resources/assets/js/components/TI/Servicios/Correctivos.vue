<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->

      <br>
      <div class="card" v-show="!detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Requisiciones
          <button type="button" @click="abrirModal('requisicion','registrar')" class="btn btn-primary float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="empleado.id" slot-scope="props">
              <template v-if="props.row.empleado.condicion == 1">
                <button type="button" class="btn btn-info btn-sm" @click="descargar(props.row.empleado)">
                  <i class="fas fa-file-pdf"></i>
                </button>
              </template>
              <template v-if="props.row.empleado.condicion == 0">
              </template>

              <button type="button" @click="cargardetalle(props.row.empleado)" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i>
              </button>

              <button type="button" @click="abrirModal('requisicion','actualizar',props.row.empleado)" class="btn btn-warning btn-sm">
                <i class="icon-pencil"></i>
              </button>
              <template v-if="props.row.empleado.condicion">
                <button type="button" class="btn btn-danger btn-sm" @click="actdesact(props.row.empleado.id, 0)">
                  <i class="icon-trash"></i>
                </button>
              </template>
              <template v-else>
                <button type="button" class="btn btn-info btn-sm" @click="actdesact(props.row.empleado.id, 1)">
                  <i class="icon-check"></i>
                </button>
              </template>
            </template>
            <template slot="empleado.condicion" slot-scope="props" >
              <template v-if="props.row.empleado.condicion == 1">
                <span class="badge badge-success">Activo</span>
              </template>
              <template v-if="props.row.empleado.condicion == 0">
                <span class="badge badge-danger">Dado de Baja</span>
              </template>
            </template>
          </v-client-table>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
      <!-- Listado de requisiocnes -->
      <br>
      <div class="card" v-show="detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Detalles de requisicion pertenecientes al proyecto:  {{ empleado == null ? '': empleado.nombrep +' con folio '+ empleado.folio }}
          <button type="button" @click="maestro()" class="btn btn-primary float-sm-right">
            <i class="fas fa-backward"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoadingDetalle"/>
          <v-client-table :columns="columnsdetallerequisicion" :data="tableDatadetallerequisicion" :options="optionsdetallerequisicion" ref="myTabledescuento">
            <template slot="req.articulo_id" slot-scope="props">
              <button type="button" class="btn btn-danger btn-sm" @click="actdesactreq(props.row.req.articulo_id, preq.requisicione_id,0)">
                <i class="icon-trash"></i>
              </button>
              <button type="button" class="btn btn-success btn-sm" @click="abrirModalA('articulo','actualizar',props.row.req.articulo_id,props.row.req)">
                <i class="far fa-file"></i>
              </button>

            </template>
          </v-client-table>
        </div>
        <!-- Nuevo y editar partidas requisiciones -->
        <div class="card" ref="formLote">
          <vue-element-loading :active="isLoadingg"/>
          <div class="card-header">
          </div>
          <div class="card-body">
            <form>
              <input type="text" class="form-control" id="requisicione_id" name="requisicione_id" v-model="preq.requisicione_id" placeholder="Articulo" hidden>
              <input type="text" class="form-control" id="articulo_id" name="articulo_id" v-model="preq.articulo_id" placeholder="Articulo" hidden>
              <input type="text" class="form-control" id="documentacionreid" name="documentacionreid" v-model="preq.documentacionreid" placeholder="Articulo" hidden>

              <div class="form-group row">
                <label for="inputArticulo" class="col-md-3 form-control-label">Articulo</label>
                <div class="col-sm-9">
                  <div class="input-group mb-3">
                    <input type="text" class="form-control"  v-model="preq.nombrearti" placeholder="Articulo" readonly>
                    <div class="input-group-append">
                      <button class="btn btn-secondary" type="button" @click="abrirModalA('articulo','registrar',preq.requisicione_id)">Buscar</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="folio">Peso</label>
                <div class="col-sm-9">
                  <input type="number" name="peso"  v-model="preq.peso" v-bind:class="'form-control'+clases.peso" autocomplete="off" id="peso">
                  <span class="text-danger">{{ errors.first('peso') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 form-control-label" for="equivalente">Equivalente</label>
                <div class="col-md-9">
                  <input type="number" name="equivalente"  v-model="preq.equivalente" v-bind:class="'form-control'+clases.equivale" autocomplete="off" id="equivalente">
                  <span class="text-danger">{{ errors.first('equivalente') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="fecha_requerido">Fecha Requerida</label>
                <div class="col-sm-9">
                  <input type="date" name="fecha_requerido"  v-model="preq.fecha_requerido" v-bind:class="'form-control'+clases.fecharequerido" autocomplete="off" id="fecha_requerido">
                  <span class="text-danger">{{ errors.first('fecha_requerido') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputArticulo" class="col-md-3 form-control-label">Documentos Requeridos</label>
                <div class="col-sm-9">
                  <div class="input-group mb-3">
                    <input type="text" class="form-control"  v-model="preq.documentacionre" placeholder="Documentos Requeridos" readonly>
                    <div class="input-group-append">
                      <button class="btn btn-secondary" type="button" @click="abrirModalA('articulo','documentos',preq.requisicione_id)">Buscar</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <button type="button" class="btn btn-secondary" @click="cancelar()">Cancelar</button>
            <button type="button" v-if="tipoAccionpr==1" class="btn btn-primary" @click="validarpartida(); guardarPR(1, preq.requisicione_id);">Guardar</button>
            <button type="button" v-if="tipoAccionpr==2" class="btn btn-primary" @click="guardarPR(0, preq.requisicione_id)">Actualizar</button>
          </div>
        </div>
        <!-- Fin  de Nuevo y editar detalle requisicion correspondiente ala tabla partidas_requisiciones -->
        <!--Inicio del modal agregar/actualizar articulos-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modala}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
          <div class="modal-dialog modal-primary modal-lg" role="document">
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
                  <input type="hidden" name="id">
                  <div class="modal-body">
                    <v-server-table ref="myTable" url="/articulo/busqueda" :columns="columnsa" :options="optionsa" @row-click="seleccionarArticulo2">
                    </v-server-table>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                </div>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal agregar articulo-->
        <!--Inicio del modal agregar/actualizar documento-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modald}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
          <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
              <div>
                <vue-element-loading :active="isLoadinggd"/>
                <div class="modal-header">
                  <h4 class="modal-title" v-text="tituloModala"></h4>
                  <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="id">
                  <div class="modal-body">
                    <!-- <v-server-table ref="myTable" url="/articulo/busqueda" :columns="columnsd" :options="optionsd" @row-click="seleccionarArticulo3">
                    </v-server-table> -->

                    <div class="form-group row">
                        <div class="col-md-4" v-for="doc in documentos">
                            <div class="form-check checkbox" >
                                <input class="" :value="doc.id" :id="doc.id" type="checkbox" v-model="doc_req" >
                                <label class="form-check-label" for="doc.id">
                                {{doc.nombre}}
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- </form> -->

                  </div>
                </div>
                <div class="modal-footer">
                  <!-- <button type="button" class="btn btn-primary" @click="guardardocumentos()">Ok</button> -->
                    <button type="button" v-show="tipoAcciondos==1" class="btn btn-primary" @click="guardardocumentos()">Guardar</button>
                    <button type="button" v-show="tipoAcciondos==0" class="btn btn-primary" @click="guardardocumentosupdate()">Actualizar</button>

                  <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                </div>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal agregar documentos-->
      </div>
      <!-- Fin Listado de escolaridades del empleado -->
    </div>
    <!--Inicio del modal agregar/actualizar requisicion-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-primary modal-lg" role="document">
        <div class="modal-content">
          <div>
            <vue-element-loading :active="isLoading"/>
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
                <label class="col-md-3 form-control-label" for="folio">Folio</label>
                <div class="col-md-9">
                  <input type="text" name="folio" v-validate="'required'" v-model="requisicion.folio" data-vv-as="folio" class="form-control" placeholder="Folio" autocomplete="off" id="folio">
                  <span class="text-danger">{{ errors.first('folio') }}</span>
                  <!-- <span v-show="errors.has('folio')" class="text-danger">{{ errors.first('folio') }}</span> -->
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="area_solicita_id">Área Solicitante</label>
                <div class="col-md-9">
                  <select class="form-control" v-validate="'required'" id="area_solicita_id" name="area_solicita_id" v-model="requisicion.area_solicita_id" data-vv-as="área solicitante">
                    <option v-for="item in listaAreasSol" :value="item.id" :key="item.id">{{item.nombre}} </option>
                  </select>
                  <span class="text-danger">{{ errors.first('area_solicita_id') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="formato_requisiciones	">Formato Requisiciones</label>
                <div class="col-md-9">
                  <input type="text" name="formato_requisiciones" v-validate="'required'" v-model="requisicion.formato_requisiciones" class="form-control" placeholder="Formato" autocomplete="off" data-vv-as="formato" id="formato_requisiciones">
                  <span class="text-danger">{{ errors.first('formato_requisiciones') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="fecha_solicitud">Fecha Solicitud</label>
                <div class="col-md-9">
                  <input type="date" name="fecha_solicitud" v-validate="'required'" v-model="requisicion.fecha_solicitud" class="form-control" placeholder="Fecha" autocomplete="off" data-vv-as="fecha solicutud" id="fecha_solicitud">
                  <span class="text-danger">{{ errors.first('fecha_solicitud') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="descripcion_uso">Descripcion de uso</label>
                <div class="col-md-9">
                  <input type="text" name="descripcion_uso" v-validate="'required'" v-model="requisicion.descripcion_uso" class="form-control" placeholder="Uso" autocomplete="off" id="descripción uso">
                  <span class="text-danger">{{ errors.first('descripcion_uso') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="tipo_compra_id">Tipo de Compra</label>
                <div class="col-md-9">
                  <select class="form-control" id="tipo_compra_id" v-validate="'required'" name="tipo_compra_id" v-model="requisicion.tipo_compra_id" data-vv-as="tipo de compra">
                    <option v-for="item in listaTipoCompra" :value="item.id" :key="item.id">{{item.nombre}} </option>
                  </select>
                  <span class="text-danger">{{ errors.first('tipo_compra_id') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="proyecto_id">Proyecto</label>
                <div class="col-md-9">
                  <select class="form-control" id="proyecto_id" v-validate="'required'" name="proyecto_id" v-model="requisicion.proyecto_id" data-vv-as="proyecto">
                    <option v-for="item in listaProyecto" :value="item.proyecto.id" :key="item.proyecto.id">{{item.proyecto.nombre}} </option>
                  </select>
                  <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="estado_id">Estado</label>
                <div class="col-md-9">
                  <select class="form-control" id="estado_id" v-validate="'required'" name="estado_id" v-model="requisicion.estado_id" data-vv-as="estado">
                    <option v-for="item in listaEstadoR" :value="item.id" :key="item.id">{{item.nombre}} </option>
                  </select>
                  <span class="text-danger">{{ errors.first('estado_id') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="solicita_empleado_id">Empleado que solicita</label>
                <div class="col-md-9">
                  <select class="form-control" id="solicita_empleado_id" v-validate="'required'" name="solicita_empleado_id" v-model="requisicion.solicita_empleado_id" data-vv-as="empleado que solicita">
                    <option v-for="item in listaEmpleados" :value="item.empleado.id" :key="item.empleado.id">{{item.empleado.nombre}} {{item.empleado.ap_paterno}} {{item.empleado.ap_materno}} </option>
                  </select>
                  <span class="text-danger">{{ errors.first('solicita_empleado_id') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="autoriza_empleado_id">Empleado que autoriza</label>
                <div class="col-md-9">
                  <select class="form-control" id="autoriza_empleado_id" v-validate="'required'" name="autoriza_empleado_id" v-model="requisicion.autoriza_empleado_id" data-vv-as="empleado que autoriza">
                    <option v-for="item in listaEmpleados" :value="item.empleado.id" :key="item.empleado.id">{{item.empleado.nombre}} {{item.empleado.ap_paterno}} {{item.empleado.ap_materno}}</option>
                  </select>
                  <span class="text-danger">{{ errors.first('autoriza_empleado_id') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="valida_empleado_id">Validado por</label>
                <div class="col-md-9">
                  <select class="form-control" id="valida_empleado_id" v-validate="'required'" name="valida_empleado_id" v-model="requisicion.valida_empleado_id" data-vv-as="validado por">
                    <option v-for="item in listaEmpleados" :value="item.empleado.id" :key="item.empleado.id">{{item.empleado.nombre}} {{item.empleado.ap_paterno}} {{item.empleado.ap_materno}}</option>
                  </select>
                  <span class="text-danger">{{ errors.first('valida_empleado_id') }}</span>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="recibe_empleado_id">Recibido por</label>
                <div class="col-md-9">
                  <select class="form-control" id="recibe_empleado_id" v-validate="'required'" name="recibe_empleado_id" v-model="requisicion.recibe_empleado_id" data-vv-as="recibido por">
                    <option v-for="item in listaEmpleados" :value="item.empleado.id" :key="item.empleado.id">{{item.empleado.nombre}} {{item.empleado.ap_paterno}} {{item.empleado.ap_materno}} </option>
                  </select>
                  <span class="text-danger">{{ errors.first('recibe_empleado_id') }}</span>
                </div>
              </div>
              <!-- </form> -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="guardarRequisicion(1)">Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="guardarRequisicion(0)">Actualizar</button>
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
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
export default {
  data (){
    return {
      url: '/requisicion',
      empleado: null,
      documentos : null,
      detalle: false,
      doc_req : [],
      requisicion: {
        id :0,
        folio	 :'',
        area_solicita_id : '',
        formato_requisiciones : '',
        fecha_solicitud: '',
        descripcion_uso	 : '',
        tipo_compra_id: '',
        proyecto_id: '',
        estado_id : '',
        solicita_empleado_id : '',
        autoriza_empleado_id : '',
        valida_empleado_id : '',
        recibe_empleado_id : '',
        condicion :0,
        Area : '',
      },
      preq : {
        requisicione_id :0,
        articulo_id : 0,
        peso : '',
        equivalente : '',
        fecha_requerido : '',
        nombrearti : '',
        validado : 0,
        documentacionre :'',
        documentacionreid :'',

      },
      clases : {
        peso : '',
        equivale : '',
        fecharequerido : '',
      },
      listaEstadoR : [],
      listaTipoNomina: [],
      listaEmpleados: [],
      listadescuento: [],
      listaAreasSol : [],
      listaTipoDescuento: [],
      listaHorarios: [],
      listaProyecto : [],
      listaTipoCompra : [],
      listaProyectos: [],
      modal : 0,
      modala : 0,
      modald : 0,
      tituloModal : '',
      tituloModala : '',
      tipoAccion : 0,
      tipoAccionpr : 0,
      tipoAcciondos : 1,
      disabled: 0,
      isLoading: false,
      isLoadingg: false,
      isLoadinggd : false,
      isLoadingDetalle: false,
      columnsa:
      ['codigo', 'nombre', 'descripcion', 'marca'],
      columns: [
        'empleado.id',
        'empleado.folio',
        'empleado.nombrep',
        'empleado.nombretc',
        'empleado.fecha_solicitud',
        'empleado.condicion',
      ],
      tableData: [],
      columnsdetallerequisicion: [
        'req.articulo_id',
        'req.narticulo',
        'req.carticulo',
        'req.darticulo',
        'req.peso',
        'req.equivale',
        'req.frequerido',
      ],
      tableDatadetallerequisicion: [],
      optionsa: {
        headings: {
          nombre: 'Nombre',
          descripcion: 'Descripción',
          marca: 'Marca',
          codigo: 'Codigo',
          id: 'Acción',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },
      options: {
        headings: {
          'empleado.id': 'Acción',
          'empleado.folio': 'Folio',
          'empleado.nombrep': 'Nombre proyecto',
          'empleado.nombretc': 'Tipo de compra',
          'empleado.fecha_solicitud': 'Fecha solicitud',
          'empleado.condicion': 'Condición',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['empleado.folio'],
        filterable: ['empleado.folio'],
        filterByColumn: true,
        texts:config.texts
      },
      optionsdetallerequisicion: {
        headings: {
          'req.articulo_id': 'Acción',
          'req.narticulo': 'Nombre artículo',
          'req.carticulo': 'Código',
          'req.darticulo': 'Descripción',
          'req.peso': 'Peso',
          'req.equivale': 'Equivalente',
          'req.frequerido': 'Fecha requerido',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['id','folio','narticulo','carticulo','darticulo','peso','equivale'],
        filterable: ['folio','narticulo','carticulo','darticulo','peso','equivale'],
        filterByColumn: true,
        listColumns: {
          condicion: [{
            id: 1,
            text: 'Activo'
          },
          {
            id: 0,
            text: 'Dado de Baja'
          }
        ]
      },
      texts:config.texts
    },
  }
},
computed:{
},
methods : {

  validarpartida(){
    if (this.preq.peso === '') {
      this.mensaje();
      this.clases.peso = ' is-invalid';
    }
    else if (this.preq.equivalente === '') {
      this.mensaje();
      this.clases.peso = '';
      this.clases.equivale = ' is-invalid';
    }
    else if (this.preq.fecha_requerido === '') {
      this.mensaje();
      this.clases.equivale = '';
      this.clases.fecharequerido = ' is-invalid';
    }
    else {
      this.preq.validado = 1;
      this.clases.fecharequerido = '';
    }
  },
  mensaje(){
    swal({
      title: 'Error complete todos los campos',
      type: 'warning',
      confirmButtonColor: '#3085d6',
      confirmButtonText: 'Aceptar!',
      confirmButtonClass: 'btn btn-success',
      buttonsStyling: false,
      reverseButtons: true
    })
  },
  getListas() {
    let me=this;
    axios.get('/areasolicitante').then(response => {
      me.listaAreasSol = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });
    axios.get('/tipocompra').then(response => {
      me.listaTipoCompra = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });
    axios.get('/proyecto').then(response => {
      me.listaProyecto = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });
    axios.get('/estadorequisiones').then(response => {
      me.listaEstadoR = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });
    axios.get('/empleado').then(response => {
      me.listaEmpleados= response.data;
    })
    .catch(function (error) {
      console.log(error);
    });
    axios.get('ducumentosrequeridos').then(response =>{
      me.documentos = response.data;
    })
    .catch(function (error){
      console.error();
    });
  },
  getData() {
    let me=this;
    axios.get('/requisicion/ver').then(response => {
      me.tableData = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });
  },
  guardardocumentos(){
    var data = this.doc_req;
    var cadena = [];
    var cadenauno = [];

    for (var i = 0; i < data.length; i++) {
      axios.get('ducumentosrequeridosb/'+data[i]).then(response =>{
         cadena.push(response.data[0].nombre);
         cadenauno.push(response.data[0].id);
     }).catch(function (error){
       console.error(error);
     })
    }
    this.preq.documentacionre = cadena;
    this.preq.documentacionreid = cadenauno;
    this.modald = 0;
  },
  actdesact(id,activar){
    if(activar){
      this.swal_titulo = 'Esta seguro de activar esta requisición?';
      this.swal_tle = 'Activado';
      this.swal_msg = 'El registro ha sido activado con éxito.';
    }else{
      this.swal_titulo = 'Esta seguro de desactivar esta requisición?';
      this.swal_tle = 'Desactivado!';
      this.swal_msg = 'El registro ha sido desactivado con éxito.';
    }
    swal({
      title: this.swal_titulo,
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
    }).then((result) => {
      if (result.value) {
        let me = this;
        axios.get(me.url+'/'+id+'/edit').then(function (response) {
          if(activar){
          toastr.success(me.swal_tle,me.swal_msg,'success');

          }else{
          toastr.error(me.swal_tle,me.swal_msg,'error');

          }
          me.getData();
        }).catch(function (error) {
          console.log(error);
        });
      } else if (
        result.dismiss === swal.DismissReason.cancel
      ) {

      }
    })
  },
  actdesactreq(id, prid,activar){
    if(activar){
      this.swal_titulo = 'Esta seguro de activar este articulo de la requisicion?';
      this.swal_tle = 'Activado';
      this.swal_msg = 'El registro ha sido activado con éxito.';
    }else{
      this.swal_titulo = 'Esta seguro de remover este articulo de la requisición?';
      this.swal_tle = 'Removido!';
      this.swal_msg = 'El registro ha sido removido con éxito.';
    }
    swal({
      title: this.swal_titulo,
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
    }).then((result) => {
      if (result.value) {
        let me = this;
        axios.get('/partidare'+'/'+id+'&'+prid+'/edit').then(function (response) {
          console.log(response);
          if(activar){
          toastr.success(me.swal_tle,me.swal_msg,'success');

          }else{
          toastr.error(me.swal_tle,me.swal_msg,'error');

          }
          me.cargardetalle(me.empleado);
        }).catch(function (error) {
          console.log(error);
        });
      } else if (
        result.dismiss === swal.DismissReason.cancel
      ) {

      }
    })
  },
  guardardocumentosupdate(){
      console.log(this.doc_req);
      var array = [] ;
      console.log(this.preq.articulo_id);
      console.log(this.preq.requisicione_id);
      var id =this.preq.requisicione_id;
      let me = this;
      axios.put('/partidare/'+this.preq.articulo_id+'/updatedoc',{
        'documento_id': this.doc_req,
        'partidarequisicione_art': this.preq.articulo_id,
        'partidarequisicione_req': this.preq.requisicione_id,

      }).then(function (response) {
        console.log(response);
        me.cerrarModal();
        me.cargardetalle(me.empleado);
      }).catch(function (error) {
        console.log(error);
      });
  },
  guardarPR(nuevo, idr){
    if (this.preq.validado == 1) {
      this.isLoading =false;
      this.detalle = true;
      this.isLoadingg = true;

      let me = this;
      axios({
        method: nuevo ? 'POST' : 'PUT',
        url: nuevo ? '/partidare' : '/partidare'+'/'+this.preq.articulo_id,
        data: {
          'requisicione_id': this.preq.requisicione_id,
          'articulo_id': this.preq.articulo_id,
          'peso': this.preq.peso,
          'equivalente': this.preq.equivalente,
          'fecha_requerido': this.preq.fecha_requerido,
          'documentacionreid' : this.preq.documentacionreid,

        }
      }).then(function (response) {
        me.isLoading = false;
        me.isLoadingg = false;
        if (response.data.status) {
          me.cargardetalle(me.empleado);
          me.cerrarModal();
          me.cancelar();
          me.getData();
          if(!nuevo){
          toastr.success('Partida Actualizada Correctamente');
          }
          else
          {
          toastr.success('Partida Registrada Correctamente');

          }
        } else {
        }
      }).catch(function (error) {
        console.log(error);
        swal({
          title: 'Error no se puede seleccionar \n 2 articulos iguales \n en la misma requisición',
          type: 'warning',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Aceptar!',
          confirmButtonClass: 'btn btn-success',
          buttonsStyling: false,
          reverseButtons: true
        })
        me.cancelar();
        me.isLoading = false;
        me.isLoadingg = false;
      });
    }
  },
  guardarRequisicion(nuevo){
    this.$validator.validate().then(result => {
      if (result) {
        this.isLoading = true;
        this.detalle = false;
        this.isLoadingg = false;
        let me = this;
        axios({
          method: nuevo ? 'POST' : 'PUT',
          url: nuevo ? me.url : me.url+'/'+this.requisicion.empleado_id,
          data: {
            'id': this.requisicion.id,
            'folio': this.requisicion.folio,
            'area_solicita_id': this.requisicion.area_solicita_id,
            'formato_requisiciones': this.requisicion.formato_requisiciones,
            'fecha_solicitud': this.requisicion.fecha_solicitud,
            'descripcion_uso': this.requisicion.descripcion_uso,
            'tipo_compra_id': this.requisicion.tipo_compra_id,
            'proyecto_id': this.requisicion.proyecto_id,
            'estado_id': this.requisicion.estado_id,
            'solicita_empleado_id': this.requisicion.solicita_empleado_id,
            'autoriza_empleado_id': this.requisicion.autoriza_empleado_id,
            'valida_empleado_id': this.requisicion.valida_empleado_id,
            'recibe_empleado_id': this.requisicion.recibe_empleado_id,
          }
        }).then(function (response) {
          me.isLoading = false;
          if (response.data.status) {
            me.cerrarModal();
            me.getData();
            if(!nuevo){
          toastr.success('Requisicion Actualizada Correctamente');
          }
          else
          {
          toastr.success('Requisicion Registrada Correctamente');

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
      else {
        swal({
          title: 'Complete todos los campos',
          type: 'warning',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Aceptar!',
          confirmButtonClass: 'btn btn-success',
          buttonsStyling: false,
          reverseButtons: true
        })
      }
    });
  },
  cerrarModal(){
    this.modal=0;
    this.modala=0;
    this.modald = 0;
    this.tituloModal='';
    Utilerias.resetObject(this.requisicion);
    //Utilerias.resetObject(this.doc_req);
    // this.preq.documentacionre ="";
    // this.doc_req = [];
  },
  abrirModalA(modelo, accion, id ,data = []){
    switch(modelo){
      case "articulo":
      {
        switch(accion){
          case 'registrar':
          {
            this.modala = 1;
            this.tituloModala = 'Registrar partida requisición';
            Utilerias.resetObject(this.preq);
            this.tipoAccionpr = 1;
            this.preq.requisicione_id = id;
            break;
          }
          case 'documentos':
          {
            this.modald = 1;
            this.tituloModala = 'Registrar los documentos requeridos';
          //  Utilerias.resetObject(this.preq);
            //this.tipoAccionpr = 1;
            //this.preq.requisicione_id = id;
            break;
          }
          case 'actualizar':
          {
            this.modald = 1;
            this.tipoAcciondos =0;
            this.tituloModala = 'Actualizar partida';
            var ida = data['articulo_id'];
            this.preq.articulo_id = ida;
            var idr = data['requisicione_id'];
            this.preq.requisicione_id = idr;
            var cadenadoc = [];

            axios.get('/partidadocumentos/'+ida+'&'+idr).then(function (response) {
              console.log(response.data.length);
              for (var i = 0; i < response.data.length; i++) {
                  cadenadoc.push(response.data[i].documento_id);
              }
            }).catch(function (error) {
              console.log(error);
            });
            this.doc_req = cadenadoc;

            break;
          }
        }
      }
    }
  },
  abrirEdicionR(data = []){
    this.tipoAccionpr=2;
    this.preq.articulo_id = data['articulo_id'];
    this.preq.peso = data['peso'];
    this.preq.equivalente = data['equivale'];
    this.preq.fecha_requerido = data['frequerido'];
  },
  abrirModal(modelo, accion, data = []){
    switch(modelo){
      case "requisicion":
      {
        switch(accion){
          case 'registrar':
          {
            this.modal= 1;
            this.tituloModal = 'Registrar requisición';
            Utilerias.resetObject(this.requisicion);
            this.tipoAccion = 1;
            this.disabled=0;
            break;
          }
          case 'actualizar':
          {
            Utilerias.resetObject(this.requisicion);
            this.modal=1;
            this.tituloModal='Actualizar requisición';
            this.requisicion.id=data['id'];
            this.tipoAccion=2;
            this.disabled=1;
            this.requisicion.folio = data['folio'];
            this.requisicion.area_solicita_id = data['area_solicita_id'];
            this.requisicion.formato_requisiciones = data['formato_requisiciones'];
            this.requisicion.fecha_solicitud = data['fecha_solicitud'];
            this.requisicion.descripcion_uso = data['descripcion_uso'];
            this.requisicion.tipo_compra_id = data['tipo_compra_id'];
            this.requisicion.proyecto_id = data['proyecto_id'];
            this.requisicion.estado_id = data['estado_id'];
            this.requisicion.solicita_empleado_id = data['solicita_empleado_id'];
            this.requisicion.autoriza_empleado_id = data['autoriza_empleado_id'];
            this.requisicion.valida_empleado_id = data['valida_empleado_id'];
            this.requisicion.recibe_empleado_id	 = data['recibe_empleado_id'];
            break;
          }
        }
      }
    }
  },
  cargardetalle(dataEmpleado = []) {
    this.detalle = true;
    this.isLoadingDetalle = true;
    this.isLoading = true;
    let me=this;
    this.empleado = dataEmpleado;
    this.preq.requisicione_id = dataEmpleado.id;
    axios.get(me.url + '/' + dataEmpleado.id + '/' +'requisicion').then(response => {
      me.tableDatadetallerequisicion = response.data;
      me.isLoadingDetalle = false;
    })
    .catch(function (error) {
      console.log(error);
      me.isLoadingDetalle = false;
    });
  },
  maestro(){
    this.detalle = false;
    this.isLoading = false;
  },
  cancelar(){
    this.preq.articulo_id = 0;
    this.preq.peso = '';
    this.preq.equivalente = '';
    this.preq.fecha_requerido = "";
    this.preq.nombrearti = "";
    this.tipoAccionpr =0;
    this.preq.documentacionre ="";
    this.doc_req = [];
  },
  abrirBusquedaArticulo() {
    this.tipoAccionpr = 1;
    this.modal = 1;
  },

  seleccionarArticulo2(data)
  {
    let me = this;
    console.log(data.row);
    this.preq.articulo_id = data.row.id;
    this.preq.nombrearti = data.row.descripcion;
    me.cerrarModal();
  },
  descargar(data) {
    window.open('descargar-requisicion/' + data.id, '_blank');
  }
},
mounted() {
  this.getData();
  this.getListas();
}
}
</script>

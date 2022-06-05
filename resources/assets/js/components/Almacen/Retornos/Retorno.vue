<template>
    <main class="main">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Retorno de material
                </div>
                <div class="card-body">
                    <vue-element-loading :active="isLoading"/>
                    <div class="row">
                        <div class="col-2">
                            <label class="form-control-label" for="proyecto_id">Proyecto:</label>
                        </div>
                        <div class="col-4">
                            <v-select :options="listaProyectos"  v-validate="'required'" v-model="proyecto" 
                            label="nombre_corto" name="proyecto" data-vv-name="proyecto" >
                            </v-select>
                            <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-primary" @click="loadSalidas()">Buscar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Salidas
                        </div>
                        <div class="card-body">
                            <vue-element-loading :active="isLoadingSalidas"/>
                            <v-client-table :columns="columns" :data="salidas" :options="options" ref="myTableSalidas">
                                <template slot="id" slot-scope="props" >
                                    <button type="button" class="btn btn-success" @click="retornoArticulo(props.row)">Retorno</button>
                                </template>
                            </v-client-table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    <!--Inicio del modal agregar/actualizar articulos-->
          <div class="modal fade" tabindex="-1" :class="{'mostrar' : modala}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dark modal-lg" role="document">
              <div class="modal-content">
                <div>

                  <vue-element-loading :active="isLoadinga"/>
                  <div class="modal-header">
                    <h4 class="modal-title">Retorno {{ articulo.nombre }}</h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="caducidad">Caducidad</label>
                        <div class="col-md-9">
                            <input type="date" name="caducidad" v-validate="''" v-model="retorno.caducidad" class="form-control" placeholder="Caducidad" autocomplete="off" id="caducidad">
                            <span class="text-danger">{{ errors.first('caducidad') }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="cantidad">Cantidad</label>
                        <div class="col-md-9">
                            <input type="number" name="cantidad" v-validate="'required'" v-model="retorno.cantidad" class="form-control" placeholder="Cantidad" autocomplete="off" id="cantidad">
                            <span class="text-danger">{{ errors.first('cantidad') }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="precio">Precio</label>
                        <div class="col-md-9">
                            <input type="number" step="0.01" lang="en" name="precio" v-validate="'required'" v-model="retorno.precio" class="form-control" placeholder="Precio" autocomplete="off" id="precio">
                            <span class="text-danger">{{ errors.first('precio') }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="adicionales">Adicionales</label>
                        <div class="col-md-9">
                            <input type="number" step="0.01" lang="en" name="adicionales" v-validate="'required'" v-model="retorno.adicionales" class="form-control" placeholder="Adicionales" autocomplete="off" id="adicionales">
                            <span class="text-danger">{{ errors.first('adicionales') }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="Proyecto">Proyecto</label>
                      <div class="col-md-9">
                        <select class="form-control" name="" v-model="retorno.proyecto_id">
                          <option :value="articulo.proyecto_id">{{articulo.nombre_corto}}</option>
                          <option value="1">General</option>
                        </select>
                      </div>
                    </div>

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="agregarRetorno()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                  </div>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!--Fin del modal agregar articulo-->

        <br>
        <div class="container-fluid">
        <div class="card">

            <div class="card-header">
                <i class="fa fa-align-justify"></i> Partidas retorno
            </div>
            <div class="card-body">
              <vue-element-loading :active="isLoadingPartidasRetorno"/>
                <v-client-table :columns="columnsPartidasRetorno" :data="tableDataPartidasRetorno" :options="optionsentinterna" ref="myTablePartidasRetorno">

                    <template slot="articulo_id" slot-scope="props">
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group dropup" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-grip-horizontal"></i> Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button type="button" class="dropdown-item" @click="eliminarpartidaentrada(props.row, 0)">
                        <i class="icon-trash"></i>&nbsp; Eliminar partida
                    </button>
                    <button type="button" class="dropdown-item" @click="actualizarPrecio(props.row)">
                        <i class="icon-pencil"></i>&nbsp; Actualizar Precio Unitario
                    </button>
                        </div>
                    </div>
                    </div>
                    </template>
                    <template slot="id" slot-scope="props">
                    <button type="button" class="btn btn-success btn-sm" @click="abrirModalB(props.row)" >
                        Almacenar en...</button>
                    </template>
                    <template slot="validacion_calidad" slot-scope="props">
                        <template v-if="props.row.validacion_calidad == 1">
                        <button type="button" class="btn btn-success btn-sm" @click="vcalidad(props.row,0)">
                            <i class="far fa-square"></i></button>
                            <button type="button" class="btn btn-danger btn-sm" @click="vcalidadsalida(props.row,0)">
                            <i class="far fa-square"></i></button>
                            </template>
                            <template v-if="props.row.validacion_calidad == 0">
                            <button type="button" class="btn btn-success btn-sm" @click="vcalidad(props.row,1)" disabled>
                                <i class="far fa-check-square"></i></button>

                            </template>
                            <template v-if="props.row.validacion_calidad == 2">
                                <button type="button" class="btn btn-danger btn-sm" @click="vcalidadsalida(props.row,1)" disabled>
                                <i class="far fa-check-square"></i></button>
                                </template>

                            </template>


                </v-client-table>
            </div>
        </div>
    </div>


    <!--Inicio del modal agregar/actualizar articulos-->
                  <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalb}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-dark modal-lg" role="document">
                      <div class="modal-content">

                        <div>
                          <vue-element-loading :active="isLoadingb"/>
                          <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModalb"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                          </div>
                          <div class="modal-body">

                        <div class="form-group row">
                          <label class="col-md-3 form-control-label" for="almacen_id">Almacen</label>
                          <div class="col-md-9">
                            <select class="form-control" id="almacen_id"  name="almacen_id"  v-model="almacen.id" @change="almac"  data-vv-as="Stand">
                              <option v-for="item in listaAlmaceness" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                            </select>
                            <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-md-3 form-control-label" for="stand_id">Stand</label>
                          <div class="col-md-9">
                            <vue-element-loading :active="isLoadingSelectStand"/>
                            <select class="form-control" id="stand_id"  name="stand_id"  v-model="almacen.stand_id" @change="niv" data-vv-as="Stand">
                              <option v-for="item in listaStand" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                            </select>
                            <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label class="col-md-3 form-control-label" for="nivel_id">Nivel</label>
                          <div class="col-md-9">
                            <vue-element-loading :active="isLoadingSelectNivel"/>
                            <select class="form-control" id="nivel_id"  name="nivel_id"  v-model="almacen.nivel_id"  data-vv-as="Nivel">
                              <option v-for="item in listaNivel" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                            </select>
                            <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="guardarDatosAlmacen()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                        <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                      </div>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              <!--Fin del modal agregar articulo-->



    </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
    data() {
    return {
      proyecto:{},
      isLoading: false,
      isLoadinga: false,
      isLoadingb: false,
      isLoadingSelectStand: false,
      isLoadingSelectNivel: false,
      isLoadingSalidas: false,
      isLoadingPartidasRetorno: false,
      listaProyectos: [],
      listaRetornos: [],
      modalb : 0,
      tituloModalb : '',
      retorno: {},
      articulo: {},
      requision: {},
      compra: {},
      entrada: {},
      proyectoId : 0,
      salidas : [],
      listaNivel : [],
      listaStand : [],
      listaAlmaceness : [],
      almacen : {
        id : 0,
        ida: '',
        nombre : '',
        stand_id : 0,
        stand : '',
        nivel_id : 0,
        nivel : '',
      },
      columnsPartidasRetorno: ['articulo_id','tipo_entrada','ad','amarca','id','validacion_calidad','cantidad'],
      tableDataPartidasRetorno: [],
      optionsentinterna: {
        headings: {
        'articulo_id' : 'Acciones',
        'tipo_entrada': 'Tipo entrada',
        'amarca': 'Marca',
        'ad': 'Artículo',
        'validacion_calidad' : 'Validación calidad',
        'cantidadcompra' : 'Cantidad',
        'id' : 'Almacenar',

        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['id','foliocompra','amarca','ad', 'tipo_entrada', 'cantidadcompra'],
        filterable: ['tipo_entrada','foliocompra','amarca','ad'],
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
      columns : ['tipo', 'folio', 'nombre', 'codigo', 'descripcion', 'marca', 'cantidad', 'id'],
      options: {
          headings: {
              'id': 'Retorno',
              'tipo': 'Tipo',
              'folio': 'Folio',
              'nombre': 'Nombre',
              'codigo': 'Codigo',
              'descripcion': 'Descripción',
              'marca': 'Marca',
              'cantidad': 'Cantidad'
          },
          perPage: 5,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          filterByColumn: true,
          texts:config.texts
      },
      modala: false,
      loadingg: false
    }
    },

    methods : {
    getData() {
        this.isLoading = true;
        let me = this;

        axios.get('/almacen/ver').then(response => {
            me.listaAlmaceness = response.data;
        })
        .catch(function (error) {
            console.log(error);
        });

        axios.get('/proyecto').then(response =>
        {
          me.listaProyectos =[];
          response.data.forEach(p=>
          {
            me.listaProyectos.push(
            {
              id:p.proyecto.id,
              nombre_corto: p.proyecto.nombre_corto,
            });
          });
          me.isLoading = false;
        })
        .catch(function (error) {
            console.log(error);
        });
    },
    loadSalidas() {
        if (this.proyecto.id == 0) return;

        this.isLoadingSalidas = true;
        let me = this;
        axios.get('/retornopartidasporproyecto/' + this.proyecto.id).then(response => {
            me.salidas = response.data;
            console.log(response);
            me.isLoadingSalidas = false;
        })
        .catch(function (error) {
            console.log(error);
        });

        axios.get('/retornoporproyecto/' + this.proyectoId).then(response => {
            // me.salidas = response.data;
            console.log(response.data);
            // me.isLoadingSalidas = false;
            this.compra = response.data.compra;
            this.entrada = response.data.entrada;
            this.requisicion = response.data.requisicion;
            this.partidasRetorno();
        })
        .catch(function (error) {
            console.log(error);
        });

    },
    partidasRetorno() {
      let me = this;
      this.isLoadingPartidasRetorno = true;
        axios.get('/entrada/' + this.entrada.id ).then(response => {
            me.tableDataPartidasRetorno = response.data;
            me.isLoadingPartidasRetorno = false;
        })
        .catch(function (error) {
            console.log(error);
        });
    },
    retornoArticulo(data) {
        this.articulo = data;
        this.retorno.cantidad = data.cantidad;

        console.log('tabls');
        console.log(data);
        this.modala = true;

    },
    cerrarModal() {
        this.modala= false;
        this.modalb=false;
    },

    agregarRetorno(nuevo, idr){
    this.isLoading = false;
    this.detalle = true;
    console.log(this.entrada);
    console.log(this.compra);

    let me = this;
    axios({
        method: 'POST',
        url: '/guardarpartidaretorno',
        data: {
        'id' : this.articulo.id,
        'entrada_id': this.entrada.id,
        'orden_compra_id': this.compra.id,
        'articulo_id': this.articulo.articulo_id,
        'proveedore_id': this.compra.proveedore_id,
        'caducidad' : this.retorno.caducidad,
        'cantidad' : this.retorno.cantidad,
        'stocke_id' : 1,
        'proyecto_id' : this.retorno.proyecto_id,
        'precio' : this.retorno.precio,
        'tipo_entrada': 'Retorno',//this.entrada.tipo_entrada_id,
        'adicionales' : this.retorno.adicionales,
        'requisicion_id': this.requisicion.id,
        }
    }).then(function (response) {
        me.isLoading = false;
        if (response.data.status) {
            me.cerrarModal();
            me.partidasRetorno();
        } else {
        me.cerrarModal();
        swal(
            'Error!',
            'No se pueden agregar articulos de diferente orden de compra o proveedor!',
            'warning'
        )
        }
    }).catch(function (error) {
        console.log(error);
        me.isLoading = false;
    });

    },

    /**
 * [guardarDatosAlmacen Metodo que actualiza la partida de la entrada agregando los datos de almacen]
 * @return {Response} [status = true]
 */
guardarDatosAlmacen(){

  let me = this;
  axios.put( '/partidaentrada/'+me.datospartida.id,{
    'id':me.datospartida.id,
    'entrada_id': me.datospartida.entrada_id,
    'orden_compra_id': me.datospartida.req_com_id,
    'articulo_id': me.datospartida.articulo_id,
    'almacene_id': this.almacen.id,
    'nivel_id': this.almacen.nivel_id,
    'stand_id': this.almacen.stand_id,
  }).then(function (response) {
    if (response.data.status) {
      toastr.success('Guardado Correctamente')
      me.partidasRetorno();
    //   me.cargardetalle(me.empleado);
      me.cerrarModal();
      //  Utilerias.resetObject(this.almacen);
    } else {
      swal({
        type: 'error',
        html: response.data.errors.join('<br>'),
      });
    }
  }).catch(function (error) {
    console.log(error);
  });


},

    /**
 * [eliminarpartidaentrada Metodo que elimina una partida de entrada en la BD]
 * @param  {Array}  [data=[]] [description]
 * @param  {int} activar   [0 = no y 1 = si]
 * @return {Response}           [status = true]
 */
eliminarpartidaentrada(data = [] ,activar){

  if(!activar){
    this.swal_titulo = 'Esta seguro de remover este artículo de la entrada?';
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
      // console.log(data);
      // console.log(activar);
      axios.post('/partidaentrada/eliminarpartidaentrada', {
        articulo_id: data.articulo_id,
        orden_compra_id: data.req_com_id,
        partida_entrada_id : data.id,
        cantidad : data.cantidad,
      })
      .then(function (response) {
        console.log(response);
        // me.cargardetalle(me.empleado);
        me.partidasRetorno();
        me.getArticulosEntrada();
        toastr.success('Eliminado Correctamente')
      })
      .catch(function (error) {
        console.log(error);
      });
    } else if (
      result.dismiss === swal.DismissReason.cancel
    ) {

    }
  })
},

actualizarPrecio(data) {
  this.modalEditar = 1;
  this.partidaEntrada = data;
  console.log(data);
  this.isLoadingb = true;
  let me = this;
  axios.post('/partidaentradainterna/otenercomentario', {
    partida_entrada_id: this.partidaEntrada.id,
  })
  .then(function (response) {
    me.comentarioArticulo = response.data.comentario;
    me.isLoadingb = false;
  })
  .catch(function (error) {
    console.log(error);
  });
},

abrirModalB(data = []){
console.log(data);

  this.modalb = 1;
  this.tituloModalb = 'Agregar a almacen';
  this.datospartida = data;
  this.almacen.id = data['almacene_id'];

  let me=this;
  var idalmacen =data['almacene_id'];
  this.isLoadingSelectStand = true;
  this.isLoadingSelectNivel = true;

  axios.get('/almacen/verstand/'+idalmacen).then(response => {
    me.listaStand = response.data;
    me.almacen.stand_id = data['stand_id'];
    me.isLoadingSelectStand = false;
    var idstand = data['stand_id'];
    axios.get('/almacen/vernivel/'+idstand).then(response => {
      me.listaNivel = response.data;
      me.almacen.nivel_id = data['nivel_id'];
      me.isLoadingSelectNivel = false;
    })
  })
  .catch(function (error) {
    console.log(error);
  });

},

/**
        * [vcalidad Metodo el cual hace la validacion de calidad donde se pase de tener la partida en un lote temporal a un lote ya definido]
        * @param  {Array}  [data=[]] [description]
        * @param  {Int} c         [description]
        * @return {Response}           [status => true]
        */
        vcalidad (data = [],c){
          var cadena = [];
          var cadenaid = [];
          var cadenanum = [];
          var steps = [];
          let me =  this;
          me.isLoadingDetalle = true;
          let formData = new FormData();
          axios.get('/partidaentrada'+'/'+data.id).then(function (response) {
            if (response.data[0].almacene_id !=  null) {
              //descometar Esta linea  axios.get('/documentosrequeridosarticulos'+'/'+data.req_com_id+'&'+data.articulo_id).then(function (response) {//busqueda de los documentos por articulos
              //inicio compruba que no existan documentos pendientes
              //  if (response.data.length == 0 ) {//decomentar esta linea subida de archivos

              axios.post('/partidaentrada/calidad', {
                entrada_id: data.entrada_id,
                orden_compra_id: data.req_com_id,
                articulo_id : data.articulo_id,
                cantidad : data.cantidad,
                caducidad : data.caducidad,
                almacene_id : data.almacene_id,
                nivel_id : data.nivel_id,
                stand_id : data.stand_id,
                proyecto_id : data.proyecto_id,
                stokeid : data.stokeid,
                precio_unitario : data.precio_unitario,
              })
              .then(function (response) {
                // me.cargardetalle(me.empleado);
                me.partidasRetorno();
                me.isLoadingDetalle = false;
                toastr.success('Validado Correctamente')
              })
              .catch(function (error) {
                console.log(error);
              });
            } else {
            toastr.error('No se puede validar hasta definir un almacen')
            me.isLoadingDetalle = false;
            }
            }).catch(function (error) {
            console.log(error);
            });
    },

    /**
 * [vcalidadsalida Metodo que se activa al no contar con la validacion de calidad]
 * @param  {Array}  [data=[]] [description]
 * @return {Response}           [description]
 */
vcalidadsalida (data = []){
  swal({
    title: 'Motivo',
    text: "Motivo por el articulo no es validado",
    showCancelButton: true,
    input: 'textarea',
    confirmButtonColor: '#4aa0f1',
    cancelButtonColor: '#898b8e',
    confirmButtonText: 'Guardar',
    cancelButtonText: 'Cerrar',
    reverseButtons: true
  }).then((result) => {
    let me= this;
    if (result.value) {
      var val= result.value;
      axios.get('/partidaentrada'+'/'+data.entrada_id+'&'+data.req_com_id+'&'+data.articulo_id+'/calidadsalida').then(function (response) {
        // me.cargardetalle(me.empleado);
        me.isLoadingDetalle = false;
        me.partidasRetorno();
      }).catch(function (error) {
        console.log(error);
      });
    }
    else {
      console.log('false');
    }
  });
},

/**
 * [almac Metodo de consulta a la BD ]
 * @return {Response} [description]
 */
almac(){
  let me = this;
  this.isLoadingSelectStand = true;
  axios.get('almacen/verstand/' + this.almacen.id).then(response => {
    me.listaStand = response.data;
    me.isLoadingSelectStand = false;
  })
  .catch(function (error) {
    console.log(error);
  });
},

/**
 * [niv Metodo de consulta a la BD ]
 * @return {Response} [description]
 */
niv(){
  let me = this;
  this.isLoadingSelectNivel = true;
  axios.get('almacen/vernivel/' + this.almacen.stand_id).then(response => {
    me.listaNivel = response.data;
    me.isLoadingSelectNivel = false;
  })
  .catch(function (error) {
    console.log(error);
  });
},

    },

    mounted() {
        this.getData();
    },
}
</script>

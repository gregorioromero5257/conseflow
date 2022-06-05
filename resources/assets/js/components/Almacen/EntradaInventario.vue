<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Entradas Interna
        </div>
        <div class="card-body">
          <v-server-table :columns="columns" url="busqueda/entradainterna" :options="options" ref="myTable">

            <template slot="articulo_id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i> Acciones
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <button  type="button" class="dropdown-item" @click="eliminarpartidaentrada(props.row)" >
                      <i class="icon-trash"></i>&nbsp; Eliminar partida
                    </button>
                  </div>
                </div>
              </div>
            </template>

            <template slot="status" slot-scope="props">

              <template v-if="props.row.almacene_id == null" >
                <button type="button" class="btn btn-success btn-sm" @click="abrirModalAlmacen(props.row)" >
                  <i class="fas fa-boxes"></i>&nbsp;Almacenar en ...&nbsp;&nbsp;</button>
                </template>
                <template v-if="props.row.almacene_id != null" >
                  <button type="button" class="btn btn-greg btn-sm" @click="abrirModalAlmacen(props.row)" >
                    <i class="fas fa-boxes"></i>&nbsp;Almacenado en ...</button>
                  </template>
                </template>

              </v-server-table>

            </div>
          </div>
          <div class="card">
            <div class="card-body">

              <div class="form-group row">
                <label for="inputArticulo" class="col-md-2 form-control-label">Artículo</label>
                <div class="col-md-8">
                  <div class="input-group mb-3">
                    <input type="text" class="form-control" v-model="partida_entrada.nombre_articulo" placeholder="Articulo" readonly>
                    <div class="input-group-append">
                      <button  class="btn btn-secondary" type="button" @click.prevent="abrirBusArticulo()">Buscar</button>
                      <button class="btn btn-secondary" type="button" @click="agregarArt()">Agregar</button>
                    </div>
                  </div>
                  <bus-articulo ref="busqueda" @enviar="editarArt($event)" @clicked="onClickArticuloSeleccionado"></bus-articulo>
                  <articulo ref="articulo" @cerrarAbrir="abrirBusArticulo()" @cerrar="abrirBusArticulo()"></articulo>
                </div>
              </div>
              <div class="form-row">

                <div class="col-md-2 mb-3">
                  <label>Cantidad</label>
                  <input class="form-control" type="text" v-validate="'decimal:2'" v-model="partida_entrada.cantidad" data-vv-name="Cantidad">
                  <span class="text-danger">{{errors.first('Cantidad')}}</span>
                </div>
                <!-- {{partida_entrada.stock}} -->
                <div class="col-md-6 mb-3">
                  <label>Proyecto</label>
                  <v-select :options="listaStocks" v-model="partida_entrada.stock" label="name" name="proyecto" data-vv-name="proyecto" ></v-select> <!---->
                </div>


                <!-- <div class="col-md-4 mb-3">
                  <label>Precio</label>
                  <input class="form-control" type="text" v-validate="'decimal:2'" v-model="partida_entrada.precio" data-vv-name="Precio">
                  <span class="text-danger">{{errors.first('Precio')}}</span>
                </div> -->

              </div>

            </div>
            <div class="card-footer">
              <vue-element-loading :active="isLoading"/>
              <button type="button" class="btn btn-secondary" @click="cancelar()"><i class="fas fa-window-close"></i>&nbsp;Cancelar</button>
              <button type="button" v-if="partida_entrada.nombre_articulo != ''" class="btn btn-dark" @click="guardarPR();"><i class="fas fa-plus"></i>&nbsp;Agregar</button>
            </div>
          </div>
          <!--Inicio del modal agregar/actualizar almacen-->
          <div class="modal fade" tabindex="-1"
           :class="{'mostrar' : modal_almacen}"
           role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dark modal-lg" role="document">
              <div class="modal-content">

                <div>
                  <vue-element-loading :active="isLoadingAlmacen"/>
                  <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModalAlmacen"></h4>
                    <button type="button" class="close" @click="cerrarModalAlmacen()" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="almacen_id">Almacen</label>
                      <div class="col-md-9">
                        <select class="form-control" id="almacen_id"  name="almacen_id"  v-model="almacen.id" @change="almac"  data-vv-as="Stand">
                          <option v-for="item in listaAlmacenes" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                        </select>
                        <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="stand_id">Stand</label>
                      <div class="col-md-9">
                        <select class="form-control" id="stand_id"  name="stand_id"  v-model="almacen.stand_id" @change="niv" data-vv-as="Stand">
                          <option v-for="item in listaStand" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                        </select>
                        <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-md-3 form-control-label" for="nivel_id">Nivel</label>
                      <div class="col-md-9">
                        <select class="form-control" id="nivel_id"  name="nivel_id"  v-model="almacen.nivel_id"  data-vv-as="Nivel">
                          <option v-for="item in listaNivel" :value="item.id" :key="item.id">{{ item.nombre }}</option>
                        </select>
                        <span class="text-danger">{{ errors.first('grupo_id') }}</span>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="guardarDatosAlmacen()"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                    <button type="button" class="btn btn-outline-dark" @click="cerrarModalAlmacen()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                  </div>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!--Fin del modal agregar almacen-->


        </div>
      </main>
    </template>
    <script>
    import Utilerias from '../Herramientas/utilerias.js';
    var config = require('../Herramientas/config-vuetables-client').call(this);

    export default {
      components: {
        'bus-articulo': require('./BusArticulo.vue'),
        'articulo' : require('./Articulo.vue')
      },
      data(){
        return {
          isLoading : false,
          columns : [
            'articulo_id',
            'pendiente',
            'codigo',
            'descripcion',
            'marca',
            'status',
            'cantidad',
            'fechaentrada',
            'stokenombre'
          ],
          options: {
            headings: {
              'articulo_id' : 'Acciones',
              'pendiente': 'Tipo entrada',
              'descripcion': 'Artículo',
              'cantidadcompra' : 'Cantidad',
              'status' : 'Almacenar',
              'fechaentrada' : 'Fecha Entrada',
              'stokenombre' : 'Proyecto',

            },
            perPage: 5,
            perPageValues: [],
            skin: config.skin,
            sortIcon: config.sortIcon,
            orderBy : 'DESC',
            filterByColumn: true,
            listColumns: {
              pendiente: [{
                id: 0,
                text: 'Inventario'
              },
              {
                id: 1,
                text: 'Pendiente'
              }
            ],
            status:[
              {
                id: 0,
                text : 'Pendiente',
              },
              {
                id: 1,
                text : 'Almacenado'
              }
            ]
          },
          texts:config.texts,
          requestAdapter : data => {
            console.log(data);
            var arr = [];
            arr.push({
              'S.nombre' : data.query.stokenombre,
              'status' : data.query.status,
              'pendiente' :data.query.pendiente,
              'codigo' : data.query.codigo,
              'descripcion' : data.query.descripcion,
              'marca' : data.query.marca,
            });
            data.query = arr[0];
            return data;
          },
        },
        modal_almacen : 0,
        tituloModalAlmacen : '',
        datospartida : null,
        almacen : {
          id : 0,
          ida: '',
          nombre : '',
          stand_id : 0,
          stand : '',
          nivel_id : 0,
          nivel : '',
        },
        listaStocks: [],
        listaNivel : [],
        listaStand : [],
        listaAlmacenes : [],
        stocke_id : '',
        isLoadingAlmacen : false,
        modal : 0,
        tituloModal : '',
        partida_entrada : {
          nombre_articulo : '',
          articulo_id : '',
          req_com_id : '',
          cantidad : '',
          precio : '',
          stock : { "id": 1, "name": "GENERAL" },
        },
        articulo_dato : '',
      }
    },
    methods : {

      getListas(){
        let me=this;

        axios.get('/almacen/ver').then(response => {
          me.listaAlmacenes = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });

        axios.get('/stock').then(response => {
          //me.listaStocks = response.data;
          for (var i = 0; i < response.data.length; i++) {
            this.listaStocks.push({
              id: response.data[i].id,
              name : response.data[i].proyecto,
            });
          }
        })
        .catch(function (error) {
          console.log(error);
        });

      },

      abrirModalAlmacen(data = []){
        this.modal_almacen = 1;
        this.tituloModalAlmacen = 'Agregar a almacen';
        this.datospartida = data;
        this.almacen.id = data['almacene_id'];

        let me=this;
        var idalmacen =data['almacene_id'];

        axios.get('/almacen/verstand/'+idalmacen).then(response => {
          me.listaStand = response.data;
          this.almacen.stand_id = data['stand_id'];
          var idstand = data['stand_id'];
          axios.get('/almacen/vernivel/'+idstand).then(response => {
            me.listaNivel = response.data;
            this.almacen.nivel_id = data['nivel_id'];
          })
        })
        .catch(function (error) {
          console.log(error);
        });

      },

      cerrarModalAlmacen(){
        this.modal_almacen = 0;
      },

      /**
      * [almac Metodo de consulta a la BD ]
      * @return {Response} [description]
      */
      almac(){
        let me = this;
        this.isLoadingAlmacen = true;
        axios.get('almacen/verstand/' + this.almacen.id).then(response => {
          me.listaStand = response.data;
          me.isLoadingAlmacen = false;
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
        this.isLoadingAlmacen = true;
        axios.get('almacen/vernivel/' + this.almacen.stand_id).then(response => {
          me.listaNivel = response.data;
          me.isLoadingAlmacen = false;
        })
        .catch(function (error) {
          console.log(error);
        });
      },

      cerrarModal(){
        this.modal = 0;
      },

      onClickArticuloSeleccionado(value) {
        console.log(value,'dsdas');
        this.articulo_dato = value;
        this.partida_entrada.articulo_id = value.id;
        this.partida_entrada.nombre_articulo = value.descripcion;
        this.partida_entrada.cantidad = 1;
        this.partida_entrada.precio = 0;
      },

      abrirBusArticulo() {
        let me = this;
        me.$refs.busqueda.mostrarBusArticulo();
        me.$refs.myTable.refresh();
      },

      agregarArt() {
        let me = this;
        me.$refs.articulo.abrirModal('articulo','registrar');
      },

      editarArt(data){
        let me = this;
        me.$refs.articulo.abrirModal('articulo','actualizar',data);
      },

      cancelar(){
        this.partida_entrada.nombre_articulo = '';
        this.partida_entrada.cantidad = '';
        this.partida_entrada.precio = '';
        this.partida_entrada.stock = { "id": 1, "name": "GENERAL" };

      },

      guardarPR(){
        if (this.partida_entrada.nombre_articulo === '' && this.partida_entrada.articulo_id === '') {
          toastr.warning('Seleccionar un articulo !!!');
        }else if (this.partida_entrada.cantidad === '') {
          toastr.warning('Escriba la cantidad');
        }else if (this.partida_entrada.precio === '') {
          this.partida_entrada.precio = 0;
        }else {
          this.isLoading = true;
          axios.post('alm/guardar/entrada/interna',{
            articulo_id : this.partida_entrada.articulo_id,
            cantidad : this.partida_entrada.cantidad,
            stock : this.partida_entrada.stock.id,
          }).then(result => {
            toastr.success('Guardado Correctamente !!!');
            let me = this;
            me.cancelar();
            me.$refs.myTable.refresh();
            this.isLoading = false;
          }).catch(e => {
            this.isLoading = false;
            console.error(e);
          });
        }
      },

      /**
      * [guardarDatosAlmacen Metodo que actualiza la partida de la entrada agregando los datos de almacen]
      * @return {Response} [status = true]
      */
      guardarDatosAlmacen(){

        let me = this;
        axios.put( 'partidaentrada/update/almacen/'+me.datospartida.id,{
          'id':me.datospartida.id,
          'entrada_id': me.datospartida.entrada_id,
          'orden_compra_id': me.datospartida.req_com_id,
          'articulo_id': me.datospartida.articulo_id,
          'almacene_id': this.almacen.id,
          'nivel_id': this.almacen.nivel_id,
          'stand_id': this.almacen.stand_id,
          'status' : 1,
        }).then(function (response) {
          if (response.data.status) {
            toastr.success('Guardado Correctamente')
            me.cargardetalle(me.empleado);
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

    },
    mounted(){
      this.getListas();
    }
  }

  </script>
  <style>

  .btn-greg {
    color: #fff;
    background-color: #e9b443;
    border-color: #e9b443; }

    </style>

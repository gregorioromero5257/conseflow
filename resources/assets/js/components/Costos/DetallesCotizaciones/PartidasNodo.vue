<template>
  <div class="card-body">

    <div >
      &nbsp;&nbsp;&nbsp;&nbsp;{{titulo_principal}}
    </div>
    <hr>
    <div v-for="(item, index) in tableData">

      <p>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page">
              <a data-toggle="collapse" :href="'#'+index" role="button" aria-expanded="false"
              aria-controls="multiCollapseExample1">{{item.cade}}</a></li>
            </ol>
          </nav>
        </p>
        <div class="row">
          <div class="col">
            <div class="collapse multi-collapse" :id="index">
              <div class="card card-body">
                <table class="VueTables__table table table-hover table-sm table-responsive">
                  <thead class="table-light">
                    <tr>
                      <th scope="col">Acción</th>
                      <th scope="col">Concepto</th>
                      <th scope="col">Unidad</th>
                      <th scope="col">Cantidad</th>
                      <th scope="col">P. Suministro</th>
                      <th scope="col">P. Instalación</th>
                      <th scope="col">P. Unitario</th>
                      <th scope="col">Importe</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="iten in item.nodos">
                      <td>
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                          <div class="btn-group dropup" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                              <button type="button" v-show="PermisosCrud.Update" @click.prevent="abrirModal('actualizar',iten)" class="dropdown-item">
                                <i class="icon-pencil"></i>&nbsp; Editar
                              </button>
                            </div>
                          </div>
                        </div>

                      </td>
                      <td>{{iten.concepto}}</td>
                      <td>{{iten.unidad}}</td>
                      <td>{{iten.cantidad}}</td>
                      <td>{{iten.p_suministro}}</td>
                      <td>{{iten.p_instalacion}}</td>
                      <td>{{iten.p_unitario}}</td>
                      <td>{{iten.importe}}</td>

                    </tr>
                    <tr>
                      <td colspan="6"></td>
                      <td><b>Total</b></td>
                      <td scope="row"><b>{{item.suma[0]['Total']}}</b></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">


            <div class="modal-header">
              <h4 class="modal-title" v-text="tituloModal"></h4>
              <button type="button"  class="close" @click="cerrarModal()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-row">
                <div class="form-group col-md-10">
                  <label>&nbsp;Concepto</label>
                  <textarea type="text" rows="4" class="form-control" v-model="partida.concepto" ></textarea>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-8">
                  <label>&nbsp;Pertenece</label>
                  <!-- <select class="form-control" v-model="partida.padre_id" >
                    <option v-for="item in listaNodos" :value="item.id">{{item.codigo}} {{item.concepto}}</option>
                  </select> -->
                  <v-select :options="listaNodos" label="concepto" v-model="partida.padre_id"/>
                </div>
                <div class="form-group col-md-2">
                  <br><br>
                  <input type="checkbox" id="checkbox" @change="vaciar()" v-model="partida.ultimo">
                  <label for="jack">Datos generales</label>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>&nbsp;Unidad</label>
                  <input  class="form-control" type="text" :disabled="partida.ultimo == false" name="unidad"
                  v-model="partida.unidad" data-vv-name="unidad">
                  <span class="text-danger"> {{errors.first('unidad')}}</span>

                </div>
                <div class="form-group col-md-6">
                  <label>&nbsp;Cantidad</label>
                  <input class="form-control" type="text" v-validate="'decimal:2'" :disabled="partida.ultimo == false" name="cantidad"
                  v-model="partida.cantidad" data-vv-name="cantidad">
                  <span class="text-danger"> {{errors.first('cantidad')}}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>&nbsp;Precio Suministro</label>
                  <input class="form-control" type="text" v-validate="'decimal:2'" :disabled="partida.ultimo == false" name="p_suministro"
                  v-model="partida.p_suministro" data-vv-name="p_suministro">
                  <span class="text-danger"> {{errors.first('p_suministro')}}</span>
                </div>
                <div class="form-group col-md-6">
                  <label>&nbsp;Precio Instalación</label>
                  <input class="form-control" type="text" v-validate="'decimal:2'" :disabled="partida.ultimo == false" name="p_instalacion"
                  v-model="partida.p_instalacion" data-vv-name="p_instalacion" >
                  <span class="text-danger"> {{errors.first('p_instalacion')}}</span>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>&nbsp;Precio Unitario</label>
                  <input class="form-control" type="text" v-validate="'decimal:2'" readonly name="p_unitario"
                  v-model="partida.p_unitario" data-vv-name="p_unitario">
                  <span class="text-danger"> {{errors.first('p_unitario')}}</span>
                </div>
                <div class="form-group col-md-6">
                  <label>&nbsp;Importe</label>
                  <input class="form-control" type="text" v-validate="'decimal:2'" readonly  name="importe"
                  v-model="partida.importe" data-vv-name="importe">
                  <span class="text-danger"> {{errors.first('importe')}}</span>
                </div>
              </div>
              <div v-show="metodos">
                {{precio_unitario}}
                {{importe}}
              </div>

            </div>
            <div class="modal-footer">
              <vue-element-loading :active="isLoadingModal"/>
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
              <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardarPartida(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarPartida(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
            </div>

          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal-->


    </div>
  </template>
  <script>
  import Utilerias from '../../Herramientas/utilerias.js';
  var config = require('../../Herramientas/config-vuetables-client').call(this);

  export default {
    data (){
      return {
        PermisosCrud : {},
        isLoadingModal: false,
        titulo_principal : '',
        modal: false,
        metodos : false,
        tipoAccion: 0,
        tituloModal: '',
        proyecto_id: 0,
        partida: {
          id: 0,
          nombre: '',
          monto_ejecutado: 0,
          monto_cotizado: 0,
          tipo_partida_id: 0,
          proyecto_id: 0,
          padre_id : 0,
          concepto : '',
          codigo : '',
          ultimo : false,
          unidad : '',
          cantidad : 0,
          p_suministro : 0,
          p_instalacion : 0,
          p_unitario : 0,
          importe : 0,
        },
        isLoading: false,
        listaTipoPartidas: [],
        listaNodos : [],
        tableData: [],
        columns: [
          'id','concepto', 'unidad', 'cantidad', 'p_suministro','p_instalacion', 'p_unitario','importe','ejecutado','total'
        ],
        tableData: [],
        options: {
          headings: {
            'id': 'Acciones',
            'nombre': 'Nombre',
            'p_suministro': 'Suministro',
            'p_instalacion': 'Instalación',
            'p_unitario': 'Unitario',
          },
          perPage: 5,
          perPageValues: [],
          skin: config.skin,
          sortIcon: config.sortIcon,
          sortable: [
            'concepto', 'unidad', 'cantidad', 'p_suministro','p_instalacion', 'p_unitario','importe','ejecutado','total'
          ],
          filterable: [
            'concepto', 'unidad', 'cantidad', 'p_suministro','p_instalacion', 'p_unitario','importe','ejecutado','total'
          ],
          filterByColumn: true,
          listColumns: { },
          texts:config.texts
        },
      }
    },
    computed : {
      //metodos que calculan los totales del los precios unitarios y importe del formucario
      precio_unitario(){
        var precio_suministro = this.partida.p_suministro = '' ? 0 : this.partida.p_suministro;
        var precio_instalacion = this.partida.p_instalacion = '' ? 0 : this.partida.p_instalacion;
        return this.partida.p_unitario = parseFloat(precio_suministro) +  parseFloat(precio_instalacion);
      },
      importe(){
        var cantidad = this.partida.cantidad;
        var precio_unitario = this.partida.p_unitario;
        return this.partida.importe = parseFloat(cantidad) * parseFloat(precio_unitario);
      }
    },
    methods: {
      /**
      * Metodo que regressa a su estado inicial a los siguientes elemento.
      */
      vaciar(){
        if (this.partida.ultimo == false) {
          this.partida.unidad = '';
          this.partida.cantidad = 0;
          this.partida.p_suministro = 0;
          this.partida.p_instalacion = 0;
          this.partida.p_unitario = 0;
          this.partida.importe = 0;
        }
      },

      /**
      * @param data
      * Metodo que es llamado desde el componente padre "Proyectos.vue" en DetallesCotizaciones
      * que cargar los registro de partidas_costos_nodos asiciados al proyecto selecionado
      */
      cargarPartidas(data) {
        this.proyecto_id = data;
        this.titulo_principal = '';
        this.isLoading = true;
        let me=this;
        axios.get('/partidascostosno/' + this.proyecto_id).then(response => {
          me.tableData = response.data;
          me.titulo_principal = response.data[0]['principal']['concepto'];
          me.isLoading = false;
        })
        .catch(function (error) {
          console.log(error);
        });
        axios.get('/partidasnodoscostos/' + this.proyecto_id).then(response => {
          this.listaNodos = response.data;
        }).catch(error => {
          console.log(error);
        })
      },

      /**
      * Metodo que abre el modal de para un nuevo registro
      */
      agregar() {
        this.abrirModal('registrar');
      },

      /**
      * Metodo que obtiene los permisos de CRUD ocupados en los botones existentes
      */
      getData() {
        let me=this;
        this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
        axios.get('/tipopartidacostos/').then(response => {
          me.listaTipoPartidas = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
      },

      /**
      *
      */
      cerrarModal(){
        this.modal=0;
        this.tituloModal='';
        Utilerias.resetObject(this.partida);
      },

      /**
      * @param accion
      * @param data
      * Metodo que abre el modal de regitro/actualización
      */
      abrirModal(accion, data = []){
        switch(accion){
          case 'registrar':
          {
            this.modal = 1;
            this.tituloModal = 'Registrar partida';
            Utilerias.resetObject(this.partida);
            this.vaciar();
            this.tipoAccion = 1;
            break;
          }
          case 'actualizar':
          {
            this.vaciar();
            this.modal=1;
            this.tituloModal='Actualizar partida';
            this.tipoAccion=2;
            this.partida.cantidad=data['cantidad'];
            this.partida.codigo=data['codigo'];
            this.partida.concepto=data['concepto'];
            this.partida.id=data['id'];
            this.partida.importe=data['importe'];
            this.partida.p_instalacion=data['p_instalacion'];
            this.partida.p_suministro=data['p_suministro'];
            this.partida.p_unitario=data['p_unitario'];
            this.partida.padre_id={'id':data['padre_id'], 'concepto' :data['padre_concepto']};
            this.partida.proyecto_id=data['proyecto_id'];
            this.partida.ultimo=data['ultimo'];
            this.partida.unidad = data['unidad'];
            break;
          }
        }
      },

      /**
      * @param nuevo
      * Metodo que guarda los datos ingresados en el formulario basando en el estado del parametro recibido
      * para registrar o actualizar
      */
      guardarPartida(nuevo){
        this.$validator.validate().then(result => {
          if (result) {
            this.isLoadingModal = true;
            let me = this;
            axios({
              method: nuevo ? 'POST' : 'PUT',
              url: nuevo ? '/partidascostos/registrarnodo' : '/partidascostos/actualizarnodo',
              data: {
                'id': this.partida.id,
                //'codigo': this.partida.codigo,
                'concepto': this.partida.concepto,
                'padre_id': this.partida.padre_id.id,
                'ultimo': this.partida.ultimo == false ? 0 : 1,
                'proyecto_id': this.proyecto_id,
                'unidad' : this.partida.unidad,
                'cantidad' : this.partida.cantidad,
                'p_suministro' : this.partida.p_suministro,
                'p_instalacion' : this.partida.p_instalacion,
                'p_unitario' : this.partida.p_unitario,
                'importe' : this.partida.importe,

              }
            }).then(function (response) {
              me.isLoadingModal = false;
              if (response.data.status) {
                me.cerrarModal();
                me.partida.ultimo = false;
                me.cargarPartidas(me.proyecto_id);
                if(!nuevo){
                  toastr.success('Partida Actualizada Correctamente');
                }
                else
                {
                  toastr.success('Partida Registrada Correctamente');
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
    },
    mounted() {
      this.getData();
    }
  }
  </script>

<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card" v-show="detalle == false">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Bitacora RP-RME
          <button type="button" @click="abrirModal('bitacora','registrar_gral')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">

          <v-client-table :data="tableDataGral" :options="options_gral" :columns="columns_gral">
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a type="button" @click="abrirModal('bitacora','actualizar_gral',props.row)" class="dropdown-item" href="#">
                      <i class="fas fa-pencil-alt"></i>&nbsp;Actualizar analisis
                    </a>
                    <a type="button" @click="verdetalle(props.row)" class="dropdown-item" href="#">
                      <i class="fas fa-eye"></i>&nbsp;Detalle
                    </a>
                    <a type="button" @click="eliminar(props.row.id)" class="dropdown-item" href="#">
                      <i class="fas fa-trash"></i>&nbsp;Eliminar
                    </a>
                  </div>
                </div>
              </div>
            </template>
            <template slot="descargar" slot-scope="props">
              <button type="button" @click="descargar(props.row.id)" class="btn btn-outline-dark" >
                <i class="fas fa-download"></i>&nbsp;<i class="fas fa-file-pdf"></i>
              </button>
            </template>
          </v-client-table>

        </div>
      </div>

      <div class="card" v-show="detalle == true">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Bitacora
          <button type="button" @click="abrirModal('bitacora','registrar')" class="btn btn-dark float-sm-right">
            <i class="fas fa-plus"></i>&nbsp;Nuevo
          </button>
          <button type="button" @click="atras()" class="btn btn-secondary float-sm-right">
            <i class="fa fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">
          <v-client-table :data="tableData" :columns="columns" :options="options">
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group dropup" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-grip-horizontal"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a type="button" @click.prevent="abrirModal('bitacora','actualizar',props.row)" class="dropdown-item" href="#">
                      <i class="fas fa-pencil-alt"></i>&nbsp;Actualizar analisis
                    </a>
                  </div>
                </div>
              </div>
            </template>
            <template slot="tipo" slot-scope="props">
              <template v-if="props.row.tipo == 1">
                RME
              </template>
              <template v-if="props.row.tipo == 2">
                RP
              </template>
            </template>

          </v-client-table>
        </div>

      </div>

      <div class="modal fade" tabindex="-1" :class="{ mostrar: modal }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div>
              <div class="modal-header">
                <h4 class="modal-title">{{tituloModal}}</h4>
                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
              <div class="modal-body">
                <!-- {{uno}} -->

                <!-- <vue-element-loading :active="isLoading" /> -->
                <div class="form-row ">
                  <div class="col-md-3 mb-3">
                    <label>Fecha de ingreso (AT)</label>
                    <input type="date" data-vv-name="Fecha"  class="form-control" v-model="uno.fecha">
                    <span class="text-danger">{{errors.first('Fecha')}}</span>
                  </div>
                  <div class="col-md-2 mb-3">
                    <label>Tipo de Residuos</label>
                    <select class="form-control" v-validate="'required'" data-vv-name="Tipo" v-model="uno.tipo">
                      <option value=""></option>
                      <option value="1">RME</option>
                      <option value="2">RP</option>
                    </select>
                    <span class="text-danger">{{errors.first('Tipo')}}</span>
                  </div>
                  <div class="col-md-7 mb-3">
                    <label>Nombre del residuo</label>
                    <select class="form-control" v-validate="'required'" data-vv-name="Nombre" v-model="uno.nombre">
                      <template v-if="uno.tipo == 2">
                        <option v-for="b in list_residuos_dos" :value="b">{{b.residuo.residuo}}</option>
                      </template>
                      <template v-if="uno.tipo == 1">
                        <option v-for="a in list_residuos_uno" :value="a">{{a.residuo.residuo}}</option>
                      </template>
                    </select>
                    <span class="text-danger">{{errors.first('Nombre')}}</span>
                  </div>
                </div>
                <div class="form-row">
                  <template v-if="uno.nombre != ''">
                  <div class="col-md-6 mb-3">
                    <label>Area o proceso</label>
                    <select class="form-control" data-vv-name="Area" v-validate="'required'" v-model="uno.area_proceso">
                      <option v-for="t in uno.nombre.fuente" :value="t.fuente_generacion">{{t.fuente_generacion}}</option>
                    </select>
                    <span class="text-danger">{{errors.first('Area')}}</span>
                  </div>
                </template>
                </div>
                <hr>
                <!-- <div class="form-row"> -->

                <!-- <div class="col-md-9 mb-3"> -->
                <!-- <label>Nombre del residuo</label> -->
                <!-- <input type="text" data-vv-name="Nombre" v-validate="'required'" class="form-control" v-model="uno.nombre"> -->
                <!-- <span class="text-danger">{{errors.first('Nombre')}}</span> -->
                <!-- </div> -->
                <!-- </div> -->
                <div class="form-row">

                  <div class="col-md-2 mb-3">
                    <label>Cantidad</label>
                    <input type="text" class="form-control" v-validate="'required'" data-vv-name="Cantidad" v-model="uno.cantidad">
                    <span class="text-danger">{{errors.first('Cantidad')}}</span>
                  </div>
                  <div class="col-md-3 mb-3">
                    <label>Unidad Medida</label>
                    <select class="form-control" v-validate="'required'" data-vv-name="Unidad" v-model="uno.unidad">
                      <option value="KG">KILOGRAMO</option>
                      <option value="LT">LITRO</option>
                      <option value="GR">GRAMO</option>
                    </select>
                    <span class="text-danger">{{errors.first('Unidad')}}</span>
                  </div>
                  <template v-if="uno.tipo == 1">
                    <div class="col-md-3 mb-3">
                      <label>Fecha salida RME</label>
                      <input type="date" class="form-control" v-model="uno.fecha_salida_rme">
                    </div>
                  </template>

                </div>

                <div class="form-row">
                  <template v-if="uno.tipo == 2" >
                    <div class="col-md-4 mb-3">
                      <label>CRETIB</label>
                      <select class="form-control"  v-model="dos.crebit">
                        <option value="1">Corrosivo</option>
                        <option value="2">Reactivo</option>
                        <option value="3">Explosivo</option>
                        <option value="4">Tóxico</option>
                        <option value="5">Tóxico Ambiental</option>
                        <option value="6">Tóxico Agudo</option>
                        <option value="7">Tóxico Crónico</option>
                        <option value="8">Inflamable</option>
                        <option value="9">Biologicamente Infeccioso</option>
                      </select>
                    </div>
                  </template>
                  <div class="col-md-3 mb-3">
                    <label>Fecha Salida</label>
                    <input type="date" class="form-control" v-model="dos.fecha" data-vv-name="Salida" v-validate="'required'">
                    <span class="text-danger">{{errors.first('Salida')}}</span>

                  </div>
                  <div class="col-md-5 mb-3">
                    <label>Proveedor</label>
                    <input type="text" class="form-control" v-model="dos.proveedor" data-vv-name="Proveedor" v-validate="'required'">
                    <span class="text-danger">{{errors.first('Proveedor')}}</span>
                  </div>
                </div>

                <template v-if="uno.tipo == 2" >

                  <div class="form-row">
                    <div class="col-md-3 mb-3">
                      <label>No. de Autorización</label>
                      <input type="text" class="form-control" v-model="dos.no_autorizacion">
                    </div>
                    <div class="col-md-3 mb-3">
                      <label>Clave Generica</label>
                      <select class="form-control" v-model="dos.clave">
                        <option value="1">SO1</option>
                        <option value="2">O1</option>
                        <option value="3">SO5</option>
                        <option value="4">L5</option>
                        <option value="5">1</option>
                      </select>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label>Núm De Manifiesto</label>
                      <input type="text" class="form-control" v-model="dos.num_manifiesto">
                    </div>
                  </div>
                </template>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" @click="cerrarModal()">
                  <i class="fas fa-window-close"></i>&nbsp;Cerrar
                </button>
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
      <!--Fin del modal agregar documentos-->



    </div>
    <div class="modal fade" tabindex="-1" :class="{ mostrar: modal_gral }" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-hidden="true">
      <div class="modal-dialog modal-dark modal-lg" role="document">
        <div class="modal-content">
          <div>
            <div class="modal-header">
              <h4 class="modal-title">{{tituloModalGral}}</h4>
              <button type="button" class="close" @click="cerrarModalGral()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-row">
                <div class="col-md-2 mb-3">
                  <label>Folio</label>
                  <input type="text" class="form-control" v-model="folio"  data-vv-name="Folio">
                  <span class="text-danger">{{errors.first('Folio')}}</span>
                </div>
                <div class="col-md-5 mb-3">
                  <label>Localidad</label>
                  <input type="text" class="form-control" v-model="localidad"  data-vv-name="Localidad">
                  <span class="text-danger">{{errors.first('Localidad')}}</span>
                </div>
                <div class="col-md-5 mb-3">
                  <label>Responsable</label>
                  <v-select :options="listaEmpleados" label="name" v-model="responsable" data-vv-name="Responsable"></v-select>
                  <span class="text-danger">{{errors.first('Responsable')}}</span>
                </div>
              </div>


            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" @click="cerrarModalGral()">
                <i class="fas fa-window-close"></i>&nbsp;Cerrar
              </button>
              <button v-show="tipoAccionGral == 1" type="button" class="btn btn-secondary" @click="GuardarGral(1)">
                Guardar
              </button>
              <button v-show="tipoAccionGral == 2" type="button" class="btn btn-secondary" @click="GuardarGral(0)">
                Actualizar
              </button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal agregar documentos-->
  </main>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default{
  data(){
    return {
      data_ : '',
      detalle : false,
      id_gral : 0,
      folio :  '',
      responsable : '',
      localidad : '',
      modal : 0,
      modal_gral : 0,
      tituloModal : '',
      tituloModalGral : '',
      tipoAccion : 0,
      tipoAccionGral : 0,
      departamentosList : [],
      listaEmpleados : [],
      area_proceso_array : '',
      id : 0,
      uno : {
        fecha : '',
        nombre : '',
        tipo : '',
        cantidad : '',
        unidad : '',
        area_proceso : '',
        fecha_salida_rme : '',
      },
      dos : {
        crebit : '',
        fecha : '',
        clave : '',
        no_autorizacion : '',
        proveedor : '',
        num_manifiesto :  '',
      },
      options:
      {
        headings:
        {
          id:'Acciones',
          responsable_nombre : 'Responsable',
        }, // Headings,
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts: config.texts
      }, //options
      columns : ['id','fecha','tipo','residuo'],
      tableData : [],
      options_gral:
      {
        headings:
        {
          id:'Acciones',
          responsable_nombre : 'Responsable',

        }, // Headings,
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts: config.texts
      }, //options
      columns_gral : ['id','folio','responsable_nombre','localidad','descargar'],
      tableDataGral : [],
      list_residuos_uno : [],
      list_residuos_dos : [],
    }
  },
  methods : {
    getEmpleado(){
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

      axios.get('get/lista/catalogo/residuos').then(response => {
        response.data.forEach((item, i) => {
          if (item.residuo.tipo == 1) {
            this.list_residuos_uno.push(item);
          }else if (item.residuo.tipo == 2) {
            this.list_residuos_dos.push(item);
          }
        });
      }).catch(e => {
        console.error(e);
      });
    },

    getData(){
      axios.get('seguridad/residuos/get/bitacora/gral').then(response => {
        this.tableDataGral = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    abrirModal(modelo, accion, data = []){
      switch(modelo){
        case "bitacora":
        {
          switch(accion){
            case 'registrar':
            {
              this.vaciarUno();
              // Utilerias.resetObject(this.uno);
              // Utilerias.resetObject(this.dos);
              this.modal_gral = 0;
              this.modal = 1;
              this.tituloModal = 'Registrar';
              this.tipoAccion = 1;
              break;
            }
            case 'actualizar':
            {
              // console.log(data,'dfgh');
              this.modal_gral = 0;
              this.modal=1;
              this.tituloModal='Actualizar';
              this.tipoAccion=2;
              this.uno.fecha = data['fecha'];
              this.uno.tipo = data['tipo'];

              if (this.uno.tipo == 1) {
                this.uno.nombre = this.list_residuos_uno.find(info => info.residuo.id == data['nombre']);
              }else if (this.uno.tipo == 2) {
                this.uno.nombre = this.list_residuos_dos.find(info => info.residuo.id == data['nombre']);
              }

              this.uno.cantidad = data['cantidad'];
              this.uno.unidad = data['unidad'];
              // this.Asignar();
              this.uno.area_proceso = data['area_proceso'];
              this.uno.fecha_salida_rme = data['fecha_salida_rme'];
              this.dos.fecha = data['fecha_dos'];
              this.dos.proveedor = data['proveedor'];
              this.dos.crebit = data['crebit'];
              this.dos.no_autorizacion = data['no_autorizacion'];
              this.dos.clave = data['clave'];
              this.dos.num_manifiesto = data['num_manifiesto'];
              this.id = data['id'];
              break;
            }
            case 'registrar_gral':
            {
              this.modal_gral = 1;
              this.tituloModalGral = 'Registrar';
              this.tipoAccionGral = 1;
              this.VaciarGral();
              break;
            }
            case 'actualizar_gral':
            {
              // console.log(data);
              this.VaciarGral();
              this.modal_gral = 1;
              this.tituloModalGral = 'Actualizar';
              this.tipoAccionGral = 2;
              this.responsable = {id: data['responsable'], name :  data['responsable_nombre']};
              this.localidad = data['localidad'];
              this.folio = data['folio'];
              this.id_gral = data['id'];
              break;
            }
          }
        }

      }
    },

    vaciarUno(){
      this.uno.fecha = '';
      this.uno.cantidad = '';
      this.uno.unidad = '';
      this.uno.fecha_salida_rme = '';
      Utilerias.resetObject(this.dos);
    },

    Guardar(nuevo){
      this.$validator.validate().then(result => {
        // console.log(result);
        if (result)  {

          axios({
            method : nuevo ? 'POST' : 'PUT',
            url : nuevo ? 'seguridad/residuos/guardar/bitacora' : 'seguridad/residuos/actualizar/bitacora',
            data : {
              id : this.id,
              bitacora_residuos_entrada_general_id : this.id_gral,
              fecha : this.uno.fecha,
              nombre : this.uno.nombre.residuo.id,
              tipo : this.uno.tipo,
              cantidad : this.uno.cantidad,
              unidad : this.uno.unidad,
              area_proceso : this.uno.area_proceso,
              fecha_salida_rme : this.uno.fecha_salida_rme,
              crebit : this.dos.crebit,
              fecha_dos : this.dos.fecha,
              proveedor : this.dos.proveedor,
              no_autorizacion : this.dos.no_autorizacion,
              clave : this.dos.clave,
              num_manifiesto : this.dos.num_manifiesto,
              // localidad : this.localidad,
              // responsable : this.responsable.id,
            },
          }).then(response => {
            console.log(response.data);
            toastr.success('Guardado correctamente !!!');
            this.cerrarModal();
            this.actualizarDetalle(this.data_);
            // this.Vaciar();
          }).catch(e => {
            console.error(e);
          });

        }
      });

    },

    cerrarModal(){
      this.modal = 0;
    },

    cerrarModalGral(){
      this.modal_gral = 0;
    },

    Vaciar(){
      Utilerias.resetObject(this.uno);
      Utilerias.resetObject(this.dos);
    },

    VaciarGral(){
      this.localidad = '';
      this.folio = '';
      this.responsable = '';
    },

    Asignar(){
      switch (this.uno.nombre) {
        case '7': this.area_proceso_array = ['Oficina','Almacén','Comedor','Baños']; break;
        case '8': this.area_proceso_array = ['Comedor']; break;
        case '9': this.area_proceso_array = ['Oficina','Comedor','Taller']; break;
        case '10': this.area_proceso_array = ['Oficina','Comedor']; break;
        case '11': this.area_proceso_array = ['Oficinas']; break;
        case '12': this.area_proceso_array = ['Oficina','Almacén', 'Taller']; break;
        case '13': this.area_proceso_array = ['Taller']; break;
        case '1': this.area_proceso_array = ['Taller']; break;
        case '2': this.area_proceso_array = ['Taller']; break;
        case '3': this.area_proceso_array = ['Taller', 'Oficinas']; break;
        case '4': this.area_proceso_array = ['Taller']; break;
        case '5': this.area_proceso_array = ['Vehículos']; break;
        case '6': this.area_proceso_array = ['Vehículos', 'Oficina (TI)']; break;
      }
    },

    descargar(data){
      window.open('descargar/bitacora/residuos/' + data, '_blank');
    },

    verdetalle(data){
      // console.log(data);
      this.detalle = true;
      this.data_ = data;
      this.id_gral = data.id;
      axios.get('seguridad/residuos/get/bitacora/' + data.id).then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    actualizarDetalle(data){
      axios.get('seguridad/residuos/get/bitacora/' + data.id).then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    atras(){
      this.detalle = false;
    },


    GuardarGral(nuevo){

          axios({
            method : nuevo ? 'POST' : 'PUT',
            url : nuevo ? 'seguridad/residuos/guardar/bitacora/gral' : 'seguridad/residuos/actualizar/bitacora/gral',
            data : {
              id : this.id_gral,
              localidad : this.localidad,
              responsable : this.responsable.id,
              folio : this.folio,
            },
          }).then(response => {
            // console.log(response.data);
            toastr.success('Guardado correctamente !!!');
            this.VaciarGral();
            this.cerrarModalGral();
            this.getData();
          }).catch(e => {
            console.error(e);
          });
    },

    eliminar(id){
      swal({
        title: 'Esta seguro(a) eliminar',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4dbd74',
        cancelButtonColor: '#f86c6b',
        confirmButtonText: 'Aceptar!',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          axios.get('eliminar/bitacora/residuos/' + id).then(response => {
            this.getData();
            toastr.success('Eliminado Correctamente');
          }).catch(e => {
            console.error(e);
          });
        }
      });
    }
  },
  mounted () {
    this.getData();
    this.getEmpleado();
  }
}

</script>

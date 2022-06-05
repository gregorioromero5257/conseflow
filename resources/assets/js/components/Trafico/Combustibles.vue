<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Conductores
          <button type="button" @click="abrirModal(1)" class="btn btn-dark float-sm-right">
            <i class="icon-plus"></i>&nbsp;Nuevo
          </button>
        </div>
        <div class="card-body">
          <v-client-table :data="tableData" :columns="columns" :options="options">
            <template slot='id' slot-scope='props'>
              <div class='btn-group' role='group'>
                <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  <i class='fas fa-grip-horizontal'></i>&nbsp;
                </button>
                <div class='dropdown-menu'>
                  <template>
                    <button type='button' @click='abrirModal(2, props.row)' class='dropdown-item'>
                      <i class='fas fa-pencil'></i>&nbsp;Actualizar
                    </button>
                    <button type='button' @click='eliminar(props.row.id)' class='dropdown-item'>
                      <i class='fas fa-trash'></i>&nbsp;Eliminar
                    </button>
                  </template>
                </div>
              </div>
            </template>
        </v-client-table>
        </div>
      </div>

      <!-- Modal -->
      <div class='modal fade' tabindex='-1' :class="{'mostrar' : modal}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
        <div class='modal-dialog modal-dark modal-lg' role='document'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h4 class='modal-title' v-text='titulo'></h4>
              <button type='button' class='close' @click='CerrarModal()' aria-label='Close'>
                <span aria-hidden='true'>×</span>
              </button>
            </div>
            <div class='modal-body'>

              <div class="form-row">
                <div class="col-md-3 mb-3">
                  <label>Proveedor</label>
                  <input type="text" class="form-control" v-validate="'required'" data-vv-name="Proveedor" v-model="proveedor">
                  <span class="text-danger">{{errors.first('Proveedor')}}</span>
                </div>
                <div class="col-md-2 mb-3">
                  <label>Folio</label>
                  <input type="text" class="form-control" v-validate="'required'" data-vv-name="Folio" v-model="folio">
                  <span class="text-danger">{{errors.first('Folio')}}</span>
                </div>
                <div class="col-md-3 mb-3">
                  <label>Fecha</label>
                  <input type="date" class="form-control" v-validate="'required'" data-vv-name="Fecha" v-model="fecha">
                  <span class="text-danger">{{errors.first('Fecha')}}</span>
                </div>
                <div class="col-md-4 mb-3">
                  <label>Proyecto</label>
                  <v-select :options="listaProyectos" v-model="proyecto" label="name" name="name" data-vv-name="Proyecto" v-validate="'required'"></v-select>
                  <span class="text-danger">{{errors.first('Proyecto')}}</span>
                </div>
              </div>

              <div class="form-row">
                <div class="col-md-5 mb-3">
                  <label>Operador</label>
                  <v-select :options="listaEmpleados" label="name" v-model="operador" data-vv-name="operador" v-validate="'required'"></v-select>
                  <span class="text-danger">{{errors.first('operador')}}</span>
                </div>
                <div class="col-md-2 mb-3">
                  <label>Factura</label>
                  <input type="text" class="form-control" v-validate="'required'" data-vv-name="Factura" v-model="factura">
                  <span class="text-danger">{{errors.first('Factura')}}</span>
                </div>
                <div class="col-md-2 mb-3">
                  <label>Placas</label>
                  <input type="text" class="form-control" v-validate="'required'" data-vv-name="Placas" v-model="placas">
                  <span class="text-danger">{{errors.first('Placas')}}</span>
                </div>
                <div class="col-md-3 mb-3">
                  <label>Unidad</label>
                  <input type="text" class="form-control" v-validate="'required'" data-vv-name="Unidad" v-model="unidad">
                  <span class="text-danger">{{errors.first('Unidad')}}</span>
                </div>
              </div>

              <div class="form-row">
                <div class="col-md-3 mb-3">
                  <label>Producto</label>
                  <input type="text" class="form-control" v-validate="'required'" data-vv-name="Producto" v-model="producto">
                  <span class="text-danger">{{errors.first('Producto')}}</span>
                </div>
                <div class="col-md-3 mb-3">
                  <label>Kilometraje</label>
                  <input type="text" class="form-control" v-validate="'required|decimal:1'" data-vv-name="Kilometraje" v-model="kilometraje">
                  <span class="text-danger">{{errors.first('Kilometraje')}}</span>
                </div>
                <div class="col-md-3 mb-3">
                  <label>Cantidad</label>
                  <input type="text" class="form-control" v-validate="'required|decimal:4'" data-vv-name="Cantidad" v-model="cantidad">
                  <span class="text-danger">{{errors.first('Cantidad')}}</span>
                </div>
              </div>

              <div class="form-row">
                <div class="col-md-3 mb-3">
                  <label>Precio</label>
                  <input type="text" class="form-control" v-validate="'required|decimal:4'" data-vv-name="Precio" v-model="precio">
                  <span class="text-danger">{{errors.first('Precio')}}</span>
                </div>
                <div class="col-md-3 mb-3">
                  <label>SubTotal</label>
                  <input type="text" class="form-control" v-validate="'required|decimal:4'" data-vv-name="SubTotal" v-model="subtotal">
                  <span class="text-danger">{{errors.first('SubTotal')}}</span>
                </div>
                <div class="col-md-3 mb-3">
                  <label>IVA</label>
                  <input type="text" class="form-control" v-validate="'required|decimal:4'" data-vv-name="IVA" v-model="iva">
                  <span class="text-danger">{{errors.first('IVA')}}</span>
                </div>
                <div class="col-md-3 mb-3">
                  <label>Total</label>
                  <input type="text" class="form-control" v-validate="'required|decimal:4'" data-vv-name="Total" v-model="total">
                  <span class="text-danger">{{errors.first('Total')}}</span>
                </div>
              </div>


              <div class="form-row">
                <div class="col-md-4 mb-3">
                  <label>&nbsp;</label>
                  <input type="file" accept="image/*" ref="adjunto" @change="cargarImg()"/>
                </div>
              </div>
              <div class="form-row">

                <div class="form-group col-md-2">
                  <label><b>#</b></label>
                </div>
                <div class="form-group col-md-6">
                  <label><b>Preview</b></label>
                </div>
                <div class="form-group col-md-1">
                  <label><b>.</b></label>
                </div>
              </div>
              <li v-for="(vi, index) in img" class="list-group-item">
                <div class="form-row">

                  <div class="form-group col-md-2">
                    <label>{{index + 1}}</label>
                  </div>
                  <div class="form-group col-md-6">
                    <label></label>
                    <img :src="vi.name" width="200px" height="150px">
                  </div>
                  <div class="form-group col-md-1">
                    <a @click="deleteu(vi, index)">
                      <span class="fas fa-trash" arial-hidden="true"></span>
                    </a>
                  </div>
                </div>
              </li>



            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-outline-dark' @click='CerrarModal()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
              <button type='button' v-if='tipoAccion == 1' class='btn btn-secondary' @click='Guardar(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
              <button type='button' v-if='tipoAccion == 2' class='btn btn-secondary' @click='Guardar(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- Modal -->

    </div>
  </main>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default{
  data(){
    return {
      id :  0,
      proveedor : 'SERV CUAUTLAPAN SA DE CV',
      folio : '',
      fecha : '',
      proyecto : '',
      operador : '',
      factura : '',
      placas : '',
      unidad : '',
      producto : '',
      kilometraje : '',
      cantidad : '',
      precio : '',
      subtotal : '',
      iva : '',
      total : '',

      listaEmpleados : [],
      listaProyectos : [],
      img : [],

      tableData : [],
      columns : ['id','proveedor','folio','fecha','proyecto','operador','factura','placas','unidad','total'],
      options: {
        headings: {
          id : 'Acciones',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts:config.texts
      },

      modal : 0,
      titulo : '',
      tipoAccion : 0,
    }
  },
  methods : {

    getData(){
      axios.get('combustible/get').then(response => {
        this.tableData = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    getLista(){
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

      axios.get('/proyecto-listar-todos').then(response => {
        response.data.forEach((item, i) => {
          this.listaProyectos.push({
            id : item.id,
            name : item.nombre_corto,
            ubicacion : item.ciudad,
          });
        });
      }).catch(e => {
        console.error(e);
      });

    },

    abrirModal(edo, data = []){
      if (edo == 1) {
        this.modal = 1;
        this.titulo = 'Guardar';
        this.tipoAccion = 1;
        this.vaciar();
      }else if (edo == 2) {
        this.modal = 1;
        this.titulo = 'Actualizar';
        this.tipoAccion = 2;
        this.vaciar();
        this.id = data['id'];
        this.proveedor = data['proveedor'];
        this.folio = data['folio'];
        this.fecha = data['fecha'];
        this.proyecto = {id:data['proyecto_id'], name : data['proyecto']};
        this.operador = {id : 0, name : data['operador']};
        this.factura = data['factura'];
        this.placas = data['placas'];
        this.unidad = data['unidad'];
        this.producto = data['producto'];
        this.kilometraje = data['kilometraje'];
        this.cantidad = data['cantidad'];
        this.precio = data['precio'];
        this.subtotal = data['subtotal'];
        this.iva = data['iva'];
        this.total = data['total'];
        this.getImages(data['id']);
      }
    },

    getImages(id){
      this.img = [];
      axios.get('get/imagenes/combustible/' + id).then(response => {
          if (response.data.length != 0) {
            this.img.push({id : response.data.id,name : response.data.img});
          }
      }).catch(e => {
        console.error(e);
      });
    },

    vaciar(){
      this.id = 0;
      this.folio = '';
      this.fecha = '';
      this.proyecto = '';
      this.operador = '';
      this.factura = '';
      this.placas = '';
      this.unidad = '';
      this.producto = '';
      this.kilometraje = '';
      this.cantidad = '';
      this.precio = '';
      this.subtotal = '';
      this.iva = '';
      this.total = '';
      this.img = [];
    },

    CerrarModal(){
      this.modal = 0;
    },

    cargarImg(){
      if (this.img.length > 0) {
        toastr.warning('Solo se puede adjuntar un archivo');
      }else {
        const selectedImage = this.$refs.adjunto.files[0];
        this.imageToBase64(selectedImage);
      }
    },

    imageToBase64 (file) {
      var reader = new FileReader()
      reader.readAsDataURL(file)
      reader.onload = () => {
        this.img.push({id : 0,name : reader.result});
      }
      reader.onerror = function (error) {
        console.log('Error: ', error)
      }
    },

    Guardar(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          axios({
            method : nuevo ? 'POST' : 'PUT',
            url : nuevo ? 'combustible/guardar' : 'combustible/actualizar',
            data : {
              id : this.id,
              proveedor : this.proveedor,
              folio : this.folio,
              fecha : this.fecha,
              proyecto_id : this.proyecto.id,
              proyecto : this.proyecto.name,
              operador : this.operador.name,
              factura : this.factura,
              placas : this.placas,
              unidad : this.unidad,
              producto : this.producto,
              kilometraje : this.kilometraje,
              cantidad : this.cantidad,
              precio : this.precio,
              subtotal : this.subtotal,
              iva : this.iva,
              total : this.total,
              adjunto : this.img,
            }
          }).then(response => {
            this.modal = 0;
            this.getData();
            toastr.success('Correcto !!!');
          }).catch(e => {
          console.error(e);
          });
        }
      });
    },

    deleteu(data, index){
      if (data.id == 0) {
        this.img.splice(index, 1);
      }else if (data.id != 0) {
        axios.get('delete/imagenes/combustible/' + data.id).then(response => {
          this.getImages(this.id);
        }).catch(e => {
          console.error(e);
        });
      }
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
          axios.get('combustible/eliminar/' + id).then(response => {
            this.getData();
            toastr.success('Eliminado !!!');
          }).catch(e => {
            console.error(e);
          });
        }
      });
    },


  },
  mounted(){
    this.getLista();
    this.getData();
  }
}

</script>

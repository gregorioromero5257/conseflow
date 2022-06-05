<template>
  <main class="main">
    <div class="container-fluid">
      <div class="card">
        <vue-element-loading :active="isLoading"/>
        <div class="card-header">
          <i class="fa fa-align-justify"></i> INVENTARIOS.
        </div>
        <div class="card-body">
          <form>
            <div class="form-row">
              <div class="col-md-6">
                <label>Articulo</label>
                <v-select id="articulo" label="name"
                :options="listaArt" v-model="articulo_">
              </v-select>
            </div>
            <div class="col-md-2">
              <label>&nbsp;</label><br>
              <button class="btn btn-dark" @click="abrirModal(1)">Agregar</button>
              <button class="btn btn-dark" v-show="articulo_ != ''" @click="abrirModal(0)">Actualizar</button>

            </div>
          </div>
          <br>
          <div class="form-row">
            <!-- <div class="col-md-2">
            <label>Nave</label>
            <select class="form-control" @change="verubicacion" v-model="nave">
            <option value="1">NAVE 1</option>
            <option value="2">NAVE 2</option>
            <option value="3">NAVE 4</option>
            <option value="4">CONTENEDORES</option>
          </select>
        </div> -->
        <!-- <div class="col-md-4">
        <label>Ubicación</label>
        <v-select id="ubicacion" label="name"
        :options="listaUbi" v-model="ubicacion">
      </v-select>
    </div> -->
    <div class="col-md-2">
      <label>Cantidad</label>
      <input type="text" class="form-control" v-model="cantidad">
    </div>
    <div class="col-md-4">
      <label>Comentario</label>
      <input type="text" class="form-control" v-model="comentario">
    </div>
  </div>
</form>
<hr>

<div class="form-row">
  <div class="col-md-2">
    <button class="btn btn-secondary" @click="Cancelar()">Cancelar</button>
    <button class="btn btn-dark" @click="Guardar()">Guardar</button>
  </div>
</div>
<hr>
<!-- <div class="form-row">
  <div class="col-md-12">
    <button class="btn btn-danger float-sm-right" @click="GenerarC()">Generar Codigos</button>
  </div>
</div> -->
<v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable"  @row-click="seleccionarArticulo">
  <!-- <template slot="id" slot-scope="props"> -->
    <!-- {{listado}} -->
    <!-- <div class="form-check form-check-inline"> -->
      <!-- <input type="checkbox" @click="seleccionar($event, props.row.registro.cod_id)"> -->
      <!-- <label class="form-check-label" for="inlineCheckbox1">1</label> -->
    <!-- </div> -->
    <!-- <h1>lol</h1> -->
    <!-- <input type="checkbox" class="form-check-input" @click="seleccionar($event, props.row.registros.cod_id)" id="exampleCheck1"> -->
  <!-- </template> -->
  <!--

  <template slot="id" slot-scope="props">
  <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
  <div class="btn-group dropup" role="group">
  <button id="btnGroupDrop1" type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  <i class="fas fa-grip-horizontal"></i> Acciones
</button>
<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
<button type="button" @click="abrirModal('servicio','actualizar',props.row)" class="dropdown-item">
<i class="icon-pencil"></i>&nbsp;Actualizar servicio.
</button>
</div>
</div>
</div>

</template> -->
</v-client-table>

<div class="card">
  <div class="card-body">
    <!-- {{array_seleccionado}} -->
    <div class="form-row" >

      <div class="form-group col-md-6">
        <label><b>DESCRIPCIÓN</b></label>
      </div>
      <div class="form-group col-md-1">
        <label><b>ACCIONES</b></label>
      </div>
      <div class="form-group col-md-2">
        <button class="btn btn-dark" @click="generar()"><i class="fas fa-file-pdf"></i>&nbsp;Generar</button>
      </div>
      <div class="form-group col-md-1">
        <button class="btn btn-secondary" @click="limpiar()"><i class="fas fa-trash"></i>&nbsp;Limpar</button>
      </div>
    </div>
    <li v-for="(vi, index) in array_seleccionado" class="list-group-item">
      <div class="form-row">
        <div class="form-group col-md-5">
          <label>{{vi.name}}</label>
        </div>
        <div class="form-group col-md-1">
          <a @click="eliminar_aignacion(vi, index)">
            <span class="fas fa-trash" arial-hidden="true"></span>
          </a>
        </div>
      </div>
    </li>
  </div>
</div>
<!--Inicio del modal agregar/actualizar-->
<div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_uno}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
  <vue-element-loading :active="isLoading"/>
  <div class="modal-dialog modal-dark modal-lg" role="document">
    <div class="modal-content">
      <div>

        <div class="modal-header">
          <h4 class="modal-title" >Agregar Articulo</h4>
          <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" > -->
          <input type="hidden" name="id">
          <div class="form-group row">
            <label class="col-md-3 form-control-label" >Nombre </label>
            <div class="col-md-9">
              <input type="text" v-validate="'required'"
              name="nombre" v-model="articulo.nombre"
              class="form-control" placeholder="Nombre"
              autocomplete="off" data-vv-name="Nombre">
              <span class="text-danger">{{ errors.first('Nombre') }}</span>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-3 form-control-label" >Categoria</label>
            <div class="col-md-9">
              <select class="form-control" data-vv-name="Categoria"
              v-model="articulo.categoria" v-validate="'required'">
              <option v-for="t in categoria" :value="t.id">
                {{t.nombre}}</option>
              </select>
            </div>
            <span class="text-danger">{{ errors.first('Categoria') }}</span>
          </div>

          <div class="form-group row">
            <label class="col-md-3 form-control-label" >Marca/Modelo</label>
            <div class="col-md-9">
              <input type="text" v-validate="'required'" name="marca"
              v-model="articulo.marca" class="form-control"
              placeholder="Marca/Modelo" autocomplete="off" id="marca"
              data-vv-name="Marca">
              <span class="text-danger">{{ errors.first('Marca') }}</span>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-3 form-control-label" >
              # Serie</label>
              <div class="col-md-9">
                <input type="text" name="serie"
                v-model="articulo.serie" class="form-control"
                placeholder="# Serie" autocomplete="off" id="serie">
                <span class="text-danger">{{ errors.first('Serie') }}</span>
              </div>
            </div>

            <div class="form-group row" v-if="articulo.categoria == 4">
              <label class="col-md-3 form-control-label" >
                Caracteristicas</label>
                <div class="col-md-9">
                  <input type="text" name="caracteristicas"
                  v-model="articulo.caracteristicas" class="form-control"
                  placeholder="# Caracteristicas" autocomplete="off" id="caracteristicas">
                  <span class="text-danger">{{ errors.first('Caracteristicas') }}</span>
                </div>
              </div>

              <!-- </form> -->
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
              <button type="button" class="btn btn-secondary" @click="guardarArt(tipoAccion)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
              <!-- v-if="tipoAccion==1" -->
              <!-- <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardarServicio(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button> -->
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </div>
</div>
</div>
</main>
</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
export default{
  data () {
    return {
      array_seleccionado : [],
      listado : '',
      tipoAccion : 2,
      isLoading : false,
      listaArt : [],
      listaUbi : [],
      articulo_ : '',
      nave : 0,
      ubicacion : '',
      cantidad : '',
      modal_uno : 0,
      categoria : [],
      comentario : '',
      articulo : {
        nombre : '',
        marca : '',
        categoria : '',
        serie : '',
        id : 0,
        caracteristicas : '',
      },
      columns: ['empleado.empleado','registro.nombre_a','registro.marca_a', 'registro.nombre_c','registro.codigo','registro.comentario','registro.impreso'],
      tableData: [],
      options: {
        headings: {
          'id': 'Acción',
          'empleado.empleado' : 'Usuario',
          'registro.nombre_a' : 'Nombre',
          'registro.marca_a' : 'Marca',
          'registro.nombre_c' : 'Categoria',
          'registro.codigo' : 'Codigo',
          'registro.comentario' : 'Comentario',
          // 'nombre_u': 'Ubicación',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['empleado.empleado','registro.nombre_a', 'registro.marca_a','registro.nombre_c','registro.codigo'],
        filterable: ['empleado.empleado','registro.nombre_a', 'registro.marca_a','registro.nombre_c','registro.codigo'],
        filterByColumn: true,
        texts:config.texts
      },
    }
  },
  methods : {
    limpiar(){
      this.array_seleccionado = [];
    },

    seleccionarArticulo(data){
      // console.log(data);
      // console.log(this.array_seleccionado.length);
      if (this.array_seleccionado.find(info => info.cod_id == data.row.registro.cod_id)) {
        toastr.warning('Ya seleccionado');
      }else if (this.array_seleccionado.length > 9) {
        toastr.warning('Solo se pueden seleccionar 10 elementos');
      }
      else {
        this.array_seleccionado.push({
          id : data.row.registro.cod_id,
          name : data.row.registro.nombre_c + ' ' + data.row.registro.marca_a + ' ' + data.row.registro.codigo + ' '+ data.row.registro.comentario,
          pos : 0,
        });
      }
    },

    eliminar_aignacion(data, index){
      if (data.pos == 0)
      {
        this.array_seleccionado.splice(index, 1);
      }
      else
      {
        let me = this;
        var id = data['id'];
        axios.get(`relaciones/eliminar/${id}`)
        .then(function (response)
        {
          me.array_seleccionado.splice(index, 1);
        })
        .catch(function (error)
        {
          console.log(error);
        });
      }
    },

    getData(){


      axios.get('/inv/art').then(response => {
        this.listaArt = [];
        response.data.forEach((item, i) => {
          this.listaArt.push({
            id: item.id,
            name : item.nombre + ' ' + item.marca + ' ' + item.ns,
            nombre : item.nombre,
            marca : item.marca,
            idcat : item.idcat,
            caracteristicas : item.caracteristicas,
          });
        });

      }).catch(e => {
        console.error(e);
      });

      axios.get('/inv/art/categoria').then(response => {
        this.categoria = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    getList(){
      axios.get('inv/get/datainitial').then(response => {
        // console.log(response.data);
        this.tableData = response.data;
      }).catch(error => {
        console.error(error);
      });
    },

    verubicacion(){
      this.ubicacion ='';
      axios.get('naves/'+this.nave).then(response => {
        this.listaUbi = [];
        response.data.forEach((item, i) => {
          this.listaUbi.push({
            id : item.id,
            name : item.cod + ' '+item.nombre,
            cod : item.cod,
          });
        });
      }).catch(e => {
        console.error(e);
      });
    },

    abrirModal(n){
      this.modal_uno = 1;
      if (n == 1) {
        this.tipoAccion = 1;
      }else if (n == 0) {
        this.tipoAccion = 0;

        this.articulo.nombre  = this.articulo_.nombre;
        this.articulo.marca = this.articulo_.marca;
        this.articulo.categoria = this.articulo_.idcat;
        this.articulo.id = this.articulo_.id;
        this.articulo.caracteristicas = this.articulo_.caracteristicas;
      }
    },

    cerrarModal(){
      this.modal_uno = 0;
    },

    guardarArt(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          this.isLoading = true;
          let me = this;
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? '/inv/art/store' : '/inv/art/update',
            data: {
              'id': this.articulo.id,
              'nombre': this.articulo.nombre,
              'categoria': this.articulo.categoria,
              'marca': this.articulo.marca,
              'serie' : this.articulo.serie,
              'caracteristicas' : this.articulo.caracteristicas,
            }
          }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
              if (response.data.status === 'error') {
                swal({
                  type: 'error',
                  html: 'Ha ocurrido un error notifiqué al administrador y recarge la página',
                });
              }else {
                me.cerrarModal();
                me.getData();
                me.articulo_ = '';
                me.tipoAccion = 2;
                me.Cancelar();
                if (!nuevo) {
                  toastr.success('Actualizado Correctamente');
                }else {
                  toastr.success('Agregado Correctamente');
                }
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

    Cancelar(){
      this.articulo_ = '';
      this.nave = 0;
      this.ubicacion = '';
      this.cantidad = '';
      this.comentario = '';

    },

    Guardar(){
      if (this.articulo_ === '') {
        toastr.warning('Agregar articulo');
      }else if (this.cantidad == '') {
        toastr.warning('Agregar cantidad');
      }else {
        this.isLoading = true;
        axios.post('/primer/reg/guardar',{
          articulo : this.articulo_.id,
          nave : this.nave,
          ubicacion : this.ubicacion.id,
          cantidad : this.cantidad,
          comentario : this.comentario,
          cod : this.ubicacion.cod,
        }).then(response => {
          this.getData();
          this.getList();
          this.Cancelar();
          this.isLoading = false;
          toastr.success('Agregado correctamente');
        }).catch(e => {
          console.error(e);
        });
      }


    },

    generar(){
      var art = '';
      var c = 0;
      this.array_seleccionado.forEach((item, i) => {
        art += '&' + item.id ;
        c++;
      });
      // if (c < 10) {
      //   toastr.warning('Necesita 10 elementos');
      // }else {
        // window.open('generar/codigos/' + art, '_blank');
        window.open('descargar-codigos/inventarios/' + art , '_blank');

      // }
      // axios.post('generar/codigos/',{data : this.array_seleccionado}).then(response => {
      //   console.log(response);
      // }).catch(error => {
      //   console.error(error);
      // });
      this.getData();
    },

    seleccionar(e, data){
      // console.log(e);
      if (e.target.checked == true) {
        this.listado += '&'+data;
      }
      if (e.target.checked == false) {
      this.listado = this.reemplazarCadena('&'+data,'',this.listado);
      }
    },

    reemplazarCadena(cadenaVieja, cadenaNueva, cadenaCompleta) {
      // Reemplaza cadenaVieja por cadenaNueva en cadenaCompleta

      for (var i = 0; i < cadenaCompleta.length; i++) {
        if (cadenaCompleta.substring(i, i + cadenaVieja.length) == cadenaVieja) {
          cadenaCompleta= cadenaCompleta.substring(0, i) + cadenaNueva + cadenaCompleta.substring(i + cadenaVieja.length, cadenaCompleta.length);
        }
      }
      return cadenaCompleta;
    },



  },
  mounted() {
    this.getData();
    this.getList();
  }
}

</script>

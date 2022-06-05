<template>
  <main class="main">
    <!-- Breadcrumb -->
    <!-- <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
  </ol> -->
  <div class="container-fluid">
    <!-- Ejemplo de tabla Listado -->
    <div class="card" v-show="detalle == 1">
      <vue-element-loading :active="isLoading"/>
      <div class="card-header">
        <i class="fa fa-align-justify"></i> EDENRED
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-4">
            <label>&nbsp;Subir Empleados</label>
            <input class="form-control" type="file" v-validate="'ext:xls,xlsx'" data-vv-as="Archivo" name="import_file_emp" @change="getArchivo" accept="*/*" id="archivo" >
            <span class="text-danger">{{ errors.first('import_file_emp') }}</span>
          </div>
          <div class="form-group col-md-4">
            <label>&nbsp;Subir Movimiemtos</label>
            <input class="form-control" type="file" v-validate="'ext:xls,xlsx'" data-vv-as="Archivo" name="import_file_mov" @change="getArchivoMov" accept="*/*" id="archivo_mov">
            <span class="text-danger">{{ errors.first('import_file_mov') }}</span>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="form-row">
          <div class="form-group col-md-4">
            <button @click="updateAvatar" class="btn btn-primary" :disabled="isLoading">Subir Archivo</button>
          </div>
          <div class="form-group col-md-4">
            <button @click="updateMovimentos(); updateEncabezado();" class="btn btn-primary" :disabled="isLoading">Subir Movimiemtos</button>
          </div>
        </div>

      </div>
    </div>
    <div class="card" v-show="detalle == 1">
      <div class="card-body">
        <v-server-table url="edenredempleados/get" :options="options" :columns="columns" ref="table">
          <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i> Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <button  type="button" @click="ver(props.row)" class="dropdown-item">
                    <i class="icon-pencil"></i>&nbsp; Ver movimientos
                  </button>
                </div>
              </div>
            </div>
          </template>
          <template slot="asignado" slot-scope="props">
            <template v-if="props.row.asignado == 1">
              <button type="button" class="btn btn-outline-success">Si</button>
            </template>
            <template v-else>
              <button type="button" class="btn btn-outline-danger">No</button>
            </template>
          </template>
        </v-server-table>
      </div>
    </div>
    <div class="card" v-show="detalle == 2">
      <div class="card-header">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> EDENRED --{{data == null ? '' : data.nombre_empleado}}
          <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
            <i class="fas fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
      </div>
      <div class="card-body">
        <v-server-table :columns="columns_movimientos" :url="'edenredmovimientos/show/'+objeto" :options="options_movimientos" ref="myTable">
        </v-server-table>
      </div>
    </div>
  </div>
</main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default {

  data(){
    return {
      archivo : null,
      archivo_mov : null,
      isLoading: false,
      detalle : 1,
      objeto : 0,
      data : '',
      columns: ['id', 'numero_nomina', 'nombre_empleado','asignado', 'numero_cuenta','numero_tarjeta','status','correo','puesto','telefono','direccion_entrega','buzon_usuario'],
      options: {
        headings: {
          id: 'Acciones',
          asignado : 'Empleado Sistema'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['numero_nomina', 'nombre_empleado', 'asignado','numero_cuenta','numero_tarjeta','status','correo','puesto','telefono','direccion_entrega','buzon_usuario'],
        filterable: ['numero_nomina', 'nombre_empleado', 'asignado','numero_cuenta','numero_tarjeta','status','correo','puesto','telefono','direccion_entrega','buzon_usuario'],
        filterByColumn: true,
        listColumns: {
          asignado: [{
            id: 1,
            text: 'Si'
          },
          {
            id: 0,
            text: 'No'
          }
        ]
      },
      texts:config.texts
    },
    columns_movimientos: ['id','fecha_movimiento','concepto','descripcion_comercio','tipo_comercio','rfc_comercio','abono','valor_pesos','dato_dos'],
    options_movimientos: {
      headings: {
        id: 'Acciones',
        dato_dos : 'Fecha Reporte',
      },
      perPage: 10,
      perPageValues: [],
      skin: config.skin,
      sortIcon: config.sortIcon,
      sortable: ['fecha_movimiento','concepto','descripcion_comercio','tipo_comercio','rfc_comercio','abono','valor_pesos','dato_dos'],
      filterable: ['fecha_movimiento','concepto','descripcion_comercio','tipo_comercio','rfc_comercio','abono','valor_pesos','dato_dos'],
      filterByColumn: true,
      texts:config.texts
    },
  }
},
methods  : {
  getArchivo(event){
    //Asignamos el archivo a  nuestra data
    this.archivo = event.target.files[0];
  },
  getArchivoMov(event){
    //Asignamos el archivo a  nuestra data
    this.archivo_mov = event.target.files[0];
    this.archivo_enc = event.target.files[0];
  },
  updateAvatar(){

    this.$validator.validate().then(result => {
      if (result) {

        this.isLoading = true;
        //Creamos el formData
        var data = new  FormData();
        //Añadimos la imagen seleccionada
        data.append('import_file', this.archivo);
        //Añadimos el método PUT dentro del formData
        // Como lo hacíamos desde un formulario simple _(no ajax)_
        data.append('_method', 'PUT');
        //Enviamos la petición
        axios.post('/edenredempleados/upload',data)
        .then(response => {
          // console.log(response);
          this.archivo = null;
          this.isLoading = false;
          // var field = this.$validator.fields.find({ name: 'email' });
          // field.reset();
          if (response.status == 200){
            swal(
              'Tarjetas agregadas',
              //  'Registros agregados: ' + response.data.nuevos + '<br>Registros repetidos: ' + response.data.repetidos,
              'correcto!!!'
            );
            $('#archivo').val('');
            this.$refs.table.refresh();
          }
          else
          swal(
            'Articulos',
            'Ocurrio un error.',
            'error'
          );
        })
        .catch(function (error) {
          this.archivo = null;
          this.isLoading = false;
          console.log(error);
          swal(
            'Articulos',
            'Ocurrio un error al leer el archivo.',
            'error'
          );
        });

      }
    })
  },
  updateMovimentos(){

    this.$validator.validate().then(result => {
      if (result) {

        this.isLoading = true;
        //Creamos el formData
        var data = new  FormData();
        //Añadimos la imagen seleccionada
        data.append('import_file', this.archivo_mov);
        //Añadimos el método PUT dentro del formData
        // Como lo hacíamos desde un formulario simple _(no ajax)_
        data.append('_method', 'PUT');
        //Enviamos la petición
        axios.post('/edenredmoviemientos/upload',data)
        .then(response => {
          // console.log(response);
          this.archivo_mov = null;
          this.isLoading = false;
          // var field = this.$validator.fields.find({ name: 'email' });
          // field.reset();
          if (response.status == 200){
            // swal(
            //   'Movimientos agregados',
            //   //  'Registros agregados: ' + response.data.nuevos + '<br>Registros repetidos: ' + response.data.repetidos,
            //   'correcto!!!'
            // );
            // $('#archivo_mov').val('');
            this.$refs.table.refresh();
          }
          else
          swal(
            'Movimiemtos',
            'Ocurrio un error.',
            'error'
          );
        })
        .catch(function (error) {
          this.archivo_mov = null;
          this.isLoading = false;
          console.log(error);
          swal(
            'Movimiemtos',
            'Ocurrio un error al leer el archivo.',
            'error'
          );
        });

      }
    })
  },

  updateEncabezado(){

    this.$validator.validate().then(result => {
      if (result) {

        this.isLoading = true;
        //Creamos el formData
        var data = new  FormData();
        //Añadimos la imagen seleccionada
        data.append('import_file', this.archivo_enc);
        //Añadimos el método PUT dentro del formData
        // Como lo hacíamos desde un formulario simple _(no ajax)_
        data.append('_method', 'PUT');
        //Enviamos la petición
        axios.post('/edenredmoviemientosenc/upload',data)
        .then(response => {
          // console.log(response);
          this.archivo_enc = null;
          this.isLoading = false;
          // var field = this.$validator.fields.find({ name: 'email' });
          // field.reset();
          if (response.status == 200){
            swal(
              'Agregados',
              //  'Registros agregados: ' + response.data.nuevos + '<br>Registros repetidos: ' + response.data.repetidos,
              'correcto!!!'
            );
            $('#archivo_mov').val('');
            this.$refs.table.refresh();
          }
          else
          swal(
            'Movimiemtos',
            'Ocurrio un error.',
            'error'
          );
        })
        .catch(function (error) {
          this.archivo_enc = null;
          this.isLoading = false;
          console.log(error);
          swal(
            'Movimiemtos',
            'Ocurrio un error al leer el archivo.',
            'error'
          );
        });

      }
    })
  },

  ver(data){
    this.detalle = 2;
    this.objeto = data.numero_nomina;
    this.data = data;
  },

  maestro(){
    this.detalle = 1;
    this.objeto = 0;
  }

}
}
</script>

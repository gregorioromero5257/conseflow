<template>

  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Subir sueldos gastos
        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-4">
              <label>&nbsp;Subir --</label>
              <input class="form-control" type="file" v-validate="'ext:xls,xlsx'" data-vv-as="Archivo"
              name="import_file_emp" @change="getArchivo" accept="*/*" id="archivo" >
              <span class="text-danger">{{ errors.first('import_file_emp') }}</span>
            </div>
            <div class="form-group col-md-4">
              <label>Empresa</label>
              <select class="form-control" v-model="empresa">
                <option value="CONSERFLOW">CONSERFLOW</option>
                <option value="CSCT">CSCT</option>
              </select>
            </div>
          </div>

        </div>
        <div class="card-footer">
          <div class="form-row">
            <div class="form-group col-md-4">
              <button @click="update" class="btn btn-primary" >Subir Archivo</button>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Generar Reporte
        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-3">
              <label>Empresa</label>
              <select class="form-control" v-model="empresa">
                <option value="CONSERFLOW">CONSERFLOW</option>
                <option value="CSCT">CSCT</option>
              </select>
            </div>
            <div class="form-group col-md-3">
              <label for="inputEmail4">Fecha Inicio:</label>
              <input type="date" name="fechauno" id="fechauno" class="form-control" v-model="fechauno" data-vv-name="fechauno">
              <span class="text-danger">{{ errors.first('fechauno') }}</span>
            </div>

            <div class="form-group col-md-3">
              <label for="inputEmail4">Fecha Fin:</label>
              <input type="date" name="fechados" id="fechados" class="form-control" v-model="fechados" data-vv-name="fechados">
              <span class="text-danger">{{ errors.first('fechados') }}</span>
            </div>

            <div class="form-group col-md-3">
              <label>&nbsp;</label>
              <br>
              <div class="dropdown" >
                <button class="btn btn-outline-success dropdown-toggle" :disabled="isDisabled"  type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Opciones
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <button class="dropdown-item btn-outline-success" type="button" @click="descargar()"><i class="far fa-file-excel"></i>&nbsp;Descargar Reporte</button>
                </div>
              </div>

            </div>

          </div>

        </div>

      </div>

      <!-- <div class="card">
      <div class="card-header">
      <i class="fa fa-align-justify"></i> Detalle Control Tiempo.
    </div>
    <div class="card-body">
    <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable"> -->
    <!-- // update text on the fly
    <input type="text" slot="control.horas_extras" slot-scope="{row, update}" v-model="row.control.horas_extras" @input="update">
    // update a checkbox
    <input type="checkbox" slot="checkbox" slot-scope="{row, update}" v-model="row.checkbox" @input="update">

    // update text on submit + toggle editable state + revert to original value on cancel
    <div slot="text2" slot-scope="{row, update, setEditing, isEditing, revertValue}">
    <span @click="setEditing(true)" v-if="!isEditing()">
    {{row.control.horas_extras}}
  </span>
  <span v-else>
  <input type="text" v-model="row.control.horas_extras">
  <button type="button" @click="update(row.control.horas_extras); setEditing(false)">Submit</button>
  <button type="button" @click="revertValue(); setEditing(false)">Cancel</button>
</span>
</div> -->
<!-- </v-client-table>
</div>
</div> -->

<!-- Fin ejemplo de tabla Listado -->

</div>
</main>

</template>
<script>

import Utilerias from '../../Herramientas/utilerias.js';

var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data() {
    return {
      archivo : null,
      empresa  :'',
      fechauno : '',
      fechados : '',
    }
  },

  methods: {

    // descargarGeneralProyectAdmin(){
    //   var lunes = this.controlt.fechauno;
    //   let datel = new Date(lunes.replace(/-+/g, '/'));
    //   var dialunes = datel.getDay();
    //
    //   var domingo = this.controlt.fechados;
    //   let dated = new Date(domingo.replace(/-+/g, '/'));
    //   var diadomingo = dated.getDay();
    //
    //   if (dialunes != 1) {
    //     swal('Error!','Debe comenzar en Lunes !','warning');
    //     this.isDisabled = true;
    //   }
    //   else if (diadomingo != 0) {
    //     swal('Error!','Debe terminar en Domingo !','warning');
    //     this.isDisabled = true;
    //   }else {
    //     window.open('descargar-control-general-proyecto-admin/' + this.controlt.fechauno + '&' + this.controlt.fechados , '_blank');
    //   }
    //
    // },



    getArchivo(event){
      //Asignamos el archivo a  nuestra data
      this.archivo = event.target.files[0];
      // console.log(event);
    },

    update(){

      if (this.empresa === '') {
        toastr.warning('Seleccione la empresa !!!');
      }else {
        // this.isLoading = true;
        //Creamos el formData
        var data = new  FormData();
        //Añadimos la imagen seleccionada
        data.append('import_file', this.archivo);
        data.append('empresa', this.empresa);
        //Añadimos el método PUT dentro del formData
        // Como lo hacíamos desde un formulario simple _(no ajax)_
        data.append('_method', 'PUT');
        //Enviamos la petición
        axios.post('/registrogastosempresas/upload',data)
        .then(response => {
          // console.log(response);
          this.archivo = null;
          // var field = this.$validator.fields.find({ name: 'email' });
          // field.reset();
          if (response.status == 200){
            this.empresa = '';
            swal(
              'agregadas',
              //  'Registros agregados: ' + response.data.nuevos + '<br>Registros repetidos: ' + response.data.repetidos,
              'correcto!!!'
            );
            $('#archivo').val('');
            // this.$refs.table.refresh();
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
          // this.isLoading = false;
          console.log(error);
          swal(
            'Articulos',
            'Ocurrio un error al leer el archivo.',
            'error'
          );
        });
      }

    },

    descargar(){
      var lunes = this.fechauno;
      let datel = new Date(lunes.replace(/-+/g, '/'));
      var dialunes = datel.getDay();

      var domingo = this.fechados;
      let dated = new Date(domingo.replace(/-+/g, '/'));
      var diadomingo = dated.getDay();

      if (dialunes != 1) {
        swal('Error!','Debe comenzar en Lunes !','warning');
        this.isDisabled = true;
      }
      else if (diadomingo != 0) {
        swal('Error!','Debe terminar en Domingo !','warning');
        this.isDisabled = true;
      }else {
        window.open('/descargar/reporte/gastos/' + this.fechauno + '&' + this.fechados + '&' + this.empresa, '_blank');
      }
    },



  },
  computed: {
    isDisabled() {
      if (this.fechauno != null && this.fechados != null) {
        return false;
      } else {
        return true;
      }
    }
  },
  mounted() {
  }
}


</script>

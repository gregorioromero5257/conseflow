<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Subir gastos
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
                <option value="CONSERFLOW">Conserflow</option>
                <option value="CSCT">CSCT</option>
              </select>
            </div>
          </div>
          <!-- {{archivo}} -->
        </div>
        <div class="card-footer">
          <div class="form-row">
            <div class="form-group col-md-4">
              <button @click="update" class="btn btn-primary" >Subir Archivo</button>
            </div>
          </div>

        </div>
      </div>

    </div>
  </main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';

export default {
  data() {
    return {
      empresa : '',
      archivo : null,

    }
  },
  methods: {
    getArchivo(){
      this.archivo = event.target.files[0];
    },


    update(){
      //Creamos el formData
      var data = new  FormData();
      //Añadimos la imagen seleccionada
      data.append('import_file', this.archivo);
      data.append('empresa', this.empresa);
      //Añadimos el método PUT dentro del formData
      // Como lo hacíamos desde un formulario simple _(no ajax)_
      data.append('_method', 'PUT');
      //Enviamos la petición
      axios.post('/registrosgastosempresa/upload',data)
      .then(response => {
        this.archivo = null;
        $('#archivo').val('');
        if (response.status == 200){
          this.getData();
          swal(
            'agregadas',
            'correcto!!!'
          );
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
        console.log(error);
        swal(
          'Articulos',
          'Ocurrio un error al leer el archivo.',
          'error'
        );
      });
    },

  },
  mounted() {
  }
}
</script>

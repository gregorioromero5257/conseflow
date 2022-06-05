<template>
  <main class="main">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body">

          <div class="form-row">
              <div class="form-group col-md-3">
                  <label>Fecha</label>
                  <input type="date" class="form-control" v-model="date" ref="date_two">
              </div>
              <div class="form-group col-md-3">
                <label>Tipo</label>
                <select class="form-control" v-model="tipo" v-validate="'required'">
                  <option value="1">Asistencia</option>
                  <option value="2">Ubicacion</option>
                </select>
              </div>
              <div class="form-group col-md-2">
                  <template v-if="tipo == 1">
                      <label>Generar Reporte</label>
                      <br>
                      <select class="form-control" @change="generate" v-model="reporte_estado">
                          <option value="1">Conserflow Semanal</option>
                          <option value="2">Conserflow Quincenal</option>
                          <option value="3">CSCT Semanal</option>
                          <option value="4">CSCT Quincenal</option>
                      </select>
                      <!-- <button type="button" class="btn btn-success" name="button" @click="generate"></button> -->
                  </template>
                  <template v-if="tipo == 2">
                      <label>Generar Reporte</label>
                      <br>
                      <select class="form-control" @change="generate" v-model="nave">
                          <option value="Nave 1">Nave 1</option>
                          <option value="Nave 2">Nave 2</option>
                          <option value="Nave 4">Nave 4</option>
                      </select>
                      <!-- <button type="button" class="btn btn-success" name="button" @click="generate"></button> -->
                  </template>
              </div>

          </div>

        </div>
      </div>
    </div>
  </main>
</template>
<script>

  export default {
    data(){
      return {
        date : '',
        reporte_estado : '',
        tipo : '',
        nave : '',
      }
    },
  methods : {
    generate(){
      this.$validator.validate().then(result => {
        if (result) {
          if (this.tipo == 1) {
            window.open('lista/asistencia/seguridad/' + this.date + '&' + this.reporte_estado + '&' + this.tipo, '_blank');
          }else if (this.tipo == 2) {
            window.open('lista/asistencia/seguridad/' + this.date + '&' + this.nave + '&' + this.tipo, '_blank');
          }
          this.reporte_estado = '';
          this.tipo = '';
        }else {
          toastr.success('Complete todos los campos');
        }
      });
    },
  },
  mounted(){

  }
  }
</script>

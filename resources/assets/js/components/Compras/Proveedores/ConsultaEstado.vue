<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Estado Proveedores
        </div>
        <div class="card-body">
          <div class="form-group row">
            <label class="col-md-2 form-control-label">Proveedor</label>
            <div class="col-md-7">
              <v-select :options="proveedores" v-model="proveedor" label="name" v-validate="'excluded:0'" data-vv-as="Proveedor"></v-select>
              <span class="text-danger">{{ errors.first('Proveedor') }}</span>
            </div>
            <div class="col-md-2">
              <button class="btn btn-dark" @click="buscar"><i class="fa fa-search"></i> Buscar</button>
              <button class="btn btn-sm btn-danger" @click="descargar()"><i class="fas fa-file-pdf"></i> Descargar</button>
            </div>
          </div>
        </div>
      </div>


      <div class="card">
        <div class="card-body">
          <table class="table table-bordered table-sm">
              <thead>
                  <tr class="table-active">
                      <th style="width:10%">Fecha</th>
                      <th style="width:10%">Tipo</th>
                      <th style="width:25%">Concepto</th>
                      <th style="width:25%">Referencia</th>
                      <th style="width:10%">Cargo</th>
                      <th style="width:10%">Abono</th>
                      <th style="width:10%">Saldos</th>
                  </tr>
              </thead>
              <tr v-for="t in ocs" >
                <td>{{t.ocp.fecha}}</td>
                <td>{{t.ocp.tipo}}</td>
                <td>{{proveedor.rz}}</td>
                <td>{{t.ocp.folio}}</td>
                <td>{{t.ocp.tipo === 'compra' ? t.ocp.total : ''}}</td>
                <td>{{t.ocp.tipo === 'pago' ? t.ocp.total : ''}}</td>
                <td>{{(t.saldo).toFixed(2)}}</td>

              </tr>
              <tr>
                <td colspan="3"></td>
                <td><b>Total</b></td>
                <td><b>{{total_compra}}</b></td>
                <td><b>{{total_pago}}</b></td>
                <td><b>{{total_t}}</b></td>
              </tr>
          </table>
        </div>
      </div>

    </div>
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data() {
    return {
      proveedores: [],
      proveedor: null,
      ocs : null,
      total_compra : 0,
      total_pago : 0,
      total_t : 0,

    }
  },
  methods : {
    buscar(){
      var total_compra = 0;
      var total_pago = 0;
      var total_t = 0;
      axios.get('buscar/estado/proveedor/'+this.proveedor.id).then(response => {
        this.ocs = response.data;
        response.data.forEach((item, i) => {
          if (item.ocp.tipo === 'compra') {
            total_compra += parseFloat((item.ocp.total).replace(',',''));
          }else if (item.ocp.tipo === 'pago') {
            total_pago +=  parseFloat(item.ocp.total);
          }
        });
        this.total_compra = total_compra;
        this.total_pago = total_pago;
        this.total_t = response.data[response.data.length -1].saldo;
      }).catch(error => {
        console.error(error);
      });
    },

    listar(){
      this.isLoading = true;
      let me=this;
      axios.get('/proveedores/activos/1').then(response => {
        me.proveedores = [];
        response.data.forEach(data =>{
          me.proveedores.push({
            id : data.id,
            name : data.nombre + ' ' + data.razon_social,
            nombre : data.nombre,
            rz : data.razon_social,
          });
        });
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    descargar(){
    window.open('descargar-consulta-estado-proveedor/' + this.proveedor.id, '_blank');
    },

  },
  mounted () {
    this.listar();
  },

}

</script>

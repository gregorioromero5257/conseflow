<template>
  <main class="main">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Empleados Documentos

        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Nombre</label>
              <input class="form-control" v-model="empleado" @keyup.enter="buscarEmpleado">
            </div>
          </div>
          <table class="table table-bordered table-sm table-responsive">
            <thead>
              <tr>
                <th width="10%" class="table-active">... </th>
                <th width="18%" class="table-active">Empleado</th>
                <th width="18%" class="table-active">NSS</th>
                <th width="18%" class="table-active">CURP</th>
                <th width="18%" class="table-active">RFC</th>
                <th width="18%" class="table-active">Clave INE</th>
                <th width="18%" class="table-active"># Credencial</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in empleados" :value="item.id" :key="item.id">
                <td>
                  <button @click="guardar(item)" class="btn btn-outline-dark"><i class="fas fa-upload"></i></button>
                  <button @click="descargar(item)" class="btn btn-outline-dark"><i class="fas fa-download"></i></button>
                </td>
                <td >{{ item.nombre + ' ' + item.ap_paterno + ' ' + item.ap_materno }}</td>
                <td><button @click="nss(item)" class="btn btn-outline-dark">{{ item.nss_imss }}</button></td>
                <td><button @click="curp(item)" class="btn btn-outline-dark">{{ item.curp }}</button></td>
                <td><button @click="rfc(item)" class="btn btn-outline-dark">{{ item.rfc }}</button></td>
                <td><button @click="ci(item)" class="btn btn-outline-dark">{{ item.clave_ine }}</button></td>
                <td><button @click="nc(item)" class="btn btn-outline-dark">{{ item.numero_credencial }}</button></td>
              </tr>
            </tbody>

          </table>
          <nav>
            <ul class="pagination">
              <li class="page-item" :class="page == 1 ? 'disabled' : ''"><a class="page-link" @click.prevent="anterior()" href="#">Anterior</a></li>
              <li class="page-item" :class="page == total ? 'disabled' : ''"><a class="page-link" @click.prevent="siguiente()" href="#">Siguiente</a></li>
              <li class="page-item"><b>Total registros : {{total_registros}}</b> </li>

            </ul>
          </nav>
        </div>
      </div>

      <!--Inicio del modal agregar/actualizar-->
      <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalver}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dark modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Documentos Descarga</h4>
              <button type="button" class="close" @click="cerrarModalVer()" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">

              <nav>

                <ul class="list-group">
                  <li v-for="p in data" class="list-group-item">
                    <a class="page-link" href="#" @click="descargarf(p.archivo)">
                    <template v-if="p.tipo == 1">
                      <span>NSS</span>
                    </template>
                    <template v-if="p.tipo == 2">
                      <span>RFC</span>
                    </template>

                    <template v-if="p.tipo == 3">
                      <span>CURP</span>
                    </template>

                    <template v-if="p.tipo == 4">
                      <span>INE</span>
                    </template>

                    <template v-if="p.tipo == 5">
                      <span>ACTA NACIMIENTO</span>
                    </template>

                    <template v-if="p.tipo == 6">
                      <span>COMPROBANTE DOMICILIO</span>
                    </template>

                    <template v-if="p.tipo == 7">
                      <span>ANTECEDENTES NO PENALES</span>
                    </template>

                  </a>
                  <button class="btn btn-outline-dark" @click="eliminar(p.archivo)"><i class="fas fa-trash"></i></button>
                  </li>
                </ul>

              </nav>

            </div>
            <div class="modal-footer">

              <button type="button" class="btn btn-outline-dark" @click="cerrarModalVer()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal-->

    </div>
  </main>
</template>
<script>
var config = require('../../Herramientas/config-vuetables-client').call(this);
import Utilerias from '../../Herramientas/utilerias.js';

export default {
  data(){
    return {
      empleado : '',
      total_paginas: [],
      page: 1,
      total: 0,
      empleados: [],
      total_registros : 0,
      modalver : 0,
      nombre_archivo  : '',
      data : [],
      data_empleado : '',
    }
  },
  methods : {
    /**
    **
    **/
    buscarEmpleado(){
      let me = this;
      me.empleados = [];
      this.isLoading = true;
      axios.get('/empleados/documentos/buscar', {
        params: {
          page: this.page,
          nombre : this.empleado
        }
      }).then(response => {
        me.empleados = response.data.empleados.data;
        me.total = response.data.empleados.last_page;
        me.total_registros = response.data.empleados.total;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });
    },

    /**  CargarPagina(){

  },
  **
  **/
  cargarPagina() {
    let me = this;
    this.isLoading = true;
    axios.get('/empleados/documentos', {
      params: {
        page: this.page
      }
    }).then(response => {
      me.empleados = response.data.empleados.data;
      me.total = response.data.empleados.last_page;
      me.total_registros = response.data.empleados.total;
      me.isLoading = false;
    })
    .catch(function (error) {
      console.log(error);
    });
  },

  anterior() {
    this.page = this.page == 1 ? 1 : this.page-1;
    this.cargarPagina();
  },
  siguiente() {
    this.page = this.page == this.total ? this.page : this.page+1;
    this.cargarPagina();
  },

  nss(data){
    const inputValue = data.nss_imss;
    Swal.fire({
      title: 'NSS',
      input: 'text',
      inputValue: inputValue,
      showCancelButton: true,

    }).then(result => {
      if (result.value) {
        // console.log(result);
        axios.post('guardar/clave/nss',{
          id  : data.id,
          nss : result.value,
        }).then(result => {
          toastr.success('Correctamente!!!');
          this.cargarPagina();

        }).catch(e => {
          console.error(e);
        })
      }
    });
  },

  curp(data){
    const inputValue = data.curp;

    Swal.fire({
      title: 'CURP',
      input: 'text',
      inputValue: inputValue,
      showCancelButton: true,

    }).then(result => {
      if (result.value) {
        // console.log(result);
        axios.post('guardar/clave/curp',{
          id  : data.id,
          curp : result.value,
        }).then(result => {
          toastr.success('Correctamente!!!');
          this.cargarPagina();

        }).catch(e => {
          console.error(e);
        })
      }
    });
  },

  rfc(data){
    const inputValue = data.rfc;

    Swal.fire({
      title: 'RFC',
      input: 'text',
      inputValue: inputValue,
      showCancelButton: true,

    }).then(result => {
      if (result.value) {
        // console.log(result);
        axios.post('guardar/clave/rfc',{
          id  : data.id,
          rfc : result.value,
        }).then(result => {
          toastr.success('Correctamente!!!');
          this.cargarPagina();

        }).catch(e => {
          console.error(e);
        })
      }
    });
  },

  ci(data){
    var inputValue = '';
    if (data.clave_ine == null) {
      inputValue = '';
    }else {
      inputValue = data.clave_ine;
    }


    Swal.fire({
      title: 'Clave INE',
      input: 'text',
      inputValue: inputValue,
      showCancelButton: true,

    }).then(result => {
      if (result.value) {
        // console.log(result);
        axios.post('guardar/clave/ine',{
          id  : data.id,
          claveine : result.value,
        }).then(result => {
          toastr.success('Correctamente!!!');
          this.cargarPagina();

        }).catch(e => {
          console.error(e);
        })
      }
    });

  },

  nc(data){
    var inputValue = '';
    if (data.numero_credencial == null) {
      inputValue = '';
    }else {
      inputValue = data.numero_credencial;
    }

    Swal.fire({
      title: 'Numero Credencial',
      input: 'text',
      inputValue: inputValue,
      showCancelButton: true,

    }).then(result => {
      if (result.value) {
        // console.log(result);
        axios.post('guardar/clave/nc',{
          id  : data.id,
          numero_credencial : result.value,
        }).then(result => {
          toastr.success('Correctamente!!!');
          this.cargarPagina();

        }).catch(e => {
          console.error(e);
        })
      }
    });

  },

  guardar(data){
    Swal.fire({
      title: 'Subir Documentos',
      html:
      '<select class="swal2-input" id="swal-input1"><option value="1">NSS</option><option value="2">RFC</option><option value="3">CURP</option><option value="4">INE</option><option value="5">ACTA NACIMIENTO</option><option value="6">COMPROBANTE DOMICILIO</option><option value="7">ANTECEDENTES NO PENALES</option></select>' +
      '<input type="file" id="swal-input2" class="swal2-input">',
      focusConfirm: false,
      showCancelButton: true,
      preConfirm: () => {
        return [
          document.getElementById('swal-input1').value,
          document.getElementById('swal-input2')
        ]
      }
    }).then(result => {
      if (result.value) {
        let formData = new FormData();
        formData.append('empleado',data.id);
        formData.append('tipo',result.value[0]);
        formData.append('archivo',result.value[1].files[0]);

        axios({
          method: 'POST',
          url: 'guardar/documentos',
          data : formData
        }).then(response => {
          toastr.success("Correcto!!!");
        });

      }
      console.log(result);
    });
  },

  descargar(data){
    this.data_empleado = data;
    this.modalver = 1;
    axios.get('documentos/empleados/' + data.id).then(response => {
      console.log(response);
      this.data = response.data;
    }).catch(e => {
      console.error(e);
    });
  },

  cerrarModalVer(){
    this.modalver = 0;
  },

  descargarf(archivo){
    let me=this;
    axios({
      url: '/descargar/fotos/' + archivo,
      method: 'GET',
      responseType: 'blob', // importante
    }).then((response) => {
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', archivo); //archivo = nombre del archivo alojado en el ftp
      document.body.appendChild(link);
      link.click();
      //--Llama el metodo para borrar el archivo local una ves descargado--//
      axios.get('/descargar/fotos/editar/' + archivo)
      .then(response => {
      })
      .catch(function (error) {
        console.log(error);
      });
      //--fin del metodo borrar--//
    });
  },

  eliminar(archivo){
    axios.get('eliminar/documento/' + archivo).then(response => {
      this.descargar(this.data_empleado);
      toastr.success('Eliminado Correctamente');
    }).catch(e => {
      console.error(e);
    });
  },

  /**
  **
  **/
},
mounted () {
  this.cargarPagina();
}
}
</script>

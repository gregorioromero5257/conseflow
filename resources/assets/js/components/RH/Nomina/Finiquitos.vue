<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <br>
      <div class="card" v-show="!detalle && !detalleFiniquito">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Finiquitos
        </div>
        <div class="card-body">
          <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
            <template slot="id" slot-scope="props">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                      <div class="btn-group dropup" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-grip-horizontal"></i> Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> 
              <button type="button" @click="cargarFiniquito(props.row)" class="dropdown-item">
                <i class="fas fa-eye"></i>&nbsp; Ver Finiquitos
              </button>
                  </div>
                </div>
              </div>
            </template>
          </v-client-table>
        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
      <!-- Listado de finiquitos del empleado -->
      <br>
      <div class="card" v-show="detalle && !detalleFiniquito">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Finiquitos pertenecientes a:  {{ empleado == null ? '': empleado.nombre + ' ' + empleado.ap_paterno + ' ' + empleado.ap_materno }}
          <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
            <i class="fa fa-arrow-left"></i>&nbsp;Atras
          </button>
        </div>
        <div class="card-body">
          <vue-element-loading :active="isLoadingDetalle"/>
          <v-client-table :columns="columnsFiniquito" :data="tableDataFiniquito" :options="optionsFiniquito" ref="myTableFiniquito">
                <template slot="id" slot-scope="props">
                  <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                      <div class="btn-group dropup" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-grip-horizontal"></i> Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> 
                    <button v-if="(props.row.formato == null)" type="button" @click="descargar(props.row.id)" class="dropdown-item">
                    <i class="icon-cloud-download"></i>&nbsp; Descargar
                    </button>

                    <button v-if="(validador == 1 && props.row.formato == null)" type="button" @click="subirformato(props.row.id)" :class="ClassF">
                    <i class="fas fa-cloud-upload-alt"></i>&nbsp;{{BtnF}}
                    </button>

                    <button v-if="(props.row.formato != null)" type="button" @click="descargarFormato(props.row.formato)" :class="ClassF2">
                    <i class="fas fa-cloud-download-alt"></i>&nbsp;{{BtnF2}}
                    </button>
                        </div>
                      </div> 
                  </div>
                </template>
          </v-client-table>
        </div>
      </div>
      <!-- Fin Listado de finiquitos del empleado -->
      </div>
  </main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  components: {
  },
  data (){
    return {
      url: '/finiquito',
      ClassF: 'btn btn-success btn-sm',
      ClassF2: 'btn btn-info btn-sm',
      BtnF2:'',
      BtnF: '',
      validador: 0,
      empleado: null,
      detalle: false,
      detalleFiniquito: false,
      listaEmpleados: [],
      listaFiniquito: [],
      isLoading: false,
      isLoadingDetalle: false,
      columns: [
        'id',
        'nombre',
        'ap_paterno',
        'ap_materno',
        'puesto',
        'departamento',
      ],
      tableData: [],
      columnsFiniquito: [
        'id',
        'empresa',
        'fecha_inicial',
        'fecha_final'
      ],
      tableDataFiniquito: [],
      options: {
        headings: {
          'id': 'Acciones',
          'nombre': 'Nombre',
          'ap_paterno': 'Apellido Paterno',
          'ap_materno': 'Apellido Materno',
          'puesto': 'Puesto',
          'departamento': 'Departamento',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['nombre', 'ap_paterno', 'ap_materno', 'puesto', 'departamento'],
        filterable: ['nombre', 'ap_paterno', 'ap_materno', 'puesto', 'departamento'],
        filterByColumn: true,
        texts:config.texts
      },
      optionsFiniquito: {
        headings: {
          'id': 'Acciones',
          'empresa': 'Empresa',
          'fecha_inicial': 'Fecha inicial',
          'fecha_final': 'Fecha final'
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['empresa', 'fecha_inicial', 'fecha_final' ],
        filterable: ['empresa', 'fecha_inicial', 'fecha_final'],
        filterByColumn: true,
        listColumns: {
          condicion : config.columnCondicion
        },
        texts:config.texts
      },
    }
  },
  computed:{
  },
  methods : {
    getData() {
      let me=this;
      axios.get('/empleadosfiniquitos').then(response => {
        me.tableData = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    descargar(data) {
      var id= data;
      window.open('finiquitos/' + id, '_blank');
      this.validador = 1;
    },
    descargarFormato(archivo){
      let me=this;
      me.ClassF2 = "btn btn-warning btn-sm";
      me.BtnF2 = "Descargando Archivo";
      axios({
        url: me.url+ '/' + archivo,
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
        axios.get(me.url + '/' + archivo + '/edit')
        .then(response => {
          me.ClassF2 = "btn btn-info btn-sm";
          me.BtnF2 = "";
        })
        .catch(function (error) {
          console.log(error);
        });
        //--fin del metodo borrar--//
      });
    },
    subirformato(id){
      swal({
        title: 'Formato de Renuncia',
        input: 'file',
        inputAttributes: {
          'accept': 'application/pdf',
          'aria-label': 'Upload your profile picture'
        },
        confirmButtonText: 'Enviar',
        showCancelButton: true,
        inputValidator: (file) => {
          return !file && 'Este Campo no puede estar Vacío'
        }
      }).then((file) => {
        let me = this;
        if(file.value) {
          this.ClassF = "btn btn-warning btn-sm";
          this.BtnF = "Subiendo Archivo";
          let formData = new FormData();

          formData.append('adjunto', file.value);
          formData.append('id',id);

          axios.post(me.url,formData
          ).then(function (response) {
            if (response.data.status) {
              toastr.success('Archivo Subido Correctamente')
              me.cargarFiniquito(me.empleado);
              me.ClassF = "btn btn-success btn-sm";
              me.BtnF = "";
              me.validador = 0;
            }
            else {
              swal({
                type: 'error',
                html: response.data.errors.join('<br>'),
              });
            }
          }).catch(function (error) {
            console.log(error);
          });
        }
        else if (file.dismiss === swal.DismissReason.cancel) {
        }
      })
    },
    cargarFiniquito(dataEmpleado = []) {
        this.detalle = true;
        this.isLoadingDetalle = true;
        let me=this;
        this.empleado = dataEmpleado;

        axios.get('/finiquitoporempleado/' + dataEmpleado.id + '/' ).then(response => {
        me.tableDataFiniquito = response.data;
        me.isLoadingDetalle = false;
        })
        .catch(function (error) {
        console.log(error);
        });
    },
    maestro(){
        this.$refs.myTableFiniquito.setFilter({
        'empresa': '', 'fecha_inicial': '', 'fecha_final': '',
        });
        this.detalle = false;
    },
    maestroContratos(){
        this.detalle = true;
        this.detalleFiniquito = false;
    },
},
mounted() {
  this.getData();
}
}
</script>

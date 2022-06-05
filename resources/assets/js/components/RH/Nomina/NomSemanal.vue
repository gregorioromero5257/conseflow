<template>
  <main class="main">
    <div class="container-fluid">
      <div class="card">
        <!-- Inicio card principal -->
        <div class="card-header">

          <template v-if="!detalle">
            <i class="fa fa-align-justify"></i> Nómina Semanal
            <button type="button" name="calcular" class="btn btn btn-outline-dark float-sm-right" @click="calcularnomina()">
              <i class="fas fa-laptop-code"></i>&nbsp;Crear Nómina</button><br>
            </template>

            <template v-if="detalle">
              <i class="fa fa-align-justify"></i> Nómina Semanal del {{data.periodo}}
              <button type="button" @click="cerrar()" class="btn btn-secondary float-sm-right">
                <i class="fa fa-arrow-left"></i>&nbsp;Atras
              </button>
            </template>

          </div>
          <div class="card-body">
            <div v-show="!detalle">
              <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTableIni">
                <template slot="id" slot-scope="props">
                  <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group dropup" role="group">
                      <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-grip-horizontal"></i> Acciones
                      </button>
                      <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <button type="button" @click="cargarNomina(props.row)" class="dropdown-item">
                          <i class="fas fa-eye"></i>&nbsp; Detalles
                        </button>
                        <button class="dropdown-item" @click="descargar(props.row, 1)" >
                          <i class="fas fa-file-excel"></i>&nbsp;Reporte General Excel</button>
                        </div>
                      </div>
                    </div>
                  </template>
                  <template slot="empresa" slot-scope="props">
                    <template v-if="props.row.empresa == 1">
                      CONSERFLOW
                    </template>
                    <template v-if="props.row.empresa == 3">
                      CSCT
                    </template>
                  </template>
                </v-client-table>
              </div>
              <div v-show="detalle">
                <!--  -->
                <div class="container" style="overflow:auto;">
                  <table class="table" style="overflow: auto;white-space: nowrap;">
                    <tr>
                      <!-- <th><b>#</b></th> -->
                      <th class="text-center" style="width:400px;"><b>NOMBRE</b></th>
                      <th><b>&nbsp;</b></th>
                      <th><b>SD</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                      <th><b>SALARIO SEMANAL</b></th>
                      <th><b>DIAS <br> LABORADOS</b></th>
                      <th><b>DESCTOS.</b>&nbsp;&nbsp;&nbsp;&nbsp;</th>
                      <th><b>INFONAVIT</b>&nbsp;&nbsp;&nbsp;&nbsp;</th>
                      <th><b>NOMINA</b></th>
                      <th><b>VIATICOS ALIMENTOS</b></th>
                      <th><b>TOTAL A PAGAR</b></th>
                      <th><b>OBSERVACIONES</b></th>
                      <th><b>BANCO</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                      <th><b>TARJETA</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                      <th><b>CUENTA</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                      <th><b>CLABE</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    </tr>
                    <tr v-for="(t, index) in tableDataNomina" >
                      <!-- <td>{{index + 1}}</td> -->
                      <td style="position:absolute;left:0px;background-color:white;width:300px;margin-top:-1px;">{{t['data'].nombre}}</td>
                      <td>{{t['data'].nombre_puesto}}</td>
                      <td>
                        <input class="form-control" type="text" @keyup.enter="savesd($event, t['data'])" :value="t['data'].sueldo_diario" >
                      </td>
                      <td>{{Math.round(t['data'].sueldo_diario * 7)}}</td>
                      <td>
                        <input class="form-control" type="text" @keyup.enter="savedt($event, t['data'])" :value="t['data'].dias_trabajados" >
                      </td>
                      <td>
                        <input class="form-control" type="text" @keyup.enter="saved($event, t['data'])" :value="t['data'].descuento">
                      </td>
                      <td>
                        <input class="form-control" type="text" @keyup.enter="savei($event, t['data'])" :value="t['data'].infonavit">
                      </td>
                      <td>
                        {{total_nomina(t['data'])}}
                      </td>
                      <td>
                        <input class="form-control" type="text" @keyup.enter="saveva($event, t['data'])" :value="t['data'].viaticos_alimentos">
                      </td>
                      <td>
                        <input class="form-control" type="text" @keyup.enter="savet($event, t['data'])" :value="totales(t['data'])">
                      </td>
                      <td></td>
                      <td>
                        <template v-if="t['datos_bancarios'] == ''">
                          <button  class="btn btn-outline-success" @click="AgregarBanco(t['data'])">AGREGAR</button>
                        </template>
                        <template v-if="t['datos_bancarios'] != ''">
                          <select class="form-control" :value="t['data'].banco_id" @change="CambiarBanco($event, t['data'])">
                            <option v-for="b in t['datos_bancarios']" :value="b.id">{{b.nombre}}</option>
                            <option value="0">AGREGAR</option>
                          </select>
                        </template>
                        <!-- {{t['datos_bancarios'] == null ? '' : t['datos_bancarios'].nombre}} -->
                      </td>
                      <td>
                        {{t['banco'] == null ? '' : t['banco'].numero_tarjeta}}<br>
                      </td>
                      <td>
                        {{t['banco'] == null ? '' : t['banco'].numero_cuenta}}
                      </td>
                      <td>
                        {{t['banco'] == null ? '' : t['banco'].clabe}}
                      </td>
                    </tr>
                  </table>

                </div>
                <!-- <div v-show="ver = 1">
                {{total_nomina}}
              </div> -->

              <!--  -->
              <!-- <v-client-table :columns="columnsNomina" :data="tableDataNomina" :options="optionsNomina" ref="myTable">
              <div slot="contratos.dias_trabajados" slot-scope="{row}">
              <span @click="setEditing(row)" v-if="!update">
              <button class="btn btn-primary">{{row.contratos.dias_trabajados}}</button>
            </span>
            <span v-else >
            <input class="form-control" v-model="row.contratos.dias_trabajados" type="number">
            <button type="button" class="btn btn-info btn-xs" @click="updates(row)">Guardar</button>
            <button type="button" class="btn btn-default btn-xs" @click="revertValue()">Cancelar</button>
          </span>
        </div>
      </v-client-table> -->
    </div>
  </div>
</div>
<!-- Fin card principal -->

<!-- Inicon modal Calcular nomina -->
<div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
  <vue-element-loading :active="isLoading"/>
  <div class="modal-dialog modal-dark modal-lg" role="document">
    <div class="modal-content">
      <div>

        <div class="modal-header">
          <h4 class="modal-title">Calcular nómina</h4>
          <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <label class="col-md-3 form-control-label" for="fecha_inicial">Fecha Inicial</label>
            <div class="col-md-9">
              <input type="date" name="fecha_inicial" v-model="nominas.fecha_inicial" class="form-control" placeholder="Fecha inicio" @change="calcularperiodo" autocomplete="off" data-vv-as="Fecha inicial">
              <span class="text-danger">{{ errors.first('fecha_inicial') }}</span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-3 form-control-label" for="fecha_final">Fecha Fin</label>
            <div class="col-md-9">
              <input type="date" name="fecha_final" v-model="nominas.fecha_final" class="form-control" placeholder="Fecha fin" @change="calcularperiodo" autocomplete="off" data-vv-as="Fecha Final">
              <span class="text-danger">{{ errors.first('fecha_final ') }}</span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-3 form-control-label" for="periodo">Empresa</label>
            <div class="col-md-9">
              <select class="form-control" v-model="nominas.empresa" data-vv-name="Empresa" >
                <option value="3">CSCT</option>
                <option value="1">CONSERFLOW</option>
              </select>
              <span class="text-danger">{{ errors.first('Empresa') }}</span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-3 form-control-label" for="periodo">Periodo</label>
            <div class="col-md-9">
              <input type="text" name="periodo" v-model="nominas.periodo"
              class="form-control" placeholder="Periodo"  autocomplete="off" data-vv-as="Periodo" >
            </div>
          </div>


          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-window-close"></i>&nbsp;Cerrar</button>
            <button type="button"  class="btn btn-secondary" @click="guardarNominas(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <!-- v-if="tipoAccion==1" -->
            <!-- <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="guardarTipoContrato(0)">Actualizar</button> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Fin modal calcular nomina  -->

</div>
</main>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);

export default {
  data (){
    return {
      ver : 0,
      headings_asistencia: ["Empleado"],
      update : false,
      url: '/nominasemanal',
      isLoading: false,
      modal : 0,
      detalle : false,
      data : null,
      texto : '',
      nominas : {
        id : '',
        fecha_final : '',
        fecha_inicial : '',
        periodo : '',
        num_dias_trabajado : 0,
        tipo_nomina_id: 0,
        empresa : '',
      },
      columns: ['id','fecha_inicial','fecha_final','periodo','empresa'],
      columnsNomina: ['empleado.nombre','empleado.ap_paterno','empleado.ap_materno','puesto.nombre','contratos.dias_trabajados','contratos.nombre_corto'],
      tableData: [],
      tableDataNomina : [],
      options: {
        headings: {
          id : 'Acciones',
        },
        perPage: 5,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['fecha_inicial','fecha_final','periodo'],
        filterable: ['fecha_inicial','fecha_final','periodo'],
        filterByColumn: true,
        texts:config.texts
      },
      optionsNomina: {
        headings: {
          'contratos.id' : 'Acciones',
          'empleado.nombre' : 'Nombre',
          'empleado.ap_paterno' : 'Apellido Paterno',
          'empleado.ap_materno' : 'Apellido Materno',
          'puesto.nombre' : 'Puesto',
          'contratos.dias_trabajados' : 'Dias trabajados',
          'contratos.nombre_corto' : 'Proyecto'
        },
        perPage: 20,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['empleado.nombre','empleado.ap_paterno','empleado.ap_materno','puesto.nombre','contratos.dias_trabajados','contratos.nombre_corto'],
        filterable: ['empleado.nombre','empleado.ap_paterno','empleado.ap_materno','puesto.nombre','contratos.dias_trabajados','contratos.nombre_corto'],
        filterByColumn: true,
        texts:config.texts
      },
    }
  },
  computed : {
    // total_nomina(){
    //   return 100;
    // }
  },
  methods : {
    /**
    *
    */
    total_nomina(data){
      return Math.round( (data.sueldo_diario * data.dias_trabajados) - data.descuento - data.infonavit);
    },

    /**
    *
    **/
    totales(data){
      var sd = data.sueldo_diario == null ? 0 : parseFloat(data.sueldo_diario);
      var dt = data.dias_trabajados == null ? 0 : parseFloat(data.dias_trabajados);
      var d = data.descuento == null ? 0 : parseFloat(data.descuento);
      var i = data.infonavit == null ? 0 : parseFloat(data.infonavit);
      var va = data.viaticos_alimentos == null ? 0 : parseFloat(data.viaticos_alimentos);

      return Math.round( (((sd * dt) - d) - i) + va);
    },
    /**
    *
    **/
    savesd(e, data){
      axios.post('guardar/sd/nomina/semanal',{
        id : data.id,
        total : e.target.value,
      }).then(response => {
        this.cargarNomina(this.data);
      }).catch(e => {
        console.error(e);
      });
    },

    savedt(e, data){
      axios.post('guardar/dias/trabajados/nomina/semanal',{
        id : data.id,
        total : e.target.value,
      }).then(response => {
        this.cargarNomina(this.data);
      }).catch(e => {
        console.error(e);
      });
    },

    saved(e, data){
      axios.post('guardar/descuento/nomina/semanal',{
        id : data.id,
        total : e.target.value,
      }).then(response => {
        this.cargarNomina(this.data);
      }).catch(e => {
        console.error(e);
      });
    },

    savei(e, data){
      axios.post('guardar/infonavit/nomina/semanal',{
        id : data.id,
        total : e.target.value,
      }).then(response => {
        this.cargarNomina(this.data);
      }).catch(e => {
        console.error(e);
      });
    },

    saveva(e, data){
      axios.post('guardar/viatico/alimentos/nomina/semanal',{
        id : data.id,
        total : e.target.value,
      }).then(response => {
        this.cargarNomina(this.data);
      }).catch(e => {
        console.error(e);
      });
    },

    savet(e, data){
      axios.post('guardar/total/nomina/semanal',{
        id : data.id,
        total : e.target.value,
      }).then(response => {
        this.cargarNomina(this.data);
      }).catch(e => {
        console.error(e);
      });
    },

    saveb(e, data){
      console.log(e);
      axios.post('guardar/banco/nomina/semanal',{
        id : data.id,
        total : e.target.tabIndex,
      }).then(response => {
        this.cargarNomina(this.data);
      }).catch(e => {
        console.error(e);
      });
    },

    /**
    *
    **/
    getData() {
      let me=this;
      axios.get('/nominasemanal').then(response => {
        me.tableData = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
      axios.get('/proyecto').then(response => {
        me.listaProyectos = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });

      axios.get('bancos').then(response => {
        // bancos = response.data;
        console.log(response.data.length);
        response.data.forEach((item, i) => {
          this.texto+= '<option value="'+item.id+'">'+item.nombre+'</option>';
        });

      }).catch(e => {
        console.error(e);
      });

    },

    /**
    *
    **/
    calcularnomina(){
      this.modal = 1;
    },

    // CerraModal NOMINA
    cerrarModal(){
      this.modal = 0;
    },
    // Fin cerrar modal NOMINA

    /**
    * Inicio calcular periodo
    **/
    calcularperiodo(){
      var fecha_inicio = this.nominas.fecha_inicial;
      var fecha_final = this.nominas.fecha_final;
      var mesi = fecha_inicio.substr(5,2);
      var mesf = fecha_final.substr(5,2);
      var diai = fecha_inicio.substr(8,2);
      var diaf = fecha_final.substr(8,2);
      var anio = fecha_final.substr(0,4);
      var periodo = '';
      var mesnombre = '';
      /*validando que el dia de la fecha inicial sea lunes*/
      var lunes = fecha_inicio;
      let datel = new Date(lunes.replace(/-+/g, '/'));
      var dialunes = datel.getDay();
      if (dialunes != 1) {
        swal('Error!','La semana debe comenzar en Lunes !','warning')
      }
      /*fin validando que el dia de la fecha inicial sea lunes*/
      if (fecha_final != '') {
        /*validando que el dia de la fecha final sea Domingo*/
        var domingo = fecha_final;
        let dated = new Date(domingo.replace(/-+/g, '/'));
        var diadomingo = dated.getDay();
        if (diadomingo != 0) {
          swal('Error!','La semana debe terminar en Domingo !','warning')
          if ((diferencia+1) < 7) {
            swal('Error!','No se pueden seleccionar menos dias correspondientes a una semana "7"!','warning')
          }
        }
        /*fin validando que el dia de la fecha final sea Domingo*/
        var fechaIngreso = new Date(fecha_inicio).getTime();//obtener el numero de dias de una fecha inicial a una final
        var fechaFin    = new Date(fecha_final).getTime();//obtener el numero de dias de una fecha inicial a una final
        var diff = fechaFin - fechaIngreso;//obtener el numero de dias de una fecha inicial a una final
        var diferencia =diff/(1000*60*60*24);//obtener el numero de dias de una fecha inicial a una final
        /*Calculando el numero de semana*/
        Date.prototype.getWeekNumber = function () {
          var d = new Date(+this);  //Creamos un nuevo Date con la fecha de "this".
          d.setHours(0, 0, 0, 0);   //Nos aseguramos de limpiar la hora.
          d.setDate(d.getDate() + 4 - (d.getDay() || 7)); // Recorremos los días para asegurarnos de estar "dentro de la semana"
          //Finalmente, calculamos redondeando y ajustando por la naturaleza de los números en JS:
          return Math.ceil((((d - new Date(d.getFullYear(), 0, 1)) / 8.64e7) + 1) / 7);
        };
        var numerodesemana = new Date(anio, (mesi-1), diai).getWeekNumber();
        /*fin Calculando el numero de semana*/

        if ((diferencia+1) > 7) {
          swal('Error!','No se pueden seleccionar mas dias correspondientes a una semana "7"!','warning')
        }
        if ((diferencia+1) == 7) {
        }
        periodo = 'Semana '+numerodesemana+ ' del ' + anio ;
        this.nominas.periodo = periodo;
        this.nominas.num_dias_trabajado = diferencia + 1;
      }
    },
    // Fin calcular periodo/

    //inicio descarga
    descargar(data = [], numero) {
      //  console.log(data);
      if (numero == 1) {
        window.open('descargar-excel-semanal/'+data.id, '_blank');
        let me = this;
        me.getData();
      }
    },
    //fin descarga

    //
    cargarNomina(data){
      this.detalle = true;
      this.isLoading = true;
      this.data = data;
      console.log(data);
      axios.get('/nominasemanal/busqueda_por_proyecto/'+data.id).then(response => {
        console.log(response.data,'k');
        this.tableDataNomina = response.data;
        this.isLoading = false;
      }).catch(function (error){
        console.log(error);
      });
    },
    //

    //
    cerrar(){
      this.detalle = false;
    },
    //

    //
    setEditing(data){
      // this.tabla.diast = data.contratos.dias_trabajados;
      this.update = true;
    },
    //

    //
    updates(row) {
      console.log(row);
      axios.post('guardar/cambiodia/',
      {
        id : row.contratos.id,
        diast : row.contratos.dias_trabajados
      }).then(response => {
        this.cargarNomina(this.data);
      }).catch(e => {
        console.error(e);
      });
    },
    //

    //
    revertValue() {
      this.update = false;
      this.cargarNomina(this.data);
    },
    //

    //
    guardarNominas(nuevo) {
      // this.isLoading = true;
      let me = this;
      axios({
        method: nuevo ? 'POST' : 'PUT',
        url: nuevo ? me.url: '/nominasemanal/'+this.tipoContrato.empleado_id,
        data: {
          'fecha_inicial':this.nominas.fecha_inicial,
          'fecha_final':this.nominas.fecha_final,
          'periodo':this.nominas.periodo,
          'dias_num' : this.nominas.num_dias_trabajado,
          'empresa' : this.nominas.empresa,
        }
      }).then(function (response) {


        // me.isLoading = false;
        me.cerrarModal();
        toastr.success('Correcto !!!');
        me.getData();
        // if (response.data.status) {
        //   if(!nuevo){ toastr.success('Nómina Actualizado Correctamente');  }
        //   else  { toastr.success('Nómina Registrada Correctamente');     }
        // } else {
        //   swal({type: 'error', html: response.data.errors.join('<br>'), });
        // }
      }).catch(function (error) {
        console.log(error);
      });
    },
    //

    CambiarBanco(e, data){
      console.log(e);
      console.log(data);
      if (e.target.value == 0) {
        // axios.post()
        //abrir sweet alert para cargar los campos nuevos
        // var bancos = [];

        console.log(this.texto);
       Swal.fire({
          title: 'AGREGAR BANCO',
          html:
          '<select id="swal-input4" class="swal2-input">'+this.texto+'</select>' +
          '<input id="swal-input1" class="swal2-input" placeholder="TARJETA">' +
          '<input id="swal-input2" class="swal2-input" placeholder="NÚMERO CUENTA">' +
          '<input id="swal-input3" class="swal2-input" placeholder="CLABE">'
           ,
          focusConfirm: false,
          preConfirm: () => {
            return [
              document.getElementById('swal-input1').value,
              document.getElementById('swal-input2').value,
              document.getElementById('swal-input3').value,
              document.getElementById('swal-input4').value
            ]
          }
        }).then(result => {
          if (result.value) {
            axios.post('guardar/nuevo/banco/nomina/semanal',{
              id : data.id,
              banco : result.value[3],
              tarjeta : result.value[0],
              numero : result.value[1],
              clabe : result.value[2],
            }).then(response => {
              this.cargarNomina(this.data);
            }).catch(e => {
              console.error(e);
            });
          }
          console.log(result);
        });
      }else if (e.target.value != 0) {
        axios.post('guardar/banco/nomina/semanal',{
          id : data.id,
          banco : e.target.value,
        }).then(response => {
          this.cargarNomina(this.data);
        }).catch(e => {
          console.error(e);
        });
      }
    },

    AgregarBanco(data){
      Swal.fire({
         title: 'AGREGAR BANCO',
         html:
         '<select id="swal-input4" class="swal2-input">'+this.texto+'</select>' +
         '<input id="swal-input1" class="swal2-input" placeholder="TARJETA">' +
         '<input id="swal-input2" class="swal2-input" placeholder="NÚMERO CUENTA">' +
         '<input id="swal-input3" class="swal2-input" placeholder="CLABE">'
          ,
         focusConfirm: false,
         preConfirm: () => {
           return [
             document.getElementById('swal-input1').value,
             document.getElementById('swal-input2').value,
             document.getElementById('swal-input3').value,
             document.getElementById('swal-input4').value
           ]
         }
       }).then(result => {
         if (result.value) {
           axios.post('agregar/nuevo/banco/nomina/semanal',{
             id : data.id,
             banco : result.value[3],
             tarjeta : result.value[0],
             numero : result.value[1],
             clabe : result.value[2],
           }).then(response => {
             this.cargarNomina(this.data);
           }).catch(e => {
             console.error(e);
           });
         }
       });
    },


  },
  mounted() {
    this.getData();
    // this.getLista();
  }
}
</script>

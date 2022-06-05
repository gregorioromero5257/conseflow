<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->

      <br>
      <div class="card" v-show="!detalle">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Nómina Quincenal {{inicialp == null ? '' : inicialp.periodo}}  {{data == null ? '' : data.nombre}}
          <div v-show="condicionuno == 2">
            <button type="button" @click="maestrodos()" class="btn btn-secondary float-sm-right">
              <i class="fa fa-arrow-left"></i>&nbsp;Atras
            </button>
          </div>
          <div v-show="condicionuno == 1">
            <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
              <i class="fa fa-arrow-left"></i>&nbsp;Atras
            </button>
          </div>
        </div>
        <div class="card-body">
          <!-- Inicio de calculo de nomina por fecha -->
          <div v-show="condicionuno == 3">
            <button type="button" name="calcular" class="btn btn-outline-dark float-sm-right" @click="calcularnomina()"><i class="fas fa-laptop-code"></i>&nbsp;Crear Nómina</button><br>
            <v-client-table :columns="columnsinicial" :data="tableDatainicial" :options="optionsinicial" ref="myTableIni">
              <template slot="id" slot-scope="props">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                  <div class="btn-group dropup" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <button type="button" @click="cargarNomina(props.row)" class="dropdown-item">
                        <i class="fas fa-eye"></i>&nbsp; Detalles
                      </button>
                      <button type="button" class="dropdown-item" @click="descargar(props.row, 1)">
                        <i class="fas fa-file-excel"></i>&nbsp;Reporte General Excel</button>
                          </div>
                        </div>
                      </div>
                    </template>
                    <template slot="empresa" slot-scope="props">
                      <template v-if="props.row.empresa == 2">
                        CONSERFLOW
                      </template>
                      <template v-if="props.row.empresa == 4">
                        CSCT
                      </template>
                    </template>
                  </v-client-table>
                </div>
                <!-- Fin de calculo de nomina por fecha -->


                <div v-show="condicionuno == 1">
                  <!-- <v-client-table :columns="columnsp" :data="tableDatap" :options="optionsp" ref="myTableAp">
                    <template slot="id" slot-scope="props">
                      <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <div class="btn-group dropup" role="group">
                          <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                          </button>
                          <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <button type="button" @click="cargarNominaProyecto(props.row)" class="dropdown-item">
                              <i class="fas fa-eye"></i>&nbsp; Ver empleados
                            </button>
                          </div>
                        </div>
                      </div>
                    </template>
                  </v-client-table> -->
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
                        <td style="color:white">{{t['data'].nombre_puesto}}</td>
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
                </div>

                <!-- fin Modal Editar nomina -->
                <!-- Inicon modal Calcular nomina -->
                <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal_n}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                              <input type="date" name="fecha_inicial" v-model="nominas.fecha_inicial" class="form-control" placeholder="Fecha inicio"  autocomplete="off" data-vv-as="Fecha_inicial">
                              <span class="text-danger">{{ errors.first('fecha_inicial') }}</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="fecha_final">Fecha Fin</label>
                            <div class="col-md-9">
                              <input type="date" name="fecha_final"  v-model="nominas.fecha_final" class="form-control" placeholder="Fecha fin"  autocomplete="off" data-vv-as="Fecha_final">
                              <span class="text-danger">{{ errors.first('fecha_final') }}</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="periodo">Empresa</label>
                            <div class="col-md-9">
                              <select class="form-control" v-model="nominas.empresa" data-vv-name="Empresa" >
                                <option value="4">CSCT</option>
                                <option value="2">CONSERFLOW</option>
                              </select>
                              <span class="text-danger">{{ errors.first('Empresa') }}</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="periodo">Periodo</label>
                            <div class="col-md-9">
                              <input type="text" name="periodo"  v-model="nominas.periodo" class="form-control" placeholder="Ingrese periodo"  autocomplete="off" data-vv-as="Periodo" >
                              <span class="text-danger">{{ errors.first('periodo') }}</span>
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
            </div>
            <div class="card" v-show="detalle">
              <div class="card-header">
                <i class="fa fa-align-justify"></i>
                Nómina Quincenal pertenecientes a:{{ empleado == null ? '': ' '+empleado.empleado.nombre + ' ' + empleado.empleado.ap_paterno + ' ' + empleado.empleado.ap_materno }}

                <button type="button" @click="maestrouno()" class="btn btn-secondary float-sm-right">
                  <i class="fa fa-arrow-left"></i>&nbsp;Atras
                </button>
              </div>

              <div class="card-body">

                <vue-element-loading :active="isLoadingdos"/>
                <table class="table table-bordered table-sm">
                  <tr>
                    <td class="table-active">Nombre</td>
                    <td colspan="3">
                      {{ empleado == null ? '': ' '+empleado.empleado.nombre + ' ' + empleado.empleado.ap_paterno + ' ' + empleado.empleado.ap_materno }}
                    </td>
                  </tr>
                  <tr>
                    <td class="table-active">Puesto</td>
                    <td>
                      {{ empleado != null ?  empleado.puesto.nombre : ''}}
                    </td>
                    <td class="table-active">Estado</td>
                    <td>
                      <template v-if="empleado!= null &&  empleado.empleado.condicion">
                        <span class="badge badge-success">Activo</span>
                      </template>
                      <template v-else>
                        <span class="badge badge-danger">Dado de Baja</span>
                      </template>
                    </td>
                  </tr>
                  <tr>
                    <td class="table-active">Proyecto</td>
                    <td colspan="3">
                      {{ proyecto != null ?  proyecto.nombre : ''}}
                    </td>
                  </tr>
                  <tr>
                    <td class="table-active">Banco(s)</td>
                    <td colspan="3">  {{ empleado != null ?  empleado.contratos.cbnombre : ''}}</td>
                  </tr>
                  <tr>
                    <td class="table-active">Números de cuenta</td>
                    <td colspan="3">{{ empleado != null ?  empleado.contratos.numero_cuenta : ''}}</td>
                  </tr>
                </table>
                <h3>Contratos</h3>
                <table class="table table-bordered table-sm">
                  <thead>
                    <tr class="table-active">
                      <th style="width:20%"><center>Días Trabajados</center></th>
                      <th style="width:10%"><center>Sueldo Diario </center></th>
                      <th style="width:10%"><center>Sueldo Diario Integrado</center></th>
                      <th style="width:15%"><center>Infonavit</center></th>
                      <th style="width:15%"><center>Transferencia</center></th>
                      <th style="width:10%"><center>Efectivo</center></th>
                      <th style="width:20%"><center>Otros ingresos</center></th>
                      <th style="width:20%"><center>Descuentos</center></th>
                      <th style="width:15%"><center>Total</center></th>

                    </tr>
                  </thead>

                  <tr>
                    <td ><center>
                      {{tabla.diast}}
                    </center></td>
                    <td>
                      {{tabla.sueldo_diario}}
                    </td>
                    <td >
                      {{tabla.sueldo_diario_integral}}
                    </td>
                    <td >
                      {{tabla.infonavit}}
                    </td>
                    <td >
                      {{tabla.transferenciaa}}
                    </td>
                    <td >
                      {{tabla.efectivo}}
                    </td>
                    <td >
                      {{tabla.otro}}
                    </td>
                    <td >
                      {{tabla.descuento}}
                    </td>
                    <td >
                      {{tabla.total}}
                    </td>


                  </tr>
                </table>
              </div>



            </div>
            <!-- Fin ejemplo de tabla Listado -->


          </div>

        </main>
      </template>

      <script>
      import Utilerias from '../../Herramientas/utilerias.js';
      var config = require('../../Herramientas/config-vuetables-client').call(this);

      export default {
        //NOTA muy importante para exportar a excel
        computed:{
          updated: function(){
            return this.exportar(this.$refs.myTableA.allFilteredData);
          },
        },
        data (){
          return {
            data : '',
            url: '/nominaquincenal',
            PermisosCRUD:{},
            s_empleado: null,//p
            empleado: null,
            proyecto : null,
            inicialp : null,
            contratos: [],
            optionsvs: [],
            condicionuno : 3,
            desabilitarboton : 1,
            hiden: 0,
            empleado: null,
            detalle: false,
            nominas : {
              fecha_final : '',
              fecha_inicial : '',
              periodo : '',
              num_dias_trabajado : 0,
              tipo_nomina_id: 0,
              empresa : '',
            },
            tabla :{
              sueldo_diario : 0,
              sueldo_diario_integral :0,
              empleado: '',
              diast : 0,
              infonavit: 0,
              otro :0,
              transferenciaa :0,
              efectivo : 0,
              total : 0,
              descuento :0,
              contrato_id:0,
              banco_id : 0,
              proyecto_id : 0,
              dias_restados : 0,

            },
            tipoContrato: {
              id: 0,
              fecha_ingreso: '',
              fecha_fin: '',
              tipo_nomina_id: 0,
              empleado_id: 0,
              contrato_id:0,
              tipo_ubicacion_id: 0,
              horario_id: 0,
              tipo_contrato_id: 0,
              proyecto_id: 0,
              motivo: '',
            },
            listaEmpleados: [],
            listaBancosEmpleado : [],
            listaProyectos : [],
            listaHorarios: [],
            modal : 0,
            modal_n : 0,
            tituloModal : '',
            tipoAccion : 0,
            disabled: 0,
            fechavalidar: 0,
            isLoading: false,
            isLoadingdos : false,
            isLoadingDetalle: false,
            columns: ['empleado.id','empleado.nombre','empleado.ap_paterno','empleado.ap_materno','puesto.nombre','departamento.nombre', ],
            columnsp: ['id','nombre','nombre_corto','clave','ciudad','fecha_inicio','fecha_fin', ],
            columnsinicial : ['id','fecha_inicial','fecha_final','periodo','empresa'],
            tableData: [],
            tableDataNomina : [],
            tableDatap: [],
            tableDatainicial : [],
            columnsTipoContrato: ['id','e_nombre','p_nombre','Días trabajado','Sueldo Diario','Transferencia','Efectivo','Otro','Total', ],
            tableDataTipoContrato: [],
            options: {
              headings: {
                'empleado.id': 'Acciones',
                'empleado.nombre': 'Nombre',
                'empleado.ap_paterno': 'Apellido Paterno',
                'empleado.ap_materno': 'Apellido Materno',
                'puesto.nombre': 'Puesto',
                'departamento.nombre': 'Departamento',
              },
              perPage: 10,
              perPageValues: [],
              skin: config.skin,
              sortIcon: config.sortIcon,
              sortable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno', 'puesto.nombre', 'departamento.nombre'],
              filterable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno', 'puesto.nombre', 'departamento.nombre'],
              filterByColumn: true,
              texts:config.texts
            },
            optionsinicial: {
              headings: {
                'id': 'Acciones',
              },
              perPage: 10,
              perPageValues: [],
              skin: config.skin,
              sortIcon: config.sortIcon,
              sortable: ['fecha_inicial','fecha_final','periodo'],
              filterable: ['fecha_inicial','fecha_final','periodo'],
              filterByColumn: true,
              texts:config.texts
            },
            optionsTipoContrato: {
              headings: {      },
              perPage: 10,
              perPageValues: [],
              skin: config.skin,
              sortIcon: config.sortIcon,
              sortable: ['tipo_nomina_id', 'fecha_ingreso', 'fecha_fin', 'tc_nombre', 'p_nombre'],
              filterable: ['tipo_nomina_id', 'fecha_ingreso', 'fecha_fin', 'tc_nombre',, 'p_nombre', 'condicion'],
              filterByColumn: true,
              listColumns: {
                condicion : config.columnCondicion
              },
              texts:config.texts
            },
            optionsp: {
              headings: {  'id':'Acciones'  },
              perPage: 10,
              perPageValues: [],
              skin: config.skin,
              sortIcon: config.sortIcon,
              sortable: ['id','nombre','nombre_corto','clave','ciudad','fecha_inicio','fecha_fin',],
              filterable: ['id','nombre','nombre_corto','clave','ciudad','fecha_inicio','fecha_fin',],
              filterByColumn: true,
              listColumns: {
                condicion : config.columnCondicion
              },
              texts:config.texts
            },
          }
        },

        methods : {
          savesd(e, data){
            axios.post('guardar/sd/nomina/quincena',{
              id : data.id,
              total : e.target.value,
            }).then(response => {
              this.cargarNomina(this.data);
            }).catch(e => {
              console.error(e);
            });
          },

          savedt(e, data){
            axios.post('guardar/dias/trabajados/nomina/quincena',{
              id : data.id,
              total : e.target.value,
            }).then(response => {
              this.cargarNomina(this.data);
            }).catch(e => {
              console.error(e);
            });
          },

          savedt(e, data){
            axios.post('guardar/dias/trabajados/nomina/quincena',{
              id : data.id,
              total : e.target.value,
            }).then(response => {
              this.cargarNomina(this.data);
            }).catch(e => {
              console.error(e);
            });
          },

          saved(e, data){
            axios.post('guardar/descuento/nomina/quincena',{
              id : data.id,
              total : e.target.value,
            }).then(response => {
              this.cargarNomina(this.data);
            }).catch(e => {
              console.error(e);
            });
          },

          savei(e, data){
            axios.post('guardar/infonavit/nomina/quincena',{
              id : data.id,
              total : e.target.value,
            }).then(response => {
              this.cargarNomina(this.data);
            }).catch(e => {
              console.error(e);
            });
          },

          saveva(e, data){
            axios.post('guardar/viatico/alimentos/nomina/quincena',{
              id : data.id,
              total : e.target.value,
            }).then(response => {
              this.cargarNomina(this.data);
            }).catch(e => {
              console.error(e);
            });
          },

          savet(e, data){
            axios.post('guardar/total/nomina/quincena',{
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
            axios.post('guardar/banco/nomina/quincena',{
              id : data.id,
              total : e.target.tabIndex,
            }).then(response => {
              this.cargarNomina(this.data);
            }).catch(e => {
              console.error(e);
            });
          },

          total_nomina(data){
            return Math.round( (data.sueldo_diario * data.dias_trabajados) - data.descuento - data.infonavit);
          },

          totales(data){
            var sd = data.sueldo_diario == null ? 0 : parseFloat(data.sueldo_diario);
            var dt = data.dias_trabajados == null ? 0 : parseFloat(data.dias_trabajados);
            var d = data.descuento == null ? 0 : parseFloat(data.descuento);
            var i = data.infonavit == null ? 0 : parseFloat(data.infonavit);
            var va = data.viaticos_alimentos == null ? 0 : parseFloat(data.viaticos_alimentos);

            return Math.round( (((sd * dt) - d) - i) + va);
          },

          listar(){//p
            this.isLoading = true;
            let me=this;
            axios.get('historico/' + this.s_empleado.id).then(response => {
              me.empleado = response.data.empleado;
              me.contratos = response.data.contratos;
              me.isLoading = false;
            })
            .catch(function (error) {
              console.log(error);
              me.isLoading = false;
            });
          },
          getData() {
            let me=this;
            this.PermisosCRUD = Utilerias.getCRUD(this.$route.path);
            axios.get('/nominaquincenal').then(response => {
              me.tableDatainicial = response.data;
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
          getLista() {
            let me=this;
            axios.get('/empleado').then(response => {
              me.listaEmpleados = response.data;
            })
            .catch(function (error) {
              console.log(error);
            });


          },
          change()
          {
            let me = this ;
            if (this.tabla.dias_restados == '') {
              this.tabla.diast = me.empleado.contratos.dias_trabajados;
            }
            else {
              this.tabla.diast = (this.tabla.diast - this.tabla.dias_restados);

            }
            // alert(this.tabla.diast);
          },
          guardarNominas(nuevo) {
            //this.isLoading = true;
            let me = this;
            axios({
              method: nuevo ? 'POST' : 'PUT',
              url: nuevo ? me.url: '/nominaquincenal/'+this.tipoContrato.empleado_id,
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
              me.getData();

              if (response.data.status) {
                // me.cerrarf();
                // me.cargarDetalleNomina(me.empleado);
                if(!nuevo){ toastr.success('Nómina Actualizado Correctamente');  }
                else  { toastr.success('Nómina Registrada Correctamente');     }
              } else {
                swal({type: 'error', html: response.data.errors.join('<br>'), });
              }
            }).catch(function (error) {
              console.log(error);
            });

          },
          guardarTipoContrato(nuevo){
            this.$validator.validate().then(result => {
              if (result) {
                this.isLoading = true;
                let me = this;
                axios({
                  method: nuevo ? 'POST' : 'PUT',
                  url: nuevo ? me.url: '/nominaquincenal/'+this.tipoContrato.empleado_id+'/editarnominaquincenal',
                  data: {
                    'empleado':this.tipoContrato.empleado_id,
                    'diast':this.tabla.diast,
                    'otro':this.tabla.otro,
                    'descuento' : this.tabla.descuento,
                    'contrato_id':this.tabla.contrato_id,
                    'sueldo_diario_neto' : this.tabla.sueldo_diario,
                    'sueldo_diario_integral' :this.tabla.sueldo_diario_integral,
                    'infonavit' : this.tabla.infonavit,
                    'nomina_id' : this.inicialp.id,
                    'banco_id' : this.tabla.banco_id,
                    'proyecto_id' : this.tabla.proyecto_id,
                    'dias_restados' : this.tabla.dias_restados,

                  }
                }).then(function (response) {
                  me.isLoading = false;
                  me.cerrarModal();
                  me.condicionuno = 3;

                  // me.getData();
                  if (response.data.status) {
                    me.cerrarf();
                    me.cargarnomina(me.inicialp);
                    if(!nuevo){ toastr.success('Contrato Actualizado Correctamente');  }
                    else  { toastr.success('Contrato Registrado Correctamente');     }
                  } else {
                    swal({type: 'error', html: response.data.errors.join('<br>'), });
                  }
                }).catch(function (error) {
                  console.log(error);
                });
              }});
            },
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
              if (fecha_final != '') {
                var fechaIngreso = new Date(fecha_inicio).getTime();
                var fechaFin    = new Date(fecha_final).getTime();
                var diff = fechaFin - fechaIngreso;
                var diferencia =diff/(1000*60*60*24);
                switch (mesi) {
                  case '01': mesnombre = 'Enero'; break;
                  case '02': mesnombre = 'Febrero'; break;
                  case '03': mesnombre = 'Marzo'; break;
                  case '04': mesnombre = 'Abril'; break;
                  case '05': mesnombre = 'Mayo'; break;
                  case '06': mesnombre = 'Junio'; break;
                  case '07': mesnombre = 'Julio'; break;
                  case '08': mesnombre = 'Agosto'; break;
                  case '09': mesnombre = 'Septiembre'; break;
                  case '10': mesnombre = 'Octubre'; break;
                  case '11': mesnombre = 'Noviembre'; break;
                  case '12': mesnombre = 'Diciembre'; break;
                }

                //validado
                this.desabilitarboton = 0;
                if(diaf <= 15){
                  periodo = 'Primera quincena del mes de ' + mesnombre + ' de ' + anio;
                  this.nominas.periodo = periodo;
                }
                if(diaf >= 16 ){
                  periodo = 'Segunda quincena del mes de ' + mesnombre + ' de ' + anio;
                  this.nominas.periodo = periodo;
                }
                if((diferencia+1) >= 28 && (diferencia+1) < 32 ){
                  periodo = 'Mes de ' + mesnombre + ' de ' + anio;
                  this.nominas.periodo = periodo;
                }
                this.nominas.num_dias_trabajado = diferencia + 1;
                //fin validaddo
              }
            },
            cerrarModal(){
              this.modal=0;
              this.modal_n = 0;
              this.tituloModal='';
              Utilerias.resetObject(this.tipoContrato);
              Utilerias.resetObject(this.nominas);

            },
            calcularnomina(){
              this.modal_n = 1;
            },
            abrirModal(modelo, accion, data = []){
              switch(modelo){
                case "tipo-contrato":
                {
                  switch(accion){
                    case 'registrar':
                    {
                      this.modal = 1;
                      let me= this;
                      this.tituloModal = 'Registrar Contrato';
                      Utilerias.resetObject(this.tipoContrato);
                      // this.tipoContrato.empleado_id = id;
                      this.tipoAccion = 1;
                      break;
                    }
                    case 'actualizar':
                    {

                      console.log(data['contratos']);
                      Utilerias.resetObject(this.tipoContrato);
                      Utilerias.resetObject(this.tabla);
                      this.modal=1;
                      this.tituloModal= 'Cálculo de nómina quincenal';
                      this.tipoAccion=2;
                      this.disabled=1;
                      this.empleado = data;
                      this.tipoContrato.empleado_id = data['empleado']['id'];
                      var mpleado_id = data['empleado']['id'];
                      let me=this;
                      axios.get('/datosbanemp/'+mpleado_id+'/datosbanemp').then(response => {
                        me.listaBancosEmpleado = response.data;
                      })
                      .catch(function (error) {
                        console.log(error);
                      });
                      this.tabla.diast = data['contratos']['dias_trabajados'];
                      this.tabla.otro = 0;
                      this.tabla.proyecto_id = data['contratos']['proyecto_id'];
                      this.tabla.banco_id = data['banco']['id'];
                      this.tabla.descuento = 0;
                      this.tabla.contrato_id = data['contratos']['contrato_id'];
                      this.tabla.infonavit = data['contratos']['infonavit'];
                      this.tabla.sueldo_diario = data['contratos']['sueldo_diario_neto'];
                      this.tabla.sueldo_diario_integral = data['contratos']['sueldo_diario_integral'];

                      break;


                    }
                  }
                }
              }
            },
            cargarDetalleNomina(dataEmpleado = []) {

              this.detalle = true;
              this.isLoadingDetalle = true;
              let me=this;
              this.empleado = dataEmpleado;
              console.log(dataEmpleado.contratos.id);
              axios.get(me.url + '/' + dataEmpleado.empleado.id+'&'+dataEmpleado.contratos.id+'&'+me.inicialp.id ).then(response => {

                this.contratos = response.data;
                this.tabla.sueldo_diario = response.data[0].sueldo_diario_neto;
                this.tabla.sueldo_diario_integral = response.data[0].sueldo_diario_integral;
                this.tabla.infonavit = response.data[0].infonavit;
                this.tabla.diast = response.data[0].dias_trabajados;
                this.tabla.otro = response.data[0].otros;
                this.tabla.descuento = response.data[0].descuento;
                this.tabla.transferenciaa = response.data[0].transferencias;
                this.tabla.efectivo = response.data[0].efectivos;
                this.tabla.total = response.data[0].totales;


                me.isLoadingDetalle = false;
                me.isLoadingdos = false;
              })
              .catch(function (error) {
                console.log(error);
              });
            },
            cargarNominaProyecto(dataProyecto =[]){
              let me=this;
              this.proyecto = dataProyecto;
              this.condicionuno = 2;
              this.isLoading = true;
              axios.get('/nominaquincenal/busqueda_por_proyecto/'+dataProyecto.id+'&'+me.inicialp.id).then(response => {
                me.tableData = response.data;
                this.isLoading = false;
              }).catch(function (error){
                console.log(error);
              });
            },

            cargarNomina(data){
              let me = this;
              this.condicionuno = 1;
              this.isLoading = true;
              me.inicialp = data;
              this.data = data;
              axios.get('/nominaquincenal/busqueda_por_proyecto/'+data.id).then(response => {
                me.tableDataNomina = response.data;
                this.isLoading = false;

              })
              .catch(function (error) {
                console.log(error);
              });
            },

            maestro(){
              this.condicionuno = 3;
              this.inicialp = null;

            },
            maestrouno(){
              this.detalle = true;
              this.isLoadingDetalle = false;
              this.isLoadingdos = false;
              this.detalle = false;
            },
            maestrodos(){
              this.condicionuno = 1;
              this.proyecto = null;
            },

            descargar(data = [], numero) {
              //  console.log(data);
              if (numero == 1) {
                window.open('descargar-excel-quincenal/'+data.id, '_blank');
                let me = this;
                me.getData();
              }
              if (numero == 2) {
                window.open('descargar-excel-quincenal-confin/'+data.id, '_blank');
                let me = this;
                me.getData();
              }
              if (numero == 3) {
                window.open('descargar-excel-quincenal-con/'+data.id, '_blank');
                let me = this;
                me.getData();
              }

            },

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
                    axios.post('guardar/nuevo/banco/nomina/quincena',{
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
                axios.post('guardar/banco/nomina/quincena',{
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
                   axios.post('agregar/nuevo/banco/nomina/quincena',{
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
            // descargarejemplo(data = [])
            // {
            //   axios.get('/nominaejemplo/'+data.id).then(response => {
            //     console.log('Archivos creados exitosamente.....................');
            //   }).catch(function (error){
            //     console.error(error);
            //   });
            //   // window.open('nominaejemplo/'+data.id, '_blank');
            //   // let me = this;
            //   // me.getData();
            // }

          },
          mounted() {
            this.getData();
            this.getLista();

          }
        }
        </script>

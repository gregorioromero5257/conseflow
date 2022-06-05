<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card" >
          <div class="card-header">
              <i class="fa fa-align-justify"></i> Buscar
          </div>
          <div class="card-body">
              <div class="form-row">
                  <div class="form-group col-md-3">
                      <label>Fecha inicial</label>
                      <input type="date" class="form-control" v-model="date_one" ref="date_one">
                  </div>
                  <div class="form-group col-md-3">
                      <label>Fecha final</label>
                      <input type="date" class="form-control" v-model="date_two" ref="date_two">
                  </div>
                  <div class="form-group col-md-1">
                      <label>&nbsp;</label>
                      <br>
                      <button type="button" class="btn btn-dark" name="button" @click="find">Buscar</button>
                  </div>
                  <div class="form-group col-md-2">
                      <template>
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
                  </div>

              </div>
          </div>
      </div>

      <div class="card">
        <div class="card-header">


        </div>
        <div class="card-body">
          <div class="form-row">
            <div class="col-md-4 mb-3">
              <input class="form-control" v-model="data_busqueda">
            </div>
            <div class="col-md-2 mb-3">
              <button class="btn btn-dark" @click="buscarEmp(0)"> <i class="fas fa-search"></i> </button>
              <button class="btn btn-dark" @click="clear()"> <i class="fas fa-broom"></i> </button>
            </div>
          </div>
          <div class="table">
            <div class="table-scroll">
              <table class="table-main">
                <thead>
                  <tr>
                    <th class="fix-col">
                      NOMBRE DEL EMPLEADO
                      <br>

                    </th>
                  <template v-if="data_response != ''">
                    <template v-for="f in data_response.fechas">

                    <th>
                      {{f[0]}}
                      <br>
                      {{f[1]}}
                    </th>

                    </template>
                  </template>

                  </tr>
                </thead>
                <tbody>
                  <template v-if="data_response != ''">
                    <template v-for="d in data_response.asistencias">
                      <tr>
                        <td class="fix-col">
                          {{d.datos_empleado.nombre}}
                        </td>
                        <template v-for="h in d.asistencias" >
                          <td>
                            <button class="btn btn-outline-success" name="button">
                              {{h.horario}}
                            </button>
                          </td>
                        </template>
                      </tr>

                    </template>
                  </template>


                </tbody>
              </table>
            </div>
          </div>
          <nav aria-label="">
              <ul class="pagination">
                  <li v-for="p in total_paginas" class="page-item">
                      <a class="page-link" href="#" @click="CargarPagina(p.index)">{{p.nombre}}</a>
                  </li>
                  <li class="page-item ml-3 my-auto">
                      Página actual: {{pagina_actual}}
                  </li>
              </ul>
          </nav>

        </div>
      </div>

    </div>

  </main>
</template>

<script>
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default
{
  data()
  {
    return {
      data_busqueda : '',
      data_response: '',
      data_response_temporal : '',
      tab: 1,
      url: '/registro',
      valor_nuevo_estado: '',
      empleado: null,
      reporte_estado: '',
      // detalle: false,
      desabilitar: false,
      archivo: null,
      date_uno: '',
      date_dos: '',
      date_one: '',
      date_two: '',
      detalle: 1,
      estado: '',
      observaciones: '',
      id: '',
      registroasistencia:
      {
        id: 0,
        hora_entrada: '',
        hora_salida_comida: '',
        hora_entrada_comida: '',
        hora_salida: '',
        fecha: '',
        dia_id: 0,
        registro_id: 0,
        empleado_id: 0,
        fechanombre: '',
      },
      listaEmpleados: [],
      listaDias: [],
      dataR: [],
      modal: 0,
      tituloModal: '',
      tipoAccion: 0,
      disabled: 0,
      isLoading: false,
      isLoadingDetalle: false,
      // Tabla de asistencias
      pagina_actual: 0,
      listTipoRegistro: [],
      total_paginas: [],
      page: 1,
      total: 0,
      headings_asistencia: ["Empleado"],
      columns: ['empleado.id', 'empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno', 'contador'],
      tableData: [],
      columnsAsistencias: ['fecha', 'nombre', 'hora_entrada', 'hora_salida_comida', 'hora_entrada_comida', 'hora_salida', ],
      tableDataAsistencia: [],
      tableDataRegistros: [],
      lstAsistenciaCompleta: [],
      lstAsistenciaIncompleta: [],
      lstInasistencia: [],
      ListadoEstados: [],
      nombre_empleado: '',
      columnsRegistros: ['registros.id', 'registros.fecha', 'nombre_dia', 'registros.hora_entrada', 'registros.hora_salida_comida', 'registros.hora_entrada_comida', 'registros.hora_salida', 'registros.observaciones'],
      options:
      {
        headings:
        {
          'empleado.id': 'Acciones',
          'empleado.nombre': 'Nombre',
          'empleado.ap_paterno': 'Apellido Paterno',
          'empleado.ap_materno': 'Apellido Materno',
          'contador': 'Estado',
        },
        perPage: 20,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno'],
        filterable: ['empleado.nombre', 'empleado.ap_paterno', 'empleado.ap_materno'],
        filterByColumn: true,
        texts: config.texts
      },
      optionsRegistros:
      {
        headings:
        {
          'registros.id': 'Acciones',
          'registros.fecha': 'Fecha',
          'registros.dia_id': 'Día',
          'registros.hora_entrada': 'Entrada',
          'registros.hora_salida_comida': 'Salida comida',
          'registros.hora_entrada_comida': 'Entrada comida',
          'registros.hora_salida': 'Salida',
          'registros.observaciones': 'Observaciones'
        },
        perPage: 20,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['registros.fecha', 'registros.dia_id', 'registros.hora_entrada', 'registros.hora_salida_comida', 'registros.hora_entrada_comida', 'registros.hora_salida', 'registros.observaciones'],
        filterable: ['registros.fecha', 'registros.dia_id', 'registros.hora_entrada', 'registros.hora_salida_comida', 'registros.hora_entrada_comida', 'registros.hora_salida', 'registros.observaciones'],
        filterByColumn: true,
        texts: config.texts
      },
      optionsdireccion:
      {
        headings:
        {
          nombre: 'Día',
        },
        perPage: 20,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: ['fecha', 'nombre', 'hora_entrada', 'hora_salida_comida', 'hora_entrada_comida', 'hora_salida'],
        filterable: ['fecha', 'nombre', 'hora_entrada', 'hora_salida_comida', 'hora_entrada_comida', 'hora_salida'],
        filterByColumn: true,
        listColumns:
        {
          condicion: config.columnCondicion
        },
        texts: config.texts
      },
    }
  },
  computed:
  {},
  methods:
  {
    /**
    * [fecha description]
    * @return Calculo de el dia actual en forma de texto
    */
    fecha()
    {
      let diasn = [7, 1, 2, 3, 4, 5, 6];
      let meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
      var x = this.registroasistencia.fecha;
      let date = new Date(x.replace(/-+/g, '/'));
      var fechaNum = date.getDate();
      var mes_name = date.getMonth();
      if (date.getDay() >= 1 && date.getDay() <= 5)
      {
        this.desabilitar = false;
      }
      else if (date.getDay() == 0 || date.getDay() == 6)
      {
        this.desabilitar = true;
      }
      this.registroasistencia.dia_id = diasn[date.getDay()];
      this.registroasistencia.fechanombre = " " + fechaNum + " de " + meses[mes_name] + " de " + date.getFullYear();

    },

    fecha_enletra(fecha)
    {
      const meses = [
        "Enero", "Febrero", "Marzo",
        "Abril", "Mayo", "Junio", "Julio",
        "Agosto", "Septiembre", "Octubre",
        "Noviembre", "Diciembre"
      ];

      const date = new Date(fecha);
      const dia = date.getDate() + 1;
      const mes = date.getMonth();
      const ano = date.getFullYear();
      return `${dia} de ${meses[mes]} del ${ano}`;
    },
    /**
    * [getData description]
    * @return {[response]} [De la consulta del ruta de la api ''/empleado']
    */
    getData()
    {
      let me = this;
      axios.get('/empleado').then(response =>
        {
          // me.tableData = response.data;
        })
        .catch(function (error)
        {
          console.log(error);
        });
      },

      GetListadoEstados()
      {
        axios.get('asistencia/registros/estados').then(response =>
          {
            this.ListadoEstados = response.data;
          }).catch(e =>
            {
              console.error(e);
            });
          },

          getLista()
          {
            let me = this;
            axios.get('/empleado').then(response =>
              {
                me.listaEmpleados = response.data;
              })
              .catch(function (error)
              {
                console.log(error);
              });
              axios.get('/dia').then(response =>
                {
                  me.listaDias = response.data;

                })
                .catch(function (error)
                {
                  console.log(error);
                });

              },
              /**
              * [guardarAsistencias description]
              * @param  {[int]} nuevo [actualizar y guardar]
              * @return {[response]}       [status => true]
              */
              guardarAsistencias(nuevo)
              {
                this.$validator.validate().then(result =>
                  {
                    if (result)
                    {
                      this.isLoading = true;
                      let me = this;
                      axios(
                        {
                          method: nuevo ? 'POST' : 'PUT',
                          url: nuevo ? '/registro' : me.url + '/' + this.registroasistencia.id,
                          data:
                          {
                            'id': this.registroasistencia.id,
                            'hora_entrada': this.registroasistencia.hora_entrada + ':00',
                            'hora_salida_comida': this.registroasistencia.hora_salida_comida + ':00',
                            'hora_entrada_comida': this.registroasistencia.hora_entrada_comida + ':00',
                            'hora_salida': this.registroasistencia.hora_salida + ':00',
                            'fecha': this.registroasistencia.fecha,
                            'dia_id': this.registroasistencia.dia_id,
                            'empleado_id': this.registroasistencia.empleado_id,
                          }
                        }).then(function (response)
                        {
                          console.log(response.data);
                          me.isLoading = false;
                          if (response.data.status)
                          {
                            me.cerrarModal();
                            toastr.success('Asistencia Registrada Correctamente')
                            me.cargarasistencia(me.empleado);

                          }
                          else
                          {
                            swal(
                              {
                                type: 'error',
                                html: response.data.errors.join('<br>'),
                              });
                            }
                          }).catch(function (error)
                          {
                            console.log(error);
                          });
                        }
                      });
                    },
                    /**
                    * [cerrarModal description]
                    * @return  [reset del model descuento]
                    */
                    cerrarModal()
                    {
                      this.modal = 0;
                      this.tituloModal = '';
                      Utilerias.resetObject(this.descuento);
                    },
                    /**
                    * [abrirModal description]
                    * @param  {[string]} modelo    [description]
                    * @param  {[string]} accion    [description]
                    * @param  {[int]} id        [description]
                    * @param  {Array}  [data=[]] [description]
                    */
                    abrirModal(modelo, accion, id, data = [])
                    {
                      switch (modelo)
                      {
                        case "direccion":
                        {
                          switch (accion)
                          {
                            case 'registrar':
                            {
                              var fecha = new Date(); //Fecha actual
                              var mes = fecha.getMonth() + 1; //obteniendo mes
                              var dia = fecha.getDate(); //obteniendo dia
                              var anio = fecha.getFullYear(); //obteniendo año
                              if (dia < 10)
                              dia = '0' + dia; //agrega cero si el menor de 10
                              if (mes < 10)
                              mes = '0' + mes //agrega cero si el menor de 10

                              this.modal = 1;
                              this.tituloModal = 'Registrar asistencia del empleado';
                              Utilerias.resetObject(this.registroasistencia);
                              this.registroasistencia.empleado_id = id;
                              this.registroasistencia.fecha = anio + "-" + mes + "-" + dia;
                              let diasn = [1, 2, 3, 4, 5, 6, 7];
                              let meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                              var x = this.registroasistencia.fecha;
                              let date = new Date(x.replace(/-+/g, '/'));
                              var fechaNum = date.getDate();
                              var mes_name = date.getMonth();
                              this.registroasistencia.dia_id = diasn[date.getDay() - 1];
                              this.registroasistencia.fechanombre = " " + fechaNum + " de " + meses[mes_name] + " de " + date.getFullYear();
                              this.tipoAccion = 1;
                              if (date.getDay() >= 1 && date.getDay() <= 5)
                              {
                                this.desabilitar = false;
                              }
                              else if (date.getDay() == 0 || date.getDay() == 6)
                              {
                                this.desabilitar = true;
                              }
                              break;
                            }
                            case 'actualizar':
                            {
                              this.modal = 1;
                              this.tituloModal = 'Actualizar asistencia';
                              this.registroasistencia.id = data['id'];
                              this.tipoAccion = 2;
                              this.registroasistencia.fecha = data['fecha'];
                              this.registroasistencia.hora_entrada = data['hora_entrada'];
                              this.registroasistencia.hora_salida_comida = data['hora_salida_comida'];
                              this.registroasistencia.hora_entrada_comida = data['hora_entrada_comida'];
                              this.registroasistencia.hora_salida = data['hora_salida'];
                              break;
                            }
                          }
                        }
                      }
                    },
                    /**
                    * [cargarasistencia description]
                    * @param  {Array}  [dataEmpleado=[]] [description]
                    * @param  {int} id                [description]
                    */
                    cargarasistencia(dataEmpleado = [], id)
                    {
                      this.detalle = true;
                      this.isLoadingDetalle = true;
                      let me = this;
                      this.empleado = dataEmpleado;
                      // console.log(id);
                      axios.get('/registro' + '/' + dataEmpleado.id + '/' + 'registro').then(response =>
                        {
                          me.tableDataAsistencia = response.data;
                          me.isLoadingDetalle = false;
                          this.registroasistencia.empleado_id = id;
                        })
                        .catch(function (error)
                        {
                          console.log(error);
                        });
                      },
                      maestro()
                      {
                        this.detalle = false;
                      },

                      find()
                      {
                        if (this.$refs.date_one.value == '')
                        {
                          toastr.warning('Ingrese fecha inicial');
                        }
                        else if (this.$refs.date_two.value == '')
                        {
                          toastr.warning('Ingrese fecha final');
                        }
                        else
                        {
                          this.CargarPagina(0);
                        }
                      },
                      anterior()
                      {
                        this.page = this.page == 1 ? 1 : this.page - 1;
                        if (this.data_busqueda === '') {
                          this.CargarPagina(this.page * 20);
                        }else {
                          this.buscarEmp(this.page * 20);
                        }
                      },
                      siguiente()
                      {
                        this.page = this.page == this.total ? this.page : this.page + 1;
                        if (this.data_busqueda === '') {
                          this.CargarPagina(this.page * 20);
                        }else {
                          this.buscarEmp(this.page * 20);
                        }
                      },
                      buscarEmp(index){
                        if (this.$refs.date_one.value == '')
                        {
                          toastr.warning('Ingrese fecha inicial');
                        }
                        else if (this.$refs.date_two.value == '')
                        {
                          toastr.warning('Ingrese fecha final');
                        }
                        else
                        {
                        this.data_response_temporal = this.data_response;
                        axios.post('buscar/nomina/empleado/',{
                          fecha_uno : this.date_one,
                          fecha_dos : this.date_two,
                          index : index,
                          data : this.data_busqueda,
                        }).then(res => {
                          if (res.status)
                          {
                            this.data_response = res.data;
                            this.pagina_actual = index + 1;
                            this.headings_asistencia = ["Empleado"];
                            this.lstAsistenciaCompleta = res.data.asistencias;
                            this.total_paginas = res.data.paginas;
                            this.total = res.data.asistencias.length;
                            res.data.fechas.forEach(f =>
                              {
                                this.headings_asistencia.push(f);
                              });
                              this.total = res.data.paginas;
                            }
                            else
                            {
                              toastr.error("Error", "Contacte al administrador");
                              console.error(res.data);
                            }
                            this.isLoading = false;
                        }).catch(e => {
                          console.error(e);
                        });
                      }
                      },
                      CargarPagina(index)
                      {
                        if (this.data_busqueda != '') {
                          this.buscarEmp(index);
                        }else {
                        this.isLoading = true;
                        axios.get(`buscar/nomina/${this.date_one}&${this.date_two}&${index}`).then(res =>
                          {
                            if (res.status)
                            {
                              this.data_response = res.data;
                              this.pagina_actual = index + 1;
                              this.headings_asistencia = ["Empleado"];
                              this.lstAsistenciaCompleta = res.data.asistencias;
                              this.total_paginas = res.data.paginas;
                              this.total = res.data.asistencias.length;
                              res.data.fechas.forEach(f =>
                                {
                                  this.headings_asistencia.push(f);
                                });
                                this.total = res.data.paginas;
                              }
                              else
                              {
                                toastr.error("Error", "Contacte al administrador");
                                console.error(res.data);
                              }
                              this.isLoading = false;
                            }).catch(e =>
                              {
                                console.error(e);
                              });
                            }
                            },
                            clear(){
                                this.data_busqueda = '';
                                this.CargarPagina(0);
                            },
                            AbrirDetalleHorario(id)
                            {
                              let estados = [];
                              let options = {};

                              // Obtener los tipos de registro
                              axios.get("asistencia/registros/estados").then(r =>
                                {
                                  r.data.estados.forEach(tr =>
                                    {
                                      estados.push(
                                        {
                                          id: tr.id,
                                          nombre: tr.nombre
                                        });
                                      });
                                      estados.push(
                                        {
                                          id: -1,
                                          nombre: "Otro"
                                        });

                                        //this i assume would be loaded via ajax or something?
                                        $.map(estados,
                                          function (o)
                                          {
                                            options[o.id] = o.nombre;
                                          });

                                          $.map(this.listTipoRegistro,
                                            function (o)
                                            {
                                              options[o.id] = o.nombre;
                                            });
                                          }).catch(r =>
                                            {
                                              console.error(r);
                                            });

                                            axios.get("/asistencia/registros/detalles/" + id).then(r =>
                                              {
                                                let continuar = false;
                                                // Mostrar datos
                                                let aux_comentarios = r.data.registro.observaciones == null ? "" : r.data.registro.observaciones;
                                                let m = this;
                                                // Actualizar detalle de horario
                                                Swal.mixin(
                                                  {
                                                    input: "text",
                                                    confirmButtonText: "Siguiente &rarr;",
                                                    showCancelButton: true,
                                                    progressSteps: ["1", "2"],
                                                  })
                                                  .queue([
                                                    {
                                                      title: "Tipo de asistencia",
                                                      text: "",
                                                      inputValue: r.data.registro.estado_asistencia_id,
                                                      input: 'select',
                                                      inputOptions: options,
                                                    },
                                                    {
                                                      title: "Comentarios",
                                                      inputValue: aux_comentarios,
                                                      text: "",
                                                    }])
                                                    .then((result) =>
                                                    {
                                                      if (result.value)
                                                      {
                                                        if (result.value == null) return;
                                                        if (result.value != "")
                                                        {
                                                          if (result.value[0] == -1)
                                                          {
                                                            console.error(result.value);
                                                            // Registra nuevo estado
                                                            Swal.fire(
                                                              {
                                                                title: "Tipo de asistencia",
                                                                text: "",
                                                                input: "text",
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#3085d6',
                                                                cancelButtonColor: '#d33',
                                                                confirmButtonText: 'Guardar',
                                                                cancelButtonText: 'Cancelar',
                                                              }).then(res_nuevo =>
                                                                {
                                                                  if (res_nuevo == null) return;
                                                                  if (res_nuevo.value == null) return;
                                                                  if (res_nuevo.value != "")
                                                                  {
                                                                    console.error(res_nuevo);
                                                                    // Guardar nuevo
                                                                    axios.post('guardar/nuevo/estado/registros',
                                                                    {
                                                                      nombre_estado: res_nuevo.value,
                                                                    }).then(response_nuevo =>
                                                                      {
                                                                        if (response_nuevo.data.status)
                                                                        {
                                                                          toastr.success('Tipo de asistencia registrado');
                                                                        }
                                                                        else
                                                                        {
                                                                          toastr.error("", "Error");
                                                                          console.error(response_nuevo);
                                                                        }
                                                                        return;
                                                                      }).catch(e =>
                                                                        {
                                                                          console.error(e);
                                                                        });
                                                                      }
                                                                    });

                                                                  }
                                                                  else
                                                                  {
                                                                    this.isLoading = true;
                                                                    axios(
                                                                      {
                                                                        method: "PUT",
                                                                        url: "/asistencia/registros/actualizar",
                                                                        data:
                                                                        {
                                                                          registro_id: id,
                                                                          tipo_estado: result.value[0],
                                                                          comentarios: result.value[1],
                                                                        },
                                                                      }).then((res) =>
                                                                      {
                                                                        this.isLoading = false;
                                                                        if (res.data.status)
                                                                        {
                                                                          toastr.success("", "Asistencia actualizada correctamente");
                                                                          this.CargarPagina(this.pagina_actual - 1);
                                                                        }
                                                                        else
                                                                        {
                                                                          toastr.error("", "Error");
                                                                          console.error(res.data);
                                                                        }
                                                                      });
                                                                    }

                                                                  }
                                                                }
                                                              });

                                                            }).catch(r =>
                                                              {
                                                                console.error(r);
                                                              });
                                                            },
                                                            cargar(data)
                                                            {
                                                              this.GetListadoEstados();
                                                              this.dataR = data;
                                                              axios.get(`resgistro/empleado/${data['empleado']['id']}&${this.date_one}&${this.date_two}`).then(response =>
                                                                {
                                                                  this.tableDataRegistros = response.data;
                                                                }).catch(error =>
                                                                  {
                                                                    console.error(error);
                                                                  });
                                                                  // console.log(data);
                                                                  // this.tableDataRegistros = data['registros'];
                                                                  this.detalle = 2;
                                                                  this.nombre_empleado = data == null ? '' : data['empleado']['nombre'] + ' ' + data['empleado']['ap_paterno'] + ' ' + data['empleado']['ap_materno'];
                                                                },

                                                                atras()
                                                                {
                                                                  $('#input_se').val('');
                                                                  $('#input_te').val('');
                                                                  // $('#input_se').html('');
                                                                  // $('#input_te').html('');
                                                                  this.detalle = 1;

                                                                },

                                                                actualizar(data)
                                                                {
                                                                  // console.log(data,'dd');
                                                                  this.modal = 1;
                                                                  this.estado = data.estado;
                                                                  this.observaciones = data.observaciones;
                                                                  this.id = data.registro_id;
                                                                },

                                                                cerrarModal()
                                                                {
                                                                  this.estado = '';
                                                                  this.observaciones = '';
                                                                  this.modal = 0;
                                                                  this.id = '';
                                                                },

                                                                guardarnomina()
                                                                {
                                                                  let me = this;
                                                                  axios(
                                                                    {
                                                                      method: 'POST',
                                                                      url: '/guardaregistros',
                                                                      data:
                                                                      {
                                                                        'id': me.id,
                                                                        'estado': me.estado,
                                                                        'observaciones': me.observaciones,
                                                                      }
                                                                    }).then(function (response)
                                                                    {
                                                                      me.cerrarModal();
                                                                      me.cargar(me.dataR);

                                                                      // me.cargar(me.dataR);
                                                                      // me.$refs.myTableR.refresh();
                                                                    }).catch(function (error)
                                                                    {
                                                                      console.log(error);
                                                                    });
                                                                  },

                                                                  generate()
                                                                  {
                                                                    if (this.$refs.date_one.value == '')
                                                                    {
                                                                      toastr.warning('Ingrese fecha inicial');
                                                                    }
                                                                    else if (this.$refs.date_two.value == '')
                                                                    {
                                                                      toastr.warning('Ingrese fecha final');
                                                                    }
                                                                    else
                                                                    {
                                                                      window.open('descargar-excel-rh/' + this.date_one + '&' + this.date_two + '&' + this.reporte_estado, '_blank');
                                                                      this.reporte_estado = '';
                                                                    }
                                                                  },

                                                                  enviarEstado(evento, data)
                                                                  {
                                                                    if (evento.target.value === 'otro')
                                                                    {
                                                                      this.valor_nuevo_estado = 'Escriba...'
                                                                    }
                                                                    else
                                                                    {
                                                                      axios.post('guardar/estado/registros',
                                                                      {
                                                                        registro_id: data,
                                                                        valor: evento.target.value,
                                                                      }).then(response =>
                                                                        {
                                                                          toastr.success('Guardado');
                                                                          this.cargar(this.dataR);
                                                                          this.GetListadoEstados();

                                                                        }).catch(e =>
                                                                          {
                                                                            console.error(e);
                                                                          });
                                                                        }
                                                                      },

                                                                      enviarNuevoEstado(evento, data)
                                                                      {
                                                                        axios.post('guardar/nuevo/estado/registros',
                                                                        {
                                                                          registro_id: data,
                                                                          valor: evento.target.value,
                                                                        }).then(response =>
                                                                          {
                                                                            toastr.success('Guardado');
                                                                            this.cargar(this.dataR);
                                                                            this.GetListadoEstados();
                                                                            this.valor_nuevo_estado = '';
                                                                          }).catch(e =>
                                                                            {
                                                                              console.error(e);
                                                                            });
                                                                          },
                                                                          CambiarTab(n)
                                                                          {
                                                                            this.tab = n;
                                                                          },
                                                                          enviarNuevaObservacion(evento, data)
                                                                          {
                                                                            axios.post('guardar/nuevo/observacion/registros',
                                                                            {
                                                                              registro_id: data,
                                                                              valor: evento.target.value,
                                                                            }).then(response =>
                                                                              {
                                                                                toastr.success('Guardado');
                                                                                this.cargar(this.dataR);
                                                                                this.GetListadoEstados();
                                                                                this.valor_nuevo_estado = '';
                                                                              }).catch(e =>
                                                                                {
                                                                                  console.error(e);
                                                                                });
                                                                              },

                                                                              borrar()
                                                                              {
                                                                                this.valor_nuevo_estado = '';
                                                                              },

                                                                            },
                                                                            mounted()
                                                                            {
                                                                              // this.getData();
                                                                              // this.getLista();
                                                                            }
                                                                          }
                                                                          </script>
                                                                          <style >
                                                                          .table-main {
                                                                            border: none;
                                                                            border-right: solid 1px rgb(75, 90, 102);
                                                                            border-collapse: separate;
                                                                            border-spacing: 0;
                                                                            font: normal 13px Arial, sans-serif;
                                                                          }

                                                                          .table-main thead th {
                                                                            background-color: rgb(203, 220, 233);
                                                                            border: none;
                                                                            color: #336B6B;
                                                                            padding: 10px;
                                                                            text-align: left;
                                                                            text-shadow: 1px 1px 1px #fff;
                                                                            white-space: nowrap;
                                                                          }

                                                                          .table-main tbody td {
                                                                            border-bottom: solid 1px rgb(75, 90, 102);
                                                                            color: #333;
                                                                            padding: 10px;
                                                                            text-shadow: 1px 1px 1px #fff;
                                                                            white-space: nowrap;
                                                                          }

                                                                          .table {
                                                                            position: relative;
                                                                            /* border: solid 2px; */
                                                                          }

                                                                          .table-scroll {
                                                                            margin-left: 310px;
                                                                            overflow-x: scroll;
                                                                            overflow-y: visible;
                                                                            padding-bottom: 5px;
                                                                            /* width: 500px; */
                                                                            /* border: solid 2px; */
                                                                          }

                                                                          .table-main .fix-col {
                                                                            border-left: solid 1px rgb(75, 90, 102);
                                                                            /* border-right: solid 1px rgb(75, 90, 102); */
                                                                            /* border-bottom: : solid 0px */
                                                                            left: 0;
                                                                            word-break: break-all;
                                                                            position: absolute;
                                                                            top: auto;
                                                                            width: 310px;
                                                                            border-bottom: solid 0px;
                                                                          }
                                                                          </style>

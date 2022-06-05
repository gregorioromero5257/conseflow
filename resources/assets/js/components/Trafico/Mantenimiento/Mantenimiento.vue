<template>
  <div class='main'>
    <!-- Card Inicio-->
    <div class='card'>
      <!-- Inicio card-->
      <div class='card-header'>
        <i class="fa fa-align-justify"></i> Mantenimiento {{empresa == 1  ? 'CONSERFLOW' : 'CSCT'}}
        <div class="dropdown">
          <button class="btn btn-secondary dropdown-toggle float-sm-right" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Empresa
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenu2" v-model="empresa">
            <button class="dropdown-item" type="button" @click="empresa = 1;getDataInicial();getData();">Conserflow</button>
            <button class="dropdown-item" type="button" @click="empresa = 2;getDataInicial();getData();">Constructora</button>
          </div>
        </div>
        <button type='button' class='btn btn-dark float-sm-right' @click='AbrirModalMantenimiento(true)'>
          <i class='fas fa-plus'>&nbsp;</i>Nuevo
        </button>
      </div>
      <div class='card-body'>
        <div class=''>
          <!-- Tabla de Mantenimiento-->
          <div class=''>
            <v-client-table :columns='columns_mantenimiento' :data='list_mantenimiento' :options='options_mantenimiento' ref='tbl_mantenimiento'>
              <template slot='id' slot-scope='props'>
                <div class='btn-group' role='group'>
                  <button id='btn_id' type='button' class='btn btn-outline-dark dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                    <i class='fas fa-grip-horizontal'></i>&nbsp; Acciones
                  </button>
                  <div class='dropdown-menu'>
                    <template>
                      <button type='button' @click='AbrirModalMantenimiento(false, props.row)' class='dropdown-item'>
                        <i class='icon-pencil'></i>&nbsp;Actualizar
                      </button>
                      <button type="button" @click="eliminar(props.row.id)" class="dropdown-item">
                        <i class="fas fa-trash"></i>&nbsp;Eliminar
                      </button>
                    </template>
                  </div>
                </div>
              </template>
              <template slot="vehiculo" slot-scope="props">
                {{props.row.unidad + ' ' + props.row.marca + ' ' + props.row.modelo + ' ' + props.row.placas}}
              </template>
            </v-client-table>
          </div>
          <!--Card body -->
        </div> <!-- card-->
      </div>
    </div>

    <!-- Modal Mantenimiento -->
    <div class='modal fade' tabindex='-1' :class="{'mostrar' : ver_modal_mantenimiento}" role='dialog' aria-labelledby='myModalLabel' style='display: none;' aria-hidden='true'>
      <div class='modal-dialog modal-dark modal-lg' role='document'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4 class='modal-title' v-text='titulo_modal_mantenimiento'></h4>
            <button type='button' class='close' @click='CerrarModalMantenimiento()' aria-label='Close'>
              <span aria-hidden='true'>×</span>
            </button>
          </div>
          <div class='modal-body'>
            <div class="form-row">
            <div class="col-md-6 mb-3">
              <label>Unidad</label>
              <v-select :options="listaCatalogo" v-model="mantenimiento.unidad" label="placas" v-validate="'required'" data-vv-name="unidad" >
              </v-select>
              <span class="text-danger">{{errors.first('unidad')}}</span>
            </div>
            <div class="col-md-6 mb-3">
              <span>{{mantenimiento.unidad == '' ? '' : mantenimiento.unidad == null ? '' : mantenimiento.unidad.unidad + ' ' + mantenimiento.unidad.modelo + ' ' + mantenimiento.unidad.marca}}</span>
            </div>
          </div>
          <div class="form-row">
            <div class="col-md-3 mb-3">
              <label>Tipo</label>
              <select class="form-control" v-model="mantenimiento.tipo">
                <option value="1">Preventivo</option>
                <option value="2">Correctivo</option>
              </select>
            </div>
            <div class="col-md-9 mb-3">
              <label>{{mantenimiento.tipo == 1 ? 'Descripción del servicio a realizar' : mantenimiento.tipo == 2 ? 'Descripción del daño, falla o avería detectada' : ''}}</label>
              <textarea class="form-control" rows="4" cols="80" v-model="mantenimiento.descripcion"></textarea>
            </div>
          </div>

          <div class="form-row">
            <div class="col-md-6 mb-3">
              <label>Nombre quién solicita el mantenimiento</label>
              <v-select :options="listaEmpleados" label="name" v-model="mantenimiento.solicita" v-validate="'required'" data-vv-name="nombre"></v-select>
              <span class="text-danger">{{errors.first('nombre')}}</span>
            </div>
            <div class="col-md-6 mb-3">
              <label>Nombre de quién recibe la solicitud de mantenimiento</label>
              <v-select :options="listaEmpleados" label="name" v-model="mantenimiento.recibe" v-validate="'required'" data-vv-name="nombre"></v-select>
              <span class="text-danger">{{errors.first('nombre')}}</span>
            </div>
          </div>

          <div class="form-row">
            <div class="col-md-6 mb-3">
              <label>Fecha inicio</label>
              <input class="form-control" type="date" v-model="mantenimiento.fecha_inicio" v-validate="'required'" data-vv-name="fecha inicio">
              <span class="text-danger">{{errors.first('fecha_inicio')}}</span>
            </div>
            <div class="col-md-6 mb-3">
              <label>Fecha salida</label>
              <input class="form-control" type="date" v-model="mantenimiento.fecha_salida" data-vv-name="fecha salida">
            </div>
          </div>
          <div class="form-row">
            <div class="col-md-8 mb-3">
              <label>Proveedor asignado</label>
              <input type="text" class="form-control" v-model="mantenimiento.proveedor" v-validate="'required'">
            </div>
          </div>
          <div class="form-row">
            <div class="col-md-12 mb-3">
              <label>Detalles del servicio efectuados</label>
              <textarea name="name" class="form-control" rows="4" cols="80" v-model="mantenimiento.detalle"></textarea>
            </div>
          </div>
          <div class="form-row">
            <div class="col-md-6 mb-3">
              <label>Refacciones y materiales empleados</label>
              <textarea name="name" class="form-control" rows="4" cols="80" v-model="mantenimiento.materiales"></textarea>
            </div>
            <div class="col-md-6 mb-3">
              <label>Quimicos empleados</label>
              <textarea name="name" class="form-control" rows="4" cols="80" v-model="mantenimiento.quimicos"></textarea>
            </div>
          </div>

          <div class="form-row">
            <div class="col-md-5 mb-3">
              <label>&nbsp;Entrega</label>
              <v-select :options="listaEmpleados" label="name" v-model="mantenimiento.entrega" v-validate="'required'" data-vv-name="entrega"></v-select>
              <span class="text-danger">{{errors.first('entrega')}}</span>
            </div>
            <div class="col-md-5 mb-3">
              <label>&nbsp;Recibe</label>
              <v-select :options="listaEmpleados" label="name" v-model="mantenimiento.recibe_empleado" v-validate="'required'" data-vv-name="recibe"></v-select>
              <span class="text-danger">{{errors.first('recibe')}}</span>
            </div>
          </div>







        </div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-outline-dark' @click='CerrarModalMantenimiento()'><i class='fas fa-times'></i>&nbsp;Cerrar</button>
          <button type='button' v-if='tipoAccion_modal_mantenimiento== 1' class='btn btn-secondary' @click='GuardarMantenimiento(1)'><i class='fas fa-save'></i>&nbsp;Guardar</button>
          <button type='button' v-if='tipoAccion_modal_mantenimiento==2' class='btn btn-secondary' @click='GuardarMantenimiento(0)'><i class='fas fa-save'></i>&nbsp;Actualizar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div> <!-- Main -->
</template>

<script>
/* CAMBIAR UBICACIÓN  */
import Utilerias from '../../Herramientas/utilerias.js';
var config = require('../../Herramientas/config-vuetables-client').call(this);
export default
{
  data()
  {
    return {
      // Tabla
      ver_modal_mantenimiento: 0,
      columns_mantenimiento: ['id','vehiculo','descripcion','fecha_inicio','fecha_salida','empleado_solicita'],
      list_mantenimiento: [],
      listaCatalogo : [],
      listaEmpleados : [],
      options_mantenimiento:
      {
        headings:
        {
          id: 'Acciones',
          vehiculo : 'Unidad',

        }, // Headings,
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        filterByColumn: true,
        texts: config.texts
      }, //options
      // Modal
      titulo_modal_mantenimiento: '',
      tipoAccion_modal_mantenimiento: 0,
      mantenimiento:
      {
        id : 0,
        unidad : '',
        tipo : '',
        descripcion : '',
        solicita : '',
        recibe : '',
        fecha_inicio : '',
        fecha_salida : '',
        proveedor : '',
        detalle : '',
        materiales : '',
        quimicos : '',
        entrega : '',
        recibe_empleado : '',
      },
      empresa : 1,
    } // return
  }, //data
  computed:
  {},
  methods:
  {
    getDataInicial(){
      this.list_mantenimiento = [];
      axios.get('mantenimiento/vehiculo/get').then(response => {
        response.data.forEach((item, i) => {
          if (item.empresa == this.empresa) {
            this.list_mantenimiento.push(item);
          }
        });
      }).catch(e => {
        console.error(e);
      });
    },
    getData(){
      this.listaCatalogo = [];
      axios.get('UnidadesStore').then(response => {
        response.data.forEach((item, i) => {
          if (item.empresa == this.empresa) {
            this.listaCatalogo.push(item);
          }
        });
      }).catch(e => {
        console.error(e);
      });
    },
    getLista(){
      this.listaEmpleados = [];
      axios.get('/vertodosempleados').then(response =>{
        response.data.forEach(data =>{
          this.listaEmpleados.push({
            id: data.id,
            name: data.nombre + ' ' + data.ap_paterno + ' ' + data.ap_materno
          });
        });
      })
      .catch(function (error)
      {
        console.log(error);
      });

    },
    AbrirModalMantenimiento(nuevo, data = [])
    {
      console.log(data);
      this.ver_modal_mantenimiento = true;
      if (nuevo)
      {
        // Crear nuevo
        this.titulo_modal_mantenimiento = 'Registrar Mantenimiento';
        this.tipoAccion_modal_mantenimiento = 1;
      }
      else
      {
        // Actualizar
        this.titulo_modal_mantenimiento = 'Actualizar Mantenimiento';
        this.tipoAccion_modal_mantenimiento = 2;
        this.mantenimiento.id = data['id'];
        this.mantenimiento.unidad = {id : data['unidad_id'], unidad : data['unidad'], modelo : data['modelo'], marca : data['marca'], placas : data['placas']};
        this.mantenimiento.tipo = data['tipo'];
        this.mantenimiento.descripcion = data['descripcion'];
        this.mantenimiento.solicita = {id : data['solicita'], name : data['empleado_solicita']};
        this.mantenimiento.recibe = {id : data['recibe'], name : data['empleado_recibe']};
        this.mantenimiento.fecha_inicio = data['fecha_inicio'];
        this.mantenimiento.fecha_salida = data['fecha_salida'];
        this.mantenimiento.proveedor = data['proveedor'];
        this.mantenimiento.detalle = data['detalle'];
        this.mantenimiento.materiales = data['materiales'];
        this.mantenimiento.quimicos = data['quimicos'];
        this.mantenimiento.entrega = {id : data['entrega'], name : data['empleado_entrega']};
        this.mantenimiento.recibe_empleado = {id : data['recibe'], name : data['empleado_recibe_uno']};
      } // Fin if
    },

    CerrarModalMantenimiento()
    {
      this.ver_modal_mantenimiento = false;
      Utilerias.resetObject(this.mantenimiento);
      this.mantenimiento.unidad = '';
    },

    GuardarMantenimiento(nuevo){
      this.$validator.validate().then(result => {
        if (result) {
          axios({
            method : nuevo ? 'POST' : 'PUT',
            url : nuevo ? 'mantenimiento/vehiculo/guardar' : 'mantenimiento/vehiculo/actualizar',
            data :{
              id :this.mantenimiento.id,
              unidad_id : this.mantenimiento.unidad.id,
              tipo : this.mantenimiento.tipo,
              descripcion : this.mantenimiento.descripcion,
              solicita : this.mantenimiento.solicita.id,
              recibe : this.mantenimiento.recibe.id,
              fecha_inicio : this.mantenimiento.fecha_inicio,
              fecha_salida : this.mantenimiento.fecha_salida,
              proveedor : this.mantenimiento.proveedor,
              detalle : this.mantenimiento.detalle,
              materiales : this.mantenimiento.materiales,
              quimicos : this.mantenimiento.quimicos,
              entrega : this.mantenimiento.entrega.id,
              recibe_empleado : this.mantenimiento.recibe_empleado.id,
              empresa : this.empresa,
            }
          }).then(response => {
            this.getDataInicial();
            this.CerrarModalMantenimiento();
            toastr.success('Correcto !!!');
          }).catch(e => {
            console.error(e);
          });
        }else {
          toastr.warning('Complete todos los campos');
        }
      })
    },


        eliminar(id){
          Swal.fire({
            title: 'Esta seguro(a) de eliminar ?',
            text: "Esta opción no se podrá desahacer!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText : 'No',
          }).then(result => {
            if (result.value) {
              axios.get('mantenimiento/vehiculo/eliminar/' + id).then(response =>{
                toastr.success('Eliminado Correctamente !!!');
                this.getDataInicial();
              }).catch(e => {
                console.error(e);
              });
            }
          })
        },

  }, // Fin metodos
  mounted()
  {
      this.getDataInicial();
      this.getData();
      this.getLista();
  }
}
</script>

<template>
  <div class="card border-secondary">

    <div class="card-header bg-dark text-white">
      <i class="fa fa-align-justify"> </i>{{title}}: {{nombre_empleado}}
      <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right" v-show="detalle">
        <i class="fas fa-arrow-left"></i>&nbsp;Atras
      </button>
    </div>
    <!-- <div class="card-body"> -->
    <vue-element-loading :active="isLoading"/>
    <table class="VueTables__table table table-hover table-sm" v-show="!detalle">
      <thead class="table-light">
        <tr>
          <th scope="col">Renovar</th>
          <th scope="col">Empleado</th>
          <th scope="col">Fecha de vencimiento</th>
          <th scope="col">Dias restantes</th>
        </tr>
      </thead>
      <tbody>
        <tr  v-for="item in contratos" :value="item.contratos.id" :key="item.contratos.id">
          <!-- :class="item.estilo"> -->
          <td>
            <button class="btn btn-sm btn-success" @click="renovar(1, item.contratos.id)"><i class="fas fa-check"></i> Si</button>
            <button class="btn btn-sm btn-danger" @click="renovar(0, item.contratos.id)"><i class="fas fa-close"></i> No</button>
          </td>
          <td>{{ item.contratos.e_nombre }}</td>
          <td>{{ item.contratos.fecha_fin }}</td>
          <td>{{ item.diff}}</td>
        </tr>
        <tr  v-for="item in contratos_renovar" :value="item.id" :key="item.id">
           <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <div class="btn-group dropup" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
          
            <button class="dropdown-item" @click="porenovar(1, item); actualizar();"><i class="fas fa-sync-alt"></i>&nbsp;Renovar</button>
            <button class="dropdown-item" @click="porenovar(0, item); crearnuevo();"><i class="far fa-plus-square"></i>&nbsp;Nuevo</button>
          </div></div></div>
          <td>{{ item.empleado }}</td>
          <td>{{ item.fecha_fin }}</td>
        </tr>
      </tbody>
    </table>

    <!-- </div> -->
    <div class="card" v-show="ver">

      <div class="card-body">
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="proyecto_id">Proyecto</label>
          <div class="col-md-3">
            <select class="form-control" id="proyecto_id"  name="proyecto_id" v-model="tipoContrato.proyecto_id" v-validate="'excluded:0'" data-vv-as="Proyecto">
              <option v-for="item in listaProyectos" :value="item.proyecto.id" :key="item.proyecto.id">{{ item.proyecto.nombre }}</option>
            </select>
            <span class="text-danger">{{ errors.first('proyecto_id') }}</span>
          </div>
          <label class="col-md-3 form-control-label" for="empresa_id">Empresa</label>
          <div class="col-md-3">
            <select class="form-control" id="empresa_id"  name="empresa_id" v-model="tipoContrato.empresa_id"
             @change="validaciondos" v-validate="'excluded:0'" data-vv-as="Empresa">
              <option v-for="item in listaEmpresas" :value="item.id" :key="item.id">{{ item.nombre }}</option>
            </select>
            <span class="text-danger">{{ errors.first('empresa_id') }}</span>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3" for="text-input">Inicio de Contrato</label>
          <div class="col-md-3">
            <input type="date" v-validate="'required'" name="fecha_ingreso" v-model="tipoContrato.fecha_ingreso" class="form-control" placeholder="Fecha de Inicio">
            <span class="text-danger">{{ errors.first('fecha_ingreso') }}</span>
          </div>
          <label class="col-md-3" for="text-input">Fin de Contrato</label>
          <div class="col-md-3">
            <input id="fechafin" type="date" v-bind:disabled="tipoContrato.tipo_contrato_id == 2" v-validate="'required'" name="fecha_fin" v-model="tipoContrato.fecha_fin" class="form-control" placeholder="Fecha de Finalización" @change="validar">
            <span class="text-danger">{{ errors.first('fecha_fin') }}</span>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="tipo_contrato_id">Tipo de Contrato</label>
          <div class="col-md-3">
            <select class="form-control" id="tipo_contrato_id"  name="tipo_contrato_id" v-model="tipoContrato.tipo_contrato_id" v-validate="'excluded:0'" data-vv-as="Tipo de Contrato">
              <option v-for="item in listaTipoContrato" :value="item.id" :key="item.id">{{ item.nombre }}</option>
            </select>
            <span class="text-danger">{{ errors.first('tipo_contrato_id') }}</span>
          </div>
          <label class="col-md-3 form-control-label" for="tipo_nomina_id">Tipo de Nómina</label>
          <div class="col-md-3">
            <select class="form-control" id="tipo_nomina_id"  name="tipo_nomina_id" v-model="tipoContrato.tipo_nomina_id" v-validate="'excluded:0'" data-vv-as="Tipo de Nómina">
              <option v-for="item in listaTipoNomina" :value="item.id" :key="item.id">{{ item.nombre }}</option>
            </select>
            <span class="text-danger">{{ errors.first('tipo_nomina_id') }}</span>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-3 form-control-label" for="horario_id">Horario de Trabajo</label>
          <div class="col-md-3">
            <select class="form-control" id="horario_id"  name="horario_id" v-model="tipoContrato.horario_id" v-validate="'excluded:0'" data-vv-as="Horario de Trabajo">
              <option v-for="item in listaHorarios" :value="item.id" :key="item.id" v-if="item.id <= 2">
                De: {{ item.hora_entrada }} A {{ item.hora_salida }}
              </option>
              <option v-for="item in listaHorarios" :value="item.id" :key="item.id" v-if="item.id == 3">
                Sin Horario
              </option>
              <option v-for="item in listaHorarios" :value="item.id" :key="item.id" v-if="item.id == 4">
                De: {{ item.hora_entrada }} A {{ item.hora_salida }}
              </option>
            </select>
            <span class="text-danger">{{ errors.first('horario_id') }}</span>
          </div>
          <label class="col-md-3 form-control-label" for="tipo_ubicacion_id">Ubicación</label>
          <div class="col-md-3">
            <select class="form-control" id="tipo_ubicacion_id"  name="tipo_ubicacion_id" v-model="tipoContrato.tipo_ubicacion_id" v-validate="'excluded:0'" data-vv-as="Ubicación" >
              <option v-for="item in listaTipoUbicacion" :value="item.id" :key="item.id">{{ item.nombre }}</option>
            </select>
            <span class="text-danger">{{ errors.first('tipo_ubicacion_id') }}</span>
          </div>
        </div>

      </div>
      <div class="card-footer">
        <button type="button" class="btn btn-secondary" v-bind:disabled="desabilotarboton == 1" @click="nuevoc(tipoContrato.contrato_id); guardarContrato();" ><i class="fas fa-save"></i>&nbsp;Guardar</button>
      </div>
    </div>

    <div class="card" v-show="renovacion">
      <div class="card-body">
        <table class="VueTables__table table table-hover table-sm">
          <thead class="table-light">
            <tr>
              <th scope="col">Empresa</th>
              <th scope="col">Proyecto</th>
            </tr>

          </thead>
          <tbody>
            <tr>
              <td>{{datos == null ? '' :datos.nombre_empresa}}</td>
              <td>{{datos == null ? '' :datos.nombre_proyecto}}</td>
            </tr>

          </tbody>
        </table>
        <table class="VueTables__table table table-hover table-sm">
          <thead class="table-light">
            <tr>
              <th scope="col">Inicio contrato</th>
              <th scope="col">Fin de contrato</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{datos == null ? '' :datos.fecha_ingreso}}</td>
              <td>{{datos == null ? '' :datos.fecha_fin}}</td>
            </tr>
          </tbody>
        </table>
        <table class="VueTables__table table table-hover table-sm">
          <thead class="table-light">
            <th>Tipo de contrato</th>
            <th>Tipo de nómina</th>
            <th>Ubicación</th>
          </thead>
          <tbody>
            <tr>
              <td>{{datos == null ? '' :datos.nombre_tipo_contrato}}</td>
              <td>{{datos == null ? '' :datos.nombre_tipo_nomina}}</td>
              <td>{{datos == null ? '' :datos.ubicacion}}</td>
            </tr>
          </tbody>
        </table>
        <br><hr>
        <div class="form-group row sm-5">
          <label class="col-md-3" for="text-input">Extender fin de contrato:</label>
          <div class="col-md-6">
            <input type="date" name="fecha_fin" v-model="tipoContrato.fecha_fin" class="form-control" placeholder="Fecha de Finalización" >
            <span class="text-danger">{{ errors.first('fecha_fin') }}</span>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="button" class="btn btn-secondary" @click="actualizarcontrato(tipoContrato.contrato_id);"><i class="fas fa-save"></i>&nbsp;Guardar</button>
      </div>
    </div>

  </div>
</template>
<script>
import Utilerias from '../../Herramientas/utilerias.js';

export default {
  data() {
    return {
      isLoading: false,
      contratos: [],
      datos : null,
      title : 'Contratos próximos a vencer',
      contratos_renovar : [],
      detalle: false,
      renovacion : false,
      solicitud: [],
      estilo : '',
      nombre_empleado : '',
      ver : false,
      desabilotarboton : 0,
      tipoContrato: {
        id: 0,
        fecha_ingreso: '',
        fecha_fin: '',
        tipo_nomina_id: 0,
        empleado_id: 0,
        tipo_ubicacion_id: 0,
        horario_id: 0,
        tipo_contrato_id: 0,
        proyecto_id: 0,
        motivo: '',
        empresa_id: 0,
        contrato_id : 0,
      },
      listaTipoNomina: [],
      listaEmpleados: [],
      listaTipoContrato: [],
      listaTipoUbicacion: [],
      listaHorarios: [],
      listaProyectos: [],
      listaEmpresas: [],
    }
  },
  methods: {
    validar(){
      var a = this.tipoContrato.fecha_ingreso;
      var b = this.tipoContrato.fecha_fin;
      var fechaIngreso = new Date(a).getTime();
      var fechaFin    = new Date(b).getTime();
      var diff = fechaFin - fechaIngreso;
      var diferencia =diff/(1000*60*60*24);
      if (diferencia < 0) {
        this.desabilotarboton = 1;
        toastr.error('La fecha de finalizacion del Contrato no debe ser menor a la fecha de Inicio');
      }
      else{
        this.desabilotarboton = 0;
      }
    },
    diaActual(){
      var hoy = new Date ();
      var dd = hoy.getDate();
      var mm = hoy.getMonth()+1; //hoy es 0!
      var yyyy = hoy.getFullYear();
      if(dd<10) {  dd='0'+dd   }
      if(mm<10) {  mm='0'+mm   }
      hoy = yyyy+'-'+mm+'-'+dd;
      this.tipoContrato.fecha_ingreso = hoy;
    },
    gerData()
    {
      let me=this;
      axios.get('/empleado').then(response => {
        me.listaEmpleados = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });

      axios.get('/tiponomina').then(response => {
        me.listaTipoNomina = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });

      axios.get('/tipocontrato').then(response => {
        me.listaTipoContrato = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });

      axios.get('/tipoubicacion').then(response => {
        me.listaTipoUbicacion = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });

      axios.get('/horario').then(response => {
        me.listaHorarios = response.data;
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
      axios.get('/empresas').then(response => {
        me.listaEmpresas = response.data;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    getData() {
      this.isLoading = true;
      let me=this;
      axios.get('/contratosproxvencer/ver').then(response => {
        me.contratos = response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });

      axios.get('/contratosrenovar').then(response =>{
        me.contratos_renovar = response.data;
      }).catch(function (error){
        console.log(error);
      });
    },
    renovar(renovar, id) {
      this.isLoading = true;
      let me=this;
      axios({
        method: 'POST',
        url: (renovar == 1) ? '/renovar/contrato' : '/norenovar/contrato',
        data: {
          'id': id
        }
      }).then(function (response) {
        me.isLoading = false;
        if (renovar == 1)
        toastr.success('Contrato en espera de renovación.');
        else
        toastr.error('Contrato no renovado');
        me.getData();
      }).catch(function (error) {
        console.log(error);
      });
    },

    porenovar(porenovar, data = [])
    { let me = this;
      var empleado_id = data['empleado_id'];
      var contrato_id = data['contrato_id'];
      var empleado =  data['empleado'];
      if (porenovar == 0) {
        me.tipoContrato.empleado_id = empleado_id;
        me.tipoContrato.contrato_id = contrato_id;
        me.nombre_empleado = empleado;
      }
      else if (porenovar == 1) {
        me.datos = data;
        me.tipoContrato.empleado_id = empleado_id;
        me.tipoContrato.contrato_id = contrato_id;
        me.nombre_empleado = empleado;
      }
    },
    validaciondos(){
      var id_empleado =this.tipoContrato.empleado_id;
      var array = [];
      let me = this;
      axios.get('/contrato/'+id_empleado +'/condicionc').then(response=> {
        for (var i = 0; i < response.data.length; i++){
          array.push(response.data[i].empresa_id);
        }
        for(var j = 0; j < array.length; j++){
          if(this.tipoContrato.empresa_id === array[j]){
            toastr.error('Error Empresa', 'No se puede crear nuevo contrato, No se puede seleccionar una empresa con la cual ya se tiene un contrato');
            this.desabilotarboton=1;
            break;
          }
          else{
            this.desabilotarboton=0;
          }
        }
      })
      .catch(function (error){
        console.log(error);
      });
    },
    crearnuevo()
    {
      this.ver = true;
      this.detalle = true;
      this.title = 'Nuevo contrato de';
      let me= this;
      me.diaActual();
    },
    actualizar()
    {
      this.renovacion = true;
      this.detalle = true;
      this.title = 'Renovar contrato de';
    },
    maestro()
    {
      Utilerias.resetObject(this.tipoContrato);
      this.ver = false;
      this.detalle = false;
      this.renovacion = false;
      this.title = 'Contratos próximos a vencer';
       this.nombre_empleado = '';
    },
    guardarContrato()
    {
      this.isLoading = true;
      let me=this;
      axios({
        method: 'POST',
        url:  '/contrato',
        data: {
          'tipo_ubicacion_id':this.tipoContrato.tipo_ubicacion_id,
          'fecha_ingreso':this.tipoContrato.fecha_ingreso,
          'fecha_fin':this.tipoContrato.fecha_fin,
          'tipo_nomina_id':this.tipoContrato.tipo_nomina_id,
          'empleado_id':this.tipoContrato.empleado_id,
          'horario_id':this.tipoContrato.horario_id,
          'tipo_contrato_id':this.tipoContrato.tipo_contrato_id,
          'proyecto_id':this.tipoContrato.proyecto_id,
          'id':this.tipoContrato.id,
          'empresa_id':this.tipoContrato.empresa_id,
          'metodo' : 'Nuevo',
        }
      }).then(function (response) {
        me.isLoading = false;
        me.maestro();
        toastr.success('Contrato Registrado Correctamente');
        toastr.info('Ahora Debe Registrar un Sueldo');
        me.getData();
      }).catch(function (error) {
        console.log(error);
      });
    },
    nuevoc(id)
    {
      this.isLoading = true;
      let me=this;
      axios({
        method: 'POST',
        url: '/nuevo/contrato',
        data: {
          'contrato_id': id,
          'id': me.datos.id,

        }
      }).then(function (response) {
        // me.getData();
      }).catch(function (error) {
        console.log(error);
      });
    },
    actualizarcontrato(id)
    {
      this.isLoading = true;
      let me=this;
      axios({
        method: 'POST',
        url: '/porenovar/contrato',
        data: {
          'id': me.datos.id,
          'id_contrato' : id,
          'fecha_fin' : me.tipoContrato.fecha_fin,
        }
      }).then(function (response) {
        me.maestro();
        toastr.success('Contrato Renovado Correctamente');
         me.getData();
      }).catch(function (error) {
        console.log(error);
      });
    },
    cargarContratos() {
      this.getData();
    }
  },
  mounted() {
     this.gerData();
     this.diaActual();

  }
}
</script>

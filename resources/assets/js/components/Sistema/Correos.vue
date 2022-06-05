<template>
  <main class="main">
    <div class="container-fluid">
      <br>
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Permisos Correos
          <!-- <button type="button"  class="btn btn-dark float-sm-right">
          <i class="fas fa-plus"></i>&nbsp;Nuevo
        </button> -->
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-3">
            <label>Usuario</label>
            
            <v-select :options="usuario" v-model="usuario_datos" label="name" v-validate="'required'" data-vv-name="Usuario" @input="validacion($event)"></v-select>
            <span class="text-danger">{{errors.first('Usuario')}}</span>
          </div>
          <div class="form-group col-md-4">
            <label>Empleado</label>
            <v-select v-model="empleado" label="name"></v-select>
          </div>
          <div class="form-group col-md-5">
            <label>Correo</label>
            <input type="text" data-vv-name="correo" v-validate="'required'" class="form-control" v-model="correo" >
            <span class="text-danger">{{errors.first('correo')}}</span>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-3">
            <label>Rango</label>
            <select class="form-control" @change="changeRango($event)"  v-validate="{ regex: /^(1|2|3|4|5|6|7|8|9|10|11|12|13)$/  }" data-vv-name="Rango" v-model="frecuencia">
              <option value="0">Seleccione rango</option>
              <option value="1">Cada minuto</option>
              <option value="2">Cada 5 minutos</option>
              <option value="3">Cada 10 minutos</option>
              <option value="4">Cada 30 minutos</option>
              <option value="5">Cada hora</option>
              <option value="6">Cada hora despues</option>
              <option value="7">Cada día</option>
              <option value="8">Cada día a </option>
              <!-- <option value="9">Cada día entre </option> -->
              <option value="10">Cada semana </option>
              <option value="11">Cada semana el </option>
              <option value="12">Cada mes</option>
              <option value="13">Cada mes el </option>
            </select>
            <span class="text-danger">{{errors.first('Rango')}}</span>
          </div>
          <!-- CASO 6 -->
          <div class="form-group col-md-1" v-show="rango == 6">
            <label>&nbsp;</label>
            <label >del minuto:&nbsp;</label>
          </div>
          <div class="form-group col-md-3" v-show="rango == 6">
            <!-- Validacion de minutos -->
            <label>&nbsp;</label>
            <input type="text" data-vv-name="input" v-model="seis_minutos" name='input-field-3' v-validate="{ regex: /^([0-5]?[0-9])$/  }" placeholder="Minuto" class="form-control" >
            <span class="text-danger">{{ errors.first('input') }}</span>
            <!-- Fin de Validacion de minutos -->
          </div>
          <!-- FIN CASO 6 -->

          <!-- CASO 8 -->
          <div class="form-group col-md-1" v-show="rango == 8">
            <!-- Validacion de horas -->
            <label>Hora</label>
            <input type="text" data-vv-name="input" v-model="ocho_hora" name='input-field-3' v-validate="{ regex: /^([01]?[0-9]|2[0-3])$/  }" placeholder="Hora" class="form-control" >
            <span class="text-danger">{{ errors.first('input') }}</span>
            <!-- Fin de Validacion de horas -->
          </div>

          <div class="form-group col-md-1" v-show="rango == 8">
            <!-- Validacion de minutos -->
            <label>Minuto</label>
            <input type="text" data-vv-name="input" v-model="ocho_minuto" name='input-field-3' v-validate="{ regex: /^([0-5]?[0-9])$/  }" placeholder="Minuto" class="form-control" >
            <span class="text-danger">{{ errors.first('input') }}</span>
            <!-- Fin de Validacion de minutos -->
          </div>
          <!-- FIN CASO 8 -->

          <!-- CASO 9 -->
          <div class="form-group col-md-1" v-show="rango == 9">
            <!-- Validacion de horas -->
            <label>Hora uno</label>
            <input type="text" data-vv-name="input" v-model="nueve_hora_uno" name='input-field-3' v-validate="{ regex: /^([01]?[0-9]|2[0-3])$/  }" placeholder="Hora" class="form-control" >
            <span class="text-danger">{{ errors.first('input') }}</span>
            <!-- Fin de Validacion de horas -->
          </div>

          <div class="form-group col-md-1" v-show="rango == 9">
            <!-- Validacion de minutos -->
            <label>Hora dos</label>
            <input type="text" data-vv-name="input" v-model="nueve_hora_dos" name='input-field-3' v-validate="{ regex: /^([01]?[0-9]|2[0-3])$/  }" placeholder="Hora" class="form-control" >
            <span class="text-danger">{{ errors.first('input') }}</span>
            <!-- Fin de Validacion de minutos -->
          </div>
          <!-- CASO 9-->

          <!-- CASO 11 -->
          <div class="form-group col-md-2" v-show="rango == 11">
            <!-- SELECT de dias de la semana -->
            <label>Nombre día </label>
            <select class="form-control" name="dias_semana" v-model="once_dias" data-vv-name="input">
              <option value="0" selected>Seleccione</option>
              <option value="1">Lunes</option>
              <option value="2">Martes</option>
              <option value="3">Miercoles</option>
              <option value="4">Jueves</option>
              <option value="5">Viernes</option>
              <option value="6">Sabado</option>
              <option value="7">Domingo</option>
            </select>
            <span class="text-danger">{{ errors.first('input') }}</span>
            <!-- fin de SELECT de dias de la semana -->
          </div>
          <div class="form-group col-md-1" v-show="rango == 11">
            <!-- Validacion de horas -->
            <label>Hora</label>
            <input type="text" data-vv-name="input" name='input-field-3' v-model="once_hora" v-validate="{ regex: /^([01]?[0-9]|2[0-3])$/  }" placeholder="Hora" class="form-control" >
            <span class="text-danger">{{ errors.first('input') }}</span>
            <!-- Fin de Validacion de horas -->
          </div>

          <div class="form-group col-md-1" v-show="rango == 11">
            <!-- Validacion de minutos -->
            <label>Minuto</label>
            <input type="text" data-vv-name="input" name='input-field-3' v-model="once_minuto" v-validate="{ regex: /^([0-5]?[0-9])$/  }" placeholder="Minuto" class="form-control" >
            <span class="text-danger">{{ errors.first('input') }}</span>
            <!-- Fin de Validacion de minutos -->
          </div>

          <!-- FIN CASO 11 -->

          <!-- CASO 13 -->
          <div class="form-group col-md-2" v-show="rango == 13">
            <!-- Validacion de dias -->
            <label>Número dia</label>
            <input type="text" data-vv-name="input" name='input-field-3' v-model="trece_num_dia" v-validate="{ regex: /^([1]?[0-9]|2[0-8])$/  }" placeholder="Minuto" class="form-control" >
            <span class="text-danger">{{ errors.first('input') }}</span>
            <!-- Fin de Validacion de dias -->
          </div>
          <div class="form-group col-md-1" v-show="rango == 13">
            <!-- Validacion de horas -->
            <label>Hora</label>
            <input type="text" data-vv-name="input" name='input-field-3' v-model="trece_hora" v-validate="{ regex: /^([01]?[0-9]|2[0-3])$/  }" placeholder="Hora" class="form-control" >
            <span class="text-danger">{{ errors.first('input') }}</span>
            <!-- Fin de Validacion de horas -->
          </div>

          <div class="form-group col-md-1" v-show="rango == 13">
            <!-- Validacion de minutos -->
            <label>Minuto</label>
            <input type="text" data-vv-name="input" name='input-field-3' v-model="trece_minuto" v-validate="{ regex: /^([0-5]?[0-9])$/  }" placeholder="Minuto" class="form-control" >
            <span class="text-danger">{{ errors.first('input') }}</span>
            <!-- Fin de Validacion de minutos -->
          </div>
          <!--FIN CASO 13 -->
          <div class="form-group col-md-2">
            <label>Tipo de correo</label>
            <select class="form-control" v-model="tipo_correo" >
              <option value="aviso:stock">Aviso stock minimo y maximo</option>
              <option value="aviso:cumpleaños">Aviso Cumpleaños</option>
            </select>
          </div>

        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <button type="button" v-if="mostrar_boton==1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button" v-if="mostrar_boton==2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
            <button type="button" class="btn btn-outline-dark" @click="vaciar()"><i class="fas fa-window-close"></i>&nbsp;Cancelar</button>
          </div>
        </div>
      </div>

    </div>
    <div class="card">
      <div class="card-body">
        <v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
          <template slot="id" slot-scope="props">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
              <div class="btn-group dropup" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                  <template>
                    <button type="button" @click="actualizar($event,props.row)" class="dropdown-item">
                      <i class="icon-pencil"></i>&nbsp; Actualizar
                    </button>
                  </template>
                  <template>
                    <button v-if="props.row.estado == 1"  type="button" class="dropdown-item" @click="eliminar(props.row.id)">
                      <i class="icon-trash"></i>&nbsp; Desactivar
                    </button>
                    <button v-if="props.row.estado == 0"  type="button" class="dropdown-item" @click="eliminar(props.row.id)">
                      <i class="icon-trash"></i>&nbsp; Activar
                    </button>
                  </template>
                </div>
              </div>
            </div>
          </template>
          <template slot="frecuencia" slot-scope="props">
            <template v-if="props.row.frecuencia == 1">
              <span class="text-success">Cada minuto</span>
            </template>
            <template v-if="props.row.frecuencia == 2">
              <span class="text-success">Cada 5 minutos</span>
            </template>
            <template v-if="props.row.frecuencia == 3">
              <span class="text-success">Cada 10 minutos</span>
            </template>
            <template v-if="props.row.frecuencia == 4">
              <span class="text-success">Cada 30 minutos</span>
            </template>
            <template v-if="props.row.frecuencia == 5">
              <span class="text-success">Cada hora</span>
            </template>
            <template v-if="props.row.frecuencia == 6">
              <span class="text-success">Cada hora despues del minuto {{props.row.tiempo_rango}}</span>
            </template>
            <template v-if="props.row.frecuencia == 7">
              <span class="text-success">Cada día</span>
            </template>
            <template v-if="props.row.frecuencia == 8">
              <span class="text-success">Cada día a las {{props.row.tiempo_rango}}</span>
            </template>
            <template v-if="props.row.frecuencia == 10">
              <span class="text-success">Cada semana</span>
            </template>
            <template v-if="props.row.frecuencia == 11">
              <span class="text-success">Cada semana el ...</span>
            </template>
            <template v-if="props.row.frecuencia == 12">
              <span class="text-success">Cada mes</span>
            </template>
            <template v-if="props.row.frecuencia == 13">
              <span class="text-success">Cada mes el ...</span>
            </template>

          </template>
          <template slot="estado" slot-scope="props">
            <template v-if="props.row.estado">
              <button type="button" class="btn btn-outline-success">Activo</button>
            </template>
            <template v-else>
              <button type="button" class="btn btn-outline-danger">Desactivado</button>
            </template>
          </template>
        </v-client-table>

      </div>
    </div>
  </div>

</main>

</template>
<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
  data(){
    return {
      id : 0,
      usuario : [],
      empleado : [],
      correo : '',
      desabilitarempleado : 0,
      desabilitarcorreo : 0,
      rango : '',
      frecuencia : 0,
      mostrar_boton : 0,
      tipo_correo : '',
      usuario_datos : '',
      seis_minutos : 0,
      ocho_hora : 0,
      ocho_minuto : 0,
      nueve_hora_uno : 0,
      nueve_hora_dos : 0,
      once_dias : 0,
      once_hora : 0,
      once_minuto : 0,
      trece_num_dia : 1,
      trece_hora : 0,
      trece_minuto :0,
      columns: ['id','name_user','empleado_nombre','correo','frecuencia','tipo_correo','estado'],
      tableData: [],
      options: {
        headings: {
          id: 'Acciones',
        },
        perPage: 10,
        perPageValues: [],
        skin: config.skin,
        sortIcon: config.sortIcon,
        sortable: [],
        filterable: [],
        filterByColumn: true,
        texts:config.texts,
        listColumns: {
          estado: [{
            id: 1,
            text: 'Activo'
          },
          {
            id: 0,
            text: 'Desactivado'
          }
        ]
      },
    },

  }
},
computed :{

},
methods : {
  getData(){
    let me = this;
    axios.get('/correosprogramados').then(response => {
      me.tableData = response.data;
    })
    .catch(function (error) {
      console.log(error);
    });
    axios.get('/usuario').then(response => {
      response.data.forEach(data => {
        me.usuario.push({id: data.usuario.id, name : data.usuario.name,
          empleado_id : data.usuario.empleado_id, nombre : data.usuario.nombre_empleado});
        });
      }).catch(error => {
        console.error(error);
      });
    },

    actualizar(evento, data)
    {
      var datos = [];
      var datos_dos = [];
      this.id = data.id;
      datos.push({id: data.user_id, name : data.name_user,
        empleado_id : data.empleado_id, nombre : data.empleado_nombre});
        this.usuario_datos = datos;
        datos_dos.push({id: data.empleado_id, name: data.empleado_nombre});
        this.empleado = datos_dos;
        this.correo = data.correo;
        this.frecuencia = data.frecuencia;
        this.tipo_correo = data.tipo_correo;
        this.mostrar_boton = 2;
        this.rango = data.frecuencia;
        switch (data.frecuencia) {
          case 6:
          this.seis_minutos = data.tiempo_rango;
          break;
          case 8:
          var valores = data.tiempo_rango.split(':');
          this.ocho_hora = valores[0];
          this.ocho_minuto = valores[1];
          break;
          case 11:
          var valores = data.tiempo_rango.split('&');
          this.once_dias = valores[0];
          var valores_uno = valores[1].split(':');
          this.once_hora = valores_uno[0];
          this.once_minuto = valores_uno[1];

          break;
          case 13:
          var valores = data.tiempo_rango.split('&');
          this.trece_num_dia = valores[0];
          var valores_uno = valores[1].split(':');
          this.trece_hora = valores_uno[0];
          this.trece_minuto = valores_uno[1];
          break;

        }

      },

      vaciar()
      {
        this.usuario = [];
        this.empleado = [];
        this.correo = '';
        this.desabilitarempleado = 0;
        this.desabilitarcorreo = 0;
        this.rango = '';
        this.frecuencia = 0;
        this.mostrar_boton = 0;
        this.tipo_correo = '';
        this.usuario_datos = '';
        this.seis_minutos = 0;
        this.ocho_hora = 0;
        this.ocho_minuto = 0;
        this.nueve_hora_uno = 0;
        this.nueve_hora_dos = 0;
        this.once_dias = 0;
        this.once_hora = 0;
        this.once_minuto = 0;
        this.trece_num_dia = 1;
        this.trece_hora = 0;
        this.trece_minuto =0;
        this.id = 0;
      },

      validacion(data)
      {
        let me = this;
        if ((Object.keys(data).length) === 4) {
          me.empleado = [];
          if(data.empleado_id === null){
            me.desabilitarcorreo = 1;
            me.empleado = [];
//            me.correo = '';
          }else {
            me.empleado.push({id:data.empleado_id, name : data.nombre});
            me.desabilitarcorreofuncion(data.empleado_id);
          }
          // }
        }else if ((Object.keys(data).length) === 1) {
          me.empleado = [];
          if(data[0].empleado_id === null){
            me.desabilitarcorreo = 1;
            me.empleado = [];
          //  me.correo = '';
          }else {
            me.empleado.push({id:data[0].empleado_id, name : data[0].nombre});
  //          me.desabilitarcorreofuncion(data[0].empleado_id);
          }
        }

      },

      desabilitarcorreofuncion(data = 1)
      {
        axios.get('/contacto/'+data).then(response => {
          if (response.data.length == 0) {
            this.desabilitarcorreo = 1;
          }else {
            this.correo = response.data.correo_electronico;
          }
        });
      },

      changeRango(data)
      {
        this.mostrar_boton = 1;
        this.rango = data.target.value;

      },

      eliminar(id)
      {
        axios.get('/correosprogramados/'+id).then(response => {
          this.getData();
          toastr.success('Correcto','Desactivado');
          this.vaciar();
        }).catch(error => {console.error(error);});
      },

      guardar(nuevo)
      {
        this.$validator.validate().then(result => {
          if(result){
            axios({
              method : nuevo ? 'POST' : 'PUT',
              url : nuevo ? '/correosprogramados' : '/correosprogramados/update',
              data : {
                'id' : this.id,
                'usuario' : this.usuario_datos.id,
                'empleado' : this.usuario_datos.empleado_id,
                'correo' : this.correo,
                'rango' : this.rango,
                'frecuencia' : this.frecuencia,
                'tipo_correo' : this.tipo_correo,
                'seis_minutos' : this.seis_minutos,
                'ocho_hora' : this.ocho_hora,
                'ocho_minuto' : this.ocho_minuto,
                'nueve_hora_uno' : this.nueve_hora_uno,
                'nueve_hora_dos' : this.nueve_hora_dos,
                'once_dias' : this.once_dias,
                'once_hora' : this.once_hora,
                'once_minuto' : this.once_minuto,
                'trece_num_dia' : this.trece_num_dia,
                'trece_hora' : this.trece_hora,
                'trece_minuto' :this.trece_minuto,
              }
            }).then(response => {
              this.getData();
              toastr.success('Correcto','Permiso Registrado');
              this.vaciar();
            }).catch(err => {
              console.error(err);
            });
          }else {
            toastr.warning('Atención','Complete o revise las validaciones');
          }
        });
      }
    },
    mounted(){
      this.getData();
    }
  }

  </script>

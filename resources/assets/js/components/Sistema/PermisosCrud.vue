<template>
  <main class="main">
    <div class="container-fluid">
      <!-- Ejemplo de tabla Listado -->
      <!-- <br> -->
      <div class="card">
        <div class="card-header">
          <!-- encabezado -->
            <i class="fa fa-align-justify"></i>Asignar permisos de CRUD.
          </div>
        <div class="card-body">
          <vue-element-loading :active="isLoading"/>
<!-- esta lista se carga desde el metodo get data se asigna a listaUsuarios, desde la ruta /usuario del controlador PermisoUser-->
          <div class="form-group">
            <label for="usuario">Selecciona Usuario</label>
<!-- se asigna el v model y se declara como array vacío [] y tambien el v-validate y el data-vv-name para la validacion que pide obligatoriamente seleccionar usuario-->
            <select class="form-control col-md-4" v-model="user" id="user" v-validate="'required'" data-vv-name="user" placeholder="Usuario" v-on:change="onChangeElementos">
              <!--se inserta un option value porque el 0 nos sirve para que al seleccionar un valor 0 nos resetee el list -->
              <option value="0">---Usuario---</option>
              <!-- se hace la iteracion de la lista (recorrido) con for que toma listaUsuarios que contiene el arreglo que se le asigna y con el alias item para usarlo en este recorrido
            el value nos sirve porque es el valor qe no se ve pero se manda a la base de datos, key es el id y se usa para q no se repita con value en el recorrido, lo que se muestra en la vista es lo q
          se escribe entre {{}}-->
              <option v-for="item in listaUsuarios" :value="item.usuario.id" :key="item.usuario.id">{{item.usuario.name}}</option>
            </select>
            <span class="text-danger">{{ errors.first('user') }}</span>
          </div>

          <div class="form-group">
            <label for="modulo">Selecciona Módulo</label>
          <!--en esta lista se carga el array de los modulos que viene desde la ruta ModulosSistema que se puede checar en web.php para ver que controlador la contiene
        aqui se crea la propiedad vonchange que recibe el id de los modulos y lo carga en una lista para llenar otro campo de rutas de elementos de menu y submenu-->
            <select class="form-control col-md-4" v-model="modulo" id="modulo" v-validate="'required'" name="modulo" placeholder="Modulo" v-on:change="onChangePagina" >
              <option value="0">---Modulo---</option>
              <option v-for="item in listaModulos" :value="item.id" :key="item.id">{{ item.nombre }}</option>
            </select>
            <span class="text-danger">{{ errors.first('modulo') }}</span>
          </div>

          <div class="form-group">
            <label for="usuario">Selecciona la Ruta</label>
            <!-- se crea el vmodel que en el data esta asignado a el objeto tipoPermisos y que contiene user, esta lista se carga desde el id enviado en la lista de modulos que al tomarla la propiedad onchange
          en a funcion onchangepagina recibe las rutas de los menus y submenus pero identifica el id y eso lo muestra desde item page despues de la iteracion -->
          <!-- en la propiedad onchange de la funcion permisorec nos permite recuperar desde la base de datos que controles ya estan activos de cada ruta  -->
            <select class="form-control col-md-4" v-model="tipoPermisos.ruta" id="ruta" v-validate="'required'" name="ruta" placeholder="Ruta" v-on:change="permisorec">
              <option value="0">---Rutas---</option>
              <option v-for="itemp in listaRutas" :pk="itemp.id" :value="itemp.page" >{{ itemp.page }}</option>
            </select>
            <span class="text-danger">{{ errors.first('ruta') }}</span>
          </div>

          <div class="form-group row">

            <div class="col-md-2 col-sm-2">
              <label for="usuario">Asigna permisos</label>
            </div>
<!-- arreglos recibe el json de la tabla de control y se hace la iteracion en un switch para q asigne ai el id del control -->
            <div class="form-group" v-for="itc in arreglos" :key="itc.id" >
              &nbsp;&nbsp;&nbsp;
              {{itc.nombre}}
              <label class="switch switch-default switch-pill switch-dark">
                <input :value= "itc.id" type="checkbox" class="switch-input" checked v-model = "control">
                <span class="switch-label"></span>
                <span class="switch-handle"></span>
              </label>
              &nbsp;&nbsp;&nbsp;
            </div>
          </div>

          <div class="modal-footer">
            <!-- en la funcion menusupdate se guardan los datos que nos envian en las list -->
            <button type="button"  class="btn btn-outline-dark" @click="menusupdate(0)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
            <button type="button"  class="btn btn-outline-danger" @click="guardarTodos()"><i class="fas fa-save"></i>&nbsp;Todos</button>
          </div>

        </div>
      </div>
      <!-- Fin ejemplo de tabla Listado -->
    </div>

  </main>
</template>

<script>
export default {
}
</script>
<style lang="css" scoped>
</style>


<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
  data (){
    return {
      url: '/usuario',
      tipoPermisos: {
        usuario_id: 0,
        control_id : 0,
        ruta :0,
        nombre: '',
        id: 0,
      },
      tipoAccion : 0,
      isLoading: false,
      tableMenus: [],
      menus:[],
      modulo : [],
      listaModulos : [],
      listaRutas : [],
      user : 0,
      control : [],
      listaUsuarios : [],
      arreglos : [],
    }
  },


  methods : {
    getData() {
      let me=this;
      axios.get(me.url).then(response => {
        me.listaUsuarios = response.data;
      })
      .catch(error => {
        console.log(error);
      });
      axios.get('/permisocrud').then(response =>{
        me.arreglos = response.data;
      })
      .catch(error =>{
        console.log(error);
      });
      axios.get('/ModulosSistema').then(response => {
        me.listaModulos = response.data;
        me.isLoading = false;
      })
      .catch(function (error) {
        console.log(error);
      });
    },
//
    onChangePagina(){
      let me = this;
      axios.get('/elementospormodulos/'+this.modulo).then(response =>{
        me.listaRutas = response.data;
        me.tipoPermisos.ruta=0;
        me.control=[];
      })
      .catch(function (error) {
        console.log(error);
      })
    },

    onChangeElementos(){
      let me = this;
      me.tipoPermisos.ruta=0;
      me.modulo=0;
      me.control= [];
    },

      menusupdate(nuevo){
      this.$validator.validate().then(result => {//esta linea asigna la validacion de que se requiera usuario y modulo obligatorio
        if (result) {
        let me = this;
          if (me.tipoPermisos.ruta!=0 && me.modulo!=0 ) {//asigna la validacion que toma el valor 0 de la lista y no asigna permisos
          axios({
            method: nuevo ? 'POST' : 'PUT',
            url: nuevo ? me.url : '/PermisosCrud/'+this.tipoPermisos.id+'/actualizar',//recibe el id desde tipo de permisos que contiene al user y a su id y lo envia a la  ruta PermisosCrud/actualizar
            data: {
              'usuario_id': 	this.user,//con this.user referenciamos al modelo que se creo en la parte de arriba, con 'usuario_id' referenciamos al campo en el controlador que recibe ese parametro
              'ruta' : this.tipoPermisos.ruta,
              'control': this.control,
            }
          }).then(function (response) {//
            if (response.data.status) {
              toastr.success(' Actualizada Correctamente');
             //resetea el valor de ese modelo a 0 por si se requiere que el complemento pase a un valor inicial
              me.tipoPermisos.ruta=0;
              me.control=[]; //este resetea el valor a 0 pero como se llena con un array se resetea este valor pasandole un []
            } else {
              swal({
                type: 'error',
                html: response.data.errors.join('<br>'),
              });
            }
          }).catch(function (error) {
            console.log(error);
          });
        }}
      });
      // console.log(this.user,this.control,this.tipoPermisos.ruta);
    },

    permisorec(){
      var controles = [];
      axios.post('/menusidempc',{//esta ruta muestra un json de los datos que se han llenado para la tabla de permisos_crud
        'usuario_id' : 	this.user, //se le pasa el usuario id
        'ruta' : this.tipoPermisos.ruta, //tmb la ruta en formato strin
      }).then(response => { //response= objeto propiedad data, que es un array todos los array tienen un tañaño que da la prop lenght en push le insertas los valores de la posicion que se envia
        for (var i = 0; i < response.data.length; i++) {
          controles.push(response.data[i].control_id)
        }
        this.control = controles;
        console.log(response);
      }).catch (error =>{
        console.error(error);
      })
    },

    guardarTodos() {
      if (this.user == 0) {
        toastr.error('Seleccionar usuario.');
        return;
      }

        this.isLoading = true;
        let me = this;
        axios({
            method: 'POST',
            url: '/permisocrud/todos',
            data: {
                'usuario_id': this.user
            }
        }).then(function (response) {
            me.isLoading = false;
            if (response.data.status) {
                toastr.success('Se asignaron todos los permisos CRUD');
            } else {
                swal({
                    type: 'error',
                    html: response.data.errors.join('<br>'),
                });
            }
        }).catch(function (error) {
            console.log(error);
        });
    },
  },
  mounted() {
    this.getData();
  }
}
</script>

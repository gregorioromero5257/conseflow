<template>
  <main class="main">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body">

          <div class="row">


            <div class="col-4">
              <!-- LISTA INICIAL -->

              <div class="list-group" id="list-tab" role="tablist">
                <template v-for="t in listas">
                  <li
                  class="list-group-item list-group-item-action"
                  >
                  <button class="btn btn-outline-info" @click="act(t)"><i class="fas fa-save"></i></button>
                  <a id="list-home-list"
                  href="#/conta/catalogo/cuentas/"
                  style="color:black;"
                  @click="buscarChild(t)"
                  >
                  {{t.codigo_agrupador +' '+t.nombre_cuenta_sub}}
                  </a>

                </li>

              </template>
            </div>
            <!-- FIN LISTA INICIAL -->
            <!-- <div class="list-group" id="list-tab" role="tablist">
            <a class="list-group-item list-group-item-action" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">Home</a>
            <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Profile</a>
            <a class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Messages</a>
            <a class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">Settings</a>
          </div> -->
        </div>

        <div class="col-8">
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
              <ol class="breadcrumb">
                <li class="breadcrumb-item" v-for="(item, k) in lista"  :key="item.id"><a href="#conta/catalogo/cuentas/" @click="cambiar(item, k)">{{item.nombre_cuenta_sub}}</a></li>
              </ol>
              <!-- {{lista}} -->
              <!-- {{fin}} -->
              <div class="form-row">
                <div class="col-mb-3 md-3">
                  <label>Cuenta</label>
                  <input type="text" class="form-control" v-model="cuenta">
                </div>
                <div class="col-mb-6 md-3">
                  <label>Nombre</label>
                  <input type="text" class="form-control" v-model="nombre">
                </div>
                <div class="col-mb-3 md-3">
                  <label>&nbsp;</label><br>
                  <button class="btn btn-outline-dark" v-if="accion == 1" @click="guardar(1)"> <i class="fas fa-save"></i> Guardar</button>
                  <button class="btn btn-outline-dark" v-if="accion == 2" @click="guardar(0)"> <i class="fas fa-save"></i> Actualizar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>
</main>
</template>
<script>

export default {
  data(){
    return {
      listas : [],
      lista : [{id : 0, nombre_cuenta_sub : 'Inicio'}],
      cuenta : '',
      nombre : '',
      fin : '',
      id : 0,
      d : '',
      accion : 1,
    }
  },
  methods : {

    getData(){
      axios.get('get/catalogo/cuentas/iniciales').then(response => {
        this.listas = response.data;
      }).catch(e => {
        console.error(e);
      });
    },

    buscarChild(d){

      this.lista.push(d);
      var data = this.lista;
      var fin = data[data.length - 1];

      this.fin = fin;
      this.listas = [];
      // console.log(d);
      axios.get('get/catalogo/cuentas/childs/' + d.id).then(response => {
        this.listas = response.data;
        this.cuenta =fin['codigo_agrupador'] + '.' +  (this.str_pad( ((response.data.length + 1).toString()),2,'0',"STR_PAD_LEFT"));

      }).catch(e => {
        console.error(e);
      });
    },


    cambiar(d, k){
      var valor = k+1;
      var valor_eliminar = this.lista.length - valor;
      var removed = this.lista.splice(valor, valor_eliminar);

      var data = this.lista;
      var fin = data[data.length - 1];
      this.fin = fin;

      this.listas = [];

      axios.get('get/catalogo/cuentas/childs/' + d.id).then(response => {
        this.listas = response.data;
        this.cuenta = fin['codigo_agrupador']+ '.'+  (this.str_pad( ((response.data.length + 1).toString()),2,'0',"STR_PAD_LEFT" ));
      }).catch(e => {
        console.error(e);
      });
    },

    str_pad(str, pad_length, pad_string, pad_type){
      var len = pad_length - str.length;

      if(len < 0) return str;

      var pad = new Array(len + 1).join(pad_string);

      if(pad_type == "STR_PAD_LEFT") return pad + str;

      return str + pad;

    },

    actualizarChild(d){
      // console.log(d);
      alert('sd');

    },

    guardar(nuevo){

      axios({
        method : nuevo ? 'POST' : 'PUT',
        url : nuevo ? 'guardar/catalogos/cuentas' : 'actualizar/catalogos/cuentas',
        data : {
              id : this.id,
              pertenece_id : this.fin.id,
              codigo_agrupador : this.cuenta,
              nombre_cuenta_sub : this.nombre,
            }
      }).then(response => {

        this.listas = [];
        axios.get('get/catalogo/cuentas/childs/' + this.fin.id).then(response => {
          this.listas = response.data;
          // this.cuenta = '';
          this.nombre = '';
          this.accion = 1;
          // this.fin = '';
        }).catch(e => {
          console.error(e);
        });

      }).catch(e => {
        console.error(e);
      });
    },

    act(d){
      // console.log(d);
      this.accion = 2;
      this.id = d.id;
      this.cuenta = d.codigo_agrupador;
      this.nombre  = d.nombre_cuenta_sub;
    },




  },
  mounted () {
    this.getData('');
  }
}

</script>

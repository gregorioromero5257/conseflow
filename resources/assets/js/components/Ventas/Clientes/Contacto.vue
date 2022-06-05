<template>
        <div>
            <!-- Ejemplo de tabla Listado -->

            <br>
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Contacto

                    <button type="button" @click="maestro()" class="btn btn-secondary float-sm-right">
                        <i class="fas fa-arrow-left"></i>&nbsp;Atras
                    </button>

                    <button  type="button" @click="abrirModal('contactos','registrar')" class="btn btn-dark float-sm-right">
                        <i class="fas fa-plus"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="card-body">
                    <vue-element-loading :active="isLoading"/>

                    <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">
                        <template slot="id" slot-scope="props">
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group dropup" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-grip-horizontal"></i> Acciones
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <button  type="button" @click="abrirModal('contactos','actualizar',props.row)" class="dropdown-item">
                                            <i class="icon-pencil"></i>&nbsp; Actualizar cliente.
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </v-client-table>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dark modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            <div class="form-group col-md-8">
                                <label> Nombre</label>
                                <input type="text" class="form-control" v-validate="'required'" v-model="contacto.nombre_contacto" data-vv-name="nombre_contacto" placeholder="Nombre">
                                <span class="text-danger">{{ errors.first('nombre_contacto') }}</span>
                            </div>

                            <div class="form-group col-md-8">
                                <label> Telefono de Oficina</label>
                                <input type="number" class="form-control" v-validate="'required'" v-model="contacto.telefono_oficina" data-vv-name="telefono_oficina" placeholder="Tel Oficina">
                                <span class="text-danger">{{ errors.first('telefono_oficina') }}</span>
                            </div>

                            <div class="form-group col-md-8" >
                                <label > Telefono movil</label>
                                <input type="number" class="form-control"  v-validate="'required'" v-model="contacto.telefono_movil" data-vv-name="telefono_movil" placeholder="Tel Movil">
                                <span class="text-danger">{{ errors.first('telefono_movil') }}</span>
                            </div>

                            <div class="form-group col-md-8">
                                <label > Area</label>
                                <input type="text" class="form-control"  v-validate="'required'" v-model="contacto.area" data-vv-name="area" placeholder="Area">
                                <span class="text-danger">{{ errors.first('area') }}</span>
                            </div>


                                <div class="form-group col-md-8">
                                    <label> Direccion de Email</label>
                                    <input type="text" class="form-control" v-validate="'required'" v-model="contacto.email" data-vv-name="email" placeholder="Email">
                                    <span class="text-danger">{{ errors.first('email') }}</span>

                                </div>



                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-secondary" @click="guardar(1)"><i class="fas fa-save"></i>&nbsp;Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="guardar(0)"><i class="fas fa-save"></i>&nbsp;Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </div>

</template>

<script>
    import Utilerias from '../../Herramientas/utilerias.js';
    var config = require('../../Herramientas/config-vuetables-client').call(this);

    export default {
        computed:{

        },
        data (){
            return {

                tituloModal : '',
                modal : 0,
                columns : ['id','nombre_contacto','telefono_oficina','telefono_movil','area','email'],
                tableData : [],
                tipoAccion : 0,
                isLoading : false,

                contacto : {
                    id : 0,
                    nombre_contacto : '',
                    telefono_oficina : '',
                    telefono_movil : '',
                    area : '',
                    email: '',
                },
                options: {
                    headings: {
                        id: 'Acciones',
                        nombre_contacto : 'Nombre',
                        telefono_oficina: 'Tel Oficina',
                        telefono_movil: 'Tel Movil',
                        area: 'Area',
                        email: 'Email',
                    },
                    perPage: 10,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    filterByColumn: true,
                    texts:config.texts
                },
            }
        },
        methods : {
            /**
             * [getData Obtencion de datos por medio de peticiones axios al controlador]
             * @return {Response} [respuesta almacenada en diferentes variables]
             */

            cargardetallecontacto(data){
                let me = this;
                 me.data = data;
                axios.get('/contactoc/'+data.id).then(response => {
                    me.tableData = response.data;
                   })
                    .catch(function (error) {
                        console.log(error);
                    });

            },

            /**
             * [buscarBanco description]
             * @param  {Int} id [description]
             * @return {Response}    [description]
             */

            /**
             * [abrirModal description]
             * @param  {String} modelo    [description]
             * @param  {String} accion    [description]
             * @param  {Array}  [data=[]] [description]
             * @return {Response}           [description]
             */
            abrirModal(modelo, accion, data = [])
            {
                switch(modelo){
                    case "contactos":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                Utilerias.resetObject(this.contacto);
                                this.modal= 1;
                                this.tituloModal = 'Registrar Contacto';
                                this.tipoAccion=1;
                                break;
                            }
                            case 'actualizar':
                            {
                                // console.log(data);
                                Utilerias.resetObject(this.contacto);
                                this.modal = 1;
                                this.tituloModal='Actualizar Contacto';
                                this.tipoAccion = 2;
                                this.id = data['id'];
                                this.contacto.nombre_contacto = data ['nombre_contacto'];
                                this.contacto.telefono_oficina = data ['telefono_oficina'];
                                this.contacto.telefono_movil = data ['telefono_movil'];
                                this.contacto.area = data ['area'];
                                this.contacto.email = data ['email'];
                                break;
                            }
                        }
                    }
                }
            },

            cerrarModal()
            {
                this.modal = 0;
            },

            /**
             * [guardar description]
             * @param  {Int} nuevo [description]
             * @return {Response}       [description]
             */
            guardar(nuevo){
                this.$validator.validate().then(result => {
                    if (result) {
                        this.isLoading = true;
                        let me = this;
                        axios({
                            method: nuevo ? 'POST' : 'PUT',
                            url: nuevo ? '/contacto': '/contacto/actualizar/',
                            data: {
                           'nombre_contacto' : this.contacto.nombre_contacto.toUpperCase(),
                           'telefono_oficina' : this.contacto.telefono_oficina,
                           'telefono_movil' : this.contacto.telefono_movil,
                           'area': this.contacto.area.toUpperCase(),
                           'email': this.contacto.email,
                           'id': this.id,
                           'cliente_id' : me.data.id,
                            }
                        }).then(function (response) {
                            me.isLoading = false;
                            if (response.data.status) {
                                me.cerrarModal();
                                me.cargardetallecontacto(me.data);
                                if(!nuevo){
                                    toastr.success('Contacto Actualizado Correctamente');
                                }
                                else
                                {
                                    toastr.success('Contacto Registrado Correctamente');
                                }
                            } else {
                                swal({
                                    type: 'error',
                                    html: response.data.errors.join('<br>'),
                                });
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                });
            },

            maestro(){
                this.$emit('contactodetalle:change');
            },
        },
    }
</script>

<template>
    <main class="main">
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <br>
        <div class="card">
            <boton-nuevo v-bind:arreglo="arreglo" v-on:abrir="abrirModal($event,...args)"></boton-nuevo>
            <div >
               <download-excel
                    class   = "btn btn-default"
                    :data   = "json_data"
                    :fields = "json_fields"
                    name    = "proyectos.xls">
                    <button type="button" class="btn btn-info btn-sm">
                        <i class="icon-check"></i>&nbsp;Exportar a Excel
                    </button>
                </download-excel>
            </div>
            <div class="card-body">
                <v-client-table ref="myTable" :columns="columns" :data="tableData" :options="options">
                    <template slot="proyecto.condicion" slot-scope="props" >
                        <template v-if="props.row.proyecto.condicion">
                            <span class="badge badge-success">Activo</span>
                        </template>
                        <template v-else>
                            <span class="badge badge-danger">Terminado</span>
                        </template>
                    </template>
                    <template slot="proyecto.id" slot-scope="props">
                        <button type="button"
                        @click="abrirModal(['proyecto','actualizar',props.row])"
                        class="btn btn-warning btn-sm">
                            <i class="icon-pencil"></i>
                        </button>  &nbsp;
                        <template v-if="props.row.proyecto.condicion">
                            <button type="button" class="btn btn-danger btn-sm"
                            @click="actdesact(props.row.proyecto.id,0)">
                                <i class="icon-trash"></i>
                            </button>
                        </template>
                        <template v-else>
                            <button type="button" class="btn btn-info btn-sm"
                            @click="actdesact(props.row.proyecto.id,1)">
                                <i class="icon-check"></i>
                            </button>
                        </template>
                    </template>
                </v-client-table>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="form" v-if="tipoAccion==1">
                        <div class="form-group row">
                             <label class="col-md-1 " for="text-input">Nombre</label>
                            <div class="col-md-3">
                            <input type="text" v-validate="'required'" name="nombre" v-model="nombre" class="form-control" placeholder="Nombre">
                            <span class="text-danger">{{ errors.first('nombre') }}</span>
                            </div>
                            <label class="col-md-1 " for="text-input">Clave</label>
                            <div class="col-md-3">
                            <input type="text" v-validate="'required'" name="clave" v-model="clave" class="form-control" placeholder="Clave">
                            <span class="text-danger">{{ errors.first('clave') }}</span>
                            </div>
                            <label class="col-md-1 " for="text-input">Ciudad</label>
                            <div class="col-md-3">
                            <input type="text" v-validate="'required'" name="ciudad" v-model="ciudad" class="form-control" placeholder="Ciudad">
                            <span class="text-danger">{{ errors.first('ciudad') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-1 " for="text-input">Nombre Corto</label>
                            <div class="col-md-3">
                            <input type="text" v-validate="'required'" name="nombre_corto" v-model="nombre_corto" class="form-control" placeholder="Nombre Corto">
                            <span class="text-danger">{{ errors.first('nombre_corto') }}</span>
                            </div>
                            <label class="col-md-1 " for="text-input">Fecha Inicio</label>
                            <div class="col-md-3">
                            <input type="date" v-validate="'required'" name="fecha_inicio" v-model="fecha_inicio" class="form-control" placeholder="Fecha de inicio">
                            <span class="text-danger">{{ errors.first('fecha_inicio') }}</span>
                            </div>

                            <label class="col-md-1 " for="text-input">Fecha Fin</label>
                            <div class="col-md-3">
                            <input type="date" v-validate="'required'" name="fecha_fin" v-model="fecha_fin" class="form-control" placeholder="Fecha de finalización" @change="validar">
                            <span class="text-danger">{{ errors.first('fecha_fin') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form" v-if="tipoAccion==2">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input"
                            v-text="labelModal1"></label>
                            <div class="col-md-9">
                            <input type="hidden" v-model="id" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                             <label class="col-md-1 " for="text-input">Nombre</label>
                            <div class="col-md-3">
                            <input type="text" v-validate="'required'" name="nombre" v-model="nombre" class="form-control" placeholder="Nombre">
                            <span class="text-danger">{{ errors.first('nombre') }}</span>
                            </div>
                            <label class="col-md-1 " for="text-input">Clave</label>
                            <div class="col-md-3">
                            <input type="text" v-validate="'required'" name="clave" v-model="clave" class="form-control" placeholder="Clave">
                            <span class="text-danger">{{ errors.first('clave') }}</span>
                            </div>
                            <label class="col-md-1 " for="text-input">Ciudad</label>
                            <div class="col-md-3">
                            <input type="text" v-validate="'required'" name="ciudad" v-model="ciudad" class="form-control" placeholder="Ciudad">
                            <span class="text-danger">{{ errors.first('ciudad') }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-1 " for="text-input">Nombre Corto</label>
                            <div class="col-md-3">
                            <input type="text" v-validate="'required'" name="nombre_corto" v-model="nombre_corto" class="form-control" placeholder="Nombre Corto">
                            <span class="text-danger">{{ errors.first('nombre_corto') }}</span>
                            </div>
                            <label class="col-md-1 " for="text-input">Fecha Inicio</label>
                            <div class="col-md-3">
                            <input type="date" v-validate="'required'" name="fecha_inicio" v-model="fecha_inicio" class="form-control" placeholder="Fecha de inicio">
                           
                            </div>

                            <label class="col-md-1 " for="text-input">Fecha Fin</label>
                            <div class="col-md-3">
                            <input type="date" v-validate="'required'" name="fecha_fin" v-model="fecha_fin" class="form-control" placeholder="Fecha de finalización" @change="validar">
                           
                            </div>
                        </div>
                    </div>
                        <div v-show="error" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsj" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrar()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizar()">Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
    </main>
</template>

<script>
var config = require('../Herramientas/config-vuetables-client').call(this);

    export default {
        //NOTA muy importante para exportar a excel
        computed:{
            updated: function(){
                return this.exportar(this.$refs.myTable.allFilteredData);
            },
        },
        data (){
            return {
                url: '/proyecto',
                modal : 0,

                swal_titulo:'',
                swal_msg :'',
                swal_tle :'',

                id:0,
                nombre : '',
                nombre_corto: '',
                clave : '',
                ciudad : '',
                fecha_inicio : '',
                fecha_fin : '',
                tituloModal : '',
                tipoAccion : 0,
                fechavalidar: 0,
                error : 0,
                errorMostrarMsj : [],
                arreglo :{
                    titulo: 'Proyectos',
                    emit:   'abrirModal',
                    modulo: 'proyecto',
                    accion: 'registrar',
                },
                json_fields : {
                        'nombre' : 'String',
                        'nombre_corto': 'String',
                        'clave' : 'String',
                        'ciudad' : 'String',
                        'condicion' : 'String',
                        'fecha_inicio' : 'String',
                        'fecha_fin' : 'String',
                        'updated_at' : 'String'
                },
                json_data : [],
                json_meta: [
                    [{
                        "key": "charset",
                        "value": "utf-8"
                    }]
                ],
                columns: [
                    'proyecto.id',
                    'proyecto.nombre',
                    'proyecto.nombre_corto',
                    'proyecto.clave',
                    'proyecto.ciudad',
                    'proyecto.fecha_inicio',
                    'proyecto.fecha_fin',
                    'proyecto.condicion',
                    'proyecto.updated_at'
                ],
                tableData: [],
                options: {
                    headings: {
                        'proyecto.id': 'Acción',
                        'proyecto.nombre': 'Nombre',
                        'proyecto.nombre_corto': 'Nombre corto',
                        'proyecto.clave': 'Clave',
                        'proyecto.ciudad': 'Ciudad',
                        'proyecto.fecha_inicio': 'F. Inicio',
                        'proyecto.fecha_fin': 'F. Fin',
                        'proyecto.condicion':'Condición',
                        'proyecto.updated_at' : 'Ultima Actualización',
                    },
                    perPage: 3,
                    perPageValues: [],
                    skin: config.skin,
                    sortIcon: config.sortIcon,
                    sortable: [
                        'proyecto.nombre',
                        'proyecto.nombre_corto',
                        'proyecto.clave',
                        'proyecto.ciudad',
                        'proyecto.fecha_inicio',
                        'proyecto.fecha_fin',
                        'proyecto.condicion',
                        'proyecto.updated_at'
                        ],
                    filterable: [
                        'proyecto.nombre',
                        'proyecto.nombre_corto',
                        'proyecto.clave',
                        'proyecto.ciudad',
                        'proyecto.fecha_inicio',
                        'proyecto.fecha_fin',
                        'proyecto.condicion',
                    ],
                    filterByColumn: true,
                    listColumns: {
                        'proyecto.condicion': [{
                            id: 1,
                            text: 'Activo'
                        },
                        {
                            id: 0,
                            text: 'Terminado'
                        }
                        ]
                    },
                    texts:config.texts
                },
            }
        },
        methods : {
            validar(){
                var a = this.fecha_inicio;
                var b = this.fecha_fin;
                var fechaInicio = new Date(a).getTime();
                var fechaFin    = new Date(b).getTime();
                var diff = fechaFin - fechaInicio;
                var diferencia =diff/(1000*60*60*24);
                if (diferencia < 0) {
                    this.fechavalidar = 1;
                    toastr.error('La fecha de finalizacion del Proyecto no debe ser menor a la fecha de Inicio');
                }
                else{
                    this.fechavalidar = 0;
                    
                }
                },
            horaActual(){
            var hoy = new Date ();
            var dd = hoy.getDate();
            var mm = hoy.getMonth()+1; //hoy es 0!
            var yyyy = hoy.getFullYear();
            if(dd<10) {  dd='0'+dd   }
            if(mm<10) {  mm='0'+mm   }
            hoy = yyyy+'-'+mm+'-'+dd;
            this.fecha_inicio = hoy;
            },
            listar(){
                let me=this;
                axios.get(me.url).then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrar(){
               if (this.fechavalidar == 0) { 
                this.$validator.validate().then(result => {
                    if (result) {
                        let me = this;
                        axios.post(me.url,{
                            'nombre' : this.nombre,
                            'nombre_corto': this.nombre_corto,
                            'clave' : this.clave,
                            'ciudad' : this.ciudad,
                            'fecha_inicio' : this.fecha_inicio,
                            'fecha_fin' : this.fecha_fin,

                        }).then(function (response) {
                            me.cerrarModal();
                            me.listar();
                            toastr.success('Proyecto Registrado Correctamente')
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                });
               }
            },
            actualizar(){
                if (this.fechavalidar == 0) { 
                this.$validator.validate().then(result => {
                    if (result) {
                        let me = this;
                        axios.put(me.url+'/'+this.id,{
                            'nombre' : this.nombre,
                            'nombre_corto': this.nombre_corto,
                            'clave' : this.clave,
                            'ciudad' : this.ciudad,
                            'fecha_inicio' : this.fecha_inicio,
                            'fecha_fin' : this.fecha_fin

                        }).then(function (response) {
                            me.cerrarModal();
                            me.listar();
                            toastr.success('Proyecto Actualizado Correctamente')
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                });
                }
            },
            actdesact(id,activar){
                if(activar){
                    this.swal_titulo = 'El proyecto ha finalizado, desea activarlo nuevamente?';
                    this.swal_tle = 'Activado';
                    this.swal_msg = 'Proyecto activado con éxito.';
                }else{
                    this.swal_titulo = 'Esta seguro que el proyecto concluyó?';
                    this.swal_tle = 'Desactivado!';
                    this.swal_msg = 'Proyecto finalizado.';
                }
                swal({
                title: this.swal_titulo,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;
                    axios.get(me.url+'/'+id+'/edit').then(function (response) {
                        if(activar){
                        toastr.success(me.swal_tle,me.swal_msg,'success');

                        }else{
                        toastr.error(me.swal_tle,me.swal_msg,'error');

                        }
                        toastr.options = {
                        "closeButton": false,
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": true,
                        "progressBar": true,
                        "positionClass": "toast-top-center",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                       }
                        me.listar();
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
                })
            },
            exportar(datos){
                var condicion = '';
                var sexo = '';
                this.json_data=[];
                this.json_data.push(
                    {
                        'nombre':'Nombre',
                        'nombre_corto':'Nombre Corto',
                        'clave':'Clave',
                        'ciudad':'Ciudad',
                        'condicion':'Estado',
                        'fecha_fin':'F. Inicio',
                        'fecha_inicio':'F. Fin',
                        'updated_at':'Actualizado por ultima vez'
                    });
                datos.forEach(dato =>{
                    this.json_data.push(
                    {
                        'nombre':dato.proyecto.nombre,
                        'nombre_corto':dato.proyecto.nombre_corto,
                        'clave':dato.proyecto.clave,
                        'ciudad':dato.proyecto.ciudad,
                        'fecha_inicio':dato.proyecto.fecha_inicio,
                        'condicion':condicion,
                        'fecha_fin':dato.proyecto.fecha_fin,
                        'updated_at':dato.proyecto.updated_at
                    });
                });
                return this.json_data;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
            },
            abrirModal(modelo, accion=[]){
                var data =  modelo[2];
                switch(modelo[0]){
                    case "proyecto":
                    {
                        switch(modelo[1]){
                            case 'registrar':
                            {
                                this.modal = 1;
                               
                                this.tituloModal = 'Registrar Proyecto';
                                this.proyecto = '';
                                this.nombre = '';
                                this.nombre_corto = '';
                                this.clave = '';
                                this.ciudad = '';
                                this.fecha_inicio = '';
                                this.fecha_fin = '';
                                this.horaActual();
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar Proyecto';
                                this.id = data.proyecto.id,
                                this.nombre = data.proyecto.nombre,
                                this.nombre_corto = data.proyecto.nombre_corto,
                                this.clave = data.proyecto.clave,
                                this.ciudad = data.proyecto.ciudad,
                                this.fecha_inicio = data.proyecto.fecha_inicio,
                                this.fecha_fin = data.proyecto.fecha_fin,
                                this.tipoAccion=2;
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listar();
            this.horaActual();
            this.$root.limpiarDashboard();
        }
    }
</script>

<template>
<main class="main">
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <br>
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Catálogo de Proveedores
            </div>
            <div class="card-body">
                <v-server-table :columns="columns" :url="'/proveedores/server'" :options="options" ref="tblProv">
                    <!-- Como usar un if cuando se tiene tres condiciones-->
                    <template slot="condicion" slot-scope="props">
                        <template v-if="props.row.condicion == 1">
                            <span class="btn btn-outline-success">Activo</span>
                        </template>
                        <template v-if="props.row.condicion == 0">
                            <span class="btn btn-outline-danger">Desactivado</span>
                        </template>
                    </template>
                    <!-- Tipo -->
                    <template slot="tipo" slot-scope="props">
                        <template v-if="props.row.tipo == 1">
                            <select class="form-control" @change="CambioTipo(props.row.id,$event)">
                                <option value=1 selected>Acreedor</option>
                                <option value=2>Proveedor</option>
                            </select>
                        </template>
                        <template v-else-if="props.row.tipo == 2">
                            <select class="form-control" @change="CambioTipo(props.row.id,$event)">
                                <option value=1>Acreedor</option>
                                <option value=2 selected>Proveedor</option>
                            </select>
                        </template>
                        <template v-else>
                            <select class="form-control" @change="CambioTipo(props.row.id,$event)">
                                <option value=1>Acreedor</option>
                                <option value=2>Proveedor</option>
                                <option value=0 selected>Seleccionar</option>
                            </select>
                        </template>
                    </template>

                </v-server-table>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>

</main>
</template>

<script>
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);
export default {
    data() {
        return {
            n_temp: 0,
            banco_edit: {},
            tipo_guardar: 1,
            ListBancos_temp: [],
            ListBancos: [],
            temp_proveedor_cuenta: '',
            temp_proveedor_clabe: '',
            temp_proveedor_banco: '',
            url: '/proveedores',
            columnsProvedores: ["banco", "cuenta", "clabe"],
            tableDataProveedores: [],
            optionsProveedores: {
                headings: {
                    clabe: "Clabe",
                    banco: "Banco de transferencia",
                    cuenta: "No. de Cuenta",
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                texts: config.texts,
                filterable: false,
            },
            modalProveedor: 0,
            PermisosCrud: {},
            Proveedor: {
                nombre: '',
                razon_social: '',
                direccion: '',
                banco_transferencia: '',
                // titular_cuenta : '',
                numero_cuenta: '',
                clabe: '',
                dia_credito: '',
                limite_credito: 0,
                condicion: 0,

                categoria: '',
                condicion_pago: '',
                giro: '',
                rfc: '',
                ciudad: '',
                estado: '',
                contacto: '',
                telefono: '',
                correo: '',
                pagina: '',
                descripcion: '',
                tipo_moneda: 0,
                tipo_cambio: '',
            },
            listaProveedores: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            isLoading: false,
            columns: ['nombre', 'razon_social', 'tipo', 'condicion'],
            tableData: [],
            options: {
                headings: {
                    nombre: 'Nombres',
                    razon_social: 'Razón Social',
                    direccion: 'Dirección',
                    condicion: 'Estado',
                    numero_cuenta: 'Número cuenta',
                    dia_credito: 'Días de credito',
                    limite_credito: 'Límite de crédito',
                    tipo: 'Tipo'
                },
                perPage: 10,
                perPageValues: [],
                skin: config.skin,
                sortIcon: config.sortIcon,
                sortable: ['nombre', 'razon_social', 'condicion', 'tipo'],
                filterable: ['nombre', 'razon_social', 'condicion', 'tipo'],
                filterByColumn: true,
                texts: config.texts
            },
        }
    },
    computed: {},
    methods: {
        getData() {
            let me = this;
            this.PermisosCrud = Utilerias.getCRUD(this.$route.path);
            axios.get(me.url).then(response => {
                    me.tableData = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getListaProveedores() {
            let me = this;
            axios.get('/proveedores').then(response => {
                    me.listaProveedores = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        CambioTipo(id, t) {
            let tipo = t.target.value;
            axios.put('/proveedores/tipo/' + id + "&" + tipo).then(response => {
                    if (response.data.success) {
                        toastr.success("Proveedore actualziado");
                        this.$refs.tblProv.refresh();
                    } else {
                        toastr.error(response.data.mensaje, "Error 500");
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    },
    mounted() {
        this.getData();
        this.getListaProveedores();
    }
}
</script>

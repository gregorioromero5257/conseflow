<template>
<div class="main">
    <!-- Card Inicio-->
    <div class="card">
        <!-- Inicio card-->
        <div class="card-header">
            <i class="fa fa-align-justify"></i> Credenciales
        </div>
        <div class="card-body">
            <div class="container">
                <div class="form-row">
                    <label class="col-md-2" for="">Empleado</label>
                    <v-select class="col-md-6" :options="listEmpleados" label="nombre" v-model="empleado"></v-select>
                    <button v-show="ver_agregar" class="btn btn-sm btn-dark" @click="AgregarEmpleado">
                        <i class="fas fa-plus mr-1"></i> Añadir
                    </button>
                </div>
            </div>
            <br />
            <div class="container col-md-8 ml-0">
                <p class="h4">Empleados</p>
                <br />
                <table class="table table-sm table-responsive table-borderless">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>NSS</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(e, i) in listEmpleados_credencial">
                            <th scope="">{{ i + 1 }}</th>
                            <td>{{ e.nombre }}</td>
                            <td>{{ e.nss }}</td>
                            <td>
                                <input type="file" name="uploader" :id="'foto_' + e.id" accept="image/jpeg" capture />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="container">
                    <button class="btn btn-secondary" @click="GenerarCredenciales">
                        Generar
                    </button>
                    <button class="btn btn-dark" @click="Limpiar">Limpiar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main -->
</template>

<script>
/* CAMBIAR UBICACIÓN  */
import Utilerias from "../../Herramientas/utilerias.js";
var config = require("../../Herramientas/config-vuetables-client").call(this);
export default
{
    data()
    {
        return {
            EmpleadosLoading: false,
            ver_agregar: true,
            // Tabla
            listEmpleados: [],
            listEmpleados_credencial: [],
            empleado:
            {},
        }; // return
    }, //data
    computed:
    {},
    methods:
    {
        /**
         * Obtiene todos los empleados
         */
        ObtenerEmpleados()
        {
            this.EmpleadosLoading = true;
            axios.get("/empleado").then((res) =>
            {
                if (res.status)
                {
                    this.listEmpleados = [];
                    res.data.forEach((e) =>
                    {
                        this.listEmpleados.push(
                        {
                            id: e.empleado.id,
                            nombre: e.empleado.nombre +
                                " " +
                                e.empleado.ap_paterno +
                                " " +
                                e.empleado.ap_materno,
                            nss: e.empleado.nss_imss,
                        });
                    });
                }
            });
            this.EmpleadosLoading = false;
        },
        /**
         * Añade el empleado actual a la lista de empleados para credencial
         */
        AgregarEmpleado()
        {
            // Comprobar que se haya seleccionado un empleado
            if (this.empleado == null)
            {
                toastr.warning("Seleccione un empleado");
                return;
            }
            if (this.empleado.id == null)
            {
                toastr.warning("Seleccione un empleado");
                return;
            }

            // Empleado duplicado
            let exisiste = this.listEmpleados_credencial.find(
                (e) => e.id == this.empleado.id
            );
            if (exisiste != null)
            {
                toastr.warning("Empleado duplicado");
                this.empleado = {};
                return;
            }
            this.listEmpleados_credencial.push(
            {
                ...this.empleado,
            });
            this.empleado = {};
            if (this.listEmpleados_credencial.length == 4)
            {
                this.ver_agregar = false;
            }
        },
        GenerarCredenciales()
        {
            if (this.listEmpleados_credencial.length <1)
            {
                toastr.warning("Seleccione todos los empleados");
                return;
            }
            // Obtener ids y validar fotos
            let ids = "";
            let data = new FormData();

            for (let i = 0; i < this.listEmpleados_credencial.length; i++)
            {
                const e = this.listEmpleados_credencial[i];
                let id_img = "foto_" + e.id;
                let img = $("#" + id_img).prop("files");
                if (img.length == 0)
                {
                    // Sin imagen
                    toastr.warning("Seleccione la imagen de " + e.nombre);
                    return;
                }
                data.append(id_img, img[0]);
                ids += e.id + "|";
            }
            data.append("ids", ids);

            // Guardar las imagenes
            axios.post("/rh/credenciales/guardarimagen", data).then((res) =>
            {
                if (res.data.status)
                {
                    window.open("/rh/credenciales/generar/" + ids, "_blank");
                }
                else
                {
                    toastr.error(res.data.mensaje);
                }
            });
        },
        /**
         *Elimina todos los empleados de las credenciales
         */
        Limpiar()
        {
            this.empleado = {};
            this.listEmpleados_credencial = [];
            this.ver_agregar = true;
        },
    }, // Fin metodos
    mounted()
    {
        this.ObtenerEmpleados();
    },
};
</script>

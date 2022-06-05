<template>
<main class="main">
    <div class="container-fluid">

        <!-- Archivos en directorio -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>{{nombre}}
                    </div>
                    <div class="card-body">
                        <a class="dropdown-item file" v-for="f in listaArchivos.archivos" @click="Descargar(f)">
                            {{f}}
                        </a>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>

        <div class="row" v-for="c in listaArchivos.carpetas">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>{{c["nombre"]}}
                    </div>
                    <div class="card-body">
                        <a class="dropdown-item" v-for="f in c[0]" @click="Descargar(f)">
                            {{f}}
                        </a>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>
</main>
</template>

<style>
.file {
    cursor: pointer;
}
</style>

<script>
export default
{
    data()
    {
        return {
            url: "procedsgi/",
            isLoading: false,
            listaArchivos: [],
            nombre: "Dirección Comercial y de Negocios",
            ruta: "negocios"
        }
    },
    methods:
    {
        ObtenerArchivos()
        {
            axios.get(this.url + "archivos/" + this.ruta).then(res =>
            {
                if (res.data.status)
                {
                    this.listaArchivos = res.data.listaArchivos;
                }
                else
                {
                    this.Error(res.data.mensaje);
                }
            }).catch(r =>
            {
                this.Error("obtener los archivos");
            })
        },
        Descargar(f)
        {
            window.open(this.url + "descargar/" + this.ruta + "&" + f, "_blank");
        },
        Error(ms)
        {
            toastr.error("Error al " + ms);
        }
    },
    mounted()
    {
        this.ObtenerArchivos();
    }
}
</script>

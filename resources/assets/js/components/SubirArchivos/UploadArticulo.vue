<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <vue-element-loading :active="isLoading"/>
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Subir Artículos
                    </div>
                    <div class="card-body">
                        <div class="form-control">
                            <input type="file" v-validate="'required|ext:xls,xlsx'" data-vv-as="Archivo" name="import_file" @change="getArchivo" accept="*/*" required>
                            <span class="text-danger">{{ errors.first('import_file') }}</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button @click="updateAvatar" class="btn btn-primary" :disabled="isLoading">Subir Archivo</button>
                    </div>
                </div>
            </div>
    </main>    
</template>
<script>
    export default {

        data(){
            return {
                archivo : null,
                isLoading: false
            }
        },
        methods  : {
            getArchivo(event){
                //Asignamos el archivo a  nuestra data
                this.archivo = event.target.files[0];
            },
            updateAvatar(){

                // if (this.archivo == null){
                //     swal(
                //         'Articulos',
                //         'Seleccionar archivo.',
                //         'error'
                //     );
                //     return;
                // }

                this.$validator.validate().then(result => {
                    if (result) {

                this.isLoading = true;
                //Creamos el formData
                var data = new  FormData();
                //Añadimos la imagen seleccionada
                data.append('import_file', this.archivo);
                //Añadimos el método PUT dentro del formData
                // Como lo hacíamos desde un formulario simple _(no ajax)_
                data.append('_method', 'PUT');
                //Enviamos la petición
                axios.post('/articulo/upload',data)
                .then(response => {
                    this.archivo = null;
                    this.isLoading = false;
                    console.log(response);
                    if (response.status == 200)
                        swal(
                            'Articulos',
                            'Registros agregados: ' + response.data.nuevos + '<br>Registros repetidos: ' + response.data.repetidos,
                            'success'
                        );
                    else
                        swal(
                        'Articulos',
                        'Ocurrio un error.',
                        'error'
                    );
                })
                .catch(function (error) {
                    this.archivo = null;
                    this.isLoading = false;
                    console.log(error);
                    swal(
                        'Articulos',
                        'Ocurrio un error al leer el archivo.',
                        'error'
                    );
                });
            
            }
                })
            }
        }
    }
</script>
<template>
<main>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="css/desk2.min.css">

    <!--Evita ver contenido despues de cerrar sesión-->
    <meta http-equiv="Expires" content="0" /> 
    <meta http-equiv="Pragma" content="no-cache" />
    <!--Fin-->
</head>

	<div style="text-align: center;">
		<div id="icon-grid">
			<div v-for="modulo in arrayModulos" :key="modulo.nombre" class="case-wrapper" :title="modulo.nombre">
				<a @click="$emit('click',modulo.id,modulo.submenu,modulo.nombre, modulo.page)"> 
				<div class="app-icon" v-bind:style="'background-color:' + modulo.color" 
				:title="modulo.nombre">
					<i v-bind:class="modulo.icono" :title="modulo.nombre"></i>
				</div>
				</a> 
				<div class="case-label ellipsis">
					<span class="case-label-text text-white" v-text="modulo.nombre"></span> 
				</div> 
			</div>
		</div>
	</div>
</main>
</template>
<style>
	span{
		color:#151527;
	}
</style>
<script>
    export default {
        data (){
            return {
                id: 0,
                arrayModulos:[]
            }
        },
        methods : {
            getRutas(){
                let me=this;
                axios.get('/modulos/por-usuario').then(function (response) {
                    me.arrayModulos = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        },
        mounted() {
            this.getRutas();
        }
    }
</script>
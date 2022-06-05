x<template>
	<main class="main">
		<div class="container-fluid">
			<!-- Ejemplo de tabla Listado -->
			<br>
			<div class="card">
				<div class="card-header">
					<i class="fa fa-align-justify"></i> Asignar permisos.

				</div>
				<div class="card-body">

					<v-client-table :columns="columns" :data="tableData" :options="options" ref="myTable">
						<template slot="usuario.condicion" slot-scope="props" >
							<template v-if="props.row.usuario.condicion">
								<button type="button" class="btn btn-outline-success">Activo</button>
								<!-- <span class="badge badge-success">Activo</span> -->
							</template>
							<template v-else>
								<button type="button" class="btn btn-outline-danger">Inactivo</button>
								<!-- <span class="badge badge-danger">Inactivo</span> -->
							</template>
						</template>
						<template slot="usuario.session_id" slot-scope="props" >
							<template v-if="props.row.usuario.session_id">
								<button type="button" class="btn btn-success">Online</button>
								<!-- <span class="badge badge-success">OnLine</span> -->
							</template>
							<template v-else>
								<button type="button" class="btn btn-danger">Offline</button>
								<!-- <span class="badge badge-danger">OffLine</span> -->
							</template>
						</template>
						<template slot="usuario.id" slot-scope="props">
							<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
							<div class="btn-group dropup" role="group">
								<button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fas fa-grip-horizontal"></i>&nbsp; Acciones
								</button>
							<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
							<template>
							<button type="button" @click="abrirModal('tipo-per','actualizar', props.row.usuario)" class="dropdown-item">
								<i class="icon-pencil"></i>&nbsp;Actualizar Permiso de Usuario
							</button>
							</template>
							</div>
							</div>
							</div>
						</template>
					</v-client-table>

				</div>
			</div>
			<!-- Fin ejemplo de tabla Listado -->
		</div>
		<!--Inicio del modal agregar/actualizar-->

		<div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
			<vue-element-loading :active="isLoading"/>
			<div class="modal-dialog modal-dark modal-lg" role="document">
				<div class="modal-content">
					<div >

						<div class="modal-header">
							<h4 class="modal-title" v-text="tituloModal"></h4>
							<button type="button" class="close" @click="cerrarModal()" aria-label="Close">
								<span aria-hidden="true">x</span>
							</button>
						</div>

						<div  class="modal-body">
							<ul class="nav nav-tabs" role="tablist">
								<ul class="nav nav-tabs" role="tablist">
									<li class="nav-item" v-for="item in tableModulos" :key="item.id">
										<a style="font-size:18px;" class="nav-link" data-toggle="tab" :href="'#tab'+item.id" role="tab" >{{item.nombre}}</a>
									</li>
								</ul>

								<div class="tab-content">

									<div class="tab-pane" v-for="item in tableModulos" :key="item.id" :id="'tab'+item.id" role="tabpanel">

										<p  style="color:black;" class="card-text">
											Selecciona las casillas a las cuales deseas asignar permiso.
										</p>

										<div class="form-group">

											<div  v-for="itemM in tableMenus" :key="itemM.id">

												<div class="form-check checkbox" v-if="(item.id == itemM.m.modulo_id)">

													<div v-show="itemM.m.page != null">
														<input class="" :value="itemM.m.id" :id="itemM.m.id" type="checkbox" v-on:change="menusupdate(tipoPermisos.id,itemM.m,0)" v-model="menus">

														<label class="form-check-label" for="itemM.m.id">
															{{itemM.m.name}}
														</label>
													</div>

													<div v-show="itemM.m.page == null">

														<a class="nav-link" data-toggle="tab" :href="'#tab'+item.id" role="tab">{{itemM.m.name}}</a>
														<div  v-for="itemMS in itemM.s" :key="itemMS.id">
															<input class="" :value="itemMS.id" :id="itemMS.id" type="checkbox" v-on:change="submenusupdate(tipoPermisos.id,itemMS,0)" v-model="submenus">
															<label class="form-check-label" for="itemMS.id">
																{{itemMS.submenu}}
															</label>
														</div>
													</div>

												</div>


											</div>

										</div>
									</div>

								</div>

							</ul>
							<input type="hidden" name="id">
							<div class="form-group row">
								<label class="col-md-3 form-control-label" for="nombre">Usuario</label>
								<div class="col-md-15">
									<input type="text" v-validate="'required'" name="nombre" v-model="tipoPermisos.nombre" class="form-control" placeholder="Nombre de usuario" autocomplete="off" id="nombre">
									<span class="text-danger">{{ errors.first('nombre') }}</span>
								</div>
							</div>
							<!-- </form> -->
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-outline-dark" @click="cerrarModal()"><i class="fas fa-times"></i>&nbsp;Cerrar</button>
							<button type="button" v-if="tipoAccion==2" class="btn btn-secondary" @click="allPermisos(tipoPermisos.id)"><i class="fas fa-check-double"></i>&nbsp;Todos</button>
						</div>
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
import Utilerias from '../Herramientas/utilerias.js';
var config = require('../Herramientas/config-vuetables-client').call(this);

export default {
	data (){
		return {
			url: '/usuario',
			tipoPermisos: {
				id: 0,
				nombre : ''
			},
			modal : 0,
			tituloModal : '',
			tipoAccion : 0,
			isLoading: false,
			columns: ['usuario.id', 'usuario.name','usuario.condicion','usuario.session_id'],
			tableData: [],
			tableModulos: [],
			tableMenus: [],
			events:[],
			tableSubMenus: [],
			menus:[],
			submenus:[],
			json_fields : {
				'condicion' : 'String',
				'session_id' : 'String'

			},
			options: {
				headings: {
					'usuario.name': 'Usuario',
					'usuario.id': 'Acciones',
					'usuario.condicion' : 'Estado',
					'usuario.session_id': 'Conexión',

				},
				perPage: 10,
				perPageValues: [],
				skin: config.skin,
				sortIcon: config.sortIcon,
				sortable: ['usuario.name'],
				filterable: ['usuario.name'],
				filterByColumn: true,
				listColumns: {
					'usuario.condicion': [{
						id: 1,
						text: 'Activo'
					},
					{
						id: 0,
						text: 'Baja'
					}
				],
				'usuario.session_id': [{
					id: 1,
					text: 'OnLine'
				},
				{
					id: 0,
					text: 'OffLine'
				}
			]
		},
		texts:config.texts
	},
}
},


methods : {
	getData() {
		let me=this;
		axios.get(me.url).then(response => {
			me.tableData = response.data;
		})
		.catch(function (error) {
			console.log(error);
		});
	},

	crear(){
		let me=this;
		axios.get('/PermisoUser/create').then(response => {
			me.tableModulos = response.data;
		})
		.catch(function(error){
			console.log(error);

		});
	},

	editado(){
		let me=this;
		axios.get('/PermisoUser/edit').then(response =>{
			me.tableSubMenus=response.data;
		})
		.catch(function(error){
			console.log(error);
		});
	},
	tablamenu(){
		let me=this;
		axios.get('/PermisoUser/0').then(response =>{
			me.tableMenus=response.data;
		})
		.catch(function (error){
			console.log(error);
		});
	},

	menusupdate(id,data = [],nuevo){
		let me = this;
		axios({
			method: nuevo ? 'POST' : 'PUT',
			url: nuevo ? me.url : '/PermisoUser/'+id+'/actualizar',
			data: {
				'modulo': data.modulo_id,
				'id': id,
				'id_menu' : data.id

			}
		}).then(function (response) {

			if (response.data.status) {

				toastr.success(' Actualizada Correctamente');

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
	submenusupdate(id,data = [],nuevo){
		let me = this;
		axios({
			method: nuevo ? 'POST' : 'PUT',
			url: nuevo ? me.url : '/PermisoUser/'+id+'/actualizarsub',
			data: {
				'menu_id': data.elementos_menu_id,
				'id': id,
				'id_submenu' : data.id

			}
		})
		.then(function (response) {

			if (response.data.status) {

				toastr.success(' Actualizada Correctamente');

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

	allPermisos(id)
	{
		this.isLoading = true;
		let me = this;
		var cadena = [];
		var cadenauno = [];
		axios.get('/todospermisos/'+id).then(response => {
			axios.get('/menusidemp/'+id).then(function (response){
				for (var i =0; i < response.data.length; i++){
					cadena.push(response.data[i].elementos_menu_id);
					cadenauno.push(response.data[i].elementos_submenu_id);
				}
				me.menus = cadena;
				me.submenus = cadenauno;
			}).catch(function (error){
				this.cerrarModal();
				console.error(error);
			});
			me.isLoading = false;
			toastr.info('Atención','Todos los permisos se han asignado correctamente');
			this.cerrarModal();

		}).catch(function (error){
			console.log(error);
			this.cerrarModal();
		});
	},

	cerrarModal(){
		this.modal=0;
		this.tituloModal='';
		Utilerias.resetObject(this.tipoPermisos);

	},
	abrirModal(modelo, accion, data = []){
		switch(modelo){
			case "tipo-per":
			{
				switch(accion){
					case 'registrar':
					{
						this.modal = 1;
						this.tituloModal = 'Registrar permiso';
						Utilerias.resetObject(this.tipoPermisos);
						this.tipoAccion = 1;
						break;
					}
					case 'actualizar':
					{

						this.modal=1;
						this.tituloModal='Actualizar Permisos';
						this.tipoAccion=2;
						this.tipoPermisos.id=data['id'];
						var id = data['id'];
						this.tipoPermisos.nombre = data['name'];
						var cadena = [];
						var cadenauno = [];
						axios.get('/menusidemp/'+id).then(function (response){
							for (var i =0; i < response.data.length; i++){
								cadena.push(response.data[i].elementos_menu_id);
								cadenauno.push(response.data[i].elementos_submenu_id);
							}
						}).catch(function (error){

						});
						this.menus = cadena;
						this.submenus = cadenauno;
						break;

					}
				}
			}
		}
	}
},
mounted() {
	this.getData();
	this.crear();
	this.editado();
	this.tablamenu();
}
}
</script>

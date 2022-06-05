    @extends('principal')
    @section('contenido')
    
    <router-view></router-view>

<!--Inicio del modal modulos-->
<div class="modal fade" tabindex="-1" :class="{'mostrar' :showModal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
      <div class="modal-content bg-secondary">
          <div class="modal-header">
              <h4 class="modal-title text-white col-10 modal-title text-center">&nbsp;MODULOS DEL SISTEMA</h4>
              <button type="button" class="close" @click="cerrarModalModulos()" aria-label="Close">
                <span  class="text-white" aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body">
          <modulos @click="cargarMenuModulo"></modulos>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-light" @click="cerrarModalModulos">CERRAR</button>
          </div>
      </div>
      <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!--Fin del modal-->
    @endsection

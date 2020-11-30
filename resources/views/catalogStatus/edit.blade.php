<form method="POST" @submit.prevent="updateCrud(getCrudDetail.id)" id="updateCatalogStatus">
    @csrf
    <div class="modal fade" id="edit">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Catalogo de estatus - Editar</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" name="name" id="name" v-model="getCrudDetail.name">
                    </div>
                    <div class="form-group">
                        <label for="type">Tipo</label>
                        <input type="text" class="form-control" name="type" id="type" v-model="getCrudDetail.type">
                    </div>
                    <div class="form-group">
                        <label for="client_id">Cliente</label>
                        <select name="client_id" id="client" class="form-control" v-model="getCrudDetail.client_id">
                            <option value="">Sin cliente</option>
                            <option v-for="client in getCrudList.client" :value="client.id">@{{client.id}} - @{{client.name}}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" value="Actualizar" class="btn btn-primary mb-2">
                </div>
            </div>
        </div>
    </div>
</form>
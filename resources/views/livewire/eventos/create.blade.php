<!-- Modal -->
<div wire:ignore.self class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create New Evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
           <div class="modal-body">
				<form>
            <div class="form-group">
                <label for="nome"></label>
                <input wire:model="nome" type="text" class="form-control" id="nome" placeholder="Nome">@error('nome') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="descricao"></label>
                <input wire:model="descricao" type="text" class="form-control" id="descricao" placeholder="Descricao">@error('descricao') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="data_inicio"></label>
                <input wire:model="data_inicio" type="text" class="form-control" id="data_inicio" placeholder="Data Inicio">@error('data_inicio') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="data_fim"></label>
                <input wire:model="data_fim" type="text" class="form-control" id="data_fim" placeholder="Data Fim">@error('data_fim') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="link"></label>
                <input wire:model="link" type="text" class="form-control" id="link" placeholder="Link">@error('link') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="imagem"></label>
                <input wire:model="imagem" type="text" class="form-control" id="imagem" placeholder="Imagem">@error('imagem') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="is_active"></label>
                <input wire:model="is_active" type="text" class="form-control" id="is_active" placeholder="Is Active">@error('is_active') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary close-modal">Save</button>
            </div>
        </div>
    </div>
</div>
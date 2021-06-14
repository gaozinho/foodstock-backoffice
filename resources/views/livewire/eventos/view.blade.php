@section('title', __('Eventos'))
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="row">
				<div class="col-lg-12 margin-tb">
					<div class="pull-left pb-3">
						<h4>
							<i class="fas fa-chalkboard-teacher"></i> Gerenciar eventos
						</h4>
					</div>
				</div>
			</div>
			@if(!$saveMode)
				<div class="row mb-3">
					<div class="col-lg-12 col-sm-12">
							<div class="form-row">
								<div class="col-auto">
									<a wire:click="create" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Evento</a> 
								</div>
								<div class="col-auto">
									<button wire:click="report()" id="btn-exportar" type="button" class="btn btn-primary btn-sm"><i class="fas fa-file-excel fa-lg"></i></button>
								</div>
								<div class="col-auto">
									<input wire:model='keyWord' type="text" class="form-control form-control-sm" name="busca" id="busca" placeholder="Pesquisar">
								</div>
							</div>
					</div>
				</div>
			@else
			<div class="row mb-3">
				<div class="col-lg-12 col-sm-12">
						<div class="form-row">
							<div class="col-auto">
								<button wire:click="cancel()" name="cancelar" value="ok" type="button" class="btn btn-primary text-uppercase"><i class="fas fa-arrow-left"></i> Voltar</button>
							</div>
						</div>
				</div>
			</div>			
			@endif



			@if($saveMode)
				@include('livewire.eventos.edit')
			@else
				<table class="table table-bordered pagination-eventos">
					<thead class="thead">
						<tr> 
							<td>#</td> 
							<th>Evento</th>
							<th>Descrição</th>
							<th>Início</th>
							<th>Fim</th>
							<th>Ativo?</th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						@foreach($eventos as $row)
						<tr>
							<td>{{ $loop->iteration }}</td> 
							<td>{{ $row->nome }}</td>
							<td>{{ $row->descricao }}</td>
							<td>{{ $row->data_inicio }}</td>
							<td>{{ $row->data_fim }}</td>
							<td>{{ $row->is_active ? 'Sim' : 'Não' }}</td>
							<td nowrap>
								<a data-toggle="modal" data-target="#updateModal" class="btn btn-sm btn-primary" wire:click="edit({{$row->id}})"><i class="fa fa-edit"></i></a>							 
								<a class="btn btn-danger btn-sm" onclick="confirm('Confirm Delete Evento id {{$row->id}}? \nDeleted Eventos cannot be recovered!')||event.stopImmediatePropagation()" wire:click="destroy({{$row->id}})"><i class="fa fa-trash"></i></a>   
							</td>
						@endforeach
					</tbody>
				</table>						
				{!! $eventos->appends(request()->query())->links() !!}

				<script>
					$(document).ready(function() {

						$(".page-item").on('click', function(e){
							$(".pagination-eventos").LoadingOverlay("show");
						});
					});
				</script>

			@endif

		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		Livewire.on('paginationLoaded', function(){
			$(".pagination-eventos").LoadingOverlay("hide");
		})
	});
</script>

<script>
	window.addEventListener('gotoTop', event => {
		window.scrollTo({
			top: 15,
			left: 15,
			behaviour: 'smooth'
		});
	})
</script>

<!-- Datepicker -->
<script src="{{ asset('node_modules/moment/moment.min.js') }}"></script>
<script src="{{ asset('node_modules/moment/locales.min.js') }}"></script>
<script src="{{ asset('node_modules/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('node_modules/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />


<!-- Editor visual textarea -->
<script src="{{ asset('node_modules/tinymce/tinymce.min.js') }}"></script>

<!-- Máscaras nos campos -->
<script src="{{ asset('node_modules/cleave.js/cleave.min.js') }}"></script>


<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>



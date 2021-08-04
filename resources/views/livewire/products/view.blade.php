<div class="mb-5">

    <div class="full-screen mb-3">
        <h2 class="mt-3 mb-0 pb-0"><i class="fas fa-hamburger"></i> Produtos comercializados</h2>
    </div>



    <div class="row">
        <div class="col-lg-8 col-md-8 margin-tb">

            <div class="card">
                <div class="card-body">

                    @if (!$saveMode)
                        <div class="row mb-3">
                            <div class="col-lg-12 col-sm-12">
                                <div class="row justify-content-between">
                                    <div class="col-md-6 align-self-end">

                                        <!-- <button wire:click="report()" id="btn-exportar" type="button"
                                            class="btn btn-primary btn-sm"><i
                                                class="fas fa-file-excel fa-lg"></i></button> -->

                                        <input wire:model='keyWord' type="text" class="form-control form-control-sm"
                                            name="busca" id="busca" placeholder="Pesquisar">

                                    </div>
                                    <div class="col-auto">
                                        <a wire:click="create" class="btn btn-success btn-lg"><i
                                                class="fas fa-plus"></i>
                                            Criar novo produto</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row mb-3">
                            <div class="col-lg-12 col-sm-12">
                                <div class="form-row">
                                    <div class="col-auto">
                                        <button wire:click="cancel()" name="cancelar" value="ok" type="button"
                                            class="btn btn-primary text-uppercase"><i class="fas fa-arrow-left"></i>
                                            Voltar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif



                    @if ($saveMode)
                        @include('livewire.products.edit')
                    @else
                        <table class="table table-hover table-bordered pagination-products">
                            <thead class="thead">
                                <tr>
                                    <td>#</td>
                                    <th>
										<div class="row justify-content-between">
											<div class="col-6">
												<a class="sort-column" href="javascript:;" wire:click="sort('name')">Produto</a> 
												{!!$sort == "name" && $direction == "ASC" ? '<i class="fas fa-caret-up"></i>' : ""!!}
												{!!$sort == "name" && $direction == "DESC" ? '<i class="fas fa-caret-down"></i>' : ""!!}
											</div>
											<div class="col-auto">
												<span style="cursor: pointer" wire:click="sort('monitor_stock')" class="sort-column badge badge-{!!$sort == 'monitor_stock' && $direction == 'ASC' ? 'secondary' : 'success'!!}">
													Monitorado estoque
													{!!$sort == "monitor_stock" && $direction == "ASC" ? '<i class="fas fa-caret-up"></i>' : ""!!}
													{!!$sort == "monitor_stock" && $direction == "DESC" ? '<i class="fas fa-caret-down"></i>' : ""!!}
												</span>
											</div>
										</div>
									</th>
									<th>Preço</th>
									<th>Estoque</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $row)
                                    <tr class="{{$row->monitor_stock ? 'bg-monitor' : '' }}">
                                        <td width="1%">{{ $loop->iteration + ($products->currentPage() - 1) * $pageSize }}</td>
                                        <td>{{ $row->name }}
											@if($row->monitor_stock)
												<i class="fas fa-eye">
											@endif

										</td>
										<td width="1%" nowrap class="text-right">
											<small>@money($row->unit_price)</small>
										</td>
                                        <td width="1%" nowrap class="text-right">
											{{ $row->current_stock }}
										</td>
                                        <td width="1%" nowrap>
                                            <a data-toggle="modal" data-target="#updateModal"
                                                class="btn btn-sm btn-primary" wire:click="edit({{ $row->id }})"><i
                                                    class="fa fa-edit"></i></a>
                                            <a class="btn btn-danger btn-sm"
                                                onclick="confirm('Confirm Delete Product id {{ $row->id }}? \nDeleted Products cannot be recovered!')||event.stopImmediatePropagation()"
                                                wire:click="destroy({{ $row->id }})"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                @endforeach
                            </tbody>
                        </table>
                        {!! $products->appends(request()->query())->links() !!}

                        <script>
                            $(document).ready(function() {

                                $(".page-item").on('click', function(e) {
                                    $(".pagination-products").LoadingOverlay("show");
                                });
                            });
                        </script>

                    @endif
                </div>
            </div>

        </div>
        <div class="col-lg-4 col-md-4 margin-tb">
            <div class="card">
                <div class="card-body">
                    <h5>
                        Cadastre seu delivery
                    </h5>

                    <p>
                        O cadastro é simples:
                    </p>
                    <p>
                        Informe o "nome fantasia" do seu delivery. Usaremos este nome em nosso aplicativo para
                        identificar sua marca.
                    </p>
                    <p>
                        O endereço, e-mail e telefone são importantes para entrarmos em contato para novidades e avisos
                        em geral. Não utilizaremos estes dados para mais nada além disto.
                    </p>
                    <p>
                        Se possível informe o CNPJ e site para conhecermos um pouco mais sobre seu delivery.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

		$('.sort-column').on('click', function (e) {
			$(".pagination-products").LoadingOverlay("show");
		});

		

        Livewire.on('paginationLoaded', function() {
            $(".pagination-products").LoadingOverlay("hide");
        })

        Livewire.on('tableUpdating', function() {
            $(".pagination-products").LoadingOverlay("show");
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
<link rel="stylesheet"
    href="{{ asset('node_modules/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />


<!-- Editor visual textarea -->
<script src="{{ asset('node_modules/tinymce/tinymce.min.js') }}"></script>

<!-- Máscaras nos campos -->
<script src="{{ asset('node_modules/cleave.js/cleave.min.js') }}"></script>


<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js">
</script>

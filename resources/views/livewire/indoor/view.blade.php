<div class="mb-5">

    <div class="full-screen mb-3">
        <h2 class="mt-3 mb-0 pb-0"><i class="fas fa-mug-hot"></i> Crie seu pedido manualmente</h2>
        <p>O marketplace que você usa ainda não está no foodStock ou atende pedidos em seu salão? Insira manualmente
            pedidos diretamente na linha de produção.
            <br>Pesquise e selecione os itens do pedido. Em seguida, escolha "criar pedido".
        </p>
    </div>

    <div class="row">
        
        <div class="col-lg-6 col-md-12 margin-tb">
            <div class="card" style="background-color: #eaedd2">
                <div class="card-body">
                    <h3>
                        Novo pedido
                    </h3>
                    <div>
                        <div class="small mb-3">
                        <hr class="my-2">
                            @if(count($orderProducts) > 0)
                            <div class="row pb-2">
                                <div class="col-2 small font-weight-bold">Qtde</div>
                                <div class="col-10 small font-weight-bold">Item</div>
                            </div>
                            @endif
                            @forelse($orderProducts as $item)
                                <div class="row pb-2">
                                    <div class="col-2">
                                        <h4 class="m-0">{{$item["quantity"]}}x</h4>
                                    </div>
                                    <div class="col-8 small">{{$item["product"]["foodstock_name"] ?? $item["product"]["name"]}}</div>
                                    <div class="col-2 small text-right">
                                        <a class="btn btn-sm btn-danger" onclick="handleRemove()" wire:click="removeProduct({{$item["product"]["id"]}})" href="javascript:;" title="Excluir">
                                            <i wire:loading wire:target="removeProduct({{$item["product"]["id"]}})"
                                                class="fas fa-cog fa-spin"></i>
                                            <i class="fa fa-trash"></i>
                                        </a>                                    
                                    </div>
                                </div>
                            @empty
                                Não há produtos neste pedido.
                            @endforelse
                            
                            <script>
                                $(document).ready(function() {

                                    $(".upper-on-keyup").keyup(function () {  
                                        $(this).val($(this).val().toUpperCase());  
                                    });
                                });                            
                            </script>
                            <hr class="my-2">
                            <div class="row pb-0">
                                <div class="col-6 small">
                                    <span class="text-muted"><small>Restaurante *</small></span>
                                    <select id="restaurant_id" name="restaurant_id" class="form-control form-control-sm" wire:model.defer='restaurant_id'>
                                        @foreach($restaurants as $id => $restaurant)
                                        <option value="{{$id}}">
                                            {{$restaurant}}
                                        </option>
                                        @endforeach
                                    </select>                                 
                                </div>                            
                                <div class="col-6 small">
                                    <span class="text-muted"><small>Este pedido vai para *</small></span>
                                    <select id="initial_step" name="initial_step" class="form-control form-control-sm" wire:model.defer='initial_step'>
                                        <option value="0">Definido pelo foodStock</option>
                                        @foreach($productionLines as $id => $productionLine)
                                        <option value="{{$id}}">
                                            {{$productionLine}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 small">
                                    <span class="text-muted"><small>Número do pedido</small></span>
                                    <input type="text" class="form-control form-control-sm upper-on-keyup" wire:model.defer='friendly_number' />
                                </div>    
                                <div class="col-6 small">
                                    <span class="text-muted"><small>Cliente</small></span>
                                    <input type="text" class="form-control form-control-sm" wire:model.defer='customer_name' />
                                </div>   
                                <div class="col-6 small">
                                    <span class="text-muted"><small>Endereço</small></span>
                                    <input type="text" class="form-control form-control-sm" wire:model.defer='address' />
                                </div>                                                                
                                <div class="col-6 small mt-2">
                                    <button class="btn btn-primary btn-lg form-control" wire:click="saveOrder">
                                        <i wire:loading wire:target="saveOrder" class="fas fa-cog fa-spin"></i>
                                        <i class="fas fa-plus"></i> Criar pedido</button>
                                </div>
                                @error('friendly_number')
                                    <div class="col-12 small mt-3">
                                        <div class="alert alert-danger">
                                            Oops. {{ $message }}
                                        </div>
                                    </div>
                                @enderror                                
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            
        </div>

<div class="col-lg-6 col-md-12 margin-tb">

            <div>
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif


            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-12 col-sm-12">
                            <div class="row justify-content-between">
                                <div class="col-md-6 align-self-end mb-2">

                                    <input wire:model='keyWord' type="text" class="form-control form-control-sm"
                                        name="busca" id="busca" placeholder="Pesquisar">

                                </div>

                            </div>
                        </div>
                    </div>

                    @if (count($products) == 0)
                        <div class="text-center mt-5">
                            <img src="{{ asset('images/ico-logo.png') }}" class="mt-2 mb-2">
                            <h3>Nenhum produto.</h3>
                        </div>
                    @else
                        <table class="table table-hover table-bordered pagination-products">
                            <thead class="thead">
                                <tr>
                                    <th>
                                        <div class="row justify-content-between">
                                            <div class="col-6">
                                                <a class="sort-column" href="javascript:;"
                                                    wire:click="sort('name')">Produto</a>
                                                {!! $sort == 'name' && $direction == 'ASC' ? '<i class="fas fa-caret-up"></i>' : '' !!}
                                                {!! $sort == 'name' && $direction == 'DESC' ? '<i class="fas fa-caret-down"></i>' : '' !!}
                                            </div>
                                            <div class="col-auto">

                                            </div>
                                        </div>
                                    </th>
                                    <th>Adicionar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $row)


                                    <tr class="loading">
                                        <td>
                                            <a onclick="formatEditLink('{{ route('products.edit', ['id' => $row->id]) }}')"
                                                href="javascript:;" class="text-dark h4">

                                                @if (filter_var($row->image, FILTER_VALIDATE_URL))
                                                    <img src="{{ $row->image }}" style="height: 60px"
                                                        class="img-thumbnail float-right ml-1">
                                                @endif

                                                @if ($row->enabled)
                                                    {{ strlen($row->foodstock_name) > 0 ? $row->foodstock_name : $row->name }}
                                                @else
                                                    <del>{{ strlen($row->foodstock_name) > 0 ? $row->foodstock_name : $row->name }}</del>
                                                @endif

                                                <div class="p-0" style="line-height: 1">
                                                    @if (!empty($row->external_code))
                                                        <span class="text-muted"><small>Código externo:
                                                                <b>{{ strtoupper($row->external_code) }}</b></small></span>
                                                    @endif

                                                </div>

                                            </a>
                                            <i wire:loading wire:target="productModels.{{ $key }}.initial_step"
                                                class="fas fa-cog fa-spin"></i>
                                            <i wire:loading
                                                wire:target="productModels.{{ $key }}.monitor_stock"
                                                class="fas fa-cog fa-spin"></i>
                                        </td>

                                        <td width="1%" nowrap class="text-center">
                                            <a class="btn btn-success" onclick="handleAdd()" wire:click="addProduct({{$row->id}})"
                                                href="javascript:;" title="Adicionar">
                                                <i wire:loading wire:target="addProduct({{$row->id}})"
                                                class="fas fa-cog fa-spin"></i>
                                                <i class="fa fa-plus"></i>
                                            </a>&nbsp;
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

    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
@push('scripts')
    <script>
        function handleAdd() {
             $("#busca").select();
        }

        function handleRemove() {
             $("#busca").select();
        }        

        $(document).ready(function() {

            $("#busca").select();

            $('.sort-column, .pretty').on('click', function(e) {
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

    <!-- Loading -->
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

@endpush

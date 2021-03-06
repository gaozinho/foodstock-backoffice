<div class="mb-5">

    <div class="full-screen mb-3">
        <h2 class="mt-3 mb-0 pb-0"><i class="fas fa-hamburger"></i> Produtos comercializados</h2>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-12 margin-tb">

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

                                    <!-- <button wire:click="report()" id="btn-exportar" type="button"
                                                    class="btn btn-primary btn-sm"><i
                                                        class="fas fa-file-excel fa-lg"></i></button> -->

                                    <input wire:model='keyWord' type="text" class="form-control form-control-sm"
                                        name="busca" id="busca" placeholder="Pesquisar">

                                </div>
                                <div class="col-auto mb-2">
                                    <a href="{{route('products.create')}}" class="btn btn-success btn-lg">
                                        <i class="fas fa-plus"></i>
                                        Criar novo produto
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="pretty p-switch p-fill">
                            <input wire:model='monitor_stock' name="monitor_stock" type="checkbox" value="1"
                                {{ old('enabled', $monitor_stock == 1) ? 'checked' : '' }} />
                            <div class="state">
                                <label>Monitorando estoque</label>
                            </div>
                        </div>
                        <div class="pretty p-switch p-fill">
                            <input wire:model='stock_alert' name="stock_alert" type="checkbox" value="1"
                                {{ old('enabled', $stock_alert == 1) ? 'checked' : '' }} />
                            <div class="state">
                                <label>Abaixo do m??nimo</label>
                            </div>
                        </div>
                        <div class="pretty p-switch p-fill">
                            <input wire:model='stock_zero' name="stock_zero" type="checkbox" value="1"
                                {{ old('enabled', $stock_zero == 1) ? 'checked' : '' }} />
                            <div class="state">
                                <label>Sem estoque</label>
                            </div>
                        </div>
                        <div class="pretty p-switch p-fill">
                            <input wire:model='enabled' name="enabled" type="checkbox" value="1"
                                {{ old('enabled', $enabled == 1) ? 'checked' : '' }} />
                            <div class="state">
                                <label>Produtos inativos</label>
                            </div>
                        </div>
                    </div>   
                    @if(count($products) == 0)
                        <div class="text-center mt-5">
                            <img src="{{ asset('images/ico-logo.png') }}" class="mt-2 mb-2">
                                <h3>Nenhum produto.</h3>
                        </div>
                    @else
                    <table class="table table-hover table-bordered pagination-products">
                        <thead class="thead">
                            <tr>
                                <th colspan="6">
                                    <span class="legend mt-0 pt-0">Legenda:
                                        <span class="badge bg-monitor">Monitorando estoque</span> 
                                        <span class="badge bg-monitor-warning">Monitorado: abaixo do m??nimo</span> 
                                        <span class="badge bg-monitor-danger">Monitorado: sem estoque</span> 
                                    </span>                                       
                                </th>
                            </tr>
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
                                            <span style="cursor: pointer" wire:click="sort('monitor_stock')"
                                                class="sort-column badge badge-{!! $sort == 'monitor_stock' && $direction == 'ASC' ? 'secondary' : 'success' !!}">
                                                <i class="fas fa-eye"></i> Monitorando estoque
                                                {!! $sort == 'monitor_stock' && $direction == 'ASC' ? '<i class="fas fa-caret-up"></i>' : '' !!}
                                                {!! $sort == 'monitor_stock' && $direction == 'DESC' ? '<i class="fas fa-caret-down"></i>' : '' !!}
                                            </span>
                                        </div>
                                    </div>
                                </th>
                                <th>Inicia em</th>
                                <th>Monitorar</th>
                                <th>A????es</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $row)

                                @php
                                    $classMonitor = "";
                                    if($row->monitor_stock == 1 && $row->current_stock > 0 && $row->current_stock < $row->minimun_stock){
                                        $classMonitor = "bg-monitor-warning";
                                    }else if($row->monitor_stock == 1 && $row->current_stock <= 0){
                                        $classMonitor = "bg-monitor-danger";
                                    }else if($row->monitor_stock == 1){
                                        $classMonitor = "bg-monitor";
                                    }
                                @endphp

                                <tr class="{{ $classMonitor }} loading">
                                    <td>
                                        <a onclick="formatEditLink('{{route('products.edit', ['id' => $row->id])}}')" href="javascript:;"  class="text-dark">
                                        
                                            @if(filter_var($row->image, FILTER_VALIDATE_URL))
                                                <img src="{{$row->image}}" style="height: 60px" class="img-thumbnail float-right ml-1">
                                            @endif

                                            @if ($row->enabled)
                                                {{ strlen($row->foodstock_name) > 0 ? $row->foodstock_name : $row->name }}
                                            @else
                                                <del>{{ strlen($row->foodstock_name) > 0 ? $row->foodstock_name : $row->name }}</del>
                                            @endif

                                            @if ($row->monitor_stock)
                                                <i class="fas fa-eye"></i>
                                            @endif

                                            @if ($row->parents)
                                            <div class="p-0" style="line-height: 1">
                                                <b><small> {{ \Illuminate\Support\Str::limit($row->parents, 70, $end = '...') }}</small></b>
                                            </div>   
                                            @endif                                                    
                                            
                                            <div class="p-0" style="line-height: 1">
                                                @if(!empty($row->external_code))
                                                    <span class="text-muted"><small>C??digo externo: <b>{{ strtoupper($row->external_code) }}</b></small></span>
                                                @endif

                                                <span class="text-muted"><small> Leva produ????o para: <b>{!!$row->initial_step == 0 ? "indeterminado" : '<span class="text-success">' . $row->initial_step . ' ' . $row->productionLineName() . '</span>'!!}</b></small></span>
                                            </div>

                                        </a>
                                        <i wire:loading wire:target="productModels.{{$key}}.initial_step" class="fas fa-cog fa-spin"></i>
                                        <i wire:loading wire:target="productModels.{{$key}}.monitor_stock" class="fas fa-cog fa-spin"></i>
                                    </td>
                                    <td>
                                        <select id="productModels.{{$key}}.initial_step" name="initial_step" class="form-control form-control-sm" wire:model='productModels.{{$key}}.initial_step'>
                                            <option value="0">N/D</option>
                                            @foreach($productionLines as $id => $productionLine)
                                            <option value="{{$id}}" {{intval($row->initial_step) == intval($id) ? "selected='selected'" : ""}}>
                                                {{$productionLine}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>        
                                    <td>
                                        <div class="form-group mb-0">
                                            <div class="pretty p-switch p-fill">
                                                <input id="productModels.{{$key}}.monitor_stock" wire:model='productModels.{{$key}}.monitor_stock' name="monitor_stock" type="checkbox" value="1"
                                                    {{ $row->monitor_stock == '1' ? 'checked' : '' }} />
                                                    <div class="state">
                                                        <label></label>
                                                    </div>
                                            </div>
                                            @if($row->monitor_stock == 1)
                                                <div style="line-height: 1">
                                                    @php
                                                        $panels = [];
                                                        foreach($row->stockPanels()->get() as $panel){
                                                            $panels[] = $panel->name;
                                                        }
                                                    @endphp
                                                    <small>{!! implode(" &bull; ", $panels) !!}</small>
                                                </div>
                                            @endif                                                
                                        </div>
                                    </td>                                                                          
                                    <!--
                                    <td width="1%" nowrap class="text-right" style="line-height: 0.9">
                                        <small>Atual: {{ $row->current_stock }}<br/>Min: {{ $row->minimun_stock }}</small>
                                    </td>
                                    -->
                                    <td width="1%" nowrap class="text-right">
                                        <a onclick="formatEditLink('{{route('products.edit', ['id' => $row->id])}}')" href="javascript:;" title="Editar">
                                            <i class="fa fa-edit"></i>
                                        </a>&nbsp;
                                        <a href="javascript:;" class="text-danger"
                                            wire:click="confirmDestroy({{ $row->id }})">
                                            <i wire:loading wire:target="confirmDestroy({{ $row->id }})" class="fas fa-cog fa-spin"></i>
                                            <i class="fa fa-trash"></i>
                                        </a>
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
        <div class="col-lg-4 col-md-12 margin-tb">
            <div class="card">
                <div class="card-body">
                    <livewire:products.user-catalogs />
                    
                    <h5>
                        Como gerenciar seus produtos
                    </h5>
                    <p>
                        Para uma integra????o de sucesso com os <i>marketplaces</i> o segredo ?? manter os "c??digos externos" (ou "SKU" ou "c??digos PDV") cadastrados corretamente no foodStock.
                    </p>
                    <p>
                        <b>Os c??digos externos fazem a liga????o entre a venda no <i>marketplace</i> e o estoque no foodStock.</b>
                    </p>
                    <p>
                        Funciona assim: todo o produto deve ter um c??digo ??nico em todas as plataformas, por exemplo, o estrogonofe tem o c??digo "EST012" no iFood e na Rappi. Assim, quando ocorre uma venda eu uma destas plataformas, se no foodStock tamb??m existe o c??digo "EST012" saberemos que o estrogonofe foi vendido e retiraremos a quantidade vendida do estoque.
                    </p>
                    <p>
                        Para manter a simplicidade, caso voc?? n??o queira cadastrar todos os produtos um a um, n??s cadastraremos para voc?? a cada venda realizada, caso o produto (e o seu c??digo externo) ainda n??o exista em nossa base. Mas recomendamos que voc?? cadastre os produtos e os respectivos estoques.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
@push('scripts')
    <script>

        function formatEditLink(baseLink){
            var queryString = window.location.href.slice(window.location.href.indexOf('?') + 1);
            console.log(window.location.href.indexOf('?'), queryString);
            if(window.location.href.indexOf('?') < 0) window.location.href = baseLink;
            else  window.location.href = baseLink + "?" + queryString;
        }

        $(document).ready(function() {

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

    <script>

        window.addEventListener('gotoTop', event => {
            window.scrollTo({
                top: 15,
                left: 15,
                behaviour: 'smooth'
            });
        })
    </script>

    <!-- Editor visual textarea -->
    <script src="{{ asset('node_modules/tinymce/tinymce.min.js') }}"></script>

    <!-- M??scaras nos campos -->
    <script src="{{ asset('node_modules/cleave.js/cleave.min.js') }}"></script>

    <!-- Loading -->
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

@endpush 
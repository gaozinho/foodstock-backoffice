
<div>
    @if($orderSummaryDetail->cancellation_requested == 0)
    <!-- TODO FAZER COMPONENTE -->
    <select name="cancellation_code" class="form-control" wire:model.defer="cancellation_code">
        <option value="501">501	PROBLEMAS DE SISTEMA</option>
        <option value="502">502	PEDIDO EM DUPLICIDADE</option>
        <option value="503">503	ITEM INDISPONÍVEL</option>
        <option value="504">504	RESTAURANTE SEM MOTOBOY</option>
        <option value="505">505	CARDÁPIO DESATUALIZADO</option>
        <option value="505">506	PEDIDO FORA DA ÁREA DE ENTREGA</option>
        <option value="507">507	CLIENTE GOLPISTA / TROTE</option>
        <option value="508">508	FORA DO HORÁRIO DO DELIVERY</option>
        <option value="509">509	DIFICULDADES INTERNAS DO RESTAURANTE</option>
        <option value="511">511	ÁREA DE RISCO</option>
        <option value="512">512	RESTAURANTE ABRIRÁ MAIS TARDE</option>
        <option value="513">513	RESTAURANTE FECHOU MAIS CEDO</option>
    </select>

    <button type="button" wire:click="cancellationRequest({{$orderSummaryId}})" class="mt-2 btn btn-secondary"><i wire:loading wire:target="cancellationRequest" class="fas fa-cog fa-spin"></i> Solicitar cancelamento</button>
    @else
    <div class="alert alert-danger mt-3 mb-0 mx-0">
        <div class="small">
            <b>ATENÇÃO! Cancelamento requisitado ao marketplace. Aguardando confirmação de cancelamento.</b>
        </div>     
    </div>    
    
    @endif
</div>


<div class="modal fade order-modal" id="order-modal" tabindex="-1" role="dialog" aria-labelledby="order-modal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                      @include('livewire.panels.order-header-include')
                </div>
                <div class="modal-body">
                    @include('livewire.panels.order-detail-include')
                </div>
                <div class="modal-footer justify-content-between">
                    
                    @if($orderSummaryDetail->canceled == 1)

                        <div>
                            <button type="button" data-dismiss="modal" class="mt-2 btn btn-secondary">Fechar <i class="fas fa-times"></i></button>
                        </div>
                        <div>
                            <button type="button" name="nextStep" value="nextStep" wire:click="nextStep({{ $orderSummaryDetail->id }}, {{$production_line_id}})" class="mt-2 btn btn-lg btn-danger text-uppercase">
                                <i wire:loading wire:target="nextStep" class="fas fa-cog fa-spin"></i> Tirar do painel <i class="fas fa-trash-alt"></i></button>
                        </div>

                    @elseif (isset($orderProductionLine))

                        <div>
                            <button type="button" data-dismiss="modal" class="mt-2 btn btn-secondary mr-1">Fechar <i class="fas fa-times"></i></button>
                            @if ($orderProductionLine->can_pause && $orderSummaryDetail->paused != 1)
                                <button type="button" name="pause" value="pause" wire:click="pause({{ $orderSummaryDetail->id }})" class="mt-2 btn btn-warning">
                                    <i wire:loading wire:target="pause" class="fas fa-cog fa-spin"></i> Pausar <i class="fas fa-pause"></i>
                                </button>
                            @endif
                            
                        </div>
                        <div>
                            <button type="button" name="nextStep" value="nextStep" wire:click="nextStep({{ $orderSummaryDetail->id}}, {{$production_line_id}})" class="mt-2 btn btn-lg btn-success text-uppercase">
                                <i wire:loading wire:target="nextStep" class="fas fa-cog fa-spin"></i> {{ $productionLine->name }} OK <i class="fas fa-step-forward"></i></button>
                        </div>

                    @endif                    

                </div>
            </div>
        </div>
    </div>
        
    <script>
        $(document).ready(function() {
            //moment.locale('pt-br');
            //var time = moment('{{$orderSummaryDetail->orderBabelized->createdDate}}').fromNow();
            //$('#clock').html(time);
        });
    </script>
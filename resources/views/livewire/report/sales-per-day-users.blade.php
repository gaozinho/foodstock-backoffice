<div>

    <button type="button" wire:click="excel('{{$selectDate}}')" class="btn btn-outline-success btn-sm">
        <i wire:loading wire:target="excel('{{$selectDate}}')" class="fas fa-cog fa-spin"></i>
        <i class="fas fa-file-excel"></i>
    </button>
    <button type="button" wire:click="pdf('{{$selectDate}}')" class="btn btn-outline-danger btn-sm">
        <i wire:loading wire:target="pdf('{{$selectDate}}')" class="fas fa-cog fa-spin"></i>
        <i class="fas fa-file-pdf"></i></button> Vendas de produtos por dia
</div>

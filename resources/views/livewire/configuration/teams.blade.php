<div class="mb-5">

    <div class="full-screen mb-3">
        <h2 class="mt-3 mb-0 pb-0"><i class="fas fa-users-cog"></i> Equipe de trabalho</h2>
        <p class="mb-2" style="line-height: 1.2">Além de você, configure outras pessoas que poderão interagir com o processo de produção de suas lojas.</p>
    </div>


    <div class="row">
        <div class="col-lg-8 col-md-8 margin-tb">


            <div class="card border">
                <div class="card-body">
                    <h5>
                        Dados do integrante da equipe
                    </h5>                                    
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <p><strong>Ops!</strong> Temos alguns problemas.</p>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        </div>
                    
                        <div class="col-lg-12 margin-tb">
                            {!! Form::model($user, ['method' => 'POST', 'wire:submit.prevent' => 'save', 'id' => 'user-form']) !!}
                            <div class="row">
                    
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <strong>Nome *</strong>
                                        {!! Form::text('name', $user->name, ['wire:model.defer' => 'user.name', 'class' => 'site form-control']) !!}
                                    </div>
                                </div>
                    
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <strong>E-mail *</strong>
                                        {!! Form::text('email', $user->email, ['wire:model.defer' => 'user.email', 'class' => 'form-control']) !!}
                                    </div>
                                </div>   
                                
                                <div class="loading">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <strong>Qual é o papel deste integrante? *</strong>
                                        <div class="row mt-2">
                                            <div class="col-12 text-muted mb-2">
                                                <small>Os papéis abaixo são aqueles que você definiu em seu <a href="{{route('configuration.production-line.index')}}">Processo de Produção</a>.</small>
                                            </div>
                                            @foreach($roles as $role)
                                            <div class="col-xs-12 col-sm-3 col-md-3">
                                                <div class="form-group">
                                                    <div class="pretty p-switch p-fill">
                                                        <input onClick='$(".loading").LoadingOverlay("show")' name="role_id[{{ $role->id }}]" type="checkbox" 
                                                            wire:model="selectedRoles.{{ $role->id }}" 
                                                            value="{{ $role->id }}"
                                                        />
                                                        <div class="state">
                                                            <label>{{$role->name}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <strong>Quais áreas do sistema podem ser acessadas? *</strong>
                                        <div class="row mt-2">
                                            @foreach($genericRoles as $role)
                                            <div class="col-xs-12 col">
                                                <div class="form-group">
                                                    <div class="pretty p-switch p-fill">
                                                        <input onClick='$(".loading").LoadingOverlay("show")' name="role_id[{{ $role->id }}]" type="checkbox" 
                                                            wire:model="selectedRoles.{{ $role->id }}" 
                                                            value="{{ $role->id }}"
                                                        />
                                                        <div class="state">
                                                            <label>{{$role->description}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>                                    

                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <strong>Este integrante terá acesso a qual loja?</strong>
                                        <div class="row mt-2">
                                            @foreach($restaurants as $restaurant)
                                            <div class="col-xs-12 col-sm-3 col-md-3">
                                                <div class="form-group">
                                                    <div class="pretty p-switch p-fill">
                                                        <input onClick='$(".loading").LoadingOverlay("show")' name="restaurant_id[{{ $restaurant->id }}]" type="checkbox" 
                                                            wire:model="selectedRestaurants.{{ $restaurant->id }}" 
                                                            value="{{ $restaurant->id }}"
                                                        />
                                                        <div class="state">
                                                            <label>{{$restaurant->name}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <strong>Senha *</strong>
                                        {!! Form::text('password', $user->password, ['wire:model.defer' => 'user.password', 'class' => 'form-control']) !!}
                                        @if(intval($user->id) > 0)
                                        <small>Deixe em branco para não alterar a senha atual.</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <strong>Confirmar senha *</strong>
                                        {!! Form::text('password_confirmation', null, ['wire:model.defer' => 'password_confirmation', 'class' => 'form-control']) !!}
                                    </div>
                                </div>                                

                            </div>    
                            <div class="form-group text-right">
                                @if(intval($user->id) > 0)
                                    <button wire:click="destroy" type="button" name="destroy" value="del" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> <i wire:loading wire:target="destroy" class="fas fa-cog fa-spin"></i>
                                        Excluir</button> 

                                    <button wire:click="save" type="button" name="save" value="ok" class="btn btn-success pr-4 pl-4 text-dark font-weight-bold text-uppercase">
                                        <i class="fas fa-save"></i> <i wire:loading wire:target="save" class="fas fa-cog fa-spin"></i>
                                        Salvar</button>                                        
                                @else

                                    <button wire:click="save" type="button" name="save" value="ok"
                                        class="btn btn-success pr-4 pl-4 text-dark font-weight-bold text-uppercase"><i
                                            class="fas fa-save"></i> <i wire:loading wire:target="save" class="fas fa-cog fa-spin"></i>
                                        Adicionar</button>
                                @endif
                            </div>
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
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 margin-tb">
            <div class="card">
                <div class="card-body">                    
                    <h5>
                        Membros da equipe
                    </h5>
                    <div class="list-group">
                        @forelse($restaurantUsers as $restaurantUser)
                            <a href="#" wire:click="loadUser({{$restaurantUser->id}})" class="list-group-item list-group-item-action">
                                <i wire:loading wire:target="loadUser({{$restaurantUser->id}})" class="fas fa-cog fa-spin"></i> {{$restaurantUser->name}}
                                <div>

                                @foreach($restaurantUser->restaurants()->get() as $restaurant)
                                    <span class="badge badge-warning">{{$restaurant->name}}</span> 
                                @endforeach
                                <br />
                                @foreach($restaurantUser->stepRoles() as $role)
                                    <span class="badge badge-danger">{{$role->description}}</span> 
                                @endforeach
                                @foreach($restaurantUser->roles()->where("roles.guard_name", "panel")->get() as $role)
                                    <span class="badge badge-danger">{{$role->description}}</span> 
                                @endforeach                                
                                </div>
                            </a>
                        @empty
                            <p>Você ainda não adicionou membros à equipe.</p>
                        @endforelse
                    </div>
                    <div  class="mt-3">
                        <small>
                            <p class="text-muted">A sua equipe acessa os painéis específicos de cada papel. Por exemplo, o cozinheiro acessa o painel de pedidos que estão na cozinha. Assim como o montador do prato acessa aqueles pedidos que já passaram pela cozinha e devem sem montados.</p>
                            <p class="text-muted">Como administrador do delivery, você acessa todos os painéis.</p>    
                        </small>
                    </div>
                </div>
            </div>                    
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
@push('scripts')
    <script>
        $(document).ready(function() {
            Livewire.on('stopLoading', function(){
                $(".loading").LoadingOverlay("hide");
            })
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
@endpush
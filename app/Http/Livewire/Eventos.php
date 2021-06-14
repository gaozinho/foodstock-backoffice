<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evento;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Exports\EventosExport;

class Eventos extends Component
{
    use WithPagination;
	use WithFileUploads;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $nome, $descricao, $data_inicio, $data_fim, $link, $imagem, $is_active;
    public $saveMode = false;

	public Evento $evento;

    protected $rules = [
        'evento.nome' => 'required|string|min:1|max:255',
        'evento.descricao' => 'max:1000|required',
        'evento.data_inicio' => 'required|date_format:d/m/Y|nullable',
        'evento.data_fim' => 'required|date_format:d/m/Y|nullable',
        'evento.link' => 'string|min:1|nullable',
        'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'evento.is_active' => 'boolean|nullable', 
    ];

    public function updatingKeyWord($value){
        $this->resetPage();
    }

    public function render()
    {
        //$this->dispatchBrowserEvent('gotoTop');
		$keyWord = '%'.$this->keyWord .'%';
        $this->emit('paginationLoaded');
        return view('livewire.eventos.view', [
            'eventos' => Evento::latest()
						->orWhere('nome', 'LIKE', $keyWord)
						->orWhere('descricao', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->saveMode = false;
    }
	
    private function resetInput()
    {		
		$this->imagem = null;
		$this->evento = new Evento();
    }

    public function save($continue)
    {
		if(intval($this->evento->id) > 0){
			$this->update($continue);
		}else{
			$this->store($continue);
		}
        $this->resetInput();
        $this->saveMode = false;
	}

    public function create()
    {
        $this->resetValidation();
        $this->evento = new Evento();
		$this->saveMode = true;
    }

    public function store()
    {
        try {
			$this->validate();
			is_object($this->imagem) ? $this->evento->imagem = $this->imagem->store('eventos', 'public') : null;
            $this->evento->save();
			session()->flash('success', 'Evento salvo com sucesso.');
            $this->dispatchBrowserEvent('gotoTop');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            session()->flash('error', 'Ops... ocorreu em erro ao tentar salvar o Evento.');
        }
    }

    public function edit($id)
    {
        $this->resetValidation();
        $this->evento = Evento::findOrFail($id);
		$this->nome = $this->evento->nome;
		$this->saveMode = true;
    }

    public function update($continue)
    {
        try {
			$this->validate();

            if(is_object($this->imagem)){
                $this->evento->imagem = $this->imagem->store('eventos', 'public');
            }

            $this->evento->save();
			session()->flash('success', 'Evento salvo com sucesso.');
            $this->dispatchBrowserEvent('gotoTop');
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            session()->flash('error', 'Ops... ocorreu em erro ao tentar salvar o Evento.');
        }
    }

    public function destroy($id)
    {
        try {
            $evento = Evento::findOrFail($id);
            Storage::delete($evento->imagem);
            $evento->delete();
			session()->flash('success', 'Evento excluído com sucesso.');
        }catch(\Illuminate\Database\QueryException $e) {
            $mensagem = $this->formatSqlError($e->getPrevious()->getErrorCode(), $e->getMessage());
			session()->flash('error', sprintf('Não foi possível excluir o registro. <br />%s', $mensagem));
        } catch (Exception $exception) {
            if(env('APP_DEBUG')) throw $exception;
            session()->flash('error', 'Unexpected error occurred while trying to process your request.');
        }
    } 

    public function report() 
    {
        $fileName = sprintf("Eventos %s.xlsx", date("d-m-Y"));
        return (new EventosExport)->filtro($this->keyWord)->download($fileName);
    }
    
    public function removeImagem(){
        Storage::delete("public/" . $this->evento->imagem);
        $this->evento->imagem = "";
    }    

    public function downloadImagem(){
		return Storage::download("public/" . $this->evento->imagem, ($this->evento->nome . "." . pathinfo($this->evento->imagem, PATHINFO_EXTENSION)));
    }       
}

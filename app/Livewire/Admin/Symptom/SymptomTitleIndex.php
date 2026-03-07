<?php

namespace App\Livewire\Admin\Symptom;

use App\Models\SymptomTitle;
use App\Models\SymptomType;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class SymptomTitleIndex extends Component
{
    use WithPagination;

    // Table State
    public $search = '';
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';

    // Form State
    public $showModal = false;
    public $titleId = null;
    public $symptom_type_id, $title, $description;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['symptom_type_id', 'title', 'description', 'titleId']);

        if ($id) {
            $this->titleId = $id;
            $sTitle = SymptomTitle::findOrFail($id);
            $this->symptom_type_id = $sTitle->symptom_type_id;
            $this->title = $sTitle->title;
            $this->description = $sTitle->description;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'symptom_type_id' => 'required|exists:symptom_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        SymptomTitle::updateOrCreate(['id' => $this->titleId], [
            'symptom_type_id' => $this->symptom_type_id,
            'title' => $this->title,
            'description' => $this->description,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->titleId ? 'Symptom Title updated.' : 'Symptom Title created.');
    }

    public function delete($id)
    {
        SymptomTitle::destroy($id);
        session()->flash('success', 'Symptom Title deleted.');
    }

    public function render()
    {
        $titles = SymptomTitle::with('type')
            ->where(function($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhereHas('type', function($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.symptom.symptom-title-index', [
            'titles' => $titles,
            'types' => SymptomType::orderBy('name')->get()
        ]);
    }
}
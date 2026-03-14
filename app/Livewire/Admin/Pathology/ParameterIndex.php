<?php

namespace App\Livewire\Admin\Pathology;

use App\Models\PathologyParameter;
use App\Models\PathologyUnit;
use Livewire\Component;
use Livewire\WithPagination;

class ParameterIndex extends Component
{
    use WithPagination;

    // DataTable State
    public $search = '';
    public $perPage = 10;
    public $sortBy = 'parameter_name';
    public $sortDir = 'ASC';

    // Form State
    public $showModal = false;
    public $parameterId = null;
    public $parameter_name, $reference_range_from, $reference_range_to, $pathology_unit_id, $description;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setSortBy($field)
    {
        $this->sortDir = ($this->sortBy === $field && $this->sortDir === 'ASC') ? 'DESC' : 'ASC';
        $this->sortBy = $field;
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['parameter_name', 'reference_range_from', 'reference_range_to', 'pathology_unit_id', 'description', 'parameterId']);

        if ($id) {
            $this->parameterId = $id;
            $param = PathologyParameter::findOrFail($id);
            $this->parameter_name = $param->parameter_name;
            $this->reference_range_from = $param->reference_range_from;
            $this->reference_range_to = $param->reference_range_to;
            $this->pathology_unit_id = $param->pathology_unit_id;
            $this->description = $param->description;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'parameter_name' => 'required|string|max:255',
            'reference_range_from' => 'nullable|string|max:50',
            'reference_range_to' => 'nullable|string|max:50',
            'pathology_unit_id' => 'required|exists:pathology_units,id',
            'description' => 'nullable|string',
        ]);

        PathologyParameter::updateOrCreate(['id' => $this->parameterId], [
            'parameter_name' => $this->parameter_name,
            'reference_range_from' => $this->reference_range_from,
            'reference_range_to' => $this->reference_range_to,
            'pathology_unit_id' => $this->pathology_unit_id,
            'description' => $this->description,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->parameterId ? 'Parameter updated.' : 'Parameter created.');
    }

    public function delete($id)
    {
        try {
            $param = PathologyParameter::findOrFail($id);
            // Check if linked to any tests
            if ($param->tests()->count() > 0) {
                session()->flash('error', 'Cannot delete. Parameter is linked to Pathology Tests.');
                return;
            }
            $param->delete();
            session()->flash('success', 'Parameter deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $parameters = PathologyParameter::with('unit')
            ->where('parameter_name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.pathology.parameter-index', [
            'parameters' => $parameters,
            'units' => PathologyUnit::orderBy('name', 'ASC')->get()
        ]);
    }
}
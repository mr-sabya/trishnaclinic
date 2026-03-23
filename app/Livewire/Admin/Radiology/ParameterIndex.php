<?php

namespace App\Livewire\Admin\Radiology;

use App\Models\RadiologyParameter;
use App\Models\RadiologyUnit;
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
    public $parameter_name, $reference_range_from, $reference_range_to, $radiology_unit_id, $description;

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
        $this->reset(['parameter_name', 'reference_range_from', 'reference_range_to', 'radiology_unit_id', 'description', 'parameterId']);

        if ($id) {
            $this->parameterId = $id;
            $param = RadiologyParameter::findOrFail($id);
            $this->parameter_name = $param->parameter_name;
            $this->reference_range_from = $param->reference_range_from;
            $this->reference_range_to = $param->reference_range_to;
            $this->radiology_unit_id = $param->radiology_unit_id;
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
            'radiology_unit_id' => 'required|exists:radiology_units,id',
            'description' => 'nullable|string',
        ]);

        RadiologyParameter::updateOrCreate(['id' => $this->parameterId], [
            'parameter_name' => $this->parameter_name,
            'reference_range_from' => $this->reference_range_from,
            'reference_range_to' => $this->reference_range_to,
            'radiology_unit_id' => $this->radiology_unit_id,
            'description' => $this->description,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->parameterId ? 'Radiology parameter updated.' : 'Radiology parameter created.');
    }

    public function delete($id)
    {
        try {
            $param = RadiologyParameter::findOrFail($id);
            // Check if linked to any tests
            if ($param->tests()->count() > 0) {
                session()->flash('error', 'Cannot delete. Parameter is linked to Radiology Tests.');
                return;
            }
            $param->delete();
            session()->flash('success', 'Parameter deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting parameter: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $parameters = RadiologyParameter::with('unit')
            ->where('parameter_name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.radiology.parameter-index', [
            'parameters' => $parameters,
            'units' => RadiologyUnit::orderBy('name', 'ASC')->get()
        ]);
    }
}

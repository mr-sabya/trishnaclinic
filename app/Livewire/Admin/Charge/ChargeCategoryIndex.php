<?php

namespace App\Livewire\Admin\Charge;

use App\Models\ChargeCategory;
use App\Models\ChargeType;
use Livewire\Component;
use Livewire\WithPagination;

class ChargeCategoryIndex extends Component
{
    use WithPagination;

    // DataTable State
    public $search = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDir = 'ASC';

    // Form State
    public $showModal = false;
    public $categoryId = null;
    public $charge_type_id;
    public $name;
    public $description;

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
        $this->reset(['name', 'description', 'charge_type_id', 'categoryId']);

        if ($id) {
            $this->categoryId = $id;
            $category = ChargeCategory::findOrFail($id);
            $this->name = $category->name;
            $this->description = $category->description;
            $this->charge_type_id = $category->charge_type_id;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'charge_type_id' => 'required|exists:charge_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        ChargeCategory::updateOrCreate(['id' => $this->categoryId], [
            'charge_type_id' => $this->charge_type_id,
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->categoryId ? 'Category updated.' : 'Category created.');
    }

    public function delete($id)
    {
        try {
            ChargeCategory::destroy($id);
            session()->flash('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot delete category. It might be linked to existing charges.');
        }
    }

    public function render()
    {
        $categories = ChargeCategory::with('chargeType')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhereHas('chargeType', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.charge.charge-category-index', [
            'categories' => $categories,
            'chargeTypes' => ChargeType::where('status', true)->get()
        ]);
    }
}

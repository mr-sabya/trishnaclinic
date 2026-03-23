<?php

namespace App\Livewire\Admin\Radiology;

use App\Models\RadiologyCategory;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryIndex extends Component
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
    public $name;

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
        $this->reset(['name', 'categoryId']);

        if ($id) {
            $this->categoryId = $id;
            $category = RadiologyCategory::findOrFail($id);
            $this->name = $category->name;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:radiology_categories,name,' . $this->categoryId,
        ]);

        RadiologyCategory::updateOrCreate(['id' => $this->categoryId], [
            'name' => $this->name,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->categoryId ? 'Category updated successfully.' : 'Category created successfully.');
    }

    public function delete($id)
    {
        try {
            $category = RadiologyCategory::findOrFail($id);

            // Safety check: Prevent deletion if category has tests
            if ($category->tests()->count() > 0) {
                session()->flash('error', 'Cannot delete. This category contains active radiology tests.');
                return;
            }

            $category->delete();
            session()->flash('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the category.');
        }
    }

    public function render()
    {
        $categories = RadiologyCategory::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.radiology.category-index', [
            'categories' => $categories
        ]);
    }
}

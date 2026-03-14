<?php

namespace App\Livewire\Admin\Pathology;

use App\Models\PathologyCategory;
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
            $category = PathologyCategory::findOrFail($id);
            $this->name = $category->name;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:pathology_categories,name,' . $this->categoryId,
        ]);

        PathologyCategory::updateOrCreate(['id' => $this->categoryId], [
            'name' => $this->name,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->categoryId ? 'Category updated successfully.' : 'Category created successfully.');
    }

    public function delete($id)
    {
        try {
            // Check if category has tests before deleting (optional safety check)
            $category = PathologyCategory::findOrFail($id);
            if ($category->tests()->count() > 0) {
                session()->flash('error', 'Cannot delete Category. It contains active pathology tests.');
                return;
            }

            $category->delete();
            session()->flash('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while trying to delete the category.');
        }
    }

    public function render()
    {
        $categories = PathologyCategory::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.pathology.category-index', [
            'categories' => $categories
        ]);
    }
}
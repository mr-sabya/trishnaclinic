<?php

namespace App\Livewire\Admin\Department;

use App\Models\AdminDepartment;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class AdminDepartmentIndex extends Component
{
    use WithPagination;

    // Table State
    public $search = '';
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';

    // Form State
    public $showModal = false;
    public $deptId = null;
    public $name, $description, $status = true;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'description', 'status', 'deptId']);

        if ($id) {
            $this->deptId = $id;
            $dept = AdminDepartment::findOrFail($id);
            $this->name = $dept->name;
            $this->description = $dept->description;
            $this->status = $dept->status;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('admin_departments')->ignore($this->deptId)],
            'description' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);

        AdminDepartment::updateOrCreate(['id' => $this->deptId], [
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->deptId ? 'Department updated.' : 'Department created.');
    }

    public function delete($id)
    {
        AdminDepartment::destroy($id);
        session()->flash('success', 'Department deleted.');
    }

    public function render()
    {
        $departments = AdminDepartment::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.department.admin-department-index', [
            'departments' => $departments
        ]);
    }
}

<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'created_at';
    public $sortDir = 'DESC';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function setSortBy($field)
    {
        $this->sortDir = ($this->sortBy === $field && $this->sortDir === 'ASC') ? 'DESC' : 'ASC';
        $this->sortBy = $field;
    }

    public function delete($id)
    {
        if ($id === Auth::user()->id) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }
        User::destroy($id);
        session()->flash('success', 'User and associated profile deleted.');
    }

    public function render()
    {
        $users = User::with('staff.adminDepartment')
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.user.index', compact('users'));
    }
}

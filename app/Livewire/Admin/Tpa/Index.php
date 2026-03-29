<?php

namespace App\Livewire\Admin\Tpa;

use App\Models\Tpa;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'name', $sortDir = 'ASC';

    public $showModal = false;
    public $tpaId = null;
    public $name, $code, $contact_number, $address, $contact_person_name, $contact_person_phone, $status = true;

    protected $paginationTheme = 'bootstrap';

    // Auto-generate code when Name is updated
    public function updatedName()
    {
        $this->generateCode();
    }

    // Auto-generate code when Contact Number is updated
    public function updatedContactNumber()
    {
        $this->generateCode();
    }

    private function generateCode()
    {
        // Only auto-generate if we are creating a NEW TPA (not editing)
        // Or if the code field is currently empty
        if (!$this->tpaId) {
            $namePart = Str::upper(Str::substr(preg_replace('/[^A-Za-z0-0]/', '', $this->name), 0, 3));
            $phonePart = Str::substr($this->contact_number, -4);

            if ($namePart || $phonePart) {
                $this->code = $namePart . $phonePart;
            }
        }
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['name', 'code', 'contact_number', 'address', 'contact_person_name', 'contact_person_phone', 'status', 'tpaId']);

        if ($id) {
            $this->tpaId = $id;
            $tpa = Tpa::findOrFail($id);
            $this->name = $tpa->name;
            $this->code = $tpa->code;
            $this->contact_number = $tpa->contact_number;
            $this->address = $tpa->address;
            $this->contact_person_name = $tpa->contact_person_name;
            $this->contact_person_phone = $tpa->contact_person_phone;
            $this->status = $tpa->status;
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', Rule::unique('tpas')->ignore($this->tpaId)],
            'contact_number' => 'required|string',
        ]);

        Tpa::updateOrCreate(['id' => $this->tpaId], [
            'name' => $this->name,
            'code' => Str::upper($this->code),
            'contact_number' => $this->contact_number,
            'address' => $this->address,
            'contact_person_name' => $this->contact_person_name,
            'contact_person_phone' => $this->contact_person_phone,
            'status' => $this->status,
        ]);

        $this->showModal = false;
        session()->flash('success', $this->tpaId ? 'TPA updated successfully.' : 'TPA created successfully.');
    }

    // ... setSortBy, updatingSearch, delete and render methods remain same ...
    public function setSortBy($field)
    {
        $this->sortDir = ($this->sortBy === $field && $this->sortDir === 'ASC') ? 'DESC' : 'ASC';
        $this->sortBy = $field;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        Tpa::destroy($id);
        session()->flash('success', 'TPA deleted successfully.');
    }

    public function render()
    {
        $tpas = Tpa::where(function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('code', 'like', '%' . $this->search . '%');
        })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.tpa.index', ['tpas' => $tpas]);
    }
}

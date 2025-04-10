<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserForm extends Component
{
    public $userId;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'customer';
    public $isEdit = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255'],
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,operator,customer',
        ];

        if ($this->isEdit) {
            $rules['email'][] = Rule::unique('users')->ignore($this->userId);
            $rules['password'] = 'nullable|min:8|confirmed';
        } else {
            $rules['email'][] = 'unique:users';
            $rules['password'] = 'required|min:8|confirmed';
        }

        return $rules;
    }

    public function mount($user = null)
    {
        if ($user) {
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->role = $user->role;
            $this->isEdit = true;
        }
    }

    public function save()
    {
        $this->validate();

        try {
            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'role' => $this->role,
            ];

            if ($this->isEdit) {
                $user = User::findOrFail($this->userId);
                
                if (!empty($this->password)) {
                    $userData['password'] = Hash::make($this->password);
                }
                
                $user->update($userData);
                
                // SweetAlert2 success message
                $this->dispatch('swal:success', [
                    'title' => 'Success!',
                    'text' => 'User updated successfully.',
                    'icon' => 'success',
                ]);
                
                // Redirect to the user details page
                return redirect()->route('admin.users.show', $user);
            } else {
                // Add password for new user
                $userData['password'] = Hash::make($this->password);
                
                $user = User::create($userData);
                
                // SweetAlert2 success message
                $this->dispatch('swal:success', [
                    'title' => 'Success!',
                    'text' => 'User created successfully.',
                    'icon' => 'success',
                ]);
                
                // Redirect to the users list
                return redirect()->route('admin.users.index');
            }
        } catch (\Exception $e) {
            // SweetAlert2 error message
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text' => 'Error saving user: ' . $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.user-form');
    }
}
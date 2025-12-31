<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use App\Enums\Role;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:8',
    ];

    protected $messages = [
        'email.required' => 'Harap bagian email diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Harap bagian kata sandi diisi.',
        'password.min' => 'Kata Sandi minimal 8 karakter.',
    ];

    public function login()
    {

        $user = User::where('email', $this->email)->first();

        if (!$user || !Hash::check($this->password, $user->password)) {
            session()->flash('error', 'Gagal, Email atau Kata Sandi salah!!!');
            return;
        }
        Auth::login($user);
        // dd($user->role);  // Tampilkan nilai role user
        return $this->redirect('/');
    }


    // private function redirectBasedOnRole($user)
    // {
    //     // dd($user->role);  // Tampilkan nilai role sebelum melakukan redirect
    //     if ($user->role === Role::Admin) {
    //         return redirect('/scan');
    //     } elseif ($user->role === Role::Supervisor) {
    //         return redirect('admin_acc');
    //     } elseif ($user->role === Role::Vendor) {
    //         // dd('t6gyfu');
    //         return redirect()->route('create-barcode');
    //     }
    // }

    public function render()
    {
        return view('livewire.auth.login')
            ->extends('layouts.app')
            ->section('content');
    }
}

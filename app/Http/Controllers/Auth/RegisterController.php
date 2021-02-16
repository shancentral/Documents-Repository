<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Auth;
use App\Rules\MatchOldPassword;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    // Change password
    public function editPassword()
    {
        return view(
            'auth.passwords.change'
        );
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required', 'min: 5'],
            'confirm_new_password' => ['same:new_password'],
        ]);

        $password_updated = User::find(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        if( $password_updated ):
            return redirect('/')
                ->with('success', 'password updated Successfully');
        endif;
    }

    public function viewUsers()
    {
        $users = User::paginate(30);
        $index = $users->firstItem();

        return view('users.index', [
            'users' => $users,
            'index' => $index
        ]);
    }

    public function createUser(Request $request)
    {
        $create = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(rand()),
        ]);

        if( $create ):
            return redirect()
                ->action('Auth\RegisterController@viewUsers', [])
                ->with('success', 'User created Successfully');
        else:
            return redirect()
                ->action('Auth\RegisterController@viewUsers', [])
                ->with('failure', 'User created Successfully');
        endif;
    }

    public function editUser($id){
        $user = User::find($id);
        return view('users.edit', [
            'user' => $user
        ]);
    }

    public function updateUser(Request $request){
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ])->validate();

        $update = User::find($request->id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        if( $update ):
            return redirect()
                ->action('Auth\RegisterController@viewUsers', [])
                ->with('success', 'User updated Successfully');
        else:
            return redirect()
                ->action('Auth\RegisterController@viewUsers', [])
                ->with('failure', 'User update failed');
        endif;
    }

    public function deleteUser($id){
        $delete = User::where('id', $id)->delete();

        if( $delete ):
            return redirect()
                ->action('Auth\RegisterController@viewUsers', [])
                ->with('success', 'User deleted Successfully');
        else:
            return redirect()
                ->action('Auth\RegisterController@viewUsers', [])
                ->with('failure', 'User delete failed');
        endif;
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\ChangePasswordRequest;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\ResetPasswordRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("users.index")->with("users", User::paginate(5));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("users.create")
            ->with("roles", Role::all())
            ->with("allUsers", User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "manager_id" => $request->manager,
            "password" => Hash::make($request->password)
        ]);

        if($request->role_ids) {
            $user->roles()->attach($request->role_ids);
        }

        session()->flash("success", "User created successfully");

        return redirect(route("users.index"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view("users.create")
            ->with("user", $user)
            ->with("roles", Role::all())
            ->with("allUsers", User::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if($user->id == auth()->user()->id) {
            session()->flash("error", "You are not authorized to change or delete your own account.");
            return redirect()->back();
        }

        $user->update([
            "name" => $request["name"],
            "manager_id" => $request["manager"]
        ]);

        if($request->role_ids) {
            $user->roles()->sync($request->role_ids);
        }
        else {
            $user->roles()->detach();
        }

        session()->flash("success", "User updated successfully");

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->id == auth()->user()->id) {
            session()->flash("error", "You are not authorized to change or delete your own account.");
            return redirect()->back();
        }

        $user->roles()->detach();

        $user->delete();

        session()->flash("success", "User deleted successfully");

        return redirect(route("users.index"));

    }

    /**
     * Return form for PWD reset
     */
    public function resetForm(User $user)
    {
        return view("users.reset")->with("user", $user);
    }

    /**
     * Reset user's password
     */
    public function reset(ResetPasswordRequest $request, User $user)
    {
        if($user->id == auth()->user()->id) {
            session()->flash("error", "You are not authorized to change or delete your own account. Please use the biult in password change functionality.");
            return redirect()->back();
        }
        else {
            $user->update([
                "password" => Hash::make($request->password),
                "is_expired" => true
            ]);
    
            session()->flash("success", "Password reset successful.");
    
            return redirect(route("users.index"));
        }
    }

    public function changePasswordForm()
    {
        return view("users.change-password");
    }
    
    public function changePassword(ChangePasswordRequest $request)
    {
        if(Hash::check($request->old_password, auth()->user()->password)) {
            auth()->user()->update([
                "password" => Hash::make($request->new_password),
                "is_expired" => false
            ]);
    
            session()->flash("success", "Password changed successfully.");
    
            return redirect("/");
        }
        else {
            session()->flash("error", "Incorrect password provided.");
            return redirect()->back();
        }
        
    }

    public function trashed()
    {
        return view("users.index")
            ->with("users", User::onlyTrashed()->get())
            ->with("trashed", true);
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->where("id", "=", "$id")->first();

        $user->restore();

        session()->flash("success", "User restored successfully.");
            
        return redirect(route("users.index"));
    }
}

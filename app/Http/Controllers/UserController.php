<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Auth;
use DB;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.index',['user' => Auth::user()]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(user $user)
    {
        return view('user.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('user.edit', ['user' => $user, 'roles' =>$roles]);    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validateUser($request);
        if (Auth::user()->role_id != $request->role_id){
            if ($request->role_id==1){
                $role_verified=TRUE;
            } else {
                $role_verified=FALSE;
            }
            $request->request->add(['role_verified' => $role_verified]);
        }

        $user->update($request->all());
        return redirect('/user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect(url()->previous())->with('message', 'The user account has been deleted');
    }

    public function validateUser(Request $request){
        return $request->validate([
            'role_id' => 'required|numeric',
            'admin' => 'prohibited',
            'name' => 'required|max:50|string',
            'email' => 'prohibited',
            'password' => 'prohibited',
        ]);
    }

    public function showAdmin(Request $request){
        $notVerifiedUsers=user::where('role_verified', 0)
            ->orderBy('name', 'asc')
            ->get();
        $users=user::orderBy('name', 'asc')->get();
        $roles = Role::all();
        return view('user.adminPage', compact('notVerifiedUsers', 'roles', 'users'));
    }

    public function verifyAdmin($id)
    {
        $user=User::where('id',$id)->first();
        $user->update(['role_verified'=>TRUE]);
        //dd($user);
        return redirect(url()->previous());
    }

    public function declineAdmin($id)
    {
        $user=User::where('id',$id)->first();
        $user->update(['role_id'=>1, 'role_verified'=>TRUE]);
        return redirect(url()->previous());

    }

    public function setAdmin($id)
    {
        $user=User::where('id',$id)->first();
        $user->update(['admin'=>1]);
        return redirect(url()->previous());
    }

    public function revokeAdmin($id)
    {
        $user=User::where('id',$id)->first();
        $user->update(['admin'=>0]);
        return redirect(url()->previous());
    }
}


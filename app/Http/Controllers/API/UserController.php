<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->authorize('isAdmin');
        if(\Gate::allows('isAdmin') || \Gate::allows('isAuthor')){
            return User::latest()->paginate(5);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:190',
            'email' => 'required|string|email|unique:users|max:190',
            'password' => 'required|string|min:6',
        ]);

        // return $request->all();
        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'mobile' => $request['mobile'],
            'address' => $request['address'],
            'type' => $request['type'],
            'photo' => $request['photo'],
            'password' => Hash::make($request['password']),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();
        $validatedData = $request->validate([
            'name' => 'required|string|max:190',
            'email' => 'required|string|email|max:190|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6',
        ]);

        $currentPhoto = $user->photo;
        if($request->photo != $currentPhoto) {
            $name = time().'.'.explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];
            
            \Image::make($request->photo)->save(public_path('img/profile/').$name);
            $request->merge(['photo' => $name]);

            $userPhoto = public_path('img/profile/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }

        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());
        return ['message' => 'Success!!!'];
    }

    public function profile()
    {
        return auth('api')->user();
    }

    public function airTicket()
    {
        return auth('api')->user();
    }

    public function updateticket(Request $request)
    {
        $user = auth('api')->user();
        $validatedData = $request->validate([
            'date' => 'required',
            'refNo' => 'required',
        ]);

        $currentRefNo = $user->refNo;
        if($request->refNo == $currentRefNo) {
            return ['message' => 'Already exist it'];
        }

        $user->update($request->all());
        return ['message' => 'Success!!!'];
    }

    public function visaProcess()
    {
        return auth('api')->user();
    }

    public function updateVisaProcess(Request $request)
    {
        $user = auth('api')->user();
        $validatedData = $request->validate([
            'vdate' => 'required',
            'vrefNo' => 'required',
        ]);

        $currentVRefNo = $user->vrefNo;
        if($request->vrefNo == $currentVRefNo) {
            return ['message' => 'Already exist it'];
        }

        $user->update($request->all());
        return ['message' => 'Success!!!'];
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:190',
            'email' => 'required|string|email|max:190|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:6',
        ]);

        $user->update($request->all());
        return ['message' => 'Updated User'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $user = User::findOrFail($id);

        //delete the user
        $user->delete();

        return ['message' => 'User Deleted Successfully!'];
    }

    public function search(){
        if($search = \Request::get('q')){
            $users = User::where(function($query) use ($search){
                $query->where('name', 'LIKE', "%$search%")
                            ->orWhere('email', 'LIKE', "%$search%")
                            ->orWhere('type', 'LIKE', "%$search%");
            })->paginate(5);
        }else{
            $users = User::latest()->paginate(5);
        }
        return $users;
    }
}

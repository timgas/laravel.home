<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use DB;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use PHPUnit\Framework\Exception;
use function PHPUnit\Framework\throwException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    public function index(Request $request)
    {
        if ($request->query('search')) {
               $querySearchWord_term = User::where('first_name', 'like', '%term%')
                ->orWhere('last_name', 'like', '%term%')
                ->orWhere('city', 'like', '%term%')
                ->orWhere('country', 'like', '%term%')
                ->get();
               return $this->success($querySearchWord_term);
        }
        $user = User::all();
        return $this->success($user);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    //{
    //
    // }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

        return $this->success($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, User $user)
    {
      if ($user->role == 'admin') {
           abort('403', 'You have not right');
      } else {
          $user->update($request->validated());
          return $this->success($user);
      }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->role == 'admin') {
            return abort('403', 'Admin cannot be deleted');
        }
        $user->delete();
        return response()->json('You have successfully deleted the user from the ID ' . $user->id);
    }
}

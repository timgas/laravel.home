<?php

namespace App\Http\Controllers;

use App\Http\Requests\Organization\StoreRequest;
use App\Http\Requests\Organization\UpdateRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{


    public function __construct()
    {
        $this->authorizeResource(Organization::class);
    }

    private function findCountId($obj) {
        $arr = [];
        if (count($obj) > 1) {
            foreach ($obj as $item) {
                $arr[]= $item;
            }
            ($arr);
        } else {
            $arr[] = $obj;
            dd($arr);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|JsonResponse
     */
    public function index()
    {

        $all_organization = Organization::with('user')->get();
        $user_organization = (Auth::user()->organizations()->get());
        if (Auth::user()->role == 'admin') {
            return $this->success($all_organization);
        } else {
            return $this->success($user_organization);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return void
     */
    public function store(StoreRequest $request)
    {
        if (Auth::user()->role != 'admin') {
            $organization = Organization::make($request->validated());
            $organization->user()->associate(auth()->user());
            $organization->save();
            return OrganizationResource::make($organization);
        } else abort('403', 'Can be created only by Employers ');

    }

    /**
     * Display the specified resource.
     *
     * @param Organization $organization
     * @param Request $request
     * @return Organization|JsonResponse
     */
    public function show(Organization $organization, Request $request)
    {
        function relationsWorkers($organization) { // функція, яка повертає зв'язок для Workers
            $vacanciesId = $organization->vacancies()->pluck('id');
            $searchWorkersId = DB::table('user_vacancy')
                ->whereIn('vacancy_id', $vacanciesId)
                ->pluck('user_id');  // Шукаємо Id вакансій до яких підписані робітники.
            $workers = User::whereIn('id', $searchWorkersId)->get();
            //Працівники які підписані на вакансії певної організації.
            return ['workers' => $workers];
        }
            switch ($request->query('vacancies')) {
                case 1 :
                    //status: active
                    $response = $organization->toArray() +
                        ['creator' => $organization->creator()->get()] +
                        ['vacancies' => $organization->vacancies()
                            ->where('status', 'active')
                            ->get()];
                    if ($request->boolean('workers')) {
                        return $response + relationsWorkers($organization);
                    }
                    return $this->success($response);
                case 2 :
                    //status: closed
                    $response = $organization->toArray() +
                        ['creator' => $organization->creator()->get()] +
                        ['vacancies' => $organization->vacancies()
                            ->where('status', 'closed')
                            ->get()];
                    if ($request->boolean('workers')) {
                        return $response + relationsWorkers($organization);
                    }
                    return $this->success($response);
                case 3 :
                    //status: active closed
                    $response = $organization->toArray() +
                        ['creator' => $organization->creator()->get()] +
                        ['vacancies' => $organization->vacancies()
                            ->get()];
                    if ($request->boolean('workers')) {
                        return $response + relationsWorkers($organization);
                    }
                    return $this->success($response);
                default :
                    // all organization vacancies
                    $response = $organization->toArray() +
                        ['creator' => $organization->creator()->get()];
                    if ($request->boolean('workers')) {
                        return $response + relationsWorkers($organization);
                    }
                    return $this->success($response);
            }

        }



    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Organization $organization
     * @return Response
     */
    public function update(UpdateRequest $request, Organization $organization)
    {
        $organization->update($request->validated());
        return $this->success($organization);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Organization $organization
     * @return Response
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();
        return response()->json('You have successfully deleted the organization ' . $organization->title);
    }
}

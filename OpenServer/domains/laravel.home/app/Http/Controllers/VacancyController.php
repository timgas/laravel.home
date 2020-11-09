<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancy\StoreRequest;
use App\Http\Requests\Vacancy\UpdateRequest;
use App\Http\Resources\VacancyResource;
use App\Models\Organization;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VacancyController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Vacancy::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->query('only_active') and auth()->user()->role == 'admin') {  //Якщо статус false можемо продивитись всі вакансії
            $vacancies = VacancyResource::collection(Vacancy::all());
            return $this->success($vacancies);
        } else {
            $statusActive = Vacancy::where('status', 'active')->get();
            return $this->success(VacancyResource::collection($statusActive));
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @param Vacancy $vacancy
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(StoreRequest $request, Vacancy $vacancy)
    {
        if (auth()->user()->role == 'admin') {
            return $this->error('You are not allowed to access this page');
        }
        $createVacancyForOrganization = Vacancy::create($request->validated());
        $createVacancyForOrganization->save();
        return $this->success($createVacancyForOrganization);

    }

    /**
     * Display the specified resource.
     *
     * @param Vacancy $vacancy
     * @return \Illuminate\Http\Response
     */
    public function show(Vacancy $vacancy)
    {
        $vacancyOrganizationId = $vacancy->toArray()['organization_id'];
        $userOrganizationId = auth()->user()->organizations()->pluck('id')->toArray();
        if (auth()->user()->role == 'admin' ||
            in_array($vacancyOrganizationId, $userOrganizationId)) {
            $vacancySubscribe = collect(VacancyResource::make($vacancy))
                    ->toArray() +    // Вакансія з підписаними на неї робітниками.
                ['worker' => $vacancy->users()->get()->makeHidden('pivot')];
            return $this->success($vacancySubscribe);
        } else {
            return $this->success(VacancyResource::make($vacancy));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Vacancy $vacancy
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Vacancy $vacancy)
    {
        if (auth()->user()->role == 'admin') {  //Перевірка для того, щоб адмін не міг заміняти у ваканцій організацію.
            $validator = Validator::make($request->all(), [
                    'vacancy_name' => 'required|string|min:4',
                    'workers_amount' =>'required|numeric|min:1',
                    'salary' => 'required|numeric'
            ]);
            $vacancy->update($validator->validated());
            return $this->success($vacancy);
        }
        $vacancy->update($request->validated());
        return $this->success($vacancy);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Vacancy $vacancy
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();
        $messageDelete = 'You have successfully deleted the organization: ';
        return $this->success($messageDelete);
    }


    /**
     * @param User $user
     * @param Vacancy $vacancy
     * @throws AuthorizationException
     */
    public function book(User $user)
    {
        $this->authorize('book', Vacancy::class);

        $requestUserId = request()->user_id;
        $requestVacancyId = request()->vacancy_id;
        if (User::
        where([
            ['id', '=', $requestUserId],
            ['role', '=', 'worker']
        ])->doesntExist()) {
            return response()->json('Only an worker can apply for a vacancy');
        }

        $vacancy = Vacancy::find($requestVacancyId);

        if (DB::table('user_vacancy')
            ->where([
                ['user_id', '=', $requestUserId],
                ['vacancy_id', '=', $requestVacancyId]
            ])->exists()) {
            return $this->error('This user has already booked.');
        } else if ($vacancy->status == 'closed') {
            return $this->error('The vacancy is already booked');
        }

        $vacancy->users()->attach($requestUserId);
        $vacancy->workers_booked += 1;
        if ($vacancy->workers_amount <= $vacancy->workers_booked) {
            $vacancy->status = 'closed';
        }
        $vacancy->save();
        return response()->json([
            'success' => true
        ]);
    }

    /**
     * @param User $user
     */
    public function un_book(User $user)
    {
        $this->authorize('unBook', Vacancy::class);
        $requestUserId = request()->user_id;
        $requestVacancyId = request()->vacancy_id;
        $vacancyModel = Vacancy::find($requestVacancyId);

        if ($vacancyModel->workers_booked <= 0) { // Якщо ніхто не підписан на ваканцію, відписатись не можна
            return response()
                ->json('You cannot unsubscribe because no one has signed up');
        } else if (DB::table('user_vacancy') // Якщо робітник не підписан до цієї ваканції, відписатись не можна
            ->where([
                ['user_id', '=', $requestUserId],
                ['vacancy_id', '=', $requestVacancyId]
            ])->doesntExist()) {
            return $this->error('You cannot unsubscribe from a vacancy because you are not subscribed');
        }
        $vacancyModel->users()->detach($requestUserId);
        $vacancyModel->workers_booked -= 1;

        if ($vacancyModel->workers_amount > $vacancyModel->workers_booked) {
            $vacancyModel->status = 'active';
        }
        $vacancyModel->save();
        return response()->json([
            'success' => true
        ]);
    }
}

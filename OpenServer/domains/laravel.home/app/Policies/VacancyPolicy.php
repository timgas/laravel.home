<?php

namespace App\Policies;

use App\Http\Requests\Vacancy\StoreRequest;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class VacancyPolicy
{
    use HandlesAuthorization;

    protected function error()
    {
        $data = [
            "success" => false,
            "data" => 'It`s not Autherizad'
        ];
    }

    public function before(User $user)
    {
        if ($user->role == 'admin') {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny()
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Vacancy $vacancy
     * @return mixed
     */
    public function view(User $user, Vacancy $vacancy)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param \Request $request
     * @return mixed
     */
    public function create(User $user)
    {
        $organizationId = $user->organizations()
            ->where('id', '=', request()->organization_id)
            ->pluck('id')->first();
        if ($organizationId == request()->organization_id) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Vacancy $vacancy
     * @return mixed
     */
    public function update(User $user, Vacancy $vacancy)
    {
        $requestOrganizationId = request()->organization_id;
        if ($vacancy->organization_id == request()->organization_id &&
            $user->organizations()->find($requestOrganizationId)) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Vacancy $vacancy
     * @return mixed
     */
    public function delete(User $user, Vacancy $vacancy)
    {
        if ($user->id == $vacancy->organization()->pluck('user_id')->first()) {
            return true;
        }
    }

    /**
     * @param User $user
     */
    public function book(User $user)
    {
        if ($user->role == 'worker' &&
            request()->user_id == $user->id) {
            return true;
        }
    }

    /**
     * @param User $user
     * @return bool
     */

    public function unBook(User $user)
    {
        $organizationId = $user->organizations->pluck('id')
            ->toArray();  //Дізнаємось скільки організацій є у роботодавця
        $vacancyId = Vacancy::whereIn('organization_id', $organizationId)
            ->pluck('id')->toArray(); //Які є ваканції у цих організацій
        $user_vacancy = DB::table('user_vacancy')
            ->whereIn('vacancy_id', $vacancyId)
            ->pluck('user_id')->toArray(); // Які саме юзери підписані на ваканції працедавця.

        if ($user->role == 'worker') {
            return request()->user_id == $user->id;
        } else if ($user->role == 'employer') {
            return in_array(request()->vacancy_id,
                    $vacancyId) && in_array(request()->user_id, $user_vacancy);
            // Перевіряєм, чи цей юзер підписаний на ваканцію працедавця,
            // якщо так даємо доступ до відписки цього юзера.
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Vacancy $vacancy
     * @return mixed
     */


    public function restore(User $user, Vacancy $vacancy)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Vacancy $vacancy
     * @return mixed
     */
    public function forceDelete(User $user, Vacancy $vacancy)
    {
        //
    }
}

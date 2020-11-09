<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Stats;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{

    /**
     * @param Vacancy $vacancy
     * @return JsonResponse
     */
    public function indexStatsVacancies(Vacancy $vacancy)
    {
        $this->authorize('before', Stats::class);
        function countVacanciesStatus($status)
        {
            return Vacancy::all()->where('status', $status)->count();
        }

        return $this->success([
            'active' => countVacanciesStatus('active'),
            'closed' => countVacanciesStatus('closed'),
            'all' => countVacanciesStatus('active') + countVacanciesStatus('closed')
        ]);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function indexStatsUsers(User $user)
    {
        $this->authorize('before', Stats::class);
        function countUsersRoles($role)
        {
            return User::all()->where('role', $role)->count();
        }

        return $this->success([
            'worker' => countUsersRoles('worker'),
            'employer' => countUsersRoles('employer'),
            'admin' => countUsersRoles('admin')
        ]);
    }

    /**
     * @param Organization $organization
     */
    public function indexStatsOrganizations(Organization $organization)
    {
        $this->authorize('before', Stats::class);
        $active = Organization::all()->count();
        $softDeletes = Organization::onlyTrashed()->count();
        $all = Organization::withTrashed()->count();
        return $this->success([
            'Active' => $active,
            'SoftDelete' => $softDeletes,
            'All' => $all
        ]);
    }
}

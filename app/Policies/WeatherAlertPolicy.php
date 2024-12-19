<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WeatherAlert;
use Illuminate\Auth\Access\HandlesAuthorization;

class WeatherAlertPolicy
{
    use HandlesAuthorization;

    public function update(User $user, WeatherAlert $alert)
    {
        return $user->id === $alert->user_id;
    }

    public function delete(User $user, WeatherAlert $alert)
    {
        return $user->id === $alert->user_id;
    }
}

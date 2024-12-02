<?php

namespace App\Repositories;

use App\Models\UserProfile;
use Prettus\Repository\Eloquent\BaseRepository;

class UserProfileRepository extends BaseRepository
{
    public function model()
    {
        return UserProfile::class;
    }
}

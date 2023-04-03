<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Contracts\Auth\UserProvider as AuthUserProvider;

class UserProvider extends EloquentUserProvider
{

    /**
     * Get a new query builder for the model instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function newModelQuery($model = null)
    {
        return is_null($model)
                ? $this->createModel()->newQueryWithoutScopes()
                : $model->newQueryWithoutScopes();
    }

    protected function transformUser($model)
    {
        return optional($model)->transform();
    }

    public function retrieveById($identifier)
    {
        return $this->transformUser(parent::retrieveById($identifier));
    }

    public function retrieveByToken($identifier, $token)
    {
        return $this->transformUser(parent::retrieveByToken($identifier, $token));
    }

    public function retrieveByCredentials(array $credentials)
    {
        return $this->transformUser(parent::retrieveByCredentials($credentials));
    }
}

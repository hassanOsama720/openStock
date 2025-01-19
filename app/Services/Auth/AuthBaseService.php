<?php

namespace App\Services\Auth;

use App\Enums\OTPThrough;
use App\Traits\GeneralTrait;
use App\Traits\UploadTrait;

class AuthBaseService
{
    use GeneralTrait, UploadTrait;

    private $model;
    private $entity;

    public function __construct($model)
    {
        $this->model = $model;
    }


    /**
     * Find the entity based on the provided (phone and country code) or (email address)
     * @param $request
     */
    protected function getEntity($request): mixed
    {
        $entity = $this->model::where(function ($query) use ($request) {
            $query->when($request->phone, function ($q) use ($request) {
                $q->where('phone', $request->phone)->where('country_code', $request->country_code);
            })->when($request->email, function ($q) use ($request) {
                $q->where('email', $request->email);
            })->when($request->sub_domain, function ($q) use ($request) {
                $q->where('sub_domain', $request->sub_domain);
            });
        })->first();

        $this->entity = $entity;

        return $entity;
    }


    protected function getIdentifierType($request, $register = false)
    {
        if ($register) {
            return isset($request['identifier_type']) ? $request['identifier_type'] : OTPThrough::PHONE;
        }
        return $request->email ? OTPThrough::EMAIL : OTPThrough::PHONE;
    }

    /**
     * Save a relation for the authenticated user (one to one).
     * @param $relation
     * @param $request
     */
    public function saveRelation($relation, $request): void
    {
        $this->entity->$relation()->create($request);
    }


    /**
     * Save a relation for the authenticated user (one to many).
     * @param $relation
     * @param $items
     */
    public function saveRelationOneToMany($relation, $items): void
    {
        $this->entity->$relation()->createMany($items);
    }


    /**
     * Save a relation for the authenticated user (many to many).
     * @param $relation
     * @param $items
     */
    public function saveRelationManyToMany($relation, $items): void
    {
        $this->entity->$relation()->attach($items);
    }


    /**
     * Save a polymorphic relation for the authenticated user.
     * @param $relation
     * @param $request
     */
    public function saveRelationPolymorphic($relation, $request): void
    {
        $this->entity->$relation()->create($request);
    }
}

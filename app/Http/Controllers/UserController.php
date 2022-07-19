<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use APP\Models\Validators\UpdateUserValidator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        //
        $users = User::query()
            ->where('approval_status', 1)
            ->when(request('name'), fn ($builder) => $builder->whereName(request('name')))
            ->with(['images'])
            ->orderBy('name', 'DESC')
            ->paginate(20);

        return UserResource::collection(
            $users
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user): JsonResource
    {

        // if (!$user->tokenCan('user.update')) {
        //     Response::HTTP_FORBIDDEN;
        // }

        $attributes = validator(
            request()->all(),
            [
                'name' => [Rule::when($user->exists, 'sometimes'), 'string', 'required'],
                'cpf' => [Rule::when($user->exists, 'sometimes'), 'string', 'required', 'unique:users, id,' . $user->id],
                'cell_phone' => [Rule::when($user->exists, 'sometimes'), 'string'],
                'address1' => [Rule::when($user->exists, 'sometimes'), 'string'],
                'address2' => [Rule::when($user->exists, 'sometimes'), 'string'],
                'postcode' => [Rule::when($user->exists, 'sometimes'), 'string'],
                'neighborhood' => [Rule::when($user->exists, 'sometimes'), 'string'],
                'city' => [Rule::when($user->exists, 'sometimes'), 'string'],
                'state' => [Rule::when($user->exists, 'sometimes'), 'string'],
                'country' => [Rule::when($user->exists, 'sometimes'), 'string']
            ]
        )->validate();

        $user->update($attributes);

        return UserResource::make(
            $user
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

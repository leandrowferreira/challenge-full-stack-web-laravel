<?php

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $roleName = !is_null($this->role_id) ? Role::find($this->role_id)->name : '';

        return [
            'id'       => $this->id,
            'ra'       => $this->ra,
            'cpf'      => $this->cpf,
            'name'     => $this->name,
            'email'    => $this->email,
            'role'     => $this->when(!is_null($this->role_id), $roleName),
        ];
    }
}

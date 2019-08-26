<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * @package App
 * @property string $title
 */
class Role extends Model
{

    protected $fillable = ['title'];


    public static function storeValidation($request)
    {
        return [
            'title' => 'max:191|required',
            'permission' => 'array',
            'permission.*' => 'integer|exists:permissions,id|max:4294967295'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'title' => 'max:191|required',
            'permission' => 'array',
            'permission.*' => 'integer|exists:permissions,id|max:4294967295'
        ];
    }





    public function permission()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }
}
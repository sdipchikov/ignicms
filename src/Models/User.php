<?php

namespace Despark\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Despark\Admin\Traits\AdminConfigTrait;
use Conner\Tagging\Taggable;
use Despark\Admin\Traits\AdminImage;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, EntrustUserTrait;
    use AdminConfigTrait;
    use Taggable;
    use AdminImage;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|max:20',
    ];

    protected $rulesUpdate = [
        'name' => 'required',
        'email' => 'required|email|unique:users,email,{id},id',
        'password' => 'min:6|max:20',
    ];

    public static $rulesProfileEdit = [
        'name' => 'required',
        'password' => 'min:6|max:20|confirmed',
        'password_confirmation' => 'min:6|max:20',
    ];

    /**
     * User constructor.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->adminColumns = [
            ['name' => 'Name', 'db_field' => 'name'],
            ['name' => 'Email', 'db_field' => 'email'],
        ];

        $this->adminFilters = [
            'text_search' => [
                'db_fields' => [
                    'name',
                    'email',
                ],
            ],
        ];

        if ($this->hasFilters()) {
            return $this->filtering();
        }
    }

    public function adminSetFormFields()
    {
        $this->adminFormFields = [
            'name' => [
                'type' => 'text',
                'label' => 'Name',
            ],
            'email' => [
                'type' => 'text',
                'label' => 'Email',
            ],
            'password' => [
                'type' => 'password',
                'label' => 'Password',
            ],
        ];

        return $this;
    }
}
<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    public $incrementing = false;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', "role", "is_expired", "manager_id"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function ideas()
    {
        return $this->hasMany(Idea::class, "submitter_id")->orderBy("created_at", "desc");
    }

    public function manager()
    {
        return $this->belongsTo(User::class, "manager_id");
    }

    public function isIam()
    {
        return $this->hasRole(Role::$IAM);
    }

    public function isRpa()
    {
        return $this->hasRole(Role::$RPA);
    }

    public function isSuperadmin()
    {
        return false;
    }

    public function isPrivilegedUser()
    {
        return $this->hasRole(Role::$RPA) || $this->hasRole(Role::$IAM);
    }

    public function isExpired()
    {
        return $this->is_expired;
    }

    public function isUser()
    {
        return $this->hasRole(Role::$USER);
    }

    public function isAdvancedUser()
    {
        return $this->hasRole(Role::$ADVANCED_USER);
    }

    public function isMt()
    {
        return $this->hasRole(Role::$MT);
    }

    public function isIt()
    {
        return $this->hasRole(Role::$IT);
    }

    public function isLss()
    {
        return $this->hasRole(Role::$LSS);
    }

    public function isCosmos()
    {
        return $this->hasRole(Role::$COSMOS);
    }

    public function isCentralResources()
    {
        if($this->isRpa() || $this->isIt() || $this->isLss() || $this->isCosmos()) {
            return true;
        }
        else {
            return false;
        }
    }

    public function isChangeBoard()
    {
        return $this->hasRole(Role::$CHG_BOARD);
    }

    public function isSubmitter()
    {
        return $this->isUser() || $this->isAdvancedUser();
    }

    public function canSeeAllIdeas()
    {
        return $this->isMt() || $this->isCentralResources() || $this->isChangeBoard();
    }

    public function canFullyEditIdea()
    {
        return $this->isRpa() || $this->isMt();
    }

    public function isIdeaProcessor()
    {
        return $this->isCentralResources() || 
            $this->isMt() ||  
            $this->isUser() || 
            $this->isAdvancedUser() ||
            $this->isChangeBoard();
    }

    public function hasRole($roleToCheck)
    {
        foreach($this->roles as $userRole) {
            if($userRole->name == $roleToCheck) {
                return true;
            }
        }
        
        return false;
    }

    public function getAuthorizationIds()
    {
        $authorizedArr = [$this->id];

        $users = User::all();

        //Getting manager's team members
        foreach ($users as $user) {
            if($user->manager_id == $this->id) {
                array_push($authorizedArr, $user->id);
            } 
        }

        //Getting all user roles
        foreach ($this->roles as $role) {
            array_push($authorizedArr, $role->id);
        }

        return $authorizedArr;
    } 

    public function scopeMtUsers($query)
    {
        return $query->whereHas("roles", function($q) {
            $q->where("name", Role::$MT);
        })->get();
    }

    public function scopeCentralResourcesUsers($query)
    {
        return $query->whereHas("roles", function($q) {
            $q->whereIn("name", [Role::$RPA, Role::$LSS, Role::$COSMOS, Role::$IT]);
        })->get();
    }

    public function scopeChangeBoardUsers($query)
    {
        return $query->whereHas("roles", function($q) {
            $q->where("name", Role::$CHG_BOARD);
        })->get();
    }

    public function scopeRpaUsers($query)
    {
        return $query->whereHas("roles", function($q) {
            $q->where("name", Role::$RPA);
        })->get();
    }

    public function scopeLssUsers($query)
    {
        return $query->whereHas("roles", function($q) {
            $q->where("name", Role::$LSS);
        })->get();
    }

    public function scopeCosmosUsers($query)
    {
        return $query->whereHas("roles", function($q) {
            $q->where("name", Role::$COSMOS);
        })->get();
    }

    public function scopeItUsers($query)
    {
        return $query->whereHas("roles", function($q) {
            $q->where("name", Role::$IT);
        })->get();
    }
}

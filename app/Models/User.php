<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Actions\ProductionLine\RecoverUserRestaurant;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'invitation', 'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'restaurant_member',
        'invitation'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function restaurants()
    {
        return $this->belongsToMany('App\Models\Restaurant', 'restaurant_has_users');
    }

    public function adminRestaurants()
    {
        return $this->hasMany('App\Models\Restaurant');
    }    

    public function menagesRestaurants(){
        return $this->adminRestaurants()->count() > 0;
    }

    public function stepRoles(){
        return Role::join("model_has_roles", "roles.id", "=", "model_has_roles.role_id")
        ->join("users", "users.id", "=", "model_has_roles.model_id")
        ->where("users.id", $this->id)
        ->where("roles.guard_name", "production-line")
        ->select("roles.id")
        ->selectRaw("(SELECT pl.name FROM production_lines pl WHERE pl.user_id = 54 AND pl.is_active = 1 AND pl.role_id = roles.id) AS description")
        ->get();
    }    

    public function recoverUserRestaurant(){
        try{
            $recoverUserRestaurant = new RecoverUserRestaurant();
            return $recoverUserRestaurant->recoverAll($this->id);
        }catch(\Exception $e){
            return null;
        }
    }
}

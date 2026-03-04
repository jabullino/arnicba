<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Intervention\Image\Format;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;




class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable,SoftDeletes,TwoFactorAuthenticatable;
     

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'ci',
        'extension_id',
        'fecnac',
        'ciudad_id',
        'provincia_id',
        'cargo_id',
        'email',
        'password',
        'two_factor_secret',
        'direccion',
        'referencias',
        'telefono',
        'fec_ingreso',
        'fec_egreso',
        'foto',
        'latitud',
        'longitud',

       
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
    
        ];
    }

    public function cargos(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function extensiones(): BelongsTo
    {
        return $this->belongsTo(Extension::class);
    }

    public function ciudades(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function provincias(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }

    public function tipopersonal(): BelongsTo
    {
        return $this->belongsTo(TipoPersonal::class);
    }

    public function file(): HasOne
    {

        return $this->hasOne(File::class);

    }

   public function vacaciones(): HasMany
    {
        return $this->hasMany(Vacacion::class);
    }


    protected function nombre():Attribute
    {
           return Attribute::make(
              
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtoupper($value),

           );

    }

    protected function apellido():Attribute
    {
           return Attribute::make(    
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtoupper($value),
           );
    }

    protected function direccion():Attribute
    {
           return Attribute::make(       
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtoupper($value),

           );

    }

    protected function referencias():Attribute
    {
           return Attribute::make(
              
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtoupper($value),

           );

    }

   public function sueldos()
{
    return $this->belongsToMany(Sueldo::class, 'bono_sueldo')
                ->withPivot('bono_id')
                ->withTimestamps();
}

public function bonos()
{
    return $this->belongsToMany(Bono::class, 'bono_sueldo')
                ->withPivot('sueldo_id')
                ->withTimestamps();
}

    public function personal(): HasOne
    {
        return $this->hasOne(Personal::class);
    }

    public function diastomadosvaciones(): HasMany
    {
        return $this->hasMany(Diastomadosvacacion::class);
    }
    
    public function devuelveNombreAdministrador(){

         return DB::table('users')
        ->where('cargo_id', 4)
        ->select('nombre', 'apellido')
        ->first();
    }

    public function devuelveNombreDirector(){

         return DB::table('users')
        ->where('cargo_id', 4)
        ->select('nombre', 'apellido')
        ->first();
    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }

}

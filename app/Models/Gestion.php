<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Gestion extends Model
{
    use SoftDeletes;

    protected $fillable = ['nombre', 'estado_id'];
    protected $table = 'gestiones';

    public function vacaciones(): HasMany
    {
        return $this->hasMany(Vacacion::class);
    }

    public function haberbasicos(): HasMany
    {
        return $this->hasMany(Haberbasico::class);
    }

    public function incrementos(): HasMany
    {
        return $this->hasMany(Incremento::class);
    }

    function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }

    public function sueldos(): HasMany
    {
        return $this->hasMany(Sueldo::class);
    }

    public function salariominimo(): HasMany
    {
        return $this->hasMany(SalarioMinimo::class);
    }

    public function haberesBasicos()
    {
        return $this->hasMany(HaberBasico::class);
    }
    public function diastomadosvaciones(): HasMany
    {
        return $this->hasMany(Diastomadosvacacion::class);
    }

    public function cajachica(): HasMany
    {
        return $this->hasMany(CajaChica::class);
    }

    protected static function boot()
    {
        
        
        parent::boot();

        static::creating(function ($gestion) {
            $ultimaGestion = self::withTrashed()->orderBy('id', 'desc')->first();

            if (!$ultimaGestion) {
                $gestion->nombre = 2024;
            } else {
                $gestion->nombre = $ultimaGestion->nombre + 1;
            }

            $gestion->estado_id = 1; // estado inicial
        });
        

        // Eliminamos eventos created que insertaban haberes automáticamente
    }

    public static function calculaDias($fec_ingreso)
    {
        $fechaFormulario = Carbon::now()->endOfYear();
        $fecha_ingreso = Carbon::parse($fec_ingreso);

        $diff = $fechaFormulario->diff($fecha_ingreso);
        $anios = $diff->y;
        $meses = $diff->m;

        if ($anios >= 1 && $anios < 5) return 15;
        if ($anios >= 5 && $anios < 10) return 20;
        if ($anios >= 10) return 30;
        if ($anios < 1 && $meses > 0) return ($meses / 12) * 15;

        return 0;
    }

    // CREAR PRIMER SEMESTRE
    public static function creaPrimerSemestre()
    {  
         $gestion=self::latest('id')->first();
        if ($gestion===null) {

        $gestion = self::create([]); // dispara creating pero no created
        
            $cargoIds = Cargo::where('id', '!=', 1)->pluck('id')->toArray();
            $haberes = [0.00, 1780.25, 3597.42,0.00,4133.88, 3933.84, 3133.75, 3042.48, 2362.00, 2362.00, 0.00];
            $smn = 2362.00;

            SalarioMinimo::create([
                'gestion_id' => $gestion->id,
                'mes_inicio' => '01',
                'mes_fin'   => '05',
                'monto'     => $smn,
            ]);

            foreach ($cargoIds as $index => $cargoId) {
                HaberBasico::create([
                    'gestion_id' => $gestion->id,
                    'cargo_id'   => $cargoId,
                    'mes_inicio' => '01',
                    'mes_fin'   => '05',
                    'monto'      => $haberes[$index] ?? 0,
                ]);
            }

            $files = File::whereNull('deleted_at')->pluck('id');
            $users = User::whereNull('deleted_at')->where('id', '!=', 1)->get();

            foreach ($users as $usr) {
                $diasVacacion = self::calculaDias($usr->fec_ingreso);

                Vacacion::create([
                    'user_id' => $usr->id,
                    'gestion_id' => $gestion->id,
                    'file_id' => $files->first() ?? null,
                    'cant_dias' => $diasVacacion,
                    'saldo_dias_gestion' => $diasVacacion,
                    'estado_id' => 1,
                ]);
            }
        } else {
            
           $gestion = self::create([]);
           $gestion_id=$gestion->id-1;

            
        $cargoIds = Cargo::where('id', '!=', 1)->pluck('id')->toArray();
        $haberes = DB::table('haber_basicos')
        ->where('gestion_id', $gestion_id)
        ->whereIn('cargo_id', $cargoIds)
        ->where('mes_inicio', '06')
        ->where('mes_fin', '12')
        ->pluck('monto') // clave => valor
        ->toArray();
            

                foreach ($cargoIds as $index => $cargoId) {
                 HaberBasico::create([
                'gestion_id' => $gestion->id,
                'cargo_id'   => $cargoId,
                'mes_inicio' => '01',
                'mes_fin'   => '05',
                'monto'      => $haberes[$index],
            ]);
                
            }
            
              $users = User::whereNull('deleted_at')->where('id', '!=', 1)->get();
              $files = File::whereNull('deleted_at')->pluck('id');
            foreach ($users as $usr) {
                $diasVacacion = self::calculaDias($usr->fec_ingreso);

                Vacacion::create([
                    'user_id' => $usr->id,
                    'gestion_id' => $gestion->id,
                    'file_id' => $files->first() ?? null,
                    'cant_dias' => $diasVacacion,
                    'saldo_dias_gestion' => $diasVacacion,
                    'estado_id' => 1,
                ]);
            }
        }

        return $gestion;
    }

    // CREAR SEGUNDO SEMESTRE
    public static function creaSegundoSemestre($salarioMinimo, $haberBasico)
    {
        $ultimaGestion = self::latest('id')->first();

        if (!$ultimaGestion) {
            throw new \Exception("No existe ninguna gestión creada para aplicar el segundo semestre.");
        }

        $cargoIds = Cargo::where('id', '!=', 1)->pluck('id');
        $haberes = HaberBasico::where('gestion_id', $ultimaGestion->id)
            ->whereRaw("CAST(mes_inicio AS UNSIGNED) = 1")
            ->whereRaw("CAST(mes_fin AS UNSIGNED) = 5")
            ->pluck('monto', 'cargo_id')
            ->map(fn($item) => (float) $item);

        SalarioMinimo::create([
            'gestion_id' => $ultimaGestion->id,
            'mes_inicio' => '06',
            'mes_fin'   => '12',
            'monto'     => $salarioMinimo,
        ]);

        foreach ($cargoIds as $cargoId) {
            $haberAnterior = $haberes->get($cargoId, 0);

            if ($cargoId == 10 || $cargoId == 11) {
                $incremento = $salarioMinimo;
            } elseif ($cargoId == 12) {
                $incremento = $haberAnterior;
            } else {
                $incremento = $haberAnterior + ($haberAnterior * $haberBasico);
            }

            HaberBasico::create([
                'gestion_id' => $ultimaGestion->id,
                'cargo_id'   => $cargoId,
                'mes_inicio' => '06',
                'mes_fin'   => '12',
                'monto'      => $incremento,
            ]);
        }
    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

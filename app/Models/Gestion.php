<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitudCajaChica;

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

    public function egresoresidentes(): HasMany
    {
        return $this->hasMany(EgresoResidente::class);
    }

    public function derivaciones(): HasMany
    {
        return $this->hasMany(Derivacion::class);
    }

     public function permisosalidas(): HasMany
    {
        return $this->hasMany(PermisoSalida::class);
    }
    public function solicitudes(): HasMany
    {
        return $this->hasMany(SolicitudCajaChica::class);
    }

    protected static function boot()
    {


        parent::boot();

        static::creating(function ($gestion) {
            $ultimaGestion = self::withTrashed()->orderBy('id', 'desc')->first();

            if (!$ultimaGestion) {
                $gestion->nombre = 2025;
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
        $gestion = self::latest('id')->first();
        if ($gestion === null) {

            $gestion = self::create([]); // dispara creating pero no created

            $cargoIds = Cargo::where('id', '!=', 1)->pluck('id')->toArray();
            $haberes = [0.00, 2219.74, 3705.34, 0.00, 4257.90, 4051.86, 3227.76, 3133.75, 2500.00, 2500.00, 0.00];
            $smn = 2500.00;

            SalarioMinimo::create([
                'gestion_id' => $gestion->id,
                'monto'     => $smn,
            ]);

            foreach ($cargoIds as $index => $cargoId) {
                HaberBasico::create([
                    'gestion_id' => $gestion->id,
                    'cargo_id'   => $cargoId,
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
            $gestion_id = $gestion->id - 1;


             $cargoIds = Cargo::where('id', '!=', 1)->pluck('id')->toArray();
             $haberes = DB::table('haber_basicos')
                      ->where('gestion_id', $gestion_id)
                      ->orderBy('id', 'desc')
                      ->get()
                      ->unique('cargo_id')
                      ->keyBy('cargo_id');



            foreach ($cargoIds as $index => $cargoId) {
                HaberBasico::create([
                    'gestion_id' => $gestion->id,
                    'cargo_id'   => $cargoId,
                    'monto' => $haberes[$cargoId]->monto ?? 0,
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
        if($haberBasico){
            
            $haberBasico=(int)$haberBasico/100;
        }

        $mes = Carbon::now()->format('m');

        if ($mes === '01') {
            $gestion = self::orderBy('id', 'desc')
                ->value('nombre');
            $nuevaGestion = (string) ((int) $gestion + 1);
            Self::create([
                'nombre' => $nuevaGestion,
                'estado_id' => '1',
            ]);

            $ultimaGestion = self::latest('id')->first();

            $cargoIds = Cargo::where('id', '!=', 1)->pluck('id');
            $haberes = HaberBasico::where('gestion_id', $ultimaGestion->id - 1)
                ->pluck('monto', 'cargo_id')
                ->map(fn($item) => (float) $item);
            if ($salarioMinimo != null) {
                SalarioMinimo::create([
                    'gestion_id' => $ultimaGestion->id,
                    'monto'     => $salarioMinimo,
                ]);
            }

            foreach ($cargoIds as $cargoId) {
                $haberAnterior = $haberes->get($cargoId, 0);

                if ($cargoId == 10 || $cargoId == 11) {
                    if ($salarioMinimo != null) {
                        $incremento = $salarioMinimo;
                    } else {
                        $ultimoSalario = SalarioMinimo::latest('created_at')->first();
                        $monto = $ultimoSalario?->monto;
                        $incremento = $monto;
                    }
                } elseif ($cargoId == 12) {
                    $incremento = $haberAnterior;
                } else {
                    if ($haberBasico != null) {
                        $incremento = $haberAnterior + ($haberAnterior * $haberBasico);
                    } else {
                        $incremento = $haberAnterior;
                    }
                }
                if ($haberBasico != null) {
                    HaberBasico::create([
                        'gestion_id' => $ultimaGestion->id,
                        'cargo_id'   => $cargoId,
                        'monto'      => $incremento,
                    ]);
                }
            }
        } else {
            $ultimaGestion = self::latest('id')->first();
            if (!$ultimaGestion) {
                throw new \Exception("No existe ninguna gestión creada para aplicar el segundo semestre.");
            }
            $cargoIds = Cargo::where('id', '!=', 1)->pluck('id');
            $haberes = HaberBasico::where('gestion_id', $ultimaGestion->id)
                ->pluck('monto', 'cargo_id')
                ->map(fn($item) => (float) $item);

            if ($salarioMinimo != null) {
                SalarioMinimo::create([
                    'gestion_id' => $ultimaGestion->id,
                    'monto'     => $salarioMinimo,
                ]);
            }


            foreach ($cargoIds as $cargoId) {
                $haberAnterior = $haberes->get($cargoId, 0);

                if ($cargoId == 10 || $cargoId == 11) {
                    if ($salarioMinimo != null) {
                        $incremento = $salarioMinimo;
                    } else {
                        $ultimoSalario = SalarioMinimo::latest('created_at')->first();
                        $monto = $ultimoSalario?->monto;
                        $incremento = $monto;
                    }
                } elseif ($cargoId == 12) {
                    $incremento = $haberAnterior;
                } else {
                    if ($haberBasico != null) {
                        $incremento = $haberAnterior + ($haberAnterior * $haberBasico);
                    } else {
                        $incremento = $haberAnterior;
                    }
                }

                if ($haberBasico != null) {

                    HaberBasico::create([
                        'gestion_id' => $ultimaGestion->id,
                        'cargo_id'   => $cargoId,
                        'monto'      => $incremento,
                    ]);
                }
            }
        }
    }

    public function devuelveNombre($id){
        $nombre=Gestion::where('id',$id)->value('nombre');
        return $nombre;
    }

    protected static function booted()
    {
        // Siempre excluir registros eliminados (deleted_at no nulo)
        static::addGlobalScope('not_deleted', function ($query) {
            $query->whereNull('deleted_at');
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\BonoAntiguedad;
use App\Models\Haberbasico;
use App\Models\OrigenFondos;
use App\Models\TipoCuenta;
use App\Models\TipoMovimiento;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\CargoSeeder;
use Database\Seeders\ExtensionSeeder;
use Database\Seeders\CiudadSeeder;
use Database\Seeders\ProvinciasSeeder;
use Database\Seeders\DocumentoSeeder;
use Database\Seeders\OrigenFondosSeeder;
use Database\Seeders\TipoMovimientoSeederSeeder;
use Database\Seeders\TipoCambioCompraSeeder;
use Database\Seeders\TipoCambioVentaSeeder;
use Database\Seeders\EstadoSeeder;
use Database\Seeders\BancosSeeder;
use Database\Seeders\DescuentoSeeder;
use Database\Seeders\GestionSeeder;
use Database\Seeders\BonoSeeder;
use Database\Seeders\BonoAntiguedadSeeder;
use Database\Seeders\SalarioMinimoSeeder;
use Database\Seeders\TipoCuentaSeeder;
use Database\Seeders\TipoMonedaSeeder;
use Database\Seeders\UsuariosSeeder;
use Database\Seeders\HaberBasicoSeeder;
use Database\Seeders\GeneroSeeder;
use Database\Seeders\TipologiaSeeder;
use Database\Seeders\MunicipioSeeder;
use Database\Seeders\UnidadEducativaSeeder;
use Database\Seeders\GradoSeeder;
use Database\Seeders\CursoSeeder;




class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(CargoSeeder::class);
        $this->call(CiudadSeeder::class);
        $this->call(ProvinciaSeeder::class);
        $this->call(ExtensionSeeder::class);
        $this->call(SubCuentaSeeder::class);
        $this->call(DocumentoSeeder::class);
        $this->call(OrigenFondosSeeder::class);
        $this->call(TipoMovimientoSeeder::class);
        $this->call(TipoCambioCompraSeeder::class);
        $this->call(TipoCambioVentaSeeder::class);
        $this->call(EstadoSeeder::class);
        $this->call(BancosSeeder::class);
        //$this->call(GestionSeeder::class);
        $this->call(DescuentoSeeder::class);
        $this->call(BonoSeeder::class);
        $this->call(BonoAntiguedadSeeder::class);
        //$this->call(SalarioMinimoSeeder::class);
        $this->call(TipoCuentaSeeder::class);
        $this->call(TipoMonedaSeeder::class);
        //$this->call(UsuariosSeeder::class);
        // $this->call(HaberBasicoSeeder::class);
        $this->call(GeneroSeeder::class);
        $this->call(TipologiaSeeder::class);
        $this->call(MunicipioSeeder::class);
        $this->call(UnidadEducativaSeeder::class);
        $this->call(GradoSeeder::class);
        $this->call(CursoSeeder::class);
        $this->call(CategoriaSeeder::class);
        $this->call(ColorSeeder::class);
        $this->call(TallaSeeder::class);
        $this->call(TallazapatoSeeder::class);
        $this->call(PresentacionSeeder::class);
        $this->call(UnidadSeeder::class);
        $this->call(CapacidadSeeder::class);
        $this->call(DestinatarioSeeder::class);

        

        // User::factory(10)->create();

        /* User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
    }
}

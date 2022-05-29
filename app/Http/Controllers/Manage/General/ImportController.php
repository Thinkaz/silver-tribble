<?php

namespace App\Http\Controllers\Manage\General;

use App\Contracts\Importer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\ImportForm;
use App\Importers\BablSuiteImporter;
use App\Importers\MyBBImporter;
use App\Importers\PrometheusImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    /**
     * A map of available importers
     *
     * @var array<string, class-string>
     */
    public static array $importers = [
        'prometheus' => PrometheusImporter::class,
        'babl' => BablSuiteImporter::class,
        'mybb' => MyBBImporter::class
    ];

    public function index()
    {
        return view('manage.general.import', [
            'importers' => array_keys(static::$importers)
        ]);
    }

    protected function testConnection(): bool
    {
        try {
            DB::connection('import')->getPdo();
        } catch (\PDOException $e) {
            return false;
        }

         return true;
    }

    public function import(ImportForm $request): RedirectResponse
    {
        config()->set('database.connections.import', [
            'driver' => 'mysql',
            'host' => $request->input('host'),
            'port' => $request->input('port'),
            'database' => $request->input('database'),
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ]);

        if (!$this->testConnection()) {
            toastr()->error('Database connection failed, please check your credentials.');
        } else {
            /** @var Importer $importer */
            $importer = app()->make(
                static::$importers[$request->input('importer')],
                [DB::connection('import')],
            );

            $importer->handle();
        }

        return redirect()->route('manage.general.import');
    }
}
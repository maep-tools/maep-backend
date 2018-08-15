<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrepareCalculator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $Filesystem;

    public $timeout = 120;
      public $tries = 1;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->Filesystem = new Filesystem();     
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $id = (int) $this->data->id;
        DB::table('processes')
            ->where('id', $id)
            ->update(['statusId' => 1]);


        // verifica que el usuario tenga una carpeta en m odelos
        $existUserFolder = Storage::disk('local')->exists('/models/' . $this->data->userId);

        // la carpeta se debe llamar con el id del usuario si esta no existe y se
        // debe guardar en la varpeta storage/app

        if (!$existUserFolder) {
            Storage::disk('local')->makeDirectory('/models/' . $this->data->userId);
        }

        // verificamos si existe la carpeta de modelos para el identificador
        $existModelFolder = Storage::disk('local')->exists('/models/' . $this->data->userId . '/' . $this->data->id);

        // para el proceso en R se debe crear una carpeta con el identificador del proceso, para ello ademas vamos a copiar el template correspondiente
        if (!$existModelFolder) {
            Storage::disk('local')->makeDirectory('/models/' . $this->data->userId . '/' . $this->data->id);   
        }

        // crea una copia de los archivos con los modelos en la carpeta
        // siempre ejecuta la cola ya que los archivos pueden haber cambiado
        $this->Filesystem->copyDirectory(storage_path() . '/app/templates/' . '1' . '/', storage_path() . '/app/models/' . $this->data->userId . '/' . $this->data->id);
        

      // en el caso de que sea de tipo excel
      if ($this->data->type === "Excel" && \File::exists(storage_path("app/temporal/".$this->data->id. ".xlsx"))) {
          \File::copy(storage_path("app/temporal/".$this->data->id. ".xlsx"), storage_path() .'/app/models/' . $this->data->userId . '/' . $this->data->id . '/python/datasystem/' . $this->data->id .'.xlsx');
      }


    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Notifications\ProcessStatus;
use Illuminate\Support\Facades\Redis;


class ProcessPythonScript implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public $timeout = 120;
      public $tries = 1;


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

           DB::table('processes')
            ->where('id', '=', $this->data->id)
            ->update(['phase' => 3]);

            $params = json_encode(json_encode($this->data));;

            chdir(storage_path() .'/app/models/' . $this->data->userId . '/' . $this->data->id . '/python/');

            $command = 'python3  -u ' . storage_path() . '/app/models/' . $this->data->userId . '/' . $this->data->id . '/python/ConsolaSDDP.py ' . $params;

            $last_line = exec($command, $output);
            $lines = implode("\n", $output);

            // Guardamos la salida de Jose
            Storage::disk('local')->put('/models/' . $this->data->userId . '/' . $this->data->id . '/python/process.txt', $lines);


            $error = null;

            if (\File::exists(storage_path() . '/app/models/'.$this->data->userId.'/'.$this->data->id.'/python/results/error.txt')) {
              $error = \File::get(storage_path() . '/app/models/'.$this->data->userId.'/'.$this->data->id.'/python/results/error.txt');
            }

            if ($error) {
                // debemos de verificar si hay error. En el caso de que exista 
                // error debemos guardar que hubo error de ejecución

                DB::table('processes')
                    ->where('id', '=', (int) $this->data->id)
                    ->update(['statusId' => 1, 'phase' => 5]);

               $user = \App\Models\User::find($this->data->userId);

               \Notification::send($user, new ProcessStatus([
                  'message' => 'Le informamos que ' .$this->data->name . ' no se ha podido procesar.',
                  'url' => env('FRONTEND_URL') . '/process/' . $this->data->id . '?step=1',                  
                  'id' => $this->data->id
                ]));


            } else {
                // debemos de verificar si hay error. En el caso de que exista 
                // error debemos guardar que hubo error de ejecución

                DB::table('processes')
                    ->where('id', '=', (int) $this->data->id)
                    ->update(['statusId' => 5, 'phase' => 4]);

                $user = \App\Models\User::find($this->data->userId);

               \Notification::send($user, new ProcessStatus([
                  'message' => $this->data->name .' ha sido procesado correctamente, puedes ir a visualizar los resultados',
                  'url' => env('FRONTEND_URL') . '/process/' . $this->data->id . '?step=3',
                  'id' => $this->data->id
                ]));

            }

        } catch (Exception $e) {
            var_dump($e); 
        }


    }
}

<?php



use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {
    
	Route::get('generateExcel/{id}', function($id) {
		\App\Jobs\GenerateExcel::dispatch($id);
	});


	Route::get('getTemplates', '\App\Http\Controllers\Api\processAPIController@getTemplates');

	// RUTAS ASOCIADAS A RESULTADOS

	// retorna la grafica principal
	Route::get('results/getMainChart/{id}', '\App\Http\Controllers\Api\ResultsController@getMainChart');
	// retorna las areas con su respetiva grafica
	Route::get('results/getAreasChart/{id}', '\App\Http\Controllers\Api\ResultsController@getAreasChart');
	// retorna los resultados en excel de inflowWind
	Route::get('results/getWindDataInflowWind/{id}', '\App\Http\Controllers\Api\ResultsController@getWindDataInflowWind');
	// retorna los resultados en excel de inflow
	Route::get('results/getWindDataInflow/{id}', '\App\Http\Controllers\Api\ResultsController@getWindDataInflow');


	// retorna los logs del modelo
	Route::get('results/getLogs/{id}', '\App\Http\Controllers\Api\ResultsController@getLogs');

	// retorna los errores del modelo
	Route::get('results/getErrors/{id}', '\App\Http\Controllers\Api\ResultsController@getErrors');

	// retorna los resultados en excel el general 
	Route::get('results/getGeneralData/{id}', '\App\Http\Controllers\Api\ResultsController@getGeneralData');

	Route::get('validateProcess/{id}', '\App\Http\Controllers\Api\processAPIController@validateProcess');

	// permite seleccionar el segmento por defecto
	Route::put('select-segment', '\App\Http\Controllers\Api\processAPIController@changeSegment');
    // Permite hacer login
    Route::post('/auth/login', '\App\Http\Controllers\Api\AuthController@login');
    // Permite registrar un usuario
    Route::post('/auth/register', '\App\Http\Controllers\Api\AuthController@register');
    // permite enviar un link de reseteo
	Route::post('/auth/password/email', '\App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
	// permite resetear el password
	Route::post('/auth/password/reset', '\App\Http\Controllers\Auth\ResetPasswordController@reset');
	// permite activar la cuenta
	Route::get('/auth/activate/token/{token}', '\App\Http\Controllers\Auth\ActivateController@activate');
    // Permite validar si un email existe en algun servidor de correo
	Route::get('/auth/validate/{email}', '\App\Http\Controllers\Api\AuthController@ValidateEmail');

	Route::get('clear-logs/{name}', function($name) {
		$rootPath = base_path();
		$client = Storage::createLocalDriver(['root' => $rootPath]);

		if ($name === 'processes') {
			$client->put('supervisord.log', '');
		}

		if ($name === 'queues') {
			$client->put('laraqueue.supervisord_out.log', '');
		}
	});


	// permite ver las areas
	Route::resource('areas', '\App\Http\Controllers\Api\AreasAPIController');
	Route::post('areas/importExcel/{id}', '\App\Http\Controllers\Api\AreasAPIController@importExcel');
	Route::post('areas/generateExcel/{id}', '\App\Http\Controllers\Api\AreasAPIController@generateExcel');

	// control de thermalConfig
	Route::resource('thermalConfig', '\App\Http\Controllers\Api\ThermalConfigAPIController');
	Route::post('thermalConfig/importExcel/{id}', '\App\Http\Controllers\Api\ThermalConfigAPIController@importExcel');
	Route::post('thermalConfig/generateExcel/{id}', '\App\Http\Controllers\Api\ThermalConfigAPIController@generateExcel');

	// control de horizonte
	Route::resource('horizonts', '\App\Http\Controllers\Api\horizontAPIController');
	Route::delete('delete-horizont-by-processId/{id}', '\App\Http\Controllers\Api\horizontAPIController@deleteByProcessId');


	// control de demanda
	Route::resource('demands', '\App\Http\Controllers\Api\DemandAPIController');
	Route::get('get-by-area/{id}', '\App\Http\Controllers\Api\DemandAPIController@getDemandByAreaId');


	Route::resource('lines', '\App\Http\Controllers\Api\linesAPIController');

	Route::resource('lines-expansion', '\App\Http\Controllers\Api\linesExpansionAPIController');


	// control de windm2 config
	Route::resource('wind-m2-config', '\App\Http\Controllers\Api\WindM2ConfigAPIController');

	// control de storage config
	Route::resource('storage-config', '\App\Http\Controllers\Api\storage_configAPIController');
	// control de storage expansion
	Route::resource('storage-expansion', '\App\Http\Controllers\Api\storageExpansionAPIController');
	// control de escenarios
	Route::resource('scenario', '\App\Http\Controllers\Api\scenarioAPIController');

	// control de inflow-wind
	Route::resource('inflow-wind', '\App\Http\Controllers\Api\inflowWindAPIController');
	Route::get('getInflowWindByWindConfigId/{id}', '\App\Http\Controllers\Api\inflowWindAPIController@getInflowWindByWindConfigId');



	//control de configuración de wind m2
	Route::resource('windM2Config', '\App\Http\Controllers\Api\WindM2ConfigAPIController');

	// control de inflow_wind_m2s
	Route::resource('inflow-wind-m2', '\App\Http\Controllers\Api\inflowWindM2APIController');
	Route::get('getInflowWindByWindConfigIdM2/{id}', '\App\Http\Controllers\Api\inflowWindAPIController@getInflowWindByWindConfigIdM2');








	// control de inflow_wind_m2s
	Route::resource('w-pow-curve-m2', '\App\Http\Controllers\Api\WPowCurveM2APIController');
	Route::get('getWPowCurveByWindConfigM2Id/{id}', '\App\Http\Controllers\Api\WPowCurveM2APIController@getWPowCurveByWindConfigM2Id');



	// control de speedIndicesM2
	Route::resource('speed-indices-m2', '\App\Http\Controllers\Api\SpeedIndicesM2APIController');
	Route::get('getSpeedIndicesByWindConfigId-m2/{id}', '\App\Http\Controllers\Api\SpeedIndicesM2APIController@getSpeedIndicesByWindConfigM2Id');


	// control de fuel
	Route::resource('/fuel', '\App\Http\Controllers\Api\FuelCostPlantAPIController');

	// control de segmento
	Route::resource('/segments', '\App\Http\Controllers\Api\SegmentAPIController');

	// control de bloques
	Route::resource('blocks', '\App\Http\Controllers\Api\BlocksAPIController');

	// control de horizonte de fuel cost
	Route::resource('fuel-cost-horizonts', '\App\Http\Controllers\Api\FuelCostHorizontAPIController');
	Route::get('getFuelCostHorizontsByFuelId/{id}', '\App\Http\Controllers\Api\FuelCostHorizontAPIController@getFuelCostHorizontsByFuelId');


	// control de entrance_stages
	Route::resource('entrance-stages', '\App\Http\Controllers\Api\EntranceStagesAPIController');

	// control de tipos
	Route::resource('types', '\App\Http\Controllers\Api\typeAPIController');

	// control de configuración termica
	Route::resource('thermal-configs', '\App\Http\Controllers\Api\thermalConfigAPIController');

	// control de expansion termal
	Route::resource('thermal-expansion', '\App\Http\Controllers\Api\Thermal_expnAPIController');

	// control de costos de racionamiento
	Route::resource('rationing-costs', '\App\Http\Controllers\Api\RationingCostAPIController');
	Route::get('getRationingCostBySegmentId/{id}', '\App\Http\Controllers\Api\RationingCostAPIController@getRationingCostBySegmentId');

	// contorl de small_config
	Route::resource('small-config', '\App\Http\Controllers\Api\SmallConfigAPIController');

	// conntrol de small_expansion
	Route::resource('small-config-expansion', '\App\Http\Controllers\Api\SmallExpnAPIController');

	// control de hidro config
	Route::resource('hydro-config', '\App\Http\Controllers\Api\HidroConfigAPIController');

	// control de hidro expansion
	Route::resource('hydro-expansion', '\App\Http\Controllers\Api\HidroExpnAPIController');

	// control de inflow
	Route::resource('inflow', '\App\Http\Controllers\Api\inflowAPIController');
	Route::get('getInflowByHydroConfigId/{id}', '\App\Http\Controllers\Api\inflowAPIController@getInflowByHydroConfigId');



	// control de wind config
	Route::resource('wind-config', '\App\Http\Controllers\Api\windConfigAPIController');

	// control de wind expansion
	Route::resource('wind-expansion', '\App\Http\Controllers\Api\windExpnAPIController');


	Route::resource('months', '\App\Http\Controllers\Api\monthAPIController');


	// control de speed indices
	Route::resource('speed-indices', '\App\Http\Controllers\Api\speedIndicesAPIController');
	Route::get('getSpeedIndicesByWindConfigId/{id}', '\App\Http\Controllers\Api\speedIndicesAPIController@getSpeedIndicesByWindConfigId');



	Route::post('uploadHidric/{id}', '\App\Http\Controllers\Api\processAPIController@uploadHidric');
	Route::post('uploadWind/{id}', '\App\Http\Controllers\Api\processAPIController@uploadWind');

	Route::post('uploadExcel/{id}', '\App\Http\Controllers\Api\processAPIController@uploadExcel');

	// rutas que necesitan autentificación
    Route::group(['middleware' => 'jwt.auth'], function () {

		// ruta para iniciar procesamiento
	    Route::post('process/{id}', '\App\Http\Controllers\Api\processAPIController@process');


	    Route::get('process/{id}', '\App\Http\Controllers\Api\processAPIController@process');

    	
    	Route::resource('categories', 'categoriesAPIController');
        Route::get('/auth/refresh', '\App\Http\Controllers\Api\AuthController@refresh');
        Route::get('/auth/user', '\App\Http\Controllers\Api\UserController@getProfile');
        Route::post('profile', '\App\Http\Controllers\Api\UserController@postProfile');
		Route::resource('processes', '\App\Http\Controllers\Api\processAPIController');

		// permite enviar otro email para activar cuenta
    	Route::get('/auth/activate/send-token', '\App\Http\Controllers\Auth\ActivateController@sendToken');
    	// Permite retornar todos los usuarios
        Route::resource('users', '\App\Http\Controllers\Admin\UserController');
        // permite retornar todos los roles
        Route::resource('roles', '\App\Http\Controllers\Admin\RoleController');
        // Permite descargar un archivo en excel
	    Route::post('users/download-excel',  '\App\Http\Controllers\Admin\UserController@downloadExcel');

    });
});








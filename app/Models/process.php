<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Areas;
use App\Models\Blocks;
use App\Models\Demand;
use App\Models\FuelCostPlant;
use App\Models\HidroConfig;
use App\Models\HidroExpn;
use App\Models\Horizont;
use App\Models\Inflow;
use App\Models\InflowWind;
use App\Models\InflowWindM2;
use App\Models\Lines;
use App\Models\linesExpansion;
use App\Models\RationingCost;
use App\Models\Scenario;
use App\Models\Segment;
use App\Models\SmallConfig;
use App\Models\SmallExpn;
use App\Models\speedIndices;
use App\Models\speedIndicesM2;
use App\Models\Storage_config;
use App\Models\storageExpansion;
use App\Models\Thermal_expn;
use App\Models\ThermalConfig;
use App\Models\WindConfig;
use App\Models\WindExpn;
use App\Models\WindM2Config;
use App\Models\WPowCurveM2;


/**
 * Class process
 * @package App\Models
 * @version March 1, 2018, 11:59 pm UTC
 *
 * @property string name
 * @property integer statusId
 * @property integer templateId
 * @property integer max_iter,
 * @property integer extra_stages
 * @property integer stages
 * @property integer seriesBack
 * @property integer seriesForw
 * @property integer stochastic
 * @property integer variance
 * @property integer sensDem
 * @property integer speed_out
 * @property integer speed_in
 * @property integer eps_area
 * @property integer eps_all
 * @property integer eps_risk
 * @property integer commit
 * @property integer lag_max
 * @property integer testing_t
 * @property integer d_correl
 * @property integer seasonality
 */
class Process extends Model
{

    public $table = 'processes';


    public $fillable = [
        'template',
        'phase',
        'name',
        'statusId',
        'templateId',
        'type',
        'max_iter',
        'bnd_stages',
        'stages',
        'seriesBack',
        'seriesForw',
        'sensDem',
        'eps_area',
        'eps_all',
        'eps_risk',
        'commit',
        'lag_max',
        'testing_t',
        'd_correl',
        'seasonality',
        'userId',
       'generate_wind',
       'segment_id',
       'blocks',


        'read_data',
        'param_calculation',
        'param_opf',
        'wind_model2',
        'flow_gates',



    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'statusId' => 'integer',
        'phase' => 'integer',
        'templateId' => 'integer',
        'template' => 'boolean',        

        'max_iter' => 'integer',
        'bnd_stages' => 'integer',
        'stages' => 'integer',
        'seriesBack' => 'integer',
        'seriesForw' => 'integer',
        'sensDem' => 'double',
        'eps_area' => 'double',
        'eps_all' => 'double',
        'eps_risk' => 'double',
        'commit' => 'double',


        'read_data' => 'boolean',
        'param_calculation' => 'boolean',
        'param_opf' => 'boolean',
        'wind_model2' =>'boolean',
        'flow_gates' => 'boolean',
        'type' => 'string',
        'lag_max' => 'integer',
        'segment_id' => 'integer',
        'testing_t' => 'integer',
        'd_correl' => 'integer',
        'seasonality' => 'integer',
        'userId' => 'integer',
        'generate_wind' => 'boolean',
        'blocks' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'phase' => 'required',
        'statusId' => 'required',
        'commit' => 'required',
        'd_correl' => 'required',
        'seasonality' => 'required',
        'userId' => 'required',
        'blocks' => 'required'

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function Areas() {
        return $this->hasMany(Areas::class, 'process_id');
    }

    public function Blocks() {
        return $this->hasMany(Blocks::class, 'process_id');
    } 

    public function Demand() {
        return $this->hasMany(Demand::class, 'process_id');
    } 

    public function FuelCostPlant() {
        return $this->hasMany(FuelCostPlant::class, 'process_id');
    } 

    public function HidroConfig() {
        return $this->hasMany(HidroConfig::class, 'process_id');
    } 

    public function HidroExpn() {
        return $this->hasMany(HidroExpn::class, 'process_id');
    } 

    public function Horizont() {
        return $this->hasMany(Horizont::class, 'process_id');
    } 

    public function Inflow() {
        return $this->hasMany(Inflow::class, 'process_id');
    } 
    
    public function InflowWind() {
        return $this->hasMany(InflowWind::class, 'process_id');
    } 

    public function InflowWindM2() {
        return $this->hasMany(InflowWindM2::class, 'process_id');
    } 

    public function Lines() {
        return $this->hasMany(Lines::class, 'process_id');
    } 

    public function linesExpansion() {
        return $this->hasMany(linesExpansion::class, 'process_id');
    } 

    public function RationingCost() {
        return $this->hasMany(RationingCost::class, 'process_id');
    } 

    public function Scenario() {
        return $this->hasMany(Scenario::class, 'process_id');
    } 

    public function Segment() {
        return $this->hasMany(Segment::class, 'process_id');
    } 

    public function SmallConfig() {
        return $this->hasMany(SmallConfig::class, 'process_id');
    } 

    public function SmallExpn() {
        return $this->hasMany(SmallExpn::class, 'process_id');
    } 

    public function SpeedIndices() {
        return $this->hasMany(SpeedIndices::class, 'process_id');
    } 

    public function SpeedIndicesM2() {
        return $this->hasMany(SpeedIndicesM2::class, 'process_id');
    } 

    public function StorageConfig () {
        return $this->hasMany(Storage_config::class, 'process_id');        
    }

    public function StorageExpansion () {
        return $this->hasMany(StorageExpansion::class, 'process_id');        
    }

    public function ThermalExpn () {
        return $this->hasMany(Thermal_expn::class, 'process_id');        
    }

    public function ThermalConfig () {
        return $this->hasMany(thermalConfig::class, 'process_id');        
    }
    
    public function WindConfig () {
        return $this->hasMany(WindConfig::class, 'process_id');        
    }

    public function WindExpn () {
        return $this->hasMany(WindExpn::class, 'process_id');        
    }

    public function WindM2Config () {
        return $this->hasMany(WindM2Config::class, 'process_id');        
    }

    public function WPowCurveM2 () {
        return $this->hasMany(WPowCurveM2::class, 'process_id');        
    }
}
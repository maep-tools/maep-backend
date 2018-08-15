<?php

namespace App\Repositories;

use App\Models\WindM2Config;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class WindM2ConfigRepository
 * @package App\Repositories
 * @version April 24, 2018, 12:11 am UTC
 *
 * @method WindM2Config findWithoutFail($id, $columns = ['*'])
 * @method WindM2Config find($id, $columns = ['*'])
 * @method WindM2Config first($columns = ['*'])
*/
class WindM2ConfigRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'rows'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return WindM2Config::class;
    }
}

<?php

namespace App\Repositories;

use App\Models\linesExpansion;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class linesExpansionRepository
 * @package App\Repositories
 * @version April 30, 2018, 2:28 pm UTC
 *
 * @method linesExpansion findWithoutFail($id, $columns = ['*'])
 * @method linesExpansion find($id, $columns = ['*'])
 * @method linesExpansion first($columns = ['*'])
*/
class linesExpansionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'a_initial',
        'a_final',
        'stage',
        'a_b',
        'b_ai',
        'efficiency',
        'resistence',
        'reactance'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return linesExpansion::class;
    }
}

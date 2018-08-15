<?php

namespace App\Repositories;

use App\Models\lines;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class linesRepository
 * @package App\Repositories
 * @version April 30, 2018, 2:24 pm UTC
 *
 * @method lines findWithoutFail($id, $columns = ['*'])
 * @method lines find($id, $columns = ['*'])
 * @method lines first($columns = ['*'])
*/
class linesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'a_initial',
        'b_final',
        'a_to_b',
        'b_to_a',
        'efficiency',
        'resistence',
        'reactance'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return lines::class;
    }
}

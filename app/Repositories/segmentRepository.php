<?php

namespace App\Repositories;

use App\Models\segment;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class segmentRepository
 * @package App\Repositories
 * @version April 11, 2018, 11:02 pm UTC
 *
 * @method segment findWithoutFail($id, $columns = ['*'])
 * @method segment find($id, $columns = ['*'])
 * @method segment first($columns = ['*'])
*/
class segmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'process_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return segment::class;
    }
}

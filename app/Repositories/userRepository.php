<?php

namespace App\Repositories;

use App\Models\User;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class processRepository
 * @package App\Repositories
 * @version March 1, 2018, 11:59 pm UTC
 *
 * @method process findWithoutFail($id, $columns = ['*'])
 * @method process find($id, $columns = ['*'])
 * @method process first($columns = ['*'])
*/
class userRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'email',
        'name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }
}

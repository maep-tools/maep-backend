<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserInviteRequest;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\userRepository;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use Excel;

class UserController extends BaseController
{

    /** @var  userRepository */
    private $userRepository;

    public function __construct(UserService $userService, userRepository $userRepository)
    {
        $this->service = $userService;
        $this->userRepository = $userRepository;
    }

    public function sendResponse ($e) {
        return response()->json($e);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $per_page = 10)
    {
        $this->userRepository->pushCriteria(new RequestCriteria($request));
        $this->userRepository->pushCriteria(new LimitOffsetCriteria($request));
        $processes = $this->userRepository->paginate($request->query('limit'));

        return $this->sendResponse($processes->toArray(), 'Processes retrieved successfully');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->userRepository->with('meta')->with('roles')->find($id);

        return $this->sendResponse($user, 'Processes retrieved successfully');
    }


    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if (! $request->search) {
            return redirect('admin/users');
        }

        $users = $this->service->search($request->search);
        return view('admin.users.index')->with('users', $users);
    }

    /**
     * Show the form for inviting a customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInvite()
    {
        return view('admin.users.invite');
    }

    /**
     * Show the form for inviting a customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function postInvite(UserInviteRequest $request)
    {
        $result = $this->service->invite($request->except(['_token', '_method']));

        if ($result) {
            return redirect('admin/users')->with('message', 'Successfully invited');
        }

        return back()->with('error', 'Failed to invite');
    }

    /**
     * Switch to a different User profile
     *
     * @return \Illuminate\Http\Response
     */
    public function switchToUser($id)
    {
        if ($this->service->switchToUser($id)) {
            return redirect('dashboard')->with('message', 'You\'ve switched users.');
        }

        return redirect('dashboard')->with('message', 'Could not switch users');
    }

    /**
     * Switch back to your original user
     *
     * @return \Illuminate\Http\Response
     */
    public function switchUserBack()
    {
        if ($this->service->switchUserBack()) {
            return back()->with('message', 'You\'ve switched back.');
        }

        return back()->with('message', 'Could not switch back');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->service->find($id);
        return view('admin.users.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->service->update($id, $request->except(['_token', '_method']));

        if ($result) {
            return [
                'status' => 'success',
                'message' => 'Modelo guardado correctamente'
            ];
        }

            return [
                'status' => 'error',
                'message' => 'Modelo no guardado.'
            ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->service->destroy($id);

        if ($result) {
            
            return redirect('admin/users')->with('message', 'Successfully deleted');
        }

        return redirect('admin/users')->with('message', 'Failed to delete');
    }


    /**
     * Download all users in a excel file
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadExcel () {

        $users = User::with('meta')->get();
        $data = [];
        $app = app();
        foreach ($users as $user) {
             $formatted = $app->make('stdClass');
             $formatted->NOMBRE = $user->name;
             $formatted->APELLIDO = $user->lastname;             
             $formatted->EMAIL = $user->email;
             $formatted->EMPRESA = $user->meta->company;
             $formatted->TELEFONO = $user->meta->phone;
             $formatted->ACTIVO = ($user->meta->is_active === 1) ? 'ACTIVO': 'INACTIVO';
             $formatted->DISPONIBLES = intval($user->meta->models);
             array_push($data, (array) $formatted);
        }

        $file = Excel::create('Usuarios', function($excel) use ($data) {
            $excel->sheet('Usuarios', function($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        });

        $file = $file->string('xlsx');

        $response =  array(
            'name' => "Usuarios",
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($file)
        );

        return response()->json($response);
    }


}

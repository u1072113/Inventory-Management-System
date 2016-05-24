<?php namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests;
use Carbon;
use Inventory\Repository\User\UserInterface;
use Excel;
use Hash;
use Image;
use Input;
use Redirect;
use Request;
use Schema;
use Auth;
use Response;
class UserController extends Controller
{

    public function __construct(UserInterface $user)
    {

        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $sort = Input::only('sortBy', 'direction');
        $search = Input::only('search');
        $users = $this->user->all(compact('search', 'sort'));
        $message = "List Of All Users";
        return view('users.index')->with(compact('users', 'message'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('users.createupdateuser');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $data = Input::all();
        $password = Input::get('password');
        if ($password != "") {
            $data['password'] = Hash::make($password);
        } else {
            unset($data['password']);
        }
        $this->user->createUser($data);
        return Redirect::action('UserController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->user->getById($id);
        return view('users.createupdateuser')->with(compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $data = Input::all();
        $password = Input::get('password');
        if ($password != "") {
            $data['password'] = Hash::make($password);
        } else {
            unset($data['password']);
        }
        $this->user->updateUser($id, $data);
        return Redirect::action('UserController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->user->deleteUser($id);
        return Response::json(['ok' => 'ok']);
    }

    public function getDeleted()
    {
        $restore = 1;
        $users = $this->user->allDeleted();
        $message = "Deleted Users";
        return view('users.index')->with(compact('users', 'restore', 'message'));
    }

    public function restore($id)
    {
        $this->user->restoreUser($id);
        return Redirect::action('UserController@index');
    }

    public function import()
    {
        $columns = Schema::getColumnListing('users');

        if (Input::has('download')) {
            $this->dataTransfer();
        }

        if (Request::isMethod('post')) {
            $file = Input::file('workbenchfile');
            $this->uploadData($file);
            return Redirect::action('UserController@index');
        }
        return View('users/import')->with(compact('columns'));
    }

    public function uploadAvatar()
    {
        if (Input::hasFile('file')) {
            $product_image = Input::file('file');
            $destinationPath = Helper::downloadPath() . '/avatars/';
            $filename = str_random(6) . '_' . $product_image->getClientOriginalName();
            $save_path = $destinationPath . $filename;
            Image::make(Input::file('file'))->fit(80)->save($save_path);
            return array('save_path' => $filename);
        }
    }

    /**
     * Downloads Data Transfer Workbench File
     */
    public function dataTransfer()
    {
        $file = Excel::create("Data Transfer WorkBench", function ($excel) {

            // Set the title
            $excel->setTitle('Data Transfer WorkBench');

            // Chain the setters
            $excel->setCreator('Stock Control System')
                ->setCompany(env('COMPANY_NAME'));

            // Call them separately
            $excel->setDescription('Data Transfer Workbench');
            $excel->sheet('Data Transfer Workbench', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $columns = Schema::getColumnListing('users');
                unset($columns[0]);
                unset($columns[11]);
                unset($columns[12]);
                unset($columns[13]);
                $sheet->fromArray($columns);
            });
        });
        $file->download();
    }

    public function uploadData($file)
    {
        Excel::load($file, function ($reader) {
            $results = $reader->toArray();
            foreach ($results as $result) {
                $result['role_id'] = 5;
                $this->user->createUser($result);
            }
        });
    }

    public function export()
    {
        $format = Input::query('type');
        $filename = Carbon::now()->format('Ymd_') . "UserList";
        $file = Excel::create($filename, function ($excel) {

            // Set the title
            $excel->setTitle('Users');

            // Chain the setters
            $excel->setCreator('Stock Control System')
                ->setCompany(env('COMPANY_NAME'));

            // Call them separately
            $excel->setDescription('Products List and their Levels');
            $excel->sheet('UserList', function ($sheet) {
                $sheet->freezeFirstRowAndColumn();
                $sheet->fromModel($this->user->allReport());
            });


        });

        if (Input::has('email')) {
            $email = Input::get('email');
            $save_details = $file->store('xlsx');
            $content = "Please find attached a list of users";
            Mail::send('emails.product', array('content' => $content), function ($message) use ($save_details, $email) {
                $message->to($email)->subject('User List');
                $message->attach($save_details['full']);
            });
            return Redirect::action('UserController@index');
        } else {
            $file->download($format);
        }
    }

    public function postLogin()
    {
        $userid = Input::get('userid');
        Auth::loginUsingId($userid);
        return redirect()->back();
    }
}

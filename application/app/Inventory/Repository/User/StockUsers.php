<?php namespace Inventory\Repository\User;

use App\User;
use Auth;
use Inventory\Repository\Setting\SettingsInterface;
use Schema;
use DB;

/**
 * Class StockUsers
 * @package Inventory\Repository\User
 */
class StockUsers implements UserInterface
{
    public function __construct(SettingsInterface $setting)
    {
        $this->setting = $setting;
    }

    public function getCompanyMembers()
    {
        if (env('LINKCOMPANYBYUSERNAME') == true) {
            return User::whereName(Auth::user()->name)->where('id', '!=', Auth::user()->id)->lists('name', 'id');
        }
        return User::whereCompanyid(Auth::user()->companyId)->lists('name', 'id');

    }

    /**
     * @param array $params
     * @return mixed
     */
    public function all(array $params)
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        $user = new User();

        if (env('SHOW_ADMINS', false)) {
//Do nothing
        } else {
            $user = $user->where('roles.id', '>', 1);
        }
        //Filter
        if ($params['sort']['sortBy'] and $params['sort']['direction']) {
            return $user->with('department')
                ->with('role')
                ->join('roles', 'roles.id', '=', 'users.role_id')
                ->join('departments', 'users.departmentId', '=', 'departments.id')
                ->select('users.*')
                ->where('users.companyId', '=', Auth::user()->companyId)
                ->orderBy($params['sort']['sortBy'], $params['sort']['direction'])
                ->paginate($pagination)->setPath('');

        }
        //Search
        if ($params['search']['search']) {
            $user = $this->search($user, $params['search']['search'], 'users');
        }
        return $user->with('department')
            ->with('role')
            ->leftJoin('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('departments', 'users.departmentId', '=', 'departments.id')
            ->select('users.*')
            ->where('users.companyId', '=', Auth::user()->companyId)
            ->paginate($pagination)->setPath('');
    }

    /**
     * @return mixed
     * All users report for excel
     */
    public function allReport()
    {
        return User::where('companyId', '=', Auth::user()->companyId)->get();
    }

    /**
     * @param $item
     * @param $search
     * @param $table
     * @return mixed
     */
    public function search($item, $search, $table)
    {
        $columns = Schema::getColumnListing($table);
        unset($columns[0]);
        $first = $columns[1];
        $item = $item->where(function ($query) use ($search, $columns) {
            foreach ($columns as $column) {
                $query->orWhere('users.' . $column, 'LIKE', "%{$search}%");
            }
        });
        return $item;
    }

    /**
     * @return mixed
     */
    public function usersList()
    {
        return User::where('companyId', '=', Auth::user()->companyId)->lists('name', 'id');
    }

    /**
     * @param $user
     * @return static
     */
    public function createUser($user)
    {
        return User::create($user);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return User::where('companyId', '=', Auth::user()->companyId)->find($id);
    }

    /**
     * @param $id
     * @param $user
     * @return mixed
     */
    public function updateUser($id, $user)
    {
        return User::find($id)->update($user);
    }

    /**
     * @param $id
     * @return int
     */
    public function deleteUser($id)
    {
        return User::destroy($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restoreUser($id)
    {
        return User::withTrashed()->where('id', $id)->restore();
    }

    /**
     * @return mixed
     */
    public function allDeleted()
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        return User::where('companyId', '=', Auth::user()->companyId)->onlyTrashed()->paginate($pagination)->setPath('');
    }

    /**
     * @return mixed
     */
    public function userCount()
    {
        return User::where('companyId', '=', Auth::user()->companyId)->get()->count();
    }

    /**
     * @return mixed
     */
    public function deletedCount()
    {
        return User::where('companyId', '=', Auth::user()->companyId)->onlyTrashed()->count();
    }

    public function getUsersForLpoGenerate()
    {
        return DB::table('users')
            ->select('id', 'companyId')
            ->groupBy('companyId')
            ->get();
    }


}

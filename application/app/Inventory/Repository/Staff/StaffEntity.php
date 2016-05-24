<?php namespace Inventory\Repository\Staff;

use App\Staff;
use Inventory\Repository\Setting\SettingsInterface;
use DB;
use Schema;

class StaffEntity implements StaffInterface
{

    public function __construct(SettingsInterface $setting)
    {
        $this->setting = $setting;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function all(array $params)
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        $staff = new Staff();

        //Filter
        if ($params['sort']['sortBy'] and $params['sort']['direction']) {
            return $staff->with('department')
                ->join('departments', 'staff.departmentId', '=', 'departments.id')
                ->select('staff.*')
                ->orderBy($params['sort']['sortBy'], $params['sort']['direction'])
                ->paginate($pagination)->setPath('');

        }
        //Search
        if ($params['search']['search']) {
            $staff = $this->search($staff, $params['search']['search'], 'staff');
        }
        return $staff->with('department')
            ->leftJoin('departments', 'staff.departmentId', '=', 'departments.id')
            ->select('staff.*')
            ->paginate($pagination)->setPath('');
    }

    /**
     * @return mixed
     * All staff report for excel
     */
    public function allReport()
    {
        return Staff::all();
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
                $query->orWhere('staff.' . $column, 'LIKE', "%{$search}%");
            }
        });
        return $item;
    }

    /**
     * @return mixed
     */
    public function staffList()
    {
        return Staff::lists('name', 'id');
    }

    /**
     * @param $user
     * @return static
     */
    public function createStaff($user)
    {
        return Staff::create($user);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getStaffById($id)
    {
        return Staff::find($id);
    }

    public function getStaffJson()
    {
        return Staff::select(DB::raw('name as text,id'))->get();
    }


    /**
     * @param $id
     * @param $user
     * @return mixed
     */
    public function updateStaff($id, $user)
    {
        return Staff::find($id)->update($user);
    }

    /**
     * @param $id
     * @return int
     */
    public function deleteStaff($id)
    {
        return Staff::destroy($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restoreStaff($id)
    {
        return Staff::withTrashed()->where('id', $id)->restore();
    }

    /**
     * @return mixed
     */
    public function allDeleted()
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        return Staff::onlyTrashed()->paginate($pagination)->setPath('');
    }

    /**
     * @return mixed
     */
    public function staffCount()
    {
        return Staff::all()->count();
    }

    /**
     * @return mixed
     */
    public function deletedStaffCount()
    {
        return User::onlyTrashed()->count();
    }
}
<?php namespace Inventory\Repository\Department;

use App\Department;
use App\Helper;
use Inventory\Repository\Setting\SettingsInterface;
use DB;
use Schema;

/**
 * Class NewDepartment
 * @package Inventory\Repository\Department
 * Department Repository
 */
class NewDepartment implements DepartmentInterface
{
    public function __construct(SettingsInterface $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Gets all departments from db
     * @param array $params
     * @return mixed
     */
    public function all(array $params)
    {
        $pagination = $this->setting->getSettings()->paginationDefault;
        //Filter
        if ($params['sort']['sortBy'] and $params['sort']['direction']) {
            $department = Department::leftJoin('dispatches', 'departments.id', '=', 'dispatches.departmentId')
                ->selectRaw('departments.* , count(dispatches.id) as dispatchcount, sum(dispatches.totalCost) as dispatchsum')
                ->groupBy('departments.id')
                ->whereRaw('dispatches. created_at > departments.budgetStartDate and dispatches. created_at < departments.budgetEndDate')
                ->orderBy($params['sort']['sortBy'], $params['sort']['direction'])
                ->skip($pagination * ($params['page'] - 1))
                ->take($pagination)
                ->get();
            return Helper::paginateQuery(
                $department,
                $this->getDepartmentCount(),
                $pagination,
                null,
                $params['sort'],
                '/department'
            )->setPath('');
        }
        $department = new Department();

        if ($params['search']['search']) {
            $department = $this->search($department, $params['search']['search'], 'departments');
        }
        $department = $department->leftJoin('dispatches', 'departments.id', '=', 'dispatches.departmentId')
            ->selectRaw('departments.* , count(dispatches.id) as dispatchcount, sum(dispatches.totalCost) as dispatchsum')
            ->whereRaw('dispatches. created_at > departments.budgetStartDate and dispatches. created_at < departments.budgetEndDate')
            ->groupBy('departments.id')
            ->skip($pagination * ($params['page'] - 1))
            ->take($pagination)
            ->get();
        return Helper::paginateQuery(
            $department,
            $this->getDepartmentCount(),
            $pagination,
            null,
            $params['sort'],
            '/department'
        )->setPath('');

    }

    /**
     * @param $item
     * @param $search
     * @param $table
     * @return mixed
     * Used to search for an department
     */
    public function search($item, $search, $table)
    {
        $columns = Schema::getColumnListing($table);
        unset($columns[0]);
        $first = $columns[1];
        $item = $item->where(function ($query) use ($search, $columns) {
            foreach ($columns as $column) {
                $query->orWhere('departments.'.$column, 'LIKE', "%{$search}%");
            }
        });
        return $item;
    }

    /**
     * Returns the number of departments
     * @return integer
     */
    public function getDepartmentCount()
    {
        return Department::all()->count();
    }

    /**
     * Adds Department to DB
     * @param $Department
     * @return static
     */
    public function addDepartment($Department)
    {
        return Department::create($Department);
    }

    /**
     * Gets Department by ID
     * @param $id
     * @return mixed
     */
    public function getDepartmentByID($id)
    {
        return Department::find($id);
    }

    /**
     * @param $id
     * @param $department
     * @return mixed
     * Updates an existing department
     */
    public function updateDepartment($id, $department)
    {
        return Department::find($id)->update($department);
    }

    /**
     * @param $id
     * @return int
     * Deletes an existing department
     */
    public function deleteDepartment($id)
    {
        return Department::destroy($id);
    }

    /**
     * @return mixed
     * Gets amount a department has used so far
     */
    public function getDepartmentAmount()
    {
        $x = array(
            'departments.name',
            DB::raw('sum(dispatches.amount)as dispatchcount'),
            DB::raw('MONTH(dispatches.created_at) month')
        );
        return Department::leftJoin('dispatches', 'dispatches.departmentId', '=', 'departments.id')
            ->select($x)
            // ->select($x)
            //   ->groupBy('products.productName', DB::raw('MONTH(dispatches.created_at)'))
            ->orderBy('month')
            ->get();

    }

    /**
     * @return mixed
     * Returns department list for use in a select2
     */
    public function departmentList()
    {
        return Department::lists('name', 'id');
    }

    /**
     * @return mixed
     * Returns JSON data for use in chart
     */
    public function getDepartmentChart()
    {
        return $department = Department::leftJoin('dispatches', 'departments.id', '=', 'dispatches.departmentId')
            ->selectRaw('departments.name , count(dispatches.id) as dispatchcount, sum(dispatches.totalCost) as dispatchsum')
            ->groupBy('departments.id')
            ->get();

    }

    /**
     * @return mixed
     * Returns Department report for excel and csv
     */
    public function getDepartmentReport()
    {
        return Department::leftJoin('dispatches', 'departments.id', '=', 'dispatches.departmentId')
            ->selectRaw('departments.* , count(dispatches.id) as dispatchcount, sum(dispatches.totalCost) as dispatchsum')
            ->groupBy('departments.id')
            ->get();
    }
}

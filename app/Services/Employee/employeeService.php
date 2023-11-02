<?php


namespace App\Services\Employee;

use App\Models\Employees;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class EmployeeService
{
    protected $moduleName;

    public function __construct()
    {
        $this->moduleName = "Employee";
    }

    public function List($view = null, $page = null, $search = null)
    {
        try {
            $query = Employees::orderBy('id','desc');
            if (!is_null($search)) {
                $query = $query->where('first_name', 'like', '%' . $search . '%')
                    ->orderByRaw('CASE
               WHEN first_name LIKE "' . $search . '%" THEN 1
               WHEN first_name LIKE "%' . $search . '%" THEN 2
               ELSE 3
               END');
            }
            if ($view) {
                $query = $query->paginate($view, ['*'], 'page', $page);
            } else {
                $query = $query->get();
            }

            return $query;

        } catch (QueryException $e) {
            // Handle the database query exception here, log it or return an error response
            return ['error' => config('constants.query_error')($this->moduleName, $e->getMessage())];
        } catch (\Exception $e) {
            // Handle other exceptions
            return ['error' => config('constants.internal_error')($e->getMessage())];
        }
    }

    /**
     * Create a Tag
     *
     * @param array $data
     * @return mixed
     */
    public function store($data)
    {
        try {
            $query = Employees::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'company_id' => $data['company_id'] ,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
            ]);

            return [
                'message' => config('constants.record_created')($this->moduleName),
                'data' => $query
            ];
        } catch (QueryException $e) {
            // Handle the database query exception here, log it or return an error response
            return ['error' => config('constants.query_error')($this->moduleName, $e->getMessage())];
        } catch (\Exception $e) {
            return ['error' => config('constants.internal_error')($e->getMessage())];
        }
    }

    /**
     * Get a Tag
     *
     * @param integer $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $query = Employees::with('company')->find($id);
            if (!$query) {
                return ['error' => config('constants.not_found')($this->moduleName)];
            }

            return $query;
        } catch (QueryException $e) {
            // Handle the database query exception here, log it or return an error response
            return ['error' => config('constants.query_error')($this->moduleName, $e->getMessage())];
        } catch (\Exception $e) {
            // Handle other exceptions
            return ['error' => config('constants.internal_error')($e->getMessage())];
        }
    }

    /**
     * Update a Tag
     *
     * @param array $data
     * @param integer $id
     * @return mixed
     */
    public function update($data, $id)
    {
        try {
            $query = Employees::find($id);
            if (!$query) {
                return ['error' => config('constants.not_found')($this->moduleName)];
            }

            // update Tag Data.
            $update = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'company_id' => $data['company_id'] ,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
            ];

            $query->update($update);

            return [
                'message' => config('constants.record_updated')($this->moduleName),
                'data' => $query
            ];
        } catch (QueryException $e) {
            // Handle the database query exception here, log it or return an error response
            return ['error' => config('constants.query_error')($this->moduleName, $e->getMessage())];
        } catch (\Exception $e) {
            // Handle other exceptions
            return ['error' => config('constants.internal_error')($e->getMessage())];
        }
    }

    /**
     * Delete a Tag
     *
     * @param integer $id
     * @return mixed
     */
    public function destroy($id)
    {
        try {
            $query = Employees::find($id);
            if (!$query) {
                return ['error' => config('constants.not_found')($this->moduleName)];
            }

            $query->delete();
            return [
                'message' => config('constants.record_deleted')($this->moduleName),
            ];
        } catch (QueryException $e) {
            // Handle the database query exception here, log it or return an error response
            return ['error' => config('constants.query_error')($this->moduleName, $e->getMessage())];
        } catch (\Exception $e) {
            // Handle other exceptions
            return ['error' => config('constants.internal_error')($e->getMessage())];
        }
    }
}

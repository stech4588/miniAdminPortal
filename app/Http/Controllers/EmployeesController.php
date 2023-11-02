<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\EmployeeCreateFormRequest;
use App\Services\Employee\EmployeeService;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    private $employeeService;
    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }
    public function indexEmployees(Request $request)
    {
        try {
            $view = $request->input('view');
            $page = (int) $request->input('page', 1);
            $search = $request->input('search');

            $data = $this->employeeService->List($view, $page, $search);

            return $this->ApiResponse($data);

        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTrace()], 500);
            }
            return response()->json(['message' => __('response.catch')], 500);
        }
    }

    public function storeEmployees(EmployeeCreateFormRequest $request)
    {
        try {
            $data = $this->employeeService->store($request->all());
            return $this->ApiResponse($data);

        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTrace()], 500);
            }
            return response()->json(['message' => __('response.catch')], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function showEmployees($id)
    {
        try {
            $data = $this->employeeService->show((int)$id);
            return $this->ApiResponse($data);

        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTrace()], 500);
            }
            return response()->json(['message' => __('response.catch')], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateEmployees(EmployeeCreateFormRequest $request, $id)
    {
        try {
            $data = $this->employeeService->update($request->all(), (int)$id);
            return $this->ApiResponse($data);

        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTrace()], 500);
            }
            return response()->json(['message' => __('response.catch')], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroyEmployees($id)
    {
        try {
            $data = $this->employeeService->destroy((int)$id);
            return $this->ApiResponse($data);

        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTrace()], 500);
            }
            return response()->json(['message' => __('response.catch')], 500);
        }
    }
}

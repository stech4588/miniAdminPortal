<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\CompanyCreateFormRequest;
use App\Services\Company\CompanyService;
use Illuminate\Http\Request;
use Exception;

class CompaniesController extends Controller
{
    private $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    //Companies
    public function indexCompanies(Request $request)
    {
        try {
            $view = $request->input('view');
            $page = (int) $request->input('page', 1);
            $search = $request->input('search');

            $data = $this->companyService->List($view, $page, $search);

            return $this->ApiResponse($data);

        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTrace()], 500);
            }
            return response()->json(['message' => __('response.catch')], 500);
        }
    }

    public function storeCompanies(CompanyCreateFormRequest $request)
    {
        try {
            $data = $this->companyService->store($request->all());
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
    public function showCompanies($id)
    {
        try {
            $data = $this->companyService->show((int)$id);
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
    public function updateCompanies(CompanyCreateFormRequest $request, $id)
    {
        try {
            $data = $this->companyService->update($request->all(), (int)$id);
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
    public function destroyCompanies($id)
    {
        try {
            $data = $this->companyService->destroy((int)$id);
            return $this->ApiResponse($data);

        } catch (Exception $e) {
            if (config('app.debug')) {
                return response()->json(['message' => $e->getMessage(), 'trace' => $e->getTrace()], 500);
            }
            return response()->json(['message' => __('response.catch')], 500);
        }
    }
}

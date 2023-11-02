<?php


namespace App\Services\Company;

use App\Models\Companies;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CompanyService
{
    protected $moduleName;

    public function __construct()
    {
        $this->moduleName = "Company";
    }

    public function List($view = null, $page = null, $search = null)
    {
        try {
            $query = Companies::orderBy('id','desc');
            if (!is_null($search)) {
                $query = $query->where('name', 'like', '%' . $search . '%')
                    ->orderByRaw('CASE
               WHEN name LIKE "' . $search . '%" THEN 1
               WHEN name LIKE "%' . $search . '%" THEN 2
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
            if ($data['logo']) {
                // Original filename without extension
                $originalName = pathinfo($data['logo']->getClientOriginalName(), PATHINFO_FILENAME);

                // Original file extension
                $extension = $data['logo']->getClientOriginalExtension();

                // Make a proper image name
                $imageName = $originalName . '.' . $extension;

                // Make path of the directory to store image
                $path = '/images/' ;
                $completeImageName = $path.$imageName;
                // Use the configured disk (local or s3)
                Storage::disk('public')->putFileAs($path, $data['logo'], $imageName);
            }

            $query = Companies::create([
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'website' => $data['website'] ?? null,
                'logo' => $completeImageName ?? null,
            ]);

            try {
                $text = 'You company has been created on '.env('APP_NAME').' with name: '.$data['name'];
                Mail::raw($text, function ($message) use($data) {
                    $message->to($data['email'])->subject('Company Creation');
                });
                $emailStatus = 'Successful';
            }catch(\Exception $e){
              $emailStatus = ['error' => config('constants.internal_error')($e->getMessage())];
                Log::error('Email error: ' . $e->getMessage());
            }


            return [
                'message' => config('constants.record_created')($this->moduleName),
                'data' => $query,
                'emailStatus' => $emailStatus
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
            $query = Companies::find($id);
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

            $query = Companies::find($id);
            if (!$query) {
                return ['error' => config('constants.not_found')($this->moduleName)];
            }
            if ($data['logo']) {
                // Original filename without extension
                $originalName = pathinfo($data['logo']->getClientOriginalName(), PATHINFO_FILENAME);

                // Original file extension
                $extension = $data['logo']->getClientOriginalExtension();

                // Make a proper image name
                $imageName = $originalName . '.' . $extension;

                // Make path of the directory to store image
                $path = '/images/' ;

                // Use the configured disk (local or s3)
                if ($imageName != $query->logo)
                Storage::disk('public')->putFileAs($path, $data['logo'], $imageName);

                $imageName = $path.$imageName;
            } else{
                $imageName = $query->logo;
            }
            // update Tag Data.
            $update = [
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'website' => $data['website'] ?? null,
                'logo' => $imageName ?? null,
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
            $query = Companies::find($id);
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

<?php

namespace App\Http\Controllers;

use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Enums\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Repositories\Country\CountryRepositoryInterface;
use ProjectManagement\Resources\CountryResource;
use Illuminate\Http\Request;
use ProjectManagement\ValidationRequests\CreateCountryRequest;
use ProjectManagement\ValidationRequests\UpdateCountryRequest;

class CountryController extends Controller
{
    protected $countryRepository;
    use ApiResponseTrait;

    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function index(Request $request)
    {
        if (!$request->has('page_num')) {
            $countries = $this->countryRepository->fetch_all();
            return $this->successResponse(CountryResource::collection($countries), ResponseMessage::OK, Response::HTTP_OK);
        }else{
            $per_page = $request->input('page_size', 10);
            $page_mum = $request->input('page_num', 1);
            $search = $request->input('search', '');
            $countries = $this->countryRepository->paginate($per_page, ['*'], 'page', $page_mum, $search);
            return $this->successResponse([
                'data' => CountryResource::collection($countries),
                'total_records' => $countries->total(),
                'current_page' => $countries->currentPage(),
                'total_pages' => $countries->lastPage(),
                'page_num' => $page_mum,
                'per_page' => $per_page,
            ], ResponseMessage::OK, Response::HTTP_OK);
        }

    }
    public function create(CreateCountryRequest $request)
    {
        $data = $request->prepareRequest();
        $country = $this->countryRepository->create($data);
        return $this->successResponse(new CountryResource($country), ResponseMessage::OK, Response::HTTP_OK);
    }

    public function update(UpdateCountryRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $country = $this->countryRepository->update($id, $data);
        return $this->successResponse(new CountryResource($country), ResponseMessage::OK, Response::HTTP_OK);
    }
    public function destroy($id)
    {
        $country = $this->countryRepository->find($id);
        if ($country) {
            $this->countryRepository->delete($id);
        } else {
            return $this->failureResponse('Country not found!', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse('', ResponseMessage::OK, Response::HTTP_OK);
    }
}

<?php

namespace App\Http\Controllers;

use ProjectManagement\Enums\ResponseMessage;
use ProjectManagement\Repositories\Client\ClientRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Repositories\Inquiry\InquiryRepositoryInterface;
use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Resources\InquiryResource;
use ProjectManagement\ValidationRequests\CreateInquiryRequest;
use ProjectManagement\ValidationRequests\UpdateInquiryRequest;
use ProjectManagement\ValidationRequests\CreateProductAttachmentRequest;
use App\Helpers\helper;
use ProjectManagement\Enums\InquiryStatuses;
use ProjectManagement\Repositories\Project\ProjectRepositoryInterface;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    protected $inquiryRepository;
    protected $projectRepository;
    protected $clientRepository;
    use ApiResponseTrait;

    public function __construct(InquiryRepositoryInterface $inquiryRepository , ProjectRepositoryInterface $projectRepository , ClientRepositoryInterface $clientRepository)
    {
        $this->inquiryRepository = $inquiryRepository;
        $this->projectRepository = $projectRepository;
        $this->clientRepository = $clientRepository;
    }

    public function index($client_id)
    {
        // $client = $this->clientRepository->find($client_id);
        // if (!$client){
        //     $this->failureResponse('Client not found!' , 404);
        // }
        $inquries = $this->inquiryRepository->fetch_all($client_id);
        return $this->successResponse(InquiryResource::collection($inquries), ResponseMessage::OK , Response::HTTP_OK);
    }

    public function getLeads(Request $request)
    {
        $perPage = $request->input('page_size', 10);
        $pageNum = $request->input('page_num', 1);
        $search = $request->input('search', '');
        if ($request->has('page_size') && $request->has('page_num')) {
        $leads = $this->inquiryRepository->paginate($perPage, ['*'], 'page', $pageNum, $search);
        return $this->successResponse([
            'data' => InquiryResource::collection($leads),
            'total_records' => $leads->total(),
            'current_page' => $leads->currentPage(),
            'total_pages' => $leads->lastPage(),
            'page_num' => $pageNum,
            'per_page' => $perPage,
        ], ResponseMessage::OK, Response::HTTP_OK);
    }else{
        $leads = $this->inquiryRepository->fetch_all(null);
        return $this->successResponse(InquiryResource::collection($leads), ResponseMessage::OK , Response::HTTP_OK);
    }
    }

    public function create(CreateInquiryRequest $request)
    {
        $data = $request->prepareRequest();
        $inquiry = $this->inquiryRepository->create($data);
        if ($request->has('attachments')) {
            $prepare_data = [
                'item_type' => 'inquiry',
                'item_id' => $inquiry->id,
                'files' => $data['attachments']
            ];
            helper::storeAttachments($prepare_data);
        }
        return $this->successResponse(new InquiryResource($inquiry), ResponseMessage::OK , Response::HTTP_OK);
    }

    public function update(UpdateInquiryRequest $request, $id)
    {
        $data = $request->prepareRequest();
        if (isset($data['status']) && $data['status'] === InquiryStatuses::COMPLETED) {
            $inquiry = $this->inquiryRepository->find($id);
            if (!$inquiry){
                return $this->failureResponse('Inquiry not found!', Response::HTTP_NOT_FOUND);
            }
            $project = $this->projectRepository->create($inquiry);
            helper::approveInquiry($inquiry->attachments , $project->id);
        }
        $inquiry = $this->inquiryRepository->update($id , $data);
        return $this->successResponse(new InquiryResource($inquiry), ResponseMessage::OK , Response::HTTP_OK);
    }

    public function storeAttachments(CreateProductAttachmentRequest $request)
    {
        $validated = $request->validatedWithFilePaths();
        helper::storeAttachments($validated);
        return $this->successResponse('', ResponseMessage::OK , Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->inquiryRepository->delete($id);
        return $this->successResponse('', ResponseMessage::OK , Response::HTTP_OK);

    }
}

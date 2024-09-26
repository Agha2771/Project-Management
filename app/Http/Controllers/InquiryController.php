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
use ProjectManagement\Models\ProjectAttachment;
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

        if ($request->hasFile('attachments')) {
            $attachments = $request->file('attachments');
            $prepare_data = [
                'item_type' => 'inquiry',
                'item_id' => $inquiry->id,
                'files' => $attachments
            ];
            helper::storeAttachments($prepare_data);
        }
        return $this->successResponse(new InquiryResource($inquiry), ResponseMessage::OK, Response::HTTP_OK);
    }


    public function update(UpdateInquiryRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $inquiry = $this->inquiryRepository->update($id , $data);
        return $this->successResponse(new InquiryResource($inquiry), ResponseMessage::OK , Response::HTTP_OK);
    }

    public function storeAttachments(Request $request)
    {
        $data = [
            'files' => $request->attachments,
            'item_id' => $request->item_id,
            'item_type' => $request->item_type,
        ];
        $array = helper::storeAttachments($data);
        return $this->successResponse($array, ResponseMessage::OK , Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->inquiryRepository->delete($id);
        return $this->successResponse('', ResponseMessage::OK , Response::HTTP_OK);

    }

    public function removeAttachment($attachment_id)
    {
        $attachment = ProjectAttachment::find($attachment_id);
        if ($attachment) {
            $filePath = $attachment->file_path;
            $attachment->delete();
            $fullPath = storage_path('app/public/' . ltrim($filePath, 'storage/'));
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            return $this->successResponse('', ResponseMessage::OK , Response::HTTP_OK);

        }
            return $this->successResponse('', ResponseMessage::ERROR , Response::HTTP_OK);

    }
}

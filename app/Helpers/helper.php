<?php

namespace App\Helpers;
use ProjectManagement\Models\Project;
use ProjectManagement\Models\Inquiry;
use ProjectManagement\Models\ProjectAttachment;

class helper
{
    /**
     * Store the attachments from the request and create attachment records.
     *
     * @param  \ProjectManagement\ValidationRequests\CreateClientNotesRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function storeAttachments($validated): bool
    {
        foreach ($validated['files'] as $filePath) {
            $attachment = new ProjectAttachment();
            $attachment->file_path = $filePath;
            $attachment->attachable_id = $validated['item_id'];
            $attachment->attachable_type = $validated['item_type'] === 'inquiry'
                ?Inquiry::class
                :Project::class;
            $attachment->save();
        }
        return true;
    }

    public static function approveInquiry($attachments , $project_id)
    {
        foreach ($attachments as $attachment) {
            $attachment->attachable_type = Project::class;
            $attachment->attachable_id = $project_id;
            $attachment->save();
        }
    }
}

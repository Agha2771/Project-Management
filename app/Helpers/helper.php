<?php

namespace App\Helpers;
use ProjectManagement\Models\Project;
use ProjectManagement\Models\Inquiry;
use ProjectManagement\Models\Payment;
use ProjectManagement\Models\Expense;
use ProjectManagement\Models\ProjectAttachment;

class helper
{
    /**
     * Store the attachments from the request and create attachment records.
     *
     * @param  \ProjectManagement\ValidationRequests\CreateClientNotesRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function storeAttachments($validated): array // Change to return array
    {
        $an_array = []; // Initialize the array
        foreach ($validated['files'] as $file) {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileNameWithoutSpaces = str_replace(' ', '_', pathinfo($originalName, PATHINFO_FILENAME));
            $filename = $fileNameWithoutSpaces . '_' . time() . '.' . $extension;
            $filePath = $file->storeAs('product_attachments', $filename, 'public');

            if (!str_contains($filePath, 'storage/')) {
                $filePath = 'storage/' . $filePath;
            }

            $attachment = new ProjectAttachment();
            $attachment->file_path = $filePath;
            $attachment->attachable_id = $validated['item_id'];
            $attachment->attachable_type = match ($validated['item_type']) {
                'inquiry' => Inquiry::class,
                'project' => Project::class,
                'payment' => Payment::class,
                'expense' => Expense::class,
                default => null,
            };

            $attachment->save();
            array_push($an_array, $attachment); // Correctly push attachment into the array
        }
        return $an_array; // Return the array of attachments
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

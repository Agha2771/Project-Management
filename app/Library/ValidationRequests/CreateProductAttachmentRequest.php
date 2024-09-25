<?php namespace ProjectManagement\ValidationRequests;

use ProjectManagement\Abstracts\FormRequest;
use ProjectManagement\Models\Project;
use ProjectManagement\Models\Inquiry;
use Illuminate\Validation\Rule;

class CreateProductAttachmentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Update this if you need to check user permissions
    }

    public function rules()
    {
        return [
            'files.*' => 'required|file|mimes:jpeg,png,pdf,doc,docx|max:2048',
            'item_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $itemType = $this->input('item_type');

                    if ($itemType === 'inquiry') {
                        if (!Inquiry::find($value)) {
                            $fail('The selected ' . $attribute . ' is invalid for the given item type.');
                        }
                    } elseif ($itemType === 'project') {
                        if (!Project::find($value)) {
                            $fail('The selected ' . $attribute . ' is invalid for the given item type.');
                        }
                    } else {
                        $fail('The selected item type is invalid.');
                    }
                }
            ],
            'item_type' => [
                'required',
                Rule::in(['inquiry', 'project'])
            ],
        ];
    }

    /**
     * Handle the file uploads and return their paths.
     *
     * @return array
     */
    public function handleFileUploads()
    {
        $filePaths = [];
        if ($this->hasFile('files')) {
            $files = $this->file('files');
            foreach ($files as $file) {
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension(); // Generate unique file name
                $filePaths[] = $file->storeAs('project_attachments', $fileName, 'public'); // Store file and add path to array
            }
        }
        return $filePaths;
    }

    /**
     * Get the validated data including the file paths.
     *
     * @return array
     */
    public function validatedWithFilePaths()
    {
        $validated = $this->validated(); // Get validated data
        $validated['files'] = $this->handleFileUploads(); // Handle file uploads and get file paths
        return $validated;
    }
}

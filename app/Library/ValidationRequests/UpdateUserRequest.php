<?php namespace ProjectManagement\ValidationRequests;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProjectManagement\Abstracts\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'status' => 'required',
            'role' => 'required|exists:roles,name',
        ];
    }

    public function prepareRequest()
    {
        $request = $this;
        return [
            'name' => $request['name'],
            'role' => $request['role'],
            'status' => $request['status']
        ];
    }

    public function uploadImage($file)
    {
        $image_64 = $file;
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
        $image = str_replace($replace, '', $image_64);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(10) . '.' . $extension;
        Storage::disk('local')->put("public/profile/$imageName", base64_decode($image), 'public');
        return public_path('storage/profile/' . $imageName);
    }
}
